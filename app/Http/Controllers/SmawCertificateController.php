<?php

namespace App\Http\Controllers;

use App\Models\SmawCertificate;
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
use Illuminate\Support\Facades\Log;

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
            $query = SmawCertificate::with(['welder', 'company', 'createdBy']);

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
                    $actions .= '<a href="' . route('smaw-certificates.show', $certificate->id) . '" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>';
                    $actions .= '<a href="' . route('smaw-certificates.edit', $certificate->id) . '" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>';
                    $actions .= '<a href="' . route('smaw-certificates.certificate', $certificate->id) . '" class="btn btn-sm btn-success" target="_blank"><i class="fas fa-file-pdf"></i></a>';
                    $actions .= '<button class="btn btn-sm btn-info" data-toggle="modal" data-target="#rt-report-modal" data-certificate-id="' . $certificate->id . '" data-welder-id="' . $certificate->welder_id . '" data-certificate-type="smaw"><i class="fas fa-upload"></i></button>';
                    $actions .= '<form action="' . route('smaw-certificates.destroy', $certificate->id) . '" method="POST" class="d-inline delete-form">';
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
        $lastCert = SmawCertificate::where('certificate_no', 'like', $prefix . '%')
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
            'fillet_welds_plate', 'fillet_welds_pipe', 'test_result',
            'rt', 'ut'
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
            'certificate_no' => 'required|string|max:50|unique:smaw_certificates,certificate_no',
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
            'base_metal_p_no_from' => 'nullable|string|max:255',
            'base_metal_p_no_to' => 'nullable|string|max:255',
            'plate_specimen' => 'boolean',
            'pipe_specimen' => 'boolean',
            'pipe_diameter' => 'nullable|string|max:255',

            // Filler Metal Information
            'filler_metal_sfa_spec' => 'nullable|string|max:255',
            'filler_metal_classification' => 'nullable|string|max:255',
            'filler_spec' => 'nullable|string|max:255',
            'filler_spec_manual' => 'nullable|string|max:255',
            'filler_spec_range' => 'nullable|string|max:255',
            'filler_f_no' => 'nullable|string|max:255',
            'filler_f_no_manual' => 'nullable|string|max:255',
            'f_number_range' => 'nullable|string|max:255',
            'f_number_range_manual' => 'nullable|string|max:255',
            'f_number_range_span' => 'nullable|string|max:255',
            'filler_class' => 'nullable|string|max:255',
            'filler_class_manual' => 'nullable|string|max:255',
            'filler_class_range' => 'nullable|string|max:255',
            
            // Vertical progression
            'vertical_progression' => 'nullable|string|max:255',
            'vertical_progression_manual' => 'nullable|string|max:255',
            'vertical_progression_range' => 'nullable|string|max:255',
            'vertical_progression_range_manual' => 'nullable|string|max:255',

            // Testing Variables
            'welding_type' => 'required|string|max:255',
            'welding_process' => 'required|string|max:255',
            'visual_control_type' => 'nullable|string|max:255',
            'joint_tracking' => 'nullable|string|max:255',
            'test_position' => 'required|string|max:255',
            'position_range' => 'nullable|string',
            'backing' => 'required|string|max:255',
            'backing_range' => 'nullable|string',
            'passes_per_side' => 'nullable|string|max:255',
            'p_number_range_span' => 'nullable|string|max:255',
            'p_number_range_manual' => 'nullable|string|max:255',
            'smaw_thickness' => 'nullable|string|max:255',
            'smaw_thickness_range' => 'nullable|string|max:255',

            // Test Results
            'visual_examination_result' => 'nullable|string|max:255',
            'vt_report_no' => 'nullable|string|max:255',
            'alternative_volumetric_result' => 'nullable|string|max:255',
            'rt_report_no' => 'nullable|string|max:255',
            'rt_doc_no' => 'nullable|string|max:255',
            'test_result' => 'boolean',
            'rt' => 'boolean',
            'ut' => 'boolean',

            // Personnel Information
            'evaluated_by' => 'required|string|max:255',
            'evaluated_company' => 'required_if:rt,0|required_if:ut,0|nullable|string|max:255',
            'mechanical_tests_by' => 'required_if:rt,0|required_if:ut,0|nullable|string|max:255',
            'lab_test_no' => 'nullable|string|max:255',
            'welding_supervised_by' => 'nullable|string|max:255',
            'supervised_company' => 'required|string|max:255',
            'certification_text' => 'required|string|max:500',

            // Organization Section
            'test_witnessed_by' => 'nullable|string|max:255',
            'witness_name' => 'nullable|string|max:255',
            'witness_signature' => 'nullable|string',
            'witness_date' => 'nullable|date',

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

        // Define all boolean fields
        $booleanFields = [
            'test_coupon', 'production_weld', 'plate_specimen', 'pipe_specimen',
            'rt', 'ut', 'fillet_welds_plate', 'fillet_welds_pipe', 'pipe_macro_fusion', 'plate_macro_fusion',
            'smaw_yes', 'smaw_no', 'test_result'
        ];
        
        // Set default false values for boolean fields
        foreach ($booleanFields as $field) {
            if (!isset($validated[$field])) {
                $validated[$field] = false;
            } else if (is_string($validated[$field]) && ($validated[$field] === 'on' || $validated[$field] === 'true' || $validated[$field] === '1')) {
                $validated[$field] = true;
            } else if (is_bool($validated[$field])) {
                // Keep boolean value as is
                $validated[$field] = $validated[$field];
            } else {
                $validated[$field] = false;
            }
        }

        // Handle photo upload
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoPath = $photo->store('smaw_certificates/photos', 'public');
            $validated['photo_path'] = $photoPath;
        }

        // Debug to check what's being received
        Log::debug('SMAW Certificate Store Debug', [
            'request_boolean_fields' => [
                'plate_specimen' => $request->input('plate_specimen'),
                'pipe_specimen' => $request->input('pipe_specimen'),
                'rt' => $request->input('rt'),
                'ut' => $request->input('ut'),
                'test_result' => $request->input('test_result')
            ],
            'validated_boolean_fields' => [
                'plate_specimen' => $validated['plate_specimen'] ?? 'not set',
                'pipe_specimen' => $validated['pipe_specimen'] ?? 'not set',
                'rt' => $validated['rt'] ?? 'not set',
                'ut' => $validated['ut'] ?? 'not set',
                'test_result' => $validated['test_result'] ?? 'not set'
            ],
            'test_position' => $validated['test_position'] ?? 'not set'
        ]);
        
        // Set default values if not provided
        $validated['visual_examination_result'] = $validated['visual_examination_result'] ?? 'Accepted';
        $validated['alternative_volumetric_result'] = $validated['alternative_volumetric_result'] ?? 'ACC';
        $validated['test_witnessed_by'] = $validated['test_witnessed_by'] ?? 'ELITE ENGINEERING ARABIA';
        
        // Use the frontend value for smaw_thickness_range directly
        // No calculation needed in backend as the frontend handles it
        if (!isset($validated['smaw_thickness_range']) || empty($validated['smaw_thickness_range'])) {
            // Just set a default value if somehow missing
            $validated['smaw_thickness_range'] = 'Not specified';
        }

        // Add current user as creator
        $validated['created_by'] = Auth::id();
        
        // Extract thickness from dia_thickness for 'thickness' field only
        if (isset($validated['dia_thickness'])) {
            // Try to extract the thickness value from the dia_thickness string
            // Assuming format like "8 inch x 18.26 mm"
            if (preg_match('/x\s*([\d.]+)\s*mm/i', $validated['dia_thickness'], $matches)) {
                $validated['thickness'] = $matches[1];
                // Only set smaw_thickness if not provided by user
                if (!isset($request->smaw_thickness) || empty($request->smaw_thickness)) {
                    $validated['smaw_thickness'] = $matches[1];
                }
            } elseif (preg_match('/([\d.]+)\s*mm/i', $validated['dia_thickness'], $matches)) {
                $validated['thickness'] = $matches[1];
                // Only set smaw_thickness if not provided by user
                if (!isset($request->smaw_thickness) || empty($request->smaw_thickness)) {
                    $validated['smaw_thickness'] = $matches[1];
                }
            } else {
                // If no thickness value can be extracted, use a default value
                $validated['thickness'] = '0';
                // Only set smaw_thickness if not provided by user
                if (!isset($request->smaw_thickness) || empty($request->smaw_thickness)) {
                    $validated['smaw_thickness'] = '0';
                }
            }
        } else {
            $validated['thickness'] = '0'; // Default value if dia_thickness is not set
            // Only set smaw_thickness if not provided by user
            if (!isset($request->smaw_thickness) || empty($request->smaw_thickness)) {
                $validated['smaw_thickness'] = '0';
            }
        }
        
        // Ensure base_metal_p_no is set
        if (!isset($validated['base_metal_p_no']) || empty($validated['base_metal_p_no'])) {
            // Try to use base_metal_p_no_from and base_metal_p_no_to if available
            if (isset($validated['base_metal_p_no_from']) && isset($validated['base_metal_p_no_to'])) {
                $validated['base_metal_p_no'] = $validated['base_metal_p_no_from'] . ' TO ' . $validated['base_metal_p_no_to'];
            } elseif (isset($validated['base_metal_p_no_manual']) && !empty($validated['base_metal_p_no_manual'])) {
                $validated['base_metal_p_no'] = $validated['base_metal_p_no_manual'];
            } else {
                // Default value if all else fails
                $validated['base_metal_p_no'] = 'P NO.1 TO P NO.1';
            }
        }
        
        // Debug to check what's being received
        Log::debug('P_NUMBER_RANGE debug', [
            'request_all' => $request->all(),
            'has_p_number_range_span' => $request->has('p_number_range_span'),
            'p_number_range_span' => $request->input('p_number_range_span'),
            'validated_p_number_range_span' => $validated['p_number_range_span'] ?? 'not set',
            'p_number_range_manual' => $request->input('p_number_range_manual'),
        ]);
        
        // Ensure filler_spec is set
        if (!isset($validated['filler_spec']) || empty($validated['filler_spec'])) {
            if (isset($validated['filler_spec_manual']) && !empty($validated['filler_spec_manual'])) {
                $validated['filler_spec'] = $validated['filler_spec_manual'];
            } else {
                // Default value if all else fails
                $validated['filler_spec'] = 'AWS A5.1';
            }
        }
        
        // Ensure filler_class is set
        if (!isset($validated['filler_class']) || empty($validated['filler_class'])) {
            if (isset($validated['filler_class_manual']) && !empty($validated['filler_class_manual'])) {
                $validated['filler_class'] = $validated['filler_class_manual'];
            } else {
                // Default value if all else fails
                $validated['filler_class'] = 'E7018';
            }
        }
        
        // Ensure filler_f_no is set
        if (!isset($validated['filler_f_no']) || empty($validated['filler_f_no'])) {
            if (isset($validated['filler_f_no_manual']) && !empty($validated['filler_f_no_manual'])) {
                $validated['filler_f_no'] = $validated['filler_f_no_manual'];
            } else {
                // Default value if all else fails
                $validated['filler_f_no'] = 'F4';
            }
        }
        
        // Ensure f_number_range is set
        if (!isset($validated['f_number_range']) || empty($validated['f_number_range'])) {
            // First check the span value
            if (isset($validated['f_number_range_span']) && !empty($validated['f_number_range_span'])) {
                $validated['f_number_range'] = $validated['f_number_range_span'];
            }
            // If not in validated data, check the raw request
            elseif ($request->has('f_number_range_span') && !empty($request->input('f_number_range_span'))) {
                $validated['f_number_range'] = $request->input('f_number_range_span');
            }
            // Try the manual entry
            elseif (isset($validated['f_number_range_manual']) && !empty($validated['f_number_range_manual'])) {
                $validated['f_number_range'] = $validated['f_number_range_manual'];
            }
            elseif ($request->has('f_number_range_manual') && !empty($request->input('f_number_range_manual'))) {
                $validated['f_number_range'] = $request->input('f_number_range_manual');
            }
            else {
                // Default value if all else fails
                $validated['f_number_range'] = 'F1 to F6';
            }
        }

        // Ensure p_number_range is set
        if (!isset($validated['p_number_range']) || empty($validated['p_number_range'])) {
            // First check the validated data
            if (isset($validated['p_number_range_span']) && !empty($validated['p_number_range_span'])) {
                $validated['p_number_range'] = $validated['p_number_range_span'];
                Log::debug('Using validated p_number_range_span: ' . $validated['p_number_range_span']);
            } 
            // If not in validated data, check the raw request
            elseif ($request->has('p_number_range_span') && !empty($request->input('p_number_range_span'))) {
                $validated['p_number_range'] = $request->input('p_number_range_span');
                Log::debug('Using request p_number_range_span: ' . $request->input('p_number_range_span'));
            }
            // Try the manual entry
            elseif (isset($validated['p_number_range_manual']) && !empty($validated['p_number_range_manual'])) {
                $validated['p_number_range'] = $validated['p_number_range_manual'];
                Log::debug('Using validated p_number_range_manual: ' . $validated['p_number_range_manual']);
            }
            elseif ($request->has('p_number_range_manual') && !empty($request->input('p_number_range_manual'))) {
                $validated['p_number_range'] = $request->input('p_number_range_manual');
                Log::debug('Using request p_number_range_manual: ' . $request->input('p_number_range_manual'));
            }
            else {
                // Use base_metal_p_no as fallback for p_number_range
                $validated['p_number_range'] = $validated['base_metal_p_no'] ?? 'P NO.1 TO P NO.15F';
                Log::debug('Using fallback p_number_range: ' . $validated['p_number_range']);
            }
        }
        
        // Ensure vertical_progression is set
        if (!isset($validated['vertical_progression']) || empty($validated['vertical_progression'])) {
            if (isset($validated['vertical_progression_manual']) && !empty($validated['vertical_progression_manual'])) {
                $validated['vertical_progression'] = $validated['vertical_progression_manual'];
            } else {
                // Default value if all else fails
                $validated['vertical_progression'] = 'Uphill';
            }
        }
        
        // Ensure vertical_progression_range is set
        if (!isset($validated['vertical_progression_range']) || empty($validated['vertical_progression_range'])) {
            // First check the request directly
            if ($request->has('vertical_progression_range') && !empty($request->input('vertical_progression_range'))) {
                $validated['vertical_progression_range'] = $request->input('vertical_progression_range');
                Log::debug('Using request vertical_progression_range: ' . $request->input('vertical_progression_range'));
            }
            // Try the manual entry
            elseif (isset($validated['vertical_progression_range_manual']) && !empty($validated['vertical_progression_range_manual'])) {
                $validated['vertical_progression_range'] = $validated['vertical_progression_range_manual'];
                Log::debug('Using validated vertical_progression_range_manual: ' . $validated['vertical_progression_range_manual']);
            }
            elseif ($request->has('vertical_progression_range_manual') && !empty($request->input('vertical_progression_range_manual'))) {
                $validated['vertical_progression_range'] = $request->input('vertical_progression_range_manual');
                Log::debug('Using request vertical_progression_range_manual: ' . $request->input('vertical_progression_range_manual'));
            }
            else {
                // Use vertical_progression as fallback
                $validated['vertical_progression_range'] = $validated['vertical_progression'] ?? 'Uphill';
                Log::debug('Using fallback vertical_progression_range: ' . $validated['vertical_progression_range']);
            }
        }

        // Generate automatic ranges based on selections
        $validated = $this->generateQualificationRanges($validated);

        // Create the certificate
        try {
            DB::beginTransaction();

            $certificate = SmawCertificate::create($validated);

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
            return redirect()->route('smaw-certificates.certificate', $certificate)
                            ->with('success', 'SMAW Certificate created successfully.');
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
        $certificate = SmawCertificate::with(['welder', 'company', 'createdBy'])->findOrFail($id);
        return view('smaw_certificates.show', compact('certificate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $certificate = SmawCertificate::findOrFail($id);
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
        // Log incoming request data for debugging without stopping execution
        Log::debug('SMAW Certificate Update - Received Data:', [
            'certificate_id' => $id,
            'test_position' => $request->input('test_position'),
            'vertical_progression' => $request->input('vertical_progression'),
            'position_range' => $request->input('position_range'),
            'pipe_specimen' => $request->input('pipe_specimen'),
            'plate_specimen' => $request->input('plate_specimen'),
            'deposit_thickness' => $request->input('deposit_thickness'),
            'deposit_thickness_range' => $request->input('deposit_thickness_range'),
        ]);
        
        // Similar validation logic as store method
        $booleanFields = [
            'test_coupon', 'production_weld', 'plate_specimen', 'pipe_specimen',
            'filler_metal_used', 'transverse_face_root_bends', 'longitudinal_bends',
            'side_bends', 'pipe_bend_corrosion', 'plate_bend_corrosion',
            'pipe_macro_fusion', 'plate_macro_fusion', 'rt_selected', 'ut_selected',
            'fillet_welds_plate', 'fillet_welds_pipe', 'test_result',
            'rt', 'ut'
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
            'certificate_no' => 'required|string|max:50|unique:smaw_certificates,certificate_no,' . $id,
            'welder_id' => 'required|exists:welders,id',
            'company_id' => 'required|exists:companies,id',
            'wps_followed' => 'required|string|max:255',
            'test_date' => 'required|date',
            'base_metal_spec' => 'required|string|max:255',
            'dia_thickness' => 'required|string|max:255',
            'test_result' => 'boolean',
            'rt' => 'boolean',
            'ut' => 'boolean',
            'welding_supervised_by' => 'sometimes|string|max:255',
            'witness_date' => 'sometimes|date',
            'evaluated_by' => 'required|string|max:255',
            'evaluated_company' => 'required_if:rt,0|required_if:ut,0|nullable|string|max:255',
            'mechanical_tests_by' => 'required_if:rt,0|required_if:ut,0|nullable|string|max:255',
            'supervised_company' => 'required|string|max:255',
            'certification_text' => 'required|string|max:500',
            'p_number_range_span' => 'nullable|string|max:255',
            'p_number_range_manual' => 'nullable|string|max:255',
            'base_metal_p_no_from' => 'nullable|string|max:255',
            'base_metal_p_no_to' => 'nullable|string|max:255',
            'base_metal_p_no_manual' => 'nullable|string|max:255',
            'smaw_thickness' => 'nullable|string|max:255',
            'smaw_thickness_range' => 'nullable|string|max:255',
            'deposit_thickness' => 'nullable|string|max:255',
            'deposit_thickness_range' => 'nullable|string|max:255',
            'filler_spec' => 'nullable|string|max:255',
            'filler_spec_manual' => 'nullable|string|max:255',
            'filler_spec_range' => 'nullable|string|max:255',
            'filler_f_no' => 'nullable|string|max:255',
            'filler_f_no_manual' => 'nullable|string|max:255',
            'f_number_range' => 'nullable|string|max:255',
            'f_number_range_manual' => 'nullable|string|max:255',
            'f_number_range_span' => 'nullable|string|max:255',
            'filler_class' => 'nullable|string|max:255',
            'filler_class_manual' => 'nullable|string|max:255',
            'filler_class_range' => 'nullable|string|max:255',
            
            // Vertical progression
            'vertical_progression' => 'nullable|string|max:255',
            'vertical_progression_manual' => 'nullable|string|max:255',
            'vertical_progression_range' => 'nullable|string|max:255',
            'vertical_progression_range_manual' => 'nullable|string|max:255',
            
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
        
        // Define all boolean fields
        $booleanFields = [
            'test_coupon', 'production_weld', 'plate_specimen', 'pipe_specimen',
            'rt', 'ut', 'fillet_welds_plate', 'fillet_welds_pipe', 'pipe_macro_fusion', 'plate_macro_fusion',
            'smaw_yes', 'smaw_no', 'test_result'
        ];
        
        // Set default false values for boolean fields
        foreach ($booleanFields as $field) {
            if (!isset($validated[$field])) {
                $validated[$field] = false;
            } else if (is_string($validated[$field]) && ($validated[$field] === 'on' || $validated[$field] === 'true' || $validated[$field] === '1')) {
                $validated[$field] = true;
            } else if (is_bool($validated[$field])) {
                // Keep boolean value as is
                $validated[$field] = $validated[$field];
            } else {
                $validated[$field] = false;
            }
        }

        // Extract thickness from dia_thickness for 'thickness' field only
        if (isset($validated['dia_thickness'])) {
            // Try to extract the thickness value from the dia_thickness string
            // Assuming format like "8 inch x 18.26 mm"
            if (preg_match('/x\s*([\d.]+)\s*mm/i', $validated['dia_thickness'], $matches)) {
                $validated['thickness'] = $matches[1];
                // Only set smaw_thickness if not provided by user
                if (!isset($request->smaw_thickness) || empty($request->smaw_thickness)) {
                    $validated['smaw_thickness'] = $matches[1];
                }
            } elseif (preg_match('/([\d.]+)\s*mm/i', $validated['dia_thickness'], $matches)) {
                $validated['thickness'] = $matches[1];
                // Only set smaw_thickness if not provided by user
                if (!isset($request->smaw_thickness) || empty($request->smaw_thickness)) {
                    $validated['smaw_thickness'] = $matches[1];
                }
            } else {
                // If no thickness value can be extracted, use a default value
                $validated['thickness'] = '0';
                // Only set smaw_thickness if not provided by user
                if (!isset($request->smaw_thickness) || empty($request->smaw_thickness)) {
                    $validated['smaw_thickness'] = '0';
                }
            }
        } else {
            $validated['thickness'] = '0'; // Default value if dia_thickness is not set
            // Only set smaw_thickness if not provided by user
            if (!isset($request->smaw_thickness) || empty($request->smaw_thickness)) {
                $validated['smaw_thickness'] = '0';
            }
        }
        
        // Ensure base_metal_p_no is set
        if (!isset($validated['base_metal_p_no']) || empty($validated['base_metal_p_no'])) {
            // Try to use base_metal_p_no_from and base_metal_p_no_to if available
            if (isset($validated['base_metal_p_no_from']) && isset($validated['base_metal_p_no_to'])) {
                $validated['base_metal_p_no'] = $validated['base_metal_p_no_from'] . ' TO ' . $validated['base_metal_p_no_to'];
            } elseif (isset($validated['base_metal_p_no_manual']) && !empty($validated['base_metal_p_no_manual'])) {
                $validated['base_metal_p_no'] = $validated['base_metal_p_no_manual'];
            } else {
                // Default value if all else fails
                $validated['base_metal_p_no'] = 'P NO.1 TO P NO.1';
            }
        }
        
        // Debug to check what's being received
        Log::debug('SMAW Certificate Update Debug', [
            'request_boolean_fields' => [
                'plate_specimen' => $request->input('plate_specimen'),
                'pipe_specimen' => $request->input('pipe_specimen'),
                'rt' => $request->input('rt'),
                'ut' => $request->input('ut'),
                'test_result' => $request->input('test_result')
            ],
            'validated_boolean_fields' => [
                'plate_specimen' => $validated['plate_specimen'] ?? 'not set',
                'pipe_specimen' => $validated['pipe_specimen'] ?? 'not set',
                'rt' => $validated['rt'] ?? 'not set',
                'ut' => $validated['ut'] ?? 'not set',
                'test_result' => $validated['test_result'] ?? 'not set'
            ],
            'test_position' => $validated['test_position'] ?? 'not set',
            'has_p_number_range_span' => $request->has('p_number_range_span'),
            'p_number_range_span' => $request->input('p_number_range_span'),
            'validated_p_number_range_span' => $validated['p_number_range_span'] ?? 'not set'
        ]);
        
        // Ensure filler_spec is set
        if (!isset($validated['filler_spec']) || empty($validated['filler_spec'])) {
            if (isset($validated['filler_spec_manual']) && !empty($validated['filler_spec_manual'])) {
                $validated['filler_spec'] = $validated['filler_spec_manual'];
            } else {
                // Default value if all else fails
                $validated['filler_spec'] = 'AWS A5.1';
            }
        }
        
        // Ensure filler_class is set
        if (!isset($validated['filler_class']) || empty($validated['filler_class'])) {
            if (isset($validated['filler_class_manual']) && !empty($validated['filler_class_manual'])) {
                $validated['filler_class'] = $validated['filler_class_manual'];
            } else {
                // Default value if all else fails
                $validated['filler_class'] = 'E7018';
            }
        }
        
        // Ensure filler_f_no is set
        if (!isset($validated['filler_f_no']) || empty($validated['filler_f_no'])) {
            if (isset($validated['filler_f_no_manual']) && !empty($validated['filler_f_no_manual'])) {
                $validated['filler_f_no'] = $validated['filler_f_no_manual'];
            } else {
                // Default value if all else fails
                $validated['filler_f_no'] = 'F4';
            }
        }
        
        // Ensure f_number_range is set
        if (!isset($validated['f_number_range']) || empty($validated['f_number_range'])) {
            // First check the span value
            if (isset($validated['f_number_range_span']) && !empty($validated['f_number_range_span'])) {
                $validated['f_number_range'] = $validated['f_number_range_span'];
            }
            // If not in validated data, check the raw request
            elseif ($request->has('f_number_range_span') && !empty($request->input('f_number_range_span'))) {
                $validated['f_number_range'] = $request->input('f_number_range_span');
            }
            // Try the manual entry
            elseif (isset($validated['f_number_range_manual']) && !empty($validated['f_number_range_manual'])) {
                $validated['f_number_range'] = $validated['f_number_range_manual'];
            }
            elseif ($request->has('f_number_range_manual') && !empty($request->input('f_number_range_manual'))) {
                $validated['f_number_range'] = $request->input('f_number_range_manual');
            }
            else {
                // Default value if all else fails
                $validated['f_number_range'] = 'F1 to F6';
            }
        }

        // Ensure p_number_range is set
        if (!isset($validated['p_number_range']) || empty($validated['p_number_range'])) {
            // First check the validated data
            if (isset($validated['p_number_range_span']) && !empty($validated['p_number_range_span'])) {
                $validated['p_number_range'] = $validated['p_number_range_span'];
                Log::debug('Using validated p_number_range_span: ' . $validated['p_number_range_span']);
            } 
            // If not in validated data, check the raw request
            elseif ($request->has('p_number_range_span') && !empty($request->input('p_number_range_span'))) {
                $validated['p_number_range'] = $request->input('p_number_range_span');
                Log::debug('Using request p_number_range_span: ' . $request->input('p_number_range_span'));
            }
            // Try the manual entry
            elseif (isset($validated['p_number_range_manual']) && !empty($validated['p_number_range_manual'])) {
                $validated['p_number_range'] = $validated['p_number_range_manual'];
                Log::debug('Using validated p_number_range_manual: ' . $validated['p_number_range_manual']);
            }
            elseif ($request->has('p_number_range_manual') && !empty($request->input('p_number_range_manual'))) {
                $validated['p_number_range'] = $request->input('p_number_range_manual');
                Log::debug('Using request p_number_range_manual: ' . $request->input('p_number_range_manual'));
            }
            else {
                // Use base_metal_p_no as fallback for p_number_range
                $validated['p_number_range'] = $validated['base_metal_p_no'] ?? 'P NO.1 TO P NO.15F';
                Log::debug('Using fallback p_number_range: ' . $validated['p_number_range']);
            }
        }
        
        // Ensure vertical_progression is set
        if (!isset($validated['vertical_progression']) || empty($validated['vertical_progression'])) {
            if (isset($validated['vertical_progression_manual']) && !empty($validated['vertical_progression_manual'])) {
                $validated['vertical_progression'] = $validated['vertical_progression_manual'];
            } else {
                // Default value if all else fails
                $validated['vertical_progression'] = 'Uphill';
            }
        }
        
        // Ensure vertical_progression_range is set
        if (!isset($validated['vertical_progression_range']) || empty($validated['vertical_progression_range'])) {
            // First check the request directly
            if ($request->has('vertical_progression_range') && !empty($request->input('vertical_progression_range'))) {
                $validated['vertical_progression_range'] = $request->input('vertical_progression_range');
                Log::debug('Using request vertical_progression_range: ' . $request->input('vertical_progression_range'));
            }
            // Try the manual entry
            elseif (isset($validated['vertical_progression_range_manual']) && !empty($validated['vertical_progression_range_manual'])) {
                $validated['vertical_progression_range'] = $validated['vertical_progression_range_manual'];
                Log::debug('Using validated vertical_progression_range_manual: ' . $validated['vertical_progression_range_manual']);
            }
            elseif ($request->has('vertical_progression_range_manual') && !empty($request->input('vertical_progression_range_manual'))) {
                $validated['vertical_progression_range'] = $request->input('vertical_progression_range_manual');
                Log::debug('Using request vertical_progression_range_manual: ' . $request->input('vertical_progression_range_manual'));
            }
            else {
                // Use vertical_progression as fallback
                $validated['vertical_progression_range'] = $validated['vertical_progression'] ?? 'Uphill';
                Log::debug('Using fallback vertical_progression_range: ' . $validated['vertical_progression_range']);
            }
        }
        
        // Handle photo upload
        if ($request->hasFile('photo')) {
            $certificate = SmawCertificate::findOrFail($id);
            
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

            // Find the certificate
            $certificate = SmawCertificate::findOrFail($id);
            
            // Special handling for test_position to make sure it's always saved
            if (!empty($request->input('test_position'))) {
                $validated['test_position'] = $request->input('test_position');
            }
            
            // Ensure position_range is saved
            if (empty($validated['position_range']) && !empty($request->input('position_range'))) {
                $validated['position_range'] = $request->input('position_range');
            }
            
            // Handle specimen checkboxes explicitly
            $validated['plate_specimen'] = filter_var($request->input('plate_specimen', false), FILTER_VALIDATE_BOOLEAN);
            $validated['pipe_specimen'] = filter_var($request->input('pipe_specimen', false), FILTER_VALIDATE_BOOLEAN);
            
            // Update the certificate
            $certificate->update($validated);

            // Log the updated certificate data
            Log::info('SMAW Certificate Updated Successfully', [
                'id' => $certificate->id,
                'test_position' => $certificate->test_position,
                'position_range' => $certificate->position_range,
                'plate_specimen' => $certificate->plate_specimen,
                'pipe_specimen' => $certificate->pipe_specimen,
                'vertical_progression' => $certificate->vertical_progression,
                'smaw_thickness' => $certificate->smaw_thickness,
                'smaw_thickness_range' => $certificate->smaw_thickness_range,
                'deposit_thickness' => $certificate->deposit_thickness,
                'deposit_thickness_range' => $certificate->deposit_thickness_range,
                'request_smaw_thickness' => $request->smaw_thickness,
                'request_deposit_thickness' => $request->deposit_thickness
            ]);

            DB::commit();

            if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
                return response()->json([
                    'success' => true,
                    'message' => 'SMAW Certificate updated successfully.',
                    'redirect' => route('smaw-certificates.certificate', $certificate),
                    'certificate' => $certificate
                ]);
            }

            return redirect()->route('smaw-certificates.certificate', $certificate)
                            ->with('success', 'SMAW Certificate updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating SMAW Certificate: ' . $e->getMessage(), [
                'id' => $id,
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

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
     */
    public function destroy($id)
    {
        $certificate = SmawCertificate::findOrFail($id);

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
        $certificate = SmawCertificate::with('welder.company', 'company')->findOrFail($id);

        // Generate QR Code for certificate verification
        $verificationUrl = route('smaw-certificates.verify', ['id' => $certificate->id, 'code' => $certificate->verification_code]);
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
        $certificate = SmawCertificate::with(['welder', 'company'])->findOrFail($id);

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

        $certificate = SmawCertificate::where('certificate_no', $validated['certificate_no'])->first();

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

        // For SMAW certificates, these values are fixed
        $data['welding_type'] = 'Manual';
        $data['welding_type_range'] = 'Manual';
        $data['welding_process'] = 'SMAW';
        $data['welding_process_range'] = 'SMAW';
        $data['visual_control_range'] = $data['visual_control_type'] ?? 'Direct Visual Control';

        // Set joint tracking range
        if (($data['joint_tracking'] ?? '') === 'With Automatic joint tracking') {
            $data['joint_tracking_range'] = 'With Automatic joint tracking';
        } else {
            $data['joint_tracking_range'] = 'With & Without Automatic joint tracking';
        }
        
        // Use the frontend value for smaw_thickness_range directly
        // No calculation needed in backend as the frontend handles it
        if (!isset($data['smaw_thickness_range']) || empty($data['smaw_thickness_range'])) {
            // Just set a default value if somehow missing
            $data['smaw_thickness_range'] = 'Not specified';
        }
        
        // Set default values for filler metal fields if not set
        if (!isset($data['filler_spec']) || empty($data['filler_spec'])) {
            $data['filler_spec'] = 'AWS A5.1';
        }
        
        if (!isset($data['filler_class']) || empty($data['filler_class'])) {
            $data['filler_class'] = 'E7018';
        }
        
        if (!isset($data['filler_f_no']) || empty($data['filler_f_no'])) {
            $data['filler_f_no'] = 'F4';
        }
        
        // Set default ranges for filler metal specifications
        if (!isset($data['filler_spec_range']) || empty($data['filler_spec_range'])) {
            $data['filler_spec_range'] = 'AWS A5.1, A5.5';
        }
        
        if (!isset($data['filler_class_range']) || empty($data['filler_class_range'])) {
            $data['filler_class_range'] = 'E7018, E7018-1';
        }
        
        if (!isset($data['f_number_range']) || empty($data['f_number_range'])) {
            $data['f_number_range'] = 'F1 to F6';
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