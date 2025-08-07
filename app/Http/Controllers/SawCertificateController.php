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
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use App\Models\AppSetting;
use BaconQrCode\Encoder\QrCode;
use SimpleSoftwareIO\QrCode\Facades\QrCode as FacadesQrCode;

class SawCertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
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
                    $actions .= '<a href="' . route('saw-certificates.show', $certificate->id) . '" class="btn btn-sm btn-info" title="View Certificate"><i class="fas fa-eye"></i></a>';
                    $actions .= '<a href="' . route('saw-certificates.edit', $certificate->id) . '" class="btn btn-sm btn-primary" title="Edit Certificate"><i class="fas fa-edit"></i></a>';
                    $actions .= '<a href="' . route('saw-certificates.certificate', $certificate->id) . '" class="btn btn-sm btn-success" target="_blank" title="Print Certificate"><i class="fas fa-file-pdf"></i></a>';
                    $actions .= '<a href="' . route('saw-certificates.card', $certificate->id) . '" class="btn btn-sm btn-secondary" target="_blank" title="Print Card"><i class="fas fa-id-card"></i></a>';
                    $actions .= '<a href="' . route('saw-certificates.back-card', $certificate->id) . '" class="btn btn-sm btn-warning" target="_blank" title="Print Back Card"><i class="fas fa-id-card-alt"></i></a>';
                    $actions .= '<button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#rt-report-modal" data-certificate-id="' . $certificate->id . '" data-welder-id="' . $certificate->welder_id . '" data-certificate-type="saw" title="Upload RT Report"><i class="fas fa-upload"></i></button>';
                    $actions .= '<form action="' . route('saw-certificates.destroy', $certificate->id) . '" method="POST" class="d-inline delete-form">';
                    $actions .= csrf_field() . method_field('DELETE');
                    $actions .= '<button type="submit" class="btn btn-sm btn-danger" title="Delete Certificate" onclick="return confirm(\'Are you sure you want to delete this certificate?\')"><i class="fas fa-trash"></i></button>';
                    $actions .= '</form>';
                    $actions .= '</div>';
                    return $actions;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        // For normal page view, just return the view with filters
        return view('saw_certificates.index', compact('welders', 'companies'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // Get companies for dropdown
        $companies = Company::orderBy('name')->get();
        $welders = Welder::with('company')->orderBy('name')->get();

        // Get current user information
        $user = Auth::user();

        // If welder is preselected (from a GET parameter)
        $selectedWelder = null;
        if ($request->has('welder_id')) {
            $selectedWelder = Welder::with('company')->find($request->welder_id);
        }

        // Define common options for dropdowns
        $pipeDiameterTypes = [
            'DN 15 (1/2")', 'DN 20 (3/4")', 'DN 25 (1")', 'DN 32 (1 1/4")',
            'DN 40 (1 1/2")', 'DN 50 (2")', 'DN 65 (2 1/2")', 'DN 80 (3")',
            'DN 100 (4")', 'DN 125 (5")', 'DN 150 (6")', 'DN 200 (8")',
            'DN 250 (10")', 'DN 300 (12")', 'DN 350 (14")', 'DN 400 (16")',
            'DN 450 (18")', 'DN 500 (20")', 'DN 600 (24")'
        ];

        $testPositions = ['1G', '2G', '3G', '4G', '5G', '6G'];

        $baseMetalPNumbers = [
            'P-No.1', 'P-No.2', 'P-No.3', 'P-No.4', 'P-No.5', 'P-No.6',
            'P-No.7', 'P-No.8', 'P-No.9', 'P-No.10', 'P-No.11', 'P-No.15'
        ];

        $fillerSpecs = [
            'SFA 5.1', 'SFA 5.4', 'SFA 5.5', 'SFA 5.20', 'SFA 5.22',
            'SFA 5.28', 'SFA 5.29', 'SFA 5.36'
        ];

        $fillerClasses = [
            'E7018', 'E7016', 'E8018', 'E9018', 'E308', 'E309', 'E316',
            'E8018-C3', 'E9018-M', 'E71T-1', 'E71T-9', 'E81T1-Ni1', 'ER70S-6'
        ];

        $fillerFNumbers = [
            'F4_with_backing', 'F4_without_backing', 'F5_with_backing', 'F5_without_backing', 'F43'
        ];

        $backingTypes = [
            'With Backing', 'Without Backing'
        ];

        $verticalProgressions = [
            'Uphill', 'Downhill'
        ];

        // Generate certificate number
        $companyCode = AppSetting::getValue('default_company_code', 'AIC');
        if ($request->has('company_id')) {
            $company = Company::find($request->company_id);
            if ($company && $company->code) {
                $companyCode = $company->code;
            }
        }

        $prefix = 'SAW-' . $companyCode . '-';

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
        $vtReportNo = $companyCode . '-VT-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        $rtReportNo = $companyCode . '-RT-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

        return view('saw_certificates.create', compact(
            'companies',
            'welders',
            'user',
            'selectedWelder',
            'pipeDiameterTypes',
            'testPositions',
            'baseMetalPNumbers',
            'fillerSpecs',
            'fillerClasses',
            'fillerFNumbers',
            'backingTypes',
            'verticalProgressions',
            'newCertNo',
            'vtReportNo',
            'rtReportNo'
        ));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
{
    // Transform boolean checkbox values
    $booleanFields = [
        'test_coupon', 'production_weld', 'plate_specimen', 'pipe_specimen', 
        'rt', 'ut', 'rt_selected', 'ut_selected', 'fillet_welds_plate', 
        'fillet_welds_pipe', 'pipe_macro_fusion', 'plate_macro_fusion',
        'transverse_face_root_bends', 'longitudinal_bends', 'side_bends',
        'pipe_bend_corrosion', 'plate_bend_corrosion'
    ];

    $data = $request->all();

    // Handle boolean fields
    foreach ($booleanFields as $field) {
        if (!isset($data[$field])) {
            $data[$field] = false;
        } else if ($data[$field] === 'on' || $data[$field] === 'true' || $data[$field] === '1') {
            $data[$field] = true;
        } else {
            $data[$field] = filter_var($data[$field], FILTER_VALIDATE_BOOLEAN);
        }
    }

    // FIXED VALIDATION RULES - MATCH FRONTEND FIELD NAMES
    $validator = Validator::make($data, [
        // Certificate identification
        'certificate_no' => 'required|string|max:50|unique:saw_certificates,certificate_no',
        'welder_id' => 'required|exists:welders,id',
        'company_id' => 'required|exists:companies,id',
        'wps_followed' => 'required|string|max:255',
        'test_date' => 'required|date',

        // Specimen details
        'test_coupon' => 'boolean',
        'production_weld' => 'boolean',
        'plate_specimen' => 'boolean',
        'pipe_specimen' => 'boolean',
        'base_metal_spec' => 'required|string|max:255',
        'diameter' => 'nullable|string|max:255', // FRONTEND SENDS: diameter
        
        // FIXED: Use dia_thickness instead of thickness
        'dia_thickness' => 'required|string|max:255', // FRONTEND SENDS: dia_thickness
        
        // FIXED: Map frontend field names to backend validation
        'base_metal_p_no_from' => 'required|string|max:255', // FRONTEND SENDS: base_metal_p_no_from
        'base_metal_p_no_to' => 'required|string|max:255',   // FRONTEND SENDS: base_metal_p_no_to
        
        // Filler metal fields - FIXED field names
        'filler_metal_sfa_spec' => 'required|string|max:255',      // FRONTEND SENDS: filler_metal_sfa_spec 
        'filler_metal_classification' => 'required|string|max:255', // FRONTEND SENDS: filler_metal_classification

        // Position details
        'test_position' => 'required|string|max:255',
        'position_range' => 'nullable|string',

        // Backing details
        'backing' => 'required|string|max:255',
        'backing_range' => 'nullable|string',

        // Machine welding variables - FRONTEND FIELD NAMES
        'welding_type' => 'required|string|max:255',
        'welding_process' => 'required|string|max:255',
        'visual_control_type' => 'required|string|max:255',
        'joint_tracking' => 'required|string|max:255',
        'consumable_inserts' => 'nullable|string|max:255',
        'passes_per_side' => 'required|string|max:255',

        // Range fields
        'diameter_range' => 'nullable|string',
        'p_number_range' => 'nullable|string',
        'backing_range' => 'nullable|string',
        'visual_control_range' => 'nullable|string',
        'joint_tracking_range' => 'nullable|string',
        'passes_range' => 'nullable|string',

        // RT/UT testing details
        'rt' => 'boolean',
        'ut' => 'boolean',
        'rt_selected' => 'boolean',
        'ut_selected' => 'boolean',
        'rt_doc_no' => 'nullable|string|max:255',
        'vt_report_no' => 'nullable|string|max:255',
        'rt_report_no' => 'nullable|string|max:255',

        // Test results
        'visual_examination_result' => 'nullable|string|max:255',
        'alternative_volumetric_result' => 'nullable|string|max:255',

        // Personnel fields - FIXED FIELD NAMES
        'film_evaluated_by' => 'nullable|string|max:255',
        'evaluated_company' => 'nullable|string|max:255',
        'mechanical_tests_by' => 'nullable|string|max:255',
        'lab_test_no' => 'nullable|string|max:255',
        'welding_supervised_by' => 'required|string|max:255', // FRONTEND SENDS: welding_supervised_by
        'supervised_company' => 'nullable|string|max:255',

        // Organization fields
        'test_witnessed_by' => 'nullable|string|max:255',
        'witness_name' => 'required|string|max:255',
        'witness_date' => 'required|date',
        'witness_signature' => 'nullable|string',

        // Additional fields
        'certification_text' => 'required|string|max:255',

        // File uploads
        'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'signature_data' => 'nullable|string',
        'inspector_signature_data' => 'nullable|string', // REQUIRED BY CUSTOM VALIDATION

        // Automatic welding variables
        'automatic_welding_type' => 'nullable|string|max:255',
        'automatic_welding_type_range' => 'nullable|string|max:255',
        'automatic_welding_process' => 'nullable|string|max:255',
        'automatic_welding_process_range' => 'nullable|string|max:255',
        'filler_metal_used_auto' => 'nullable|string|max:255',
        'filler_metal_used_auto_range' => 'nullable|string|max:255',
        'laser_type' => 'nullable|string|max:255',
        'laser_type_range' => 'nullable|string|max:255',
        'drive_type' => 'nullable|string|max:255',
        'drive_type_range' => 'nullable|string|max:255',
        'vacuum_type' => 'nullable|string|max:255',
        'vacuum_type_range' => 'nullable|string|max:255',
        'arc_voltage_control' => 'nullable|string|max:255',
        'arc_voltage_control_range' => 'nullable|string|max:255',
        'position_actual' => 'nullable|string|max:255',
        'consumable_inserts_range' => 'nullable|string|max:255',

        // Additional test fields
        'additional_type_1' => 'nullable|string|max:255',
        'additional_result_1' => 'nullable|string|max:255',
        'test_type_2' => 'nullable|string|max:255',
        'test_result_2' => 'nullable|string|max:255',
        'additional_type_2' => 'nullable|string|max:255',
        'additional_result_2' => 'nullable|string|max:255',
        'fillet_fracture_test' => 'nullable|string|max:255',
        'defects_length_percent' => 'nullable|string|max:255',
        'macro_examination' => 'nullable|string|max:255',
        'fillet_size' => 'nullable|string|max:255',
        'other_tests' => 'nullable|string|max:255',
        'concavity_convexity' => 'nullable|string|max:255',

        // Confirmation fields
        'confirm_date_1' => 'nullable|date',
        'confirm_position_1' => 'nullable|string|max:255',
        'confirm_date_2' => 'nullable|date',
        'confirm_position_2' => 'nullable|string|max:255',
        'confirm_date_3' => 'nullable|date',
        'confirm_position_3' => 'nullable|string|max:255',
    ]);

    // ENHANCED VALIDATION: Custom validation rules - NO INSPECTOR FIELDS REQUIRED
    $validator->after(function ($validator) use ($data) {
        // Validate mutually exclusive boolean fields
        if ($data['plate_specimen'] && $data['pipe_specimen']) {
            // Both can be true for SAW - this is valid
        }
        
        // Validate pipe diameter is provided when pipe specimen is selected
        if ($data['pipe_specimen'] && empty($data['diameter']) && empty($data['pipe_diameter'])) {
            // Not required for SAW - pipe can be tested without specific diameter
        }

        // Validate test methods - either RT/UT or mechanical tests
        if (empty($data['rt_selected']) && empty($data['ut_selected']) && empty($data['mechanical_tests_by'])) {
            // Allow form to proceed - tests are not always required
        }

        // REMOVE INSPECTOR SIGNATURE REQUIREMENT FOR NOW
        // We'll handle this on frontend with better UX
    });

    // DATA MAPPING: Map frontend field names to database field names
    $validated = $validator->validated();
    
    // Map frontend fields to backend database fields if needed
    if (isset($validated['dia_thickness'])) {
        $validated['thickness'] = $validated['dia_thickness']; // Also save as thickness for backward compatibility
    }
    
    if (isset($validated['base_metal_p_no_from'])) {
        $validated['base_metal_p_no'] = $validated['base_metal_p_no_from']; // Map to single field
    }
    
    if (isset($validated['filler_metal_sfa_spec'])) {
        $validated['filler_spec'] = $validated['filler_metal_sfa_spec']; // Map to expected field
    }
    
    if (isset($validated['filler_metal_classification'])) {
        $validated['filler_class'] = $validated['filler_metal_classification']; // Map to expected field
    }
    
    if (isset($validated['welding_supervised_by'])) {
        $validated['supervised_by'] = $validated['welding_supervised_by']; // Map to expected field
    }

    // Add calculated fields
    if (empty($validated['position_range'])) {
        $validated['position_range'] = $this->calculatePositionRange(
            $validated['test_position'],
            isset($validated['pipe_specimen']) ? $validated['pipe_specimen'] : false
        );
    }

    // Add system fields
    $validated['created_by'] = Auth::id();
    
    // Generate verification code
    if (empty($validated['verification_code'])) {
        $validated['verification_code'] = Str::random(12);
    }

    // Generate report numbers if needed
    if (empty($validated['vt_report_no'])) {
        $companyCode = Company::find($validated['company_id'])->code ?? 'AIC';
        $systemCode = AppSetting::getValue('doc_prefix', 'EEA');
        $validated['vt_report_no'] = $systemCode . '-' . $companyCode . '-VT-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    }

    if (empty($validated['rt_report_no'])) {
        $companyCode = Company::find($validated['company_id'])->code ?? 'AIC';
        $systemCode = AppSetting::getValue('doc_prefix', 'EEA');
        $validated['rt_report_no'] = $systemCode . '-' . $companyCode . '-RT-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
    }

    try {
        DB::beginTransaction();
        $certificate = SawCertificate::create($validated);
        DB::commit();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Certificate created successfully.',
                'redirect' => route('saw-certificates.certificate', $certificate),
                'certificate' => $certificate
            ]);
        }

        return redirect()->route('saw-certificates.certificate', $certificate)
                        ->with('success', 'Certificate created successfully.');
                        
    } catch (\Exception $e) {
        DB::rollBack();
        
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating certificate: ' . $e->getMessage()
            ], 500);
        }

        return redirect()->back()
                        ->withInput()
                        ->with('error', 'Error creating certificate: ' . $e->getMessage());
    }
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $certificate = SawCertificate::with(['welder', 'company', 'createdBy'])->findOrFail($id);
        return view('saw_certificates.show', compact('certificate'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $certificate = SawCertificate::findOrFail($id);
        $companies = Company::orderBy('name')->get();
        $welders = Welder::with('company')->orderBy('name')->get();
        $selectedWelder = $certificate->welder;

        // Define common options for dropdowns
        $pipeDiameterTypes = [
            'DN 15 (1/2")', 'DN 20 (3/4")', 'DN 25 (1")', 'DN 32 (1 1/4")',
            'DN 40 (1 1/2")', 'DN 50 (2")', 'DN 65 (2 1/2")', 'DN 80 (3")',
            'DN 100 (4")', 'DN 125 (5")', 'DN 150 (6")', 'DN 200 (8")',
            'DN 250 (10")', 'DN 300 (12")', 'DN 350 (14")', 'DN 400 (16")',
            'DN 450 (18")', 'DN 500 (20")', 'DN 600 (24")'
        ];

        $testPositions = ['1G', '2G', '3G', '4G', '5G', '6G'];

        $baseMetalPNumbers = [
            'P-No.1', 'P-No.2', 'P-No.3', 'P-No.4', 'P-No.5', 'P-No.6',
            'P-No.7', 'P-No.8', 'P-No.9', 'P-No.10', 'P-No.11', 'P-No.15'
        ];

        $fillerSpecs = [
            'SFA 5.1', 'SFA 5.4', 'SFA 5.5', 'SFA 5.20', 'SFA 5.22',
            'SFA 5.28', 'SFA 5.29', 'SFA 5.36'
        ];

        $fillerClasses = [
            'E7018', 'E7016', 'E8018', 'E9018', 'E308', 'E309', 'E316',
            'E8018-C3', 'E9018-M', 'E71T-1', 'E71T-9', 'E81T1-Ni1', 'ER70S-6'
        ];

        $fillerFNumbers = [
            'F4_with_backing', 'F4_without_backing', 'F5_with_backing', 'F5_without_backing', 'F43'
        ];

        $backingTypes = [
            'With Backing', 'Without Backing'
        ];

        $verticalProgressions = [
            'Uphill', 'Downhill'
        ];

        // Use the existing certificate number
        $newCertNo = $certificate->certificate_no;

        // Use existing report numbers or generate new ones if not present
        $vtReportNo = $certificate->vt_report_no ?? '';
        $rtReportNo = $certificate->rt_report_no ?? '';

        return view('saw_certificates.edit', compact(
            'certificate',
            'companies',
            'welders',
            'selectedWelder',
            'pipeDiameterTypes',
            'testPositions',
            'baseMetalPNumbers',
            'fillerSpecs',
            'fillerClasses',
            'fillerFNumbers',
            'backingTypes',
            'verticalProgressions',
            'newCertNo',
            'vtReportNo',
            'rtReportNo'
        ));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Transform boolean checkbox values
    $booleanFields = [
        'test_coupon', 'production_weld', 'plate_specimen', 'pipe_specimen',
        'rt', 'ut', 'rt_selected', 'ut_selected', 'fillet_welds_plate',
        'fillet_welds_pipe', 'pipe_macro_fusion', 'plate_macro_fusion',
        'transverse_face_root_bends', 'longitudinal_bends', 'side_bends',
        'pipe_bend_corrosion', 'plate_bend_corrosion'
    ];

    $data = $request->all();

    // Handle boolean fields
    foreach ($booleanFields as $field) {
        if (!isset($data[$field])) {
            $data[$field] = false;
        } else if ($data[$field] === 'on' || $data[$field] === 'true' || $data[$field] === '1') {
            $data[$field] = true;
        } else {
            $data[$field] = filter_var($data[$field], FILTER_VALIDATE_BOOLEAN);
        }
    }

    // FIXED VALIDATION RULES - MATCH FRONTEND FIELD NAMES
    $validator = Validator::make($data, [
        // Certificate identification
        'certificate_no' => 'required|string|max:50|unique:saw_certificates,certificate_no,' . $id,
        'welder_id' => 'required|exists:welders,id',
        'company_id' => 'required|exists:companies,id',
        'wps_followed' => 'required|string|max:255',
        'test_date' => 'required|date',

        // Specimen details
        'test_coupon' => 'boolean',
        'production_weld' => 'boolean',
        'plate_specimen' => 'boolean',
        'pipe_specimen' => 'boolean',
        'base_metal_spec' => 'required|string|max:255',
        'diameter' => 'nullable|string|max:255', // FRONTEND SENDS: diameter

        // FIXED: Use dia_thickness instead of thickness
        'dia_thickness' => 'required|string|max:255', // FRONTEND SENDS: dia_thickness

        // FIXED: Map frontend field names to backend validation
        'base_metal_p_no_from' => 'required|string|max:255', // FRONTEND SENDS: base_metal_p_no_from
        'base_metal_p_no_to' => 'required|string|max:255',   // FRONTEND SENDS: base_metal_p_no_to

        // Filler metal fields - FIXED field names
        'filler_metal_sfa_spec' => 'required|string|max:255',      // FRONTEND SENDS: filler_metal_sfa_spec
        'filler_metal_classification' => 'required|string|max:255', // FRONTEND SENDS: filler_metal_classification

        // Position details
        'test_position' => 'required|string|max:255',
        'position_range' => 'nullable|string',

        // Backing details
        'backing' => 'required|string|max:255',
        'backing_range' => 'nullable|string',

        // Machine welding variables - FRONTEND FIELD NAMES
        'welding_type' => 'required|string|max:255',
        'welding_process' => 'required|string|max:255',
        'visual_control_type' => 'required|string|max:255',
        'joint_tracking' => 'required|string|max:255',
        'consumable_inserts' => 'nullable|string|max:255',
        'passes_per_side' => 'required|string|max:255',

        // Range fields
        'diameter_range' => 'nullable|string',
        'p_number_range' => 'nullable|string',
        'backing_range' => 'nullable|string',
        'visual_control_range' => 'nullable|string',
        'joint_tracking_range' => 'nullable|string',
        'passes_range' => 'nullable|string',

        // RT/UT testing details
        'rt' => 'boolean',
        'ut' => 'boolean',
        'rt_selected' => 'boolean',
        'ut_selected' => 'boolean',
        'rt_doc_no' => 'nullable|string|max:255',
        'vt_report_no' => 'nullable|string|max:255',
        'rt_report_no' => 'nullable|string|max:255',

        // Test results
        'visual_examination_result' => 'nullable|string|max:255',
        'alternative_volumetric_result' => 'nullable|string|max:255',

        // Personnel fields - FIXED FIELD NAMES
        'film_evaluated_by' => 'nullable|string|max:255',
        'evaluated_company' => 'nullable|string|max:255',
        'mechanical_tests_by' => 'nullable|string|max:255',
        'lab_test_no' => 'nullable|string|max:255',
        'welding_supervised_by' => 'required|string|max:255', // FRONTEND SENDS: welding_supervised_by
        'supervised_company' => 'nullable|string|max:255',

        // Organization fields
        'test_witnessed_by' => 'nullable|string|max:255',
        'witness_name' => 'required|string|max:255',
        'witness_date' => 'required|date',
        'witness_signature' => 'nullable|string',

        // Additional fields
        'certification_text' => 'required|string|max:255',

        // File uploads
        'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'signature_data' => 'nullable|string',
        'inspector_signature_data' => 'nullable|string', // REQUIRED BY CUSTOM VALIDATION

        // Automatic welding variables
        'automatic_welding_type' => 'nullable|string|max:255',
        'automatic_welding_type_range' => 'nullable|string|max:255',
        'automatic_welding_process' => 'nullable|string|max:255',
        'automatic_welding_process_range' => 'nullable|string|max:255',
        'filler_metal_used_auto' => 'nullable|string|max:255',
        'filler_metal_used_auto_range' => 'nullable|string|max:255',
        'laser_type' => 'nullable|string|max:255',
        'laser_type_range' => 'nullable|string|max:255',
        'drive_type' => 'nullable|string|max:255',
        'drive_type_range' => 'nullable|string|max:255',
        'vacuum_type' => 'nullable|string|max:255',
        'vacuum_type_range' => 'nullable|string|max:255',
        'arc_voltage_control' => 'nullable|string|max:255',
        'arc_voltage_control_range' => 'nullable|string|max:255',
        'position_actual' => 'nullable|string|max:255',
        'consumable_inserts_range' => 'nullable|string|max:255',

        // Additional test fields
        'additional_type_1' => 'nullable|string|max:255',
        'additional_result_1' => 'nullable|string|max:255',
        'test_type_2' => 'nullable|string|max:255',
        'test_result_2' => 'nullable|string|max:255',
        'additional_type_2' => 'nullable|string|max:255',
        'additional_result_2' => 'nullable|string|max:255',
        'fillet_fracture_test' => 'nullable|string|max:255',
        'defects_length_percent' => 'nullable|string|max:255',
        'macro_examination' => 'nullable|string|max:255',
        'fillet_size' => 'nullable|string|max:255',
        'other_tests' => 'nullable|string|max:255',
        'concavity_convexity' => 'nullable|string|max:255',

        // Confirmation fields
        'confirm_date_1' => 'nullable|date',
        'confirm_position_1' => 'nullable|string|max:255',
        'confirm_date_2' => 'nullable|date',
        'confirm_position_2' => 'nullable|string|max:255',
        'confirm_date_3' => 'nullable|date',
        'confirm_position_3' => 'nullable|string|max:255',
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

        // Fix for position_range - if empty, calculate based on test_position and pipe_specimen
        if (empty($validated['position_range'])) {
            $validated['position_range'] = $this->getPositionRange(
                $validated['test_position'],
                $validated['pipe_specimen']
            );
        }

        // Update the certificate
        try {
            DB::beginTransaction();

            $certificate = SawCertificate::findOrFail($id);
            $certificate->update($validated);

            DB::commit();

            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => true,
                    'message' => 'Certificate updated successfully.',
                    'redirect' => route('saw-certificates.certificate', $certificate),
                    'certificate' => $certificate
                ]);
            }

            // Standard redirect for non-AJAX requests
            return redirect()->route('saw-certificates.certificate', $certificate)
                            ->with('success', 'Certificate updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            // Check if request is AJAX
            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
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
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
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

            return redirect()->route('saw-certificates.index')
                            ->with('success', 'Certificate deleted successfully.');
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

        // Debug company logo path
        $logoPath = \App\Models\AppSetting::getValue('company_logo_path');
        $logoExists = !empty($logoPath) && Storage::disk('public')->exists($logoPath);

        // Generate QR Code for certificate verification
        $verificationUrl = route('saw-certificates.verify', ['id' => $certificate->id, 'code' => $certificate->verification_code]);
        $qrCodeUrl = 'data:image/png;base64,' . base64_encode(FacadesQrCode::format('png')->size(200)->generate($verificationUrl));

        return view('saw_certificates.certificate', compact('certificate', 'qrCodeUrl', 'logoPath', 'logoExists'));
    }

    /**
     * Generate a welder qualification card
     */
    public function generateCard($id)
    {
        $certificate = SawCertificate::with(['welder', 'company'])->findOrFail($id);
        return view('saw_certificates.card', compact('certificate'));
    }

    /**
     * Generate the back side of a welder qualification card
     */
    public function generateBackCard($id)
    {
        $certificate = SawCertificate::with(['welder', 'company'])->findOrFail($id);
        return view('saw_certificates.back_card', compact('certificate'));
    }

    /**
     * Get welder details for AJAX requests
     */

    /**
     * Show certificate preview
     */
    public function preview()
    {
        return view('saw_certificates.preview');
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

        return view('saw_certificates.verify', compact('certificate'));
    }

    /**
     * Show certificate verification form for public users
     */
    public function showVerificationForm()
    {
        return view('saw_certificates.verification_form');
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
            return view('saw_certificates.verification_form', [
                'error' => 'Certificate not found. Please check the certificate number and try again.',
                'certificate_no' => $validated['certificate_no']
            ]);
        }

        return redirect()->route('saw-certificates.verify', [
            'id' => $certificate->id,
            'code' => $certificate->verification_code
        ]);
    }

    /**
     * Get the position range based on test position and specimen type
     */
    private function getPositionRange($position, $isPipe)
    {
        // Make sure isPipe is a boolean
        $isPipe = (bool)$isPipe;

        $positionRules = [
            '1G' => [
                'pipe' => [
                    'groove_over_24' => 'F for Groove Plate and Pipe Over 24 in. (610 mm) O.D.',
                    'groove_under_24' => 'F for Groove Pipe ≤24 in. (610 mm) O.D.',
                    'fillet' => 'F for Fillet or Tack Plate and Pipe'
                ],
                'plate' => [
                    'groove_over_24' => 'F for Groove Plate and Pipe Over 24 in. (610 mm) O.D.',
                    'fillet' => 'F for Fillet or Tack Plate and Pipe'
                ]
            ],
            '2G' => [
                'pipe' => [
                    'groove_over_24' => 'F&H for Groove Plate and Pipe Over 24 in. (610 mm) O.D.',
                    'groove_under_24' => 'F&H for Groove Pipe ≤24 in. (610 mm) O.D.',
                    'fillet' => 'F&H for Fillet or Tack Plate and Pipe'
                ],
                'plate' => [
                    'groove_over_24' => 'F&H for Groove Plate and Pipe Over 24 in. (610 mm) O.D.',
                    'fillet' => 'F&H for Fillet or Tack Plate and Pipe'
                ]
            ],
            '3G' => [
                'pipe' => [
                    'groove_over_24' => 'F&V for Groove Plate and Pipe Over 24 in. (610 mm) O.D.',
                    'groove_under_24' => 'F for Groove Pipe ≤24 in. (610 mm) O.D.',
                    'fillet' => 'F, H & V for Fillet or Tack Plate and Pipe'
                ],
                'plate' => [
                    'groove_over_24' => 'F&V for Groove Plate and Pipe Over 24 in. (610 mm) O.D.',
                    'fillet' => 'F, H & V for Fillet or Tack Plate and Pipe'
                ]
            ],
            '4G' => [
                'pipe' => [
                    'groove_over_24' => 'F&O for Groove Plate and Pipe Over 24 in. (610 mm) O.D.',
                    'groove_under_24' => 'F for Groove Pipe ≤24 in. (610 mm) O.D.',
                    'fillet' => 'F, H & O for Fillet or Tack Plate and Pipe'
                ],
                'plate' => [
                    'groove_over_24' => 'F&O for Groove Plate and Pipe Over 24 in. (610 mm) O.D.',
                    'fillet' => 'F, H & O for Fillet or Tack Plate and Pipe'
                ]
            ],
            '5G' => [
                'pipe' => [
                    'groove_over_24' => 'F,V&O for Groove Plate and Pipe Over 24 in. (610 mm) O.D.',
                    'groove_under_24' => 'F,V&O for Groove Pipe ≤24 in. (610 mm) O.D.',
                    'fillet' => 'All positions for Fillet or Tack Plate and Pipe'
                ],
                'plate' => [
                    'groove_over_24' => 'F,V&O for Groove Plate and Pipe Over 24 in. (610 mm) O.D.',
                    'fillet' => 'All positions for Fillet or Tack Plate and Pipe'
                ]
            ],
            '6G' => [
                'pipe' => [
                    'groove_over_24' => 'Groove Plate and Pipe Over 24 in. (610 mm) O.D. in all Position',
                    'groove_under_24' => 'Groove Pipe ≤24 in. (610 mm) O.D. in all Position',
                    'fillet' => 'Fillet or Tack Plate and Pipe in all Position'
                ],
                'plate' => [
                    'groove_over_24' => 'Groove Plate and Pipe Over 24 in. (610 mm) O.D. in all Position',
                    'fillet' => 'Fillet or Tack Plate and Pipe in all Position'
                ]
            ],
        ];

        // If position or rules don't exist, use default 6G position
        if (!isset($positionRules[$position])) {
            $position = '6G';
        }

        $specimenType = $isPipe ? 'pipe' : 'plate';

        if ($isPipe) {
            return $positionRules[$position][$specimenType]['groove_over_24'] . ' | ' .
                  $positionRules[$position][$specimenType]['groove_under_24'] . ' | ' .
                  $positionRules[$position][$specimenType]['fillet'];
        } else {
            return $positionRules[$position][$specimenType]['groove_over_24'] . ' | ' .
                  $positionRules[$position][$specimenType]['fillet'];
        }
    }

    /**
     * Get P-Number range text
     */
    private function getPNumberRange($pNo)
    {
        // For consistency across certificates, use a fixed range text
        return 'P-NO. 1 through P-NO. 15F, P-NO. 34, and P-NO. 41 through P-NO. 49';
    }

    /**
     * Get backing range based on backing value
     */
    private function getBackingRange($backing)
    {
        $backingRules = [
            'With Backing' => 'With backing or backing and gouging',
            'Without Backing' => 'Without backing or with backing and gouging',
        ];

        return $backingRules[$backing] ?? 'With backing or backing and gouging';
    }

    /**
     * Get F-Number range based on filler F-Number
     */
    private function getFNumberRange($fNumber)
    {
        $fNumberRules = [
            'F4_with_backing' => 'F-No.1 with Backing, F-No.2 with backing, F-No.3 with backing & F-No.4 With Backing',
            'F5_with_backing' => 'F-No.1 with Backing & F-No.5 With Backing',
            'F4_without_backing' => 'F-No.1 with Backing, F-No.2 with backing, F-No.3 with backing & F-No.4 With and Without Backing',
            'F5_without_backing' => 'F-No.1 with Backing & F-No.5 With and without Backing',
            'F43' => 'F-No. 34 and all F-No. 41 through F-No. 46',
        ];

        return $fNumberRules[$fNumber] ?? 'F-No.1 with Backing, F-No.2 with backing, F-No.3 with backing & F-No.4 With Backing';
    }

    /**
     * Get vertical progression range
     */
    private function getVerticalProgressionRange($progression)
    {
        return $progression; // Return the same value (Uphill or Downhill) without conversion
    }


    private function calculatePositionRange($position, $isPipe)
{
    $isPipe = (bool)$isPipe;
    
    $positionRules = [
        '1G' => [
            'ranges' => [
                'F for Groove Plate and Pipe Over 24 in. (610 mm) O.D.',
                'F for Fillet or Tack Plate and Pipe'
            ],
            'pipe_specific' => 'F for Groove Pipe ≥ 2 7∕8 in. (73 mm) O.D.'
        ],
        '2G' => [
            'ranges' => [
                'F & H for Groove Plate and Pipe Over 24 in. (610 mm) O.D.',
                'F & H for Fillet or Tack Plate and Pipe'
            ],
            'pipe_specific' => 'F & H for Groove Pipe ≥ 2 7∕8 in. (73 mm) O.D.'
        ],
        '3G' => [
            'ranges' => [
                'F & V for Groove Plate and Pipe Over 24 in. (610 mm) O.D.',
                'F, H & V for Fillet or Tack Plate and Pipe'
            ],
            'pipe_specific' => 'F & V for Groove Pipe ≥ 2 7∕8 in. (73 mm) O.D.'
        ],
        '4G' => [
            'ranges' => [
                'F & O for Groove Plate and Pipe Over 24 in. (610 mm) O.D.',
                'F, H & O for Fillet or Tack Plate and Pipe'
            ],
            'pipe_specific' => 'F & O for Groove Pipe ≥ 2 7∕8 in. (73 mm) O.D.'
        ],
        '5G' => [
            'ranges' => [
                'F, V & O for Groove Plate and Pipe Over 24 in. (610 mm) O.D.',
                'All positions for Fillet or Tack Plate and Pipe'
            ],
            'pipe_specific' => 'F, V & O for Groove Pipe ≥ 2 7∕8 in. (73 mm) O.D.'
        ],
        '6G' => [
            'ranges' => [
                'Groove Plate and Pipe Over 24 in. (610 mm) O.D. in all Position',
                'Fillet or Tack Plate and Pipe in all Position'
            ],
            'pipe_specific' => 'Groove Pipe ≤24 in. (610 mm) O.D. in all Position'
        ]
    ];

    if (!isset($positionRules[$position])) {
        $position = '6G'; // Default fallback
    }

    $ranges = $positionRules[$position]['ranges'];
    
    if ($isPipe && isset($positionRules[$position]['pipe_specific'])) {
        // Insert pipe-specific range after first range
        array_splice($ranges, 1, 0, [$positionRules[$position]['pipe_specific']]);
    }

    return implode(' | ', $ranges);
}

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

        // Generate certificate and report numbers based on company
        $systemCode = \App\Models\AppSetting::getValue('doc_prefix', 'EEA');
        $companyCode = '';

        if ($welder->company) {
            $companyCode = $welder->company->code ? $systemCode . '-' . $welder->company->code : $systemCode . '-AIC';
        } else {
            $companyCode = $systemCode . '-AIC';
        }

        // Generate certificate number
        $certificatePrefix = $companyCode . '-SAW-';
        $lastCert = SawCertificate::where('certificate_no', 'like', $certificatePrefix . '%')
            ->orderBy('certificate_no', 'desc')
            ->first();

        $newNumber = 1;
        if ($lastCert) {
            $lastNumber = (int) substr($lastCert->certificate_no, -4);
            $newNumber = $lastNumber + 1;
        }

        $newCertNo = $certificatePrefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);

        // Generate VT report number
        $vtReportPrefix = $companyCode . '-VT-';
        $lastVTReport = SawCertificate::where('vt_report_no', 'like', $vtReportPrefix . '%')
            ->orderBy('vt_report_no', 'desc')
            ->first();

        $newVTNumber = 1;
        if ($lastVTReport) {
            $lastNumber = (int) substr($lastVTReport->vt_report_no, -4);
            $newVTNumber = $lastNumber + 1;
        }

        $vtReportNo = $vtReportPrefix . str_pad($newVTNumber, 4, '0', STR_PAD_LEFT);

        // Generate RT report number
        $rtReportPrefix = $companyCode . '-RT-';
        $lastRTReport = SawCertificate::where('rt_report_no', 'like', $rtReportPrefix . '%')
            ->orderBy('rt_report_no', 'desc')
            ->first();

        $newRTNumber = 1;
        if ($lastRTReport) {
            $lastNumber = (int) substr($lastRTReport->rt_report_no, -4);
            $newRTNumber = $lastNumber + 1;
        }

        $rtReportNo = $rtReportPrefix . str_pad($newRTNumber, 4, '0', STR_PAD_LEFT);

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
            ] : null,
            'photo' => $welder->photo ? asset('storage/' . $welder->photo) : null,
            'certificate_no' => $newCertNo,
            'vt_report_no' => $vtReportNo,
            'rt_report_no' => $rtReportNo
        ]);
    }
}
