<?php

namespace App\Http\Controllers;

use App\Models\FcawCertificate;
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

class FcawCertificateController extends Controller
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
            $query = FcawCertificate::with(['welder', 'company', 'createdBy']);
            
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
                    $actions .= '<a href="' . route('fcaw-certificates.show', $certificate->id) . '" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>';
                    $actions .= '<a href="' . route('fcaw-certificates.edit', $certificate->id) . '" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>';
                    $actions .= '<a href="' . route('fcaw-certificates.certificate', $certificate->id) . '" class="btn btn-sm btn-success" target="_blank"><i class="fas fa-file-pdf"></i></a>';
                    $actions .= '<form action="' . route('fcaw-certificates.destroy', $certificate->id) . '" method="POST" class="d-inline delete-form">';
                    $actions .= csrf_field() . method_field('DELETE');
                    $actions .= '<button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this certificate?\')"><i class="fas fa-trash"></i></button>';
                    $actions .= '</form>';
                    $actions .= '</div>';
                    return $actions;
                })
                ->rawColumns(['actions'])
                ->make(true);
        }
        
        // For normal page view, just return the view with filters
        return view('fcaw_certificates.index', compact('welders', 'companies'));
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
        $welders = Welder::orderBy('name')->get();
        
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
        
        $prefix = 'FCAW-' . $companyCode . '-';
        
        // Find last certificate number for this company
        $lastCert = FcawCertificate::where('certificate_no', 'like', $prefix . '%')
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
        
        return view('fcaw_certificates.create', compact(
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
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   /**
 * Store a newly created resource in storage.
 *
 * @param  \Illuminate\Http\Request  $request
 * @return \Illuminate\Http\Response
 */
public function store(Request $request)
{
    // Transform boolean checkbox values to ensure they're properly processed
    $booleanFields = [
        'test_coupon', 'production_weld', 'plate_specimen', 'pipe_specimen', 'rt', 'ut',
        'fillet_welds_plate', 'fillet_welds_pipe', 'pipe_macro_fusion', 'plate_macro_fusion',
        'transverse_face_root', 'longitudinal_bends', 'side_bends',
        'pipe_bend_corrosion', 'plate_bend_corrosion', 'gtaw_process', 'fcaw_process'
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

    // Handle manual entry fields properly
    if (isset($data['filler_spec']) && $data['filler_spec'] === '__manual__') {
        $data['filler_spec'] = $data['filler_spec_manual'] ?? '';
    }
    
    if (isset($data['filler_class']) && $data['filler_class'] === '__manual__') {
        $data['filler_class'] = $data['filler_class_manual'] ?? '';
    }
    
    if (isset($data['filler_f_no']) && $data['filler_f_no'] === '__manual__') {
        $data['filler_f_no'] = $data['filler_f_no_manual'] ?? '';
    }
    
    if (isset($data['base_metal_p_no']) && $data['base_metal_p_no'] === '__manual__') {
        $data['base_metal_p_no'] = $data['base_metal_p_no_manual'] ?? '';
    }
    
    if (isset($data['backing']) && $data['backing'] === '__manual__') {
        $data['backing'] = $data['backing_manual'] ?? '';
    }

    // Create a validator instance with the transformed data
    $validator = Validator::make($data, [
        // Certificate identification
        'certificate_no' => 'required|string|max:50|unique:fcaw_certificates,certificate_no',
        'welder_id' => 'required|exists:welders,id',
        'company_id' => 'required|exists:companies,id',
        'wps_followed' => 'required|string|max:255',
        'test_date' => 'required|date',
        'revision_no' => 'nullable|string|max:50',

        // Specimen details
        'test_coupon' => 'boolean',
        'production_weld' => 'boolean',
        'plate_specimen' => 'boolean',
        'pipe_specimen' => 'boolean',
        'base_metal_spec' => 'required|string|max:255',
        'diameter' => function($attribute, $value, $fail) use ($data) {
            if (!empty($data['pipe_specimen']) && $data['pipe_specimen']) {
                if (empty($value)) {
                    $fail('The diameter field is required when pipe specimen is selected.');
                }
            }
        },
        'thickness' => function($attribute, $value, $fail) use ($data) {
            if (empty($value)) {
                if (!empty($data['pipe_specimen']) && $data['pipe_specimen']) {
                    $fail('The thickness field is required when pipe specimen is selected.');
                }
            }
        },

        // Pipe information
        'pipe_diameter_type' => 'nullable|string|max:255',
        'pipe_diameter_manual' => 'nullable|string|max:255',
        'diameter_range' => 'nullable|string',
        
        // Metal and filler details
        'base_metal_p_no' => 'required|string|max:255',
        'base_metal_p_no_manual' => 'nullable|string|max:255',
        'p_number_range' => 'nullable|string',
        'p_number_range_manual' => 'nullable|string|max:255',
        
        // Position details
        'test_position' => 'required|string|max:255',
        'position_range' => 'nullable|string',
        'position_range_manual' => 'nullable|string|max:255',
        
        // Backing details
        'backing' => 'required|string|max:255',
        'backing_manual' => 'nullable|string|max:255',
        'backing_range' => 'nullable|string',
        
        // Vertical progression
        'vertical_progression' => 'required|string|max:255',
        'vertical_progression_range' => 'nullable|string',
        
        // Filler details
        'filler_spec' => 'required|string|max:255',
        'filler_spec_manual' => 'nullable|string|max:255',
        'filler_spec_range' => 'nullable|string',
        'filler_class' => 'required|string|max:255',
        'filler_class_manual' => 'nullable|string|max:255',
        'filler_class_range' => 'nullable|string',
        'filler_f_no' => 'required|string|max:255',
        'filler_f_no_manual' => 'nullable|string|max:255',
        'f_number_range' => 'nullable|string',
        
        // Process fields
        'fcaw_thickness' => 'nullable|string|max:255',
        'fcaw_thickness_range' => 'nullable|string|max:255',
        'deposit_thickness' => 'nullable|string|max:255',
        'deposit_thickness_range' => 'nullable|string|max:255',
        'welding_process' => 'nullable|string|max:255',
        'welding_type' => 'nullable|string|max:255',
        
        // Additional welding parameters
        'transfer_mode' => 'nullable|string|max:255',
        'transfer_mode_range' => 'nullable|string',
        'backing_gas' => 'nullable|string|max:255',
        'backing_gas_range' => 'nullable|string',
        'gtaw_current_type' => 'nullable|string|max:255',
        'gtaw_current_type_range' => 'nullable|string|max:255',
        'equipment_type' => 'nullable|string|max:255',
        'equipment_type_range' => 'nullable|string|max:255',
        'technique' => 'nullable|string|max:255',
        'technique_range' => 'nullable|string|max:255',
        'oscillation' => 'nullable|string|max:255',
        'oscillation_value' => 'nullable|string|max:255',
        'oscillation_range' => 'nullable|string',
        'operation_mode' => 'nullable|string|max:255',
        'operation_mode_range' => 'nullable|string',
        
        // RT/UT testing details
        'rt' => 'boolean',
        'ut' => 'boolean',
        'rt_doc_no' => 'nullable|string|max:255',
        'vt_report_no' => 'nullable|string|max:255',
        'rt_report_no' => 'nullable|string|max:255',
        
        // Test results
        'transverse_face_root' => 'boolean',
        'longitudinal_bends' => 'boolean', 
        'side_bends' => 'boolean',
        'pipe_bend_corrosion' => 'boolean',
        'plate_bend_corrosion' => 'boolean',
        'pipe_macro_fusion' => 'boolean',
        'plate_macro_fusion' => 'boolean',
        'fillet_welds_plate' => 'boolean',
        'fillet_welds_pipe' => 'boolean',
        
        // Additional test fields
        'additional_type_1' => 'nullable|string|max:255',
        'additional_result_1' => 'nullable|string|max:255',
        'additional_type_2' => 'nullable|string|max:255',
        'additional_result_2' => 'nullable|string|max:255',
        'fillet_fracture_test' => 'nullable|string|max:255',
        'defects_length' => 'nullable|string|max:255',
        'macro_exam' => 'nullable|string|max:255',
        'fillet_size' => 'nullable|string|max:255',
        'other_tests' => 'nullable|string|max:255',
        'concavity_convexity' => 'nullable|string|max:255',
        
        // Personnel fields
        'evaluated_by' => 'nullable|string|max:255',
        'evaluated_company' => 'nullable|string|max:255',
        'mechanical_tests_by' => 'nullable|string|max:255',
        'lab_test_no' => 'nullable|string|max:255',
        'supervised_by' => 'required|string|max:255',
        'supervised_company' => 'nullable|string|max:255',
        
        // Certification fields
        'certification_text' => 'required|string|max:1000', // Make required
        'confirm_date1' => 'nullable|date',
        'confirm_title1' => 'nullable|string|max:255',
        'confirm_date2' => 'nullable|date', 
        'confirm_title2' => 'nullable|string|max:255',
        'confirm_date3' => 'nullable|date',
        'confirm_title3' => 'nullable|string|max:255',
        
        // Inspector fields
        'inspector_name' => 'required|string|max:255',
        'inspector_date' => 'required|date',
        'inspector_signature_data' => 'nullable|string',
        
        // File uploads
        'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'signature_data' => 'nullable|string',
    ]);
    
    // Apply conditional validation rules
    $data = $request->all();
    
    // 1. For plate specimens, diameter is not required
    if (isset($data['plate_specimen']) && filter_var($data['plate_specimen'], FILTER_VALIDATE_BOOLEAN) 
        && !filter_var($data['pipe_specimen'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
        $validator->setRules(array_merge(
            $validator->getRules(), 
            ['diameter' => 'nullable|string|max:255']
        ));
    }
    
    // 2. If RT or UT is enabled, evaluated_by and supervised_by are not required
    if ((isset($data['rt']) && filter_var($data['rt'], FILTER_VALIDATE_BOOLEAN)) ||
        (isset($data['ut']) && filter_var($data['ut'], FILTER_VALIDATE_BOOLEAN))) {
        $validator->setRules(array_merge(
            $validator->getRules(), 
            [
                'evaluated_by' => 'nullable|string|max:255',
                'supervised_by' => 'nullable|string|max:255'
            ]
        ));
    }
    
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
            $validated['pipe_specimen'] ?? false
        );
    }
    
    // Add current user as creator
    $validated['created_by'] = Auth::id();
    
    // Add report numbers if not provided
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
    
    // Create the certificate
    try {
        DB::beginTransaction();
        
        $certificate = FcawCertificate::create($validated);
        
        DB::commit();
        
        // Check if request is AJAX
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            // Make sure the certificate URL is clearly available in the response
            $certificateUrl = route('fcaw-certificates.certificate', $certificate);
            
            return response()->json([
                'success' => true,
                'message' => 'Certificate created successfully.',
                'redirect' => route('fcaw-certificates.index'),
                'certificate_url' => $certificateUrl,
                'print_url' => $certificateUrl, // Adding an alternative name just to be safe
                'certificate' => [
                    'id' => $certificate->id,
                    'url' => $certificateUrl
                ]
            ]);
        }
        
        // For non-AJAX requests, we'll add JavaScript to open certificate in a new tab and redirect to index
        return redirect()->route('fcaw-certificates.index')
                        ->with('success', 'Certificate created successfully.')
                        ->with('open_certificate', route('fcaw-certificates.certificate', $certificate));
    } catch (\Exception $e) {
        DB::rollBack();
        
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
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $certificate = FcawCertificate::with(['welder', 'company', 'createdBy'])->findOrFail($id);
        return view('fcaw_certificates.show', compact('certificate'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $certificate = FcawCertificate::findOrFail($id);
        $companies = Company::orderBy('name')->get();
        $welders = Welder::orderBy('name')->get();
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
        
        return view('fcaw_certificates.edit', compact(
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
  /**
 * Update the specified resource in storage.
 *
 * @param  \Illuminate\Http\Request  $request
 * @param  int  $id
 * @return \Illuminate\Http\Response
 */
public function update(Request $request, $id)
{
    // Transform boolean checkbox values to ensure they're properly processed
    $booleanFields = [
        'test_coupon', 'production_weld', 'plate_specimen', 'pipe_specimen', 'rt', 'ut',
        'fillet_welds_plate', 'fillet_welds_pipe', 'pipe_macro_fusion', 'plate_macro_fusion',
        'transverse_face_root', 'longitudinal_bends', 'side_bends',
        'pipe_bend_corrosion', 'plate_bend_corrosion', 'gtaw_process', 'fcaw_process'
    ];

    $data = $request->all();

    // Handle potential duplicate fields in the form submission
    foreach ($data as $key => $value) {
        if (is_array($value) && !in_array($key, ['_token', '_method'])) {
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

    // Handle manual entry fields properly
    if (isset($data['filler_spec']) && $data['filler_spec'] === '__manual__') {
        $data['filler_spec'] = $data['filler_spec_manual'] ?? '';
    }
    
    if (isset($data['filler_class']) && $data['filler_class'] === '__manual__') {
        $data['filler_class'] = $data['filler_class_manual'] ?? '';
    }
    
    if (isset($data['filler_f_no']) && $data['filler_f_no'] === '__manual__') {
        $data['filler_f_no'] = $data['filler_f_no_manual'] ?? '';
    }
    
    if (isset($data['base_metal_p_no']) && $data['base_metal_p_no'] === '__manual__') {
        $data['base_metal_p_no'] = $data['base_metal_p_no_manual'] ?? '';
    }
    
    if (isset($data['backing']) && $data['backing'] === '__manual__') {
        $data['backing'] = $data['backing_manual'] ?? '';
    }

    // Create a validator instance with the transformed data
    $validator = Validator::make($data, [
        // Certificate identification
        'certificate_no' => 'required|string|max:50|unique:fcaw_certificates,certificate_no,' . $id,
        'welder_id' => 'required|exists:welders,id',
        'company_id' => 'required|exists:companies,id',
        'wps_followed' => 'required|string|max:255',
        'test_date' => 'required|date',
        'revision_no' => 'nullable|string|max:50',

        // Specimen details
        'test_coupon' => 'boolean',
        'production_weld' => 'boolean',
        'plate_specimen' => 'boolean',
        'pipe_specimen' => 'boolean',
        'base_metal_spec' => 'required|string|max:255',
        'diameter' => function($attribute, $value, $fail) use ($data) {
            if (!empty($data['pipe_specimen']) && $data['pipe_specimen']) {
                if (empty($value)) {
                    $fail('The diameter field is required when pipe specimen is selected.');
                }
            }
        },
        'thickness' => function($attribute, $value, $fail) use ($data) {
            if (empty($value)) {
                if (!empty($data['pipe_specimen']) && $data['pipe_specimen']) {
                    $fail('The thickness field is required when pipe specimen is selected.');
                }
            }
        },

        // Pipe information
        'pipe_diameter_type' => 'nullable|string|max:255',
        'pipe_diameter_manual' => 'nullable|string|max:255',
        'diameter_range' => 'nullable|string',

        // Metal and filler details
        'base_metal_p_no' => 'required|string|max:255',
        'base_metal_p_no_manual' => 'nullable|string|max:255',
        'p_number_range' => 'nullable|string',
        'p_number_range_manual' => 'nullable|string|max:255',

        // Position details
        'test_position' => 'required|string|max:255',
        'position_range' => 'nullable|string',
        'position_range_manual' => 'nullable|string|max:255',

        // Backing details
        'backing' => 'required|string|max:255',
        'backing_manual' => 'nullable|string|max:255',
        'backing_range' => 'nullable|string',
        
        // Vertical progression
        'vertical_progression' => 'required|string|max:255',
        'vertical_progression_range' => 'nullable|string',

        // Filler details
        'filler_spec' => 'required|string|max:255',
        'filler_spec_manual' => 'nullable|string|max:255',
        'filler_spec_range' => 'nullable|string',
        'filler_class' => 'required|string|max:255',
        'filler_class_manual' => 'nullable|string|max:255',
        'filler_class_range' => 'nullable|string',
        'filler_f_no' => 'required|string|max:255',
        'filler_f_no_manual' => 'nullable|string|max:255',
        'f_number_range' => 'nullable|string',

        // Process fields
        'fcaw_thickness' => 'nullable|string|max:255',
        'fcaw_thickness_range' => 'nullable|string|max:255',
        'deposit_thickness' => 'nullable|string|max:255',
        'deposit_thickness_range' => 'nullable|string|max:255',
        'welding_process' => 'nullable|string|max:255',
        'welding_type' => 'nullable|string|max:255',
        
        // Additional welding parameters
        'transfer_mode' => 'nullable|string|max:255',
        'transfer_mode_range' => 'nullable|string',
        'backing_gas' => 'nullable|string|max:255',
        'backing_gas_range' => 'nullable|string',
        'gtaw_current_type' => 'nullable|string|max:255',
        'gtaw_current_type_range' => 'nullable|string|max:255',
        'equipment_type' => 'nullable|string|max:255',
        'equipment_type_range' => 'nullable|string|max:255',
        'technique' => 'nullable|string|max:255',
        'technique_range' => 'nullable|string|max:255',
        'oscillation' => 'nullable|string|max:255',
        'oscillation_value' => 'nullable|string|max:255',
        'oscillation_range' => 'nullable|string',
        'operation_mode' => 'nullable|string|max:255',
        'operation_mode_range' => 'nullable|string',

        // RT/UT testing details
        'rt' => 'boolean',
        'ut' => 'boolean',
        'rt_doc_no' => 'nullable|string|max:255',
        'vt_report_no' => 'nullable|string|max:255',
        'rt_report_no' => 'nullable|string|max:255',
        
        // Test results
        'transverse_face_root' => 'boolean',
        'longitudinal_bends' => 'boolean', 
        'side_bends' => 'boolean',
        'pipe_bend_corrosion' => 'boolean',
        'plate_bend_corrosion' => 'boolean',
        'pipe_macro_fusion' => 'boolean',
        'plate_macro_fusion' => 'boolean',
        'fillet_welds_plate' => 'boolean',
        'fillet_welds_pipe' => 'boolean',
        
        // Additional test fields
        'additional_type_1' => 'nullable|string|max:255',
        'additional_result_1' => 'nullable|string|max:255',
        'additional_type_2' => 'nullable|string|max:255',
        'additional_result_2' => 'nullable|string|max:255',
        'fillet_fracture_test' => 'nullable|string|max:255',
        'defects_length' => 'nullable|string|max:255',
        'macro_exam' => 'nullable|string|max:255',
        'fillet_size' => 'nullable|string|max:255',
        'other_tests' => 'nullable|string|max:255',
        'concavity_convexity' => 'nullable|string|max:255',

        // Personnel fields
        'evaluated_by' => 'nullable|string|max:255',
        'evaluated_company' => 'nullable|string|max:255',
        'mechanical_tests_by' => 'nullable|string|max:255',
        'lab_test_no' => 'nullable|string|max:255',
        'supervised_by' => 'required|string|max:255',
        'supervised_company' => 'nullable|string|max:255',

        // Certification fields
        'certification_text' => 'required|string|max:1000', // Make required
        'confirm_date1' => 'nullable|date',
        'confirm_title1' => 'nullable|string|max:255',
        'confirm_date2' => 'nullable|date', 
        'confirm_title2' => 'nullable|string|max:255',
        'confirm_date3' => 'nullable|date',
        'confirm_title3' => 'nullable|string|max:255',
        
        // Inspector fields
        'inspector_name' => 'required|string|max:255',
        'inspector_date' => 'required|date',
        'inspector_signature_data' => 'nullable|string',

        // File uploads
        'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        'signature_data' => 'nullable|string',
    ]);

    // Apply conditional validation rules
    $data = $request->all();
    
    // 1. For plate specimens, diameter is not required
    if (isset($data['plate_specimen']) && filter_var($data['plate_specimen'], FILTER_VALIDATE_BOOLEAN) 
        && !filter_var($data['pipe_specimen'] ?? false, FILTER_VALIDATE_BOOLEAN)) {
        $validator->setRules(array_merge(
            $validator->getRules(), 
            ['diameter' => 'nullable|string|max:255']
        ));
    }
    
    // 2. If RT or UT is enabled, evaluated_by and supervised_by are not required
    if ((isset($data['rt']) && filter_var($data['rt'], FILTER_VALIDATE_BOOLEAN)) ||
        (isset($data['ut']) && filter_var($data['ut'], FILTER_VALIDATE_BOOLEAN))) {
        $validator->setRules(array_merge(
            $validator->getRules(), 
            [
                'evaluated_by' => 'nullable|string|max:255',
                'supervised_by' => 'nullable|string|max:255'
            ]
        ));
    }
    
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
            $validated['pipe_specimen'] ?? false
        );
    }

    // Update the certificate
    try {
        DB::beginTransaction();

        $certificate = FcawCertificate::findOrFail($id);
        $certificate->update($validated);

        DB::commit();

        // Check if request is AJAX
        if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
            // Make sure the certificate URL is clearly available in the response
            $certificateUrl = route('fcaw-certificates.certificate', $certificate);
            
            return response()->json([
                'success' => true,
                'message' => 'Certificate updated successfully.',
                'redirect' => route('fcaw-certificates.index'),
                'certificate_url' => $certificateUrl,
                'print_url' => $certificateUrl, // Adding an alternative name just to be safe
                'certificate' => [
                    'id' => $certificate->id,
                    'url' => $certificateUrl
                ]
            ]);
        }

        // For non-AJAX requests, we'll add JavaScript to open certificate in a new tab and redirect to index
        return redirect()->route('fcaw-certificates.index')
                        ->with('success', 'Certificate updated successfully.')
                        ->with('open_certificate', route('fcaw-certificates.certificate', $certificate));
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
        $certificate = FcawCertificate::findOrFail($id);
        
        try {
            // Delete photo if it exists
            if ($certificate->photo_path) {
                Storage::disk('public')->delete($certificate->photo_path);
            }
            
            $certificate->delete();
            
            return redirect()->route('fcaw-certificates.index')
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
        $certificate = FcawCertificate::with('welder.company', 'company')->findOrFail($id);
        
        // Debug company logo path
        $logoPath = \App\Models\AppSetting::getValue('company_logo_path');
        $logoExists = !empty($logoPath) && Storage::disk('public')->exists($logoPath);
        
        // Generate QR Code for certificate verification
        $verificationUrl = route('fcaw-certificates.verify', ['id' => $certificate->id, 'code' => $certificate->verification_code]);
        $qrCodeUrl = 'data:image/png;base64,' . base64_encode(FacadesQrCode::format('png')->size(200)->generate($verificationUrl));

        return view('fcaw_certificates.certificate', compact('certificate', 'qrCodeUrl', 'logoPath', 'logoExists'));
    }
    
    /**
     * Generate a welder qualification card
     */
    public function generateCard($id)
    {
        $certificate = FcawCertificate::with(['welder', 'company'])->findOrFail($id);
        return view('fcaw_certificates.card', compact('certificate'));
    }
    
    /**
     * Generate the back side of a welder qualification card
     */
    public function generateBackCard($id)
    {
        $certificate = FcawCertificate::with(['welder', 'company'])->findOrFail($id);
        return view('fcaw_certificates.back_card', compact('certificate'));
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
     * Show certificate preview 
     */
    public function preview()
    {
        return view('fcaw_certificates.preview');
    }
    
    /**
     * Verify certificate by ID and verification code
     */
    public function verify($id, $code)
    {
        $certificate = FcawCertificate::with(['welder', 'company'])->findOrFail($id);
        
        if ($certificate->verification_code !== $code) {
            return abort(404);
        }
        
        return view('fcaw_certificates.verify', compact('certificate'));
    }
    
    /**
     * Show certificate verification form for public users
     */
    public function showVerificationForm()
    {
        return view('fcaw_certificates.verification_form');
    }
    
    /**
     * Verify certificate by certificate number
     */
    public function verifyByCertificateNo(Request $request)
    {
        $validated = $request->validate([
            'certificate_no' => 'required|string',
        ]);
        
        $certificate = FcawCertificate::where('certificate_no', $validated['certificate_no'])->first();
        
        if (!$certificate) {
            return view('fcaw_certificates.verification_form', [
                'error' => 'Certificate not found. Please check the certificate number and try again.',
                'certificate_no' => $validated['certificate_no']
            ]);
        }
        
        return redirect()->route('fcaw-certificates.verify', [
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
}
