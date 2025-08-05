<?php

namespace App\Http\Controllers;

use App\Models\SawCertificate;
use App\Models\Company;
use App\Models\Welder;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\AppSetting;
use SimpleSoftwareIO\QrCode\Facades\QrCode as FacadesQrCode;

class SmawCertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Get data for dropdown filters
        $welders = Welder::orderBy('name')->pluck('name', 'id');
        $companies = Company::orderBy('name')->pluck('name', 'id');

        // If this is a DataTables AJAX request
        if ($request->ajax()) {
            // Start query for certificates with related data
            $query = SawCertificate::with(['welder', 'company', 'createdBy']);

            // Apply filters
            if ($request->filled('certificate_no')) {
                $query->where('certificate_no', 'like', '%' . $request->certificate_no . '%');
            }

            if ($request->filled('welder_id')) {
                $query->where('welder_id', $request->welder_id);
            }

            if ($request->filled('company_id')) {
                $query->where('company_id', $request->company_id);
            }

            if ($request->filled('test_result')) {
                $query->where('test_result', $request->test_result);
            }

            if ($request->filled('date_from')) {
                $query->whereDate('test_date', '>=', $request->date_from);
            }

            if ($request->filled('date_to')) {
                $query->whereDate('test_date', '<=', $request->date_to);
            }

            // Get data and format for DataTables
            return datatables()
                ->of($query)
                ->addColumn('welder_name', function($certificate) {
                    return $certificate->welder->name ?? 'N/A';
                })
                ->addColumn('company_name', function($certificate) {
                    return $certificate->company->name ?? 'N/A';
                })
                ->addColumn('actions', function($certificate) {
                    $actions = '<div class="btn-group" role="group">';
                    $actions .= '<a href="' . route('saw-certificates.show', $certificate->id) . '" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>';
                    $actions .= '<a href="' . route('saw-certificates.edit', $certificate->id) . '" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>';
                    $actions .= '<a href="' . route('saw-certificates.certificate', $certificate->id) . '" class="btn btn-sm btn-success" target="_blank"><i class="fas fa-file-pdf"></i></a>';
                    $actions .= '<form action="' . route('saw-certificates.destroy', $certificate->id) . '" method="POST" class="d-inline delete-form">';
                    $actions .= csrf_field() . method_field('DELETE');
                    $actions .= '<button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this certificate?\')"><i class="fas fa-trash"></i></button>';
                    $actions .= '</form>';
                    $actions .= '</div>';
                    return $actions;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('smaw_certificates.index', compact('welders', 'companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Get companies and welders for dropdowns
        $companies = Company::orderBy('name')->get();
        $welders = Welder::orderBy('name')->get();

        // Get current user information
        $user = Auth::user();

        // If welder is preselected (from a GET parameter)
        $selectedWelder = null;
        if ($request->has('welder_id')) {
            $selectedWelder = Welder::with('company')->find($request->welder_id);
        }

        // Generate certificate number
        $companyCode = AppSetting::getValue('default_company_code', 'AIC');
        if ($request->has('company_id')) {
            $company = Company::find($request->company_id);
            if ($company && $company->code) {
                $companyCode = $company->code;
            }
        }

        $systemCode = AppSetting::getValue('doc_prefix', 'EEA');
        $prefix = $systemCode . '-' . $companyCode . '-WQT-';

        // Find last certificate number for this company
        $lastCert = SawCertificate::where('certificate_no', 'like', $prefix . '%')
            ->orderBy('certificate_no', 'desc')
            ->first();

        $newNumber = 1;
        if ($lastCert) {
            $parts = explode('-', $lastCert->certificate_no);
            $lastNumber = intval(end($parts));
            $newNumber = $lastNumber + 1;
        }

        $newCertNo = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        // Generate VT and RT report numbers
        $vtReportNo = $systemCode . '-' . $companyCode . '-VT-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        $rtReportNo = $systemCode . '-' . $companyCode . '-RT-' . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        return view('smaw_certificates.create', compact(
            'companies',
            'welders',
            'user',
            'selectedWelder',
            'newCertNo',
            'vtReportNo',
            'rtReportNo'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Transform boolean checkbox values
        $booleanFields = [
            'test_coupon', 'production_weld', 'plate_specimen', 'pipe_specimen',
            'filler_metal_used', 'transverse_face_root_bends', 'longitudinal_bends',
            'side_bends', 'pipe_bend_corrosion', 'plate_bend_corrosion',
            'pipe_macro_fusion', 'plate_macro_fusion', 'rt_selected', 'ut_selected',
            'fillet_welds_plate', 'fillet_welds_pipe'
        ];

        $data = $request->all();

        // Handle potential duplicate fields in the form submission
        foreach ($data as $key => $value) {
            if (is_array($value) && !in_array($key, ['_token'])) {
                // Take only the first value if it's an array but not expected to be
                $data[$key] = $value[0];
            }
        }

        // Set default false values for boolean fields if they're not present in request
        foreach ($booleanFields as $field) {
            if (!isset($data[$field])) {
                $data[$field] = false;
            } else if ($data[$field] === 'on' || $data[$field] === 'true' || $data[$field] === '1') {
                $data[$field] = true;
            } else {
                $data[$field] = filter_var($data[$field], FILTER_VALIDATE_BOOLEAN);
            }
        }

        // Create a validator instance with the transformed data
        $validator = Validator::make($data, [
            // Basic Certificate Information
            'certificate_no' => 'required|string|max:50|unique:saw_certificates,certificate_no',
            'welder_id' => 'required|exists:welders,id',
            'company_id' => 'required|exists:companies,id',
            'wps_followed' => 'required|string|max:255',
            'test_date' => 'required|date',

            // Test Description
            'test_coupon' => 'boolean',
            'production_weld' => 'boolean',
            'base_metal_spec' => 'required|string|max:255',
            'dia_thickness' => 'required|string|max:255',

            // Base Metal Information
            'base_metal_p_no_from' => 'required|string|max:255',
            'base_metal_p_no_to' => 'required|string|max:255',
            'plate_specimen' => 'boolean',
            'pipe_specimen' => 'boolean',
            'pipe_diameter' => 'nullable|string|max:255',

            // Filler Metal Information
            'filler_metal_sfa_spec' => 'nullable|string|max:255',
            'filler_metal_classification' => 'nullable|string|max:255',

            // Testing Variables
            'welding_type' => 'required|string|max:255',
            'welding_process' => 'required|string|max:255',
            'visual_control_type' => 'required|string|max:255',
            'joint_tracking' => 'required|string|max:255',
            'test_position' => 'required|string|max:255',
            'position_range' => 'nullable|string',
            'backing' => 'required|string|max:255',
            'backing_range' => 'nullable|string',
            'passes_per_side' => 'required|string|max:255',

            // Test Results
            'visual_examination_result' => 'nullable|string|max:255',
            'vt_report_no' => 'nullable|string|max:255',
            'alternative_volumetric_result' => 'nullable|string|max:255',
            'rt_report_no' => 'nullable|string|max:255',
            'rt_doc_no' => 'nullable|string|max:255',

            // Personnel Information
            'evaluated_by' => 'required|string|max:255',
            'evaluated_company' => 'required|string|max:255',
            'mechanical_tests_by' => 'required_if:rt,0,ut,0|nullable|string|max:255',
            'lab_test_no' => 'nullable|string|max:255',
            'welding_supervised_by' => 'required|string|max:255',
            'supervised_company' => 'required|string|max:255',
            'certification_text' => 'required|string|max:500',

            // Organization Section
            'test_witnessed_by' => 'nullable|string|max:255',
            'witness_name' => 'nullable|string|max:255',
            'witness_signature' => 'nullable|string',
            'witness_date' => 'required|date',

            // File uploads
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Check validation result before proceeding
        if ($validator->fails()) {
            // For AJAX requests, return JSON response
            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // For regular requests, redirect back with errors
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Get validated data
        $validated = $validator->validated();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoPath = $photo->store('saw_certificates/photos', 'public');
            $validated['photo_path'] = $photoPath;
        }

        // Set default values if not provided
        $validated['visual_examination_result'] = $validated['visual_examination_result'] ?? 'Accepted';
        $validated['alternative_volumetric_result'] = $validated['alternative_volumetric_result'] ?? 'ACC';
        $validated['test_witnessed_by'] = $validated['test_witnessed_by'] ?? 'ELITE ENGINEERING ARABIA';

        // Add current user as creator
        $validated['created_by'] = Auth::id();

        // Generate automatic ranges based on selections
        $validated = $this->generateQualificationRanges($validated);

        // Create the certificate
        try {
            DB::beginTransaction();

            $certificate = SawCertificate::create($validated);

            DB::commit();

            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => true,
                    'message' => 'SMAW Certificate created successfully.',
                    'redirect' => route('smaw-certificates.certificate', $certificate),
                    'certificate' => $certificate
                ]);
            }

            // Standard redirect for non-AJAX requests
            return redirect()->route('saw-certificates.certificate', $certificate)
                            ->with('success', 'SAW Certificate created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Clean up uploaded file if there was an error
            if (isset($photoPath) && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }

            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating certificate: ' . $e->getMessage()
                ], 422);
            }

            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Error creating certificate: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $certificate = SawCertificate::with(['welder', 'company', 'createdBy'])->findOrFail($id);
        return view('smaw_certificates.show', compact('certificate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $certificate = SawCertificate::findOrFail($id);
        $companies = Company::orderBy('name')->get();
        $welders = Welder::orderBy('name')->get();
        $selectedWelder = $certificate->welder;

        // Use the existing certificate number
        $newCertNo = $certificate->certificate_no;

        // Use existing report numbers or generate new ones if not present
        $vtReportNo = $certificate->vt_report_no ?? '';
        $rtReportNo = $certificate->rt_report_no ?? '';

        return view('smaw_certificates.edit', compact(
            'certificate',
            'companies',
            'welders',
            'selectedWelder',
            'newCertNo',
            'vtReportNo',
            'rtReportNo'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Similar validation logic as store method
        $booleanFields = [
            'test_coupon', 'production_weld', 'plate_specimen', 'pipe_specimen',
            'filler_metal_used', 'transverse_face_root_bends', 'longitudinal_bends',
            'side_bends', 'pipe_bend_corrosion', 'plate_bend_corrosion',
            'pipe_macro_fusion', 'plate_macro_fusion', 'rt_selected', 'ut_selected',
            'fillet_welds_plate', 'fillet_welds_pipe'
        ];

        $data = $request->all();

        // Transform boolean values
        foreach ($booleanFields as $field) {
            if (!isset($data[$field])) {
                $data[$field] = false;
            } else if ($data[$field] === 'on' || $data[$field] === 'true' || $data[$field] === '1') {
                $data[$field] = true;
            } else {
                $data[$field] = filter_var($data[$field], FILTER_VALIDATE_BOOLEAN);
            }
        }

        $validator = Validator::make($data, [
            'certificate_no' => 'required|string|max:50|unique:saw_certificates,certificate_no,' . $id,
            'welder_id' => 'required|exists:welders,id',
            'company_id' => 'required|exists:companies,id',
            'wps_followed' => 'required|string|max:255',
            'test_date' => 'required|date',
            'base_metal_spec' => 'required|string|max:255',
            'dia_thickness' => 'required|string|max:255',
            'welding_supervised_by' => 'required|string|max:255',
            'witness_date' => 'required|date',
            'evaluated_by' => 'required|string|max:255',
            'evaluated_company' => 'required|string|max:255',
            'mechanical_tests_by' => 'required_if:rt,0,ut,0|nullable|string|max:255',
            'supervised_company' => 'required|string|max:255',
            'certification_text' => 'required|string|max:500',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $certificate = SawCertificate::findOrFail($id);
            
            // Delete old photo if exists
            if ($certificate->photo_path) {
                Storage::disk('public')->delete($certificate->photo_path);
            }
            
            $photo = $request->file('photo');
            $photoPath = $photo->store('smaw_certificates/photos', 'public');
            $validated['photo_path'] = $photoPath;
        }

        // Generate automatic ranges
        $validated = $this->generateQualificationRanges($validated);

        try {
            DB::beginTransaction();

            $certificate = SawCertificate::findOrFail($id);
            $certificate->update($validated);

            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'SMAW Certificate updated successfully.',
                    'redirect' => route('smaw-certificates.certificate', $certificate)
                ]);
            }

            return redirect()->route('smaw-certificates.certificate', $certificate)
                            ->with('success', 'SMAW Certificate updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error updating certificate: ' . $e->getMessage()
                ], 422);
            }

            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Error updating certificate: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $certificate = SawCertificate::findOrFail($id);

        try {
            // Delete photo if it exists
            if ($certificate->photo_path) {
                Storage::disk('public')->delete($certificate->photo_path);
            }

            $certificate->delete();

            return redirect()->route('smaw-certificates.index')
                            ->with('success', 'SMAW Certificate deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()
                            ->with('error', 'Error deleting certificate: ' . $e->getMessage());
        }
    }

    /**
     * Generate a printable certificate
     */
    public function generateCertificate(string $id)
    {
        $certificate = SawCertificate::with('welder.company', 'company')->findOrFail($id);

        // Generate QR Code for certificate verification
        $verificationUrl = route('saw-certificates.verify', ['id' => $certificate->id, 'code' => $certificate->verification_code]);
        $qrCodeUrl = 'data:image/png;base64,' . base64_encode(FacadesQrCode::format('png')->size(200)->generate($verificationUrl));

        return view('smaw_certificates.certificate', compact('certificate', 'qrCodeUrl'));
    }

    /**
     * Get welder details for AJAX requests
     */
    public function getWelderDetails($id)
    {
        $welder = Welder::with('company')->find($id);

        if (!$welder) {
            return response()->json(['error' => 'Welder not found'], 404);
        }

        // Generate full URL for photo if it exists
        $photoUrl = null;
        if ($welder->photo) {
            $photoUrl = asset('storage/' . $welder->photo);
        }

        return response()->json([
            'welder' => [
                'id' => $welder->id,
                'name' => $welder->name,
                'welder_id_no' => $welder->welder_no,
                'passport_no' => $welder->passport_id_no,
                'iqama_no' => $welder->iqama_no,
                'photo_path' => $photoUrl,
                'photo' => $welder->photo
            ],
            'company' => $welder->company ? [
                'id' => $welder->company->id,
                'name' => $welder->company->name,
                'code' => $welder->company->code
            ] : null
        ]);
    }

    /**
     * Verify certificate by ID and verification code
     */
    public function verify($id, $code)
    {
        $certificate = SawCertificate::with(['welder', 'company'])->findOrFail($id);

        if ($certificate->verification_code !== $code) {
            return abort(404);
        }

        $isValid = true;
        $message = 'Certificate is valid and authentic.';

        return view('smaw_certificates.verify', compact('certificate', 'isValid', 'message'));
    }

    /**
     * Show certificate verification form for public users
     */
    public function showVerificationForm()
    {
        return view('smaw_certificates.verification_form');
    }

    /**
     * Verify certificate by certificate number
     */
    public function verifyByCertificateNo(Request $request)
    {
        $validated = $request->validate([
            'certificate_no' => 'required|string',
        ]);

        $certificate = SawCertificate::where('certificate_no', $validated['certificate_no'])->first();

        if (!$certificate) {
            return view('smaw_certificates.verify', [
                'isValid' => false,
                'message' => 'Certificate not found. Please check the certificate number and try again.',
                'certificate' => null
            ]);
        }

        return redirect()->route('smaw-certificates.verify', [
            'id' => $certificate->id,
            'code' => $certificate->verification_code
        ]);
    }

    /**
     * Generate qualification ranges based on input values
     */
    private function generateQualificationRanges($data)
    {
        // Generate position range
        if (empty($data['position_range'])) {
            $data['position_range'] = $this->getPositionRange(
                $data['test_position'] ?? '1G',
                $data['pipe_specimen'] ?? false
            );
        }

        // Generate backing range
        if (empty($data['backing_range'])) {
            $data['backing_range'] = $this->getBackingRange($data['backing'] ?? 'With backing');
        }

        // Generate passes range
        if (empty($data['passes_range'])) {
            $data['passes_range'] = $this->getPassesRange($data['passes_per_side'] ?? 'multiple passes per side');
        }

        // Set welding type range (usually same as actual for SAW)
        $data['welding_type_range'] = $data['welding_type'] ?? 'Machine';
        $data['welding_process_range'] = $data['welding_process'] ?? 'SAW';
        $data['visual_control_range'] = $data['visual_control_type'] ?? 'Direct Visual Control';

        // Set joint tracking range
        if (($data['joint_tracking'] ?? '') === 'With Automatic joint tracking') {
            $data['joint_tracking_range'] = 'With Automatic joint tracking';
        } else {
            $data['joint_tracking_range'] = 'With & Without Automatic joint tracking';
        }

        return $data;
    }

    /**
     * Get position qualification range based on test position and specimen type
     */
    private function getPositionRange($position, $isPipe)
    {
        $ranges = [];
        
        switch ($position) {
            case '1G':
                $ranges[] = 'F for Groove Plate and Pipe Over 24 in. (610 mm) O.D.';
                if ($isPipe) {
                    $ranges[] = 'F for Groove Pipe ≥ 2 7∕8 in. (73 mm) O.D.';
                }
                $ranges[] = 'F for Fillet or Tack Plate and Pipe';
                break;
            
            case '2G':
                $ranges[] = 'F & H for Groove Plate and Pipe Over 24 in. (610 mm) O.D.';
                if ($isPipe) {
                    $ranges[] = 'F & H for Groove Pipe ≥ 2 7∕8 in. (73 mm) O.D.';
                }
                $ranges[] = 'F & H for Fillet or Tack Plate and Pipe';
                break;
            
            default:
                $ranges[] = 'F for Groove Plate and Pipe Over 24 in. (610 mm) O.D.';
                $ranges[] = 'F for Fillet or Tack Plate and Pipe';
        }
        
        return implode(' | ', $ranges);
    }

    /**
     * Get backing qualification range
     */
    private function getBackingRange($backing)
    {
        switch ($backing) {
            case 'With backing':
                return 'With backing';
            case 'Without backing':
                return 'With or Without backing';
            default:
                return 'With backing';
        }
    }

    /**
     * Get passes qualification range
     */
    private function getPassesRange($passes)
    {
        switch ($passes) {
            case 'Single passes per side':
                return 'Single passes per side';
            case 'multiple passes per side':
                return 'Single & multiple passes per side';
            default:
                return 'Single & multiple passes per side';
        }
    }
}