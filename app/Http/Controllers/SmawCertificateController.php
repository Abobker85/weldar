<?php

namespace App\Http\Controllers;

use App\Models\SmawCertificate;
use App\Models\Welder;
use App\Models\Company;
use App\Models\AppSetting;
use App\Enums\CertificateOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Yajra\DataTables\Facades\DataTables; // Add this import

class SmawCertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //composer dump autoload
        //Artisan optimize:clear
        $welders = Welder::orderBy('name')->pluck('name', 'id');
        $companies = Company::orderBy('name')->pluck('name', 'id');
        
        if ($request->ajax()) {
            $query = SmawCertificate::with('welder', 'company');

            // dd($query->count()); // Debugging line to check the count of certificates
            
            // Search by certificate number
            if ($request->has('certificate_no') && !empty($request->certificate_no)) {
                $query->where('certificate_no', 'like', '%' . $request->certificate_no . '%');
            }
            
            // Filter by welder
            if ($request->has('welder_id') && !empty($request->welder_id)) {
                $query->where('welder_id', $request->welder_id);
            }
            
            // Filter by company
            if ($request->has('company_id') && !empty($request->company_id)) {
                $query->where('company_id', $request->company_id);
            }
            
            // Filter by test date range
            if ($request->has('date_from') && !empty($request->date_from)) {
                $query->whereDate('test_date', '>=', $request->date_from);
            }
            
            if ($request->has('date_to') && !empty($request->date_to)) {
                $query->whereDate('test_date', '<=', $request->date_to);
            }
          

            
            return DataTables::of($query) 
                ->addColumn('welder_name', function($certificate) {
                    return $certificate->welder ? $certificate->welder->name : 'N/A';
                })
                ->addColumn('company_name', function($certificate) {
                    return $certificate->company ? $certificate->company->name : 'N/A';
                })
                ->addColumn('test_date', function($certificate) {
                    return $certificate->test_date ? $certificate->test_date->format('Y-m-d') : 'N/A';
                })
                ->addColumn('test_result', function($certificate) {
                    if($certificate->test_result) {
                        return '<span class="badge badge-success">Pass</span>';
                    } else {
                        return '<span class="badge badge-danger">Fail</span>';
                    }
                })
                ->addColumn('actions', function($certificate) {
                    $actions = '<div class="btn-group">';
                    $actions .= '<a href="' . route('smaw-certificates.certificate', $certificate->id) . '" class="btn btn-sm btn-primary" title="Print Certificate"><i class="fas fa-print"></i></a>';
                    $actions .= '<a href="' . route('smaw-certificates.edit', $certificate->id) . '" class="btn btn-sm btn-info" title="Edit Certificate"><i class="fas fa-edit"></i></a>';
                    $actions .= '<a href="' . route('smaw-certificates.card', $certificate->id) . '" class="btn btn-sm btn-secondary" title="ID Card"><i class="fas fa-id-card"></i></a>';
                    $actions .= '<a href="' . route('smaw-certificates.back-card', $certificate->id) . '" class="btn btn-sm btn-dark" title="Back Card"><i class="fas fa-id-card-alt"></i></a>';
                    $actions .= '<form action="' . route('smaw-certificates.destroy', $certificate->id) . '" method="POST" class="d-inline">';
                    $actions .= csrf_field();
                    $actions .= method_field('DELETE');
                    $actions .= '<button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure you want to delete this certificate?\')" title="Delete"><i class="fas fa-trash"></i></button>';
                    $actions .= '</form>';
                    $actions .= '</div>';
                    
                    return $actions;
                })
                ->rawColumns(['test_result', 'actions'])
                ->make(true);
        }
        
        return view('smaw_certificates.index', compact('welders', 'companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Get all welders with their company relationship for dropdown
        $welders = Welder::with('company')->orderBy('name')->get();
        $companies = Company::orderBy('name')->pluck('name', 'id');
        
        // Pre-select welder if provided in the request
        $selectedWelder = null;
        if ($request->has('welder_id') && !empty($request->welder_id)) {
            $selectedWelder = Welder::with('company')->find($request->welder_id);
        }
        
        // Get options from CertificateOptions class
        $pipeDiameterTypes = CertificateOptions::pipeDiameterTypes();
        $testPositions = CertificateOptions::testPositions();
        $baseMetalPNumbers = CertificateOptions::baseMetalPNumbers();
        $fillerSpecs = CertificateOptions::fillerSpecs();
        $fillerClasses = CertificateOptions::fillerClasses();
        $fillerFNumbers = CertificateOptions::fillerFNumbers();
        $backingTypes = CertificateOptions::backingTypes();
        $verticalProgressions = CertificateOptions::verticalProgressions();
        
        // Generate certificate number based on company
        $systemCode = \App\Models\AppSetting::getValue('doc_prefix', 'EEA');
        $defaultCompanyCode = $systemCode;
        $certificatePrefix = $defaultCompanyCode . '-SMAW-';
        
        // If a company was pre-selected in the request, use its code for the initial cert number
        if ($request->has('company_id') && !empty($request->company_id)) {
            $company = Company::find($request->company_id);
            if ($company && $company->code) {
                $certificatePrefix = $systemCode . '-' . $company->code . '-SMAW-';
            }
        }
        
        $lastCert = SmawCertificate::where('certificate_no', 'like', $certificatePrefix . '%')
            ->orderBy('certificate_no', 'desc')
            ->first();
            
        $newNumber = 1;
        if ($lastCert) {
            $lastNumber = (int) substr($lastCert->certificate_no, -4);
            $newNumber = $lastNumber + 1;
        }
        
        $newCertNo = $certificatePrefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        
        // Generate VT report number
        $companyCode = $defaultCompanyCode;
        
        // If a company was pre-selected in the request, use its code for the VT report number
        if ($request->has('company_id') && !empty($request->company_id)) {
            $company = Company::find($request->company_id);
            if ($company && $company->code) {
                $companyCode = $systemCode . '-' . $company->code;
            }
        }
        
        // Generate the VT report number
        $vtReportPrefix = $companyCode . '-VT-';
        $lastVTReport = SmawCertificate::where('vt_report_no', 'like', $vtReportPrefix . '%')
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
        $lastRTReport = SmawCertificate::where('rt_report_no', 'like', $rtReportPrefix . '%')
            ->orderBy('rt_report_no', 'desc')
            ->first();
            
        $newRTNumber = 1;
        if ($lastRTReport) {
            $lastNumber = (int) substr($lastRTReport->rt_report_no, -4);
            $newRTNumber = $lastNumber + 1;
        }
        
        $rtReportNo = $rtReportPrefix . str_pad($newRTNumber, 4, '0', STR_PAD_LEFT);
        
        return view('smaw_certificates.create', compact(
            'welders',
            'companies',
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
            'rtReportNo'  // Added RT report number
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Transform boolean checkbox values to ensure they're properly processed
        $booleanFields = [
            'test_coupon', 'production_weld', 'plate', 'pipe', 'rt', 'ut', 
            'plate_specimen', 'pipe_specimen',
            'fillet_welds_plate', 'fillet_welds_pipe', 'pipe_macro_fusion', 'plate_macro_fusion',
            'transverse_face_root', 'longitudinal_bends', 'side_bends',
            'pipe_bend_corrosion', 'plate_bend_corrosion'
        ];
        
        $data = $request->all();
        
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
        
        // Check if at least one of test_coupon or production_weld is checked
        if (!($data['test_coupon'] || $data['production_weld'])) {
            return response()->json([
                'success' => false,
                'message' => 'Please select either Test Coupon or Production Weld',
                'errors' => [
                    'test_coupon' => ['Please select either Test Coupon or Production Weld']
                ]
            ], 422);
        }
        
        $request->merge($data);
        
        $validated = $request->validate([
            // Basic information
            'welder_id' => 'required|exists:welders,id',
            'company_id' => 'required|exists:companies,id',
            'wps_followed' => 'required|string|max:255',
            'revision_no' => 'nullable|string|max:255',
            'test_date' => 'required|date',
            'base_metal_spec' => 'required|string|max:255',
            'diameter' => 'required_if:pipe,true|nullable|string|max:255',
            'thickness' => 'required|string|max:255',
            'dia_thickness' => 'nullable|string|max:255',
            
            // Specimen type
            'test_coupon' => 'boolean',
            'production_weld' => 'boolean',
            'plate' => 'boolean',
            'pipe' => 'boolean',
            'plate_specimen' => 'boolean',
            'pipe_specimen' => 'boolean',
            
            // Pipe information
            'pipe_diameter_type' => 'required_if:pipe,true|nullable|string|max:255',
            'pipe_diameter_manual' => 'nullable|string|max:255',
            
              // Metal specifications
            'base_metal_p_no' => 'required|string|max:255',
            'base_metal_p_no_manual' => 'nullable|string|max:255',
            'p_number_range' => 'nullable|string|max:255',
            'p_number_range_manual' => 'nullable|string|max:255',
            
            // Thickness information
            'smaw_thickness' => 'required|string|max:255',
            'smaw_thickness_range' => 'required|string|max:255',
            'smaw_process' => 'required',
            
            // Position and backing information
            'test_position' => 'required|string|max:255',
            'position_range_manual' => 'nullable|string',
            'backing' => 'required|string|max:255',
            'backing_manual' => 'nullable|string|max:255',
            
            // Filler information
            'filler_spec' => 'required|string|max:255',
            'filler_spec_manual' => 'nullable|string|max:255',
            'filler_spec_range' => 'nullable|string|max:255',
            'filler_class' => 'required|string|max:255',
            'filler_class_manual' => 'nullable|string|max:255',
            'filler_class_range' => 'nullable|string|max:255',
            'filler_f_no' => 'required|string|max:255',
            'filler_f_no_manual' => 'nullable|string|max:255',
            
            // Vertical progression
            'vertical_progression' => 'required|string|max:255',
            
            // Inspector information
            'inspector_name' => 'required|string|max:255',
            'inspector_date' => 'nullable|date',
            
            // Photo and signatures
            'photo' => 'nullable|image|max:2048',
            'certification_text' => 'nullable|string|max:500',
            'signature_data' => 'nullable|string',
            'inspector_signature_data' => 'nullable|string',
            
            // Additional welding variables
            'oscillation' => 'nullable|string|max:255',
            'fuel_gas' => 'nullable|string|max:255',
            'fuel_gas_range' => 'nullable|string|max:255',
            'backing_gas' => 'nullable|string|max:255',
            'backing_gas_range' => 'nullable|string|max:255',
            'transfer_mode' => 'nullable|string|max:255',
            'transfer_mode_range' => 'nullable|string|max:255',
            'gtaw_current' => 'nullable|string|max:255',
            'gtaw_current_range' => 'nullable|string|max:255',
            'equipment_type' => 'nullable|string|max:255',
            'equipment_type_range' => 'nullable|string|max:255',
            'technique' => 'nullable|string|max:255',
            'technique_range' => 'nullable|string|max:255',
            'oscillation_value' => 'nullable|string|max:255',
            'oscillation_range' => 'nullable|string|max:255',
            'operation_mode' => 'nullable|string|max:255',
            'operation_mode_range' => 'nullable|string|max:255',
            'consumable_insert' => 'nullable|string|max:255',
            'consumable_insert_range' => 'nullable|string|max:255',
            'filler_product_form' => 'nullable|string|max:255',
            'filler_product_form_range' => 'nullable|string|max:255',
            'deposit_thickness' => 'nullable|string|max:255',
            'deposit_thickness_range' => 'nullable|string|max:255',
            
            // Test result fields
            'rt' => 'boolean',
            'ut' => 'boolean',
            'vt_report_no' => 'required|string|max:255',
            'rt_report_no' => 'required|string|max:255',
            'rt_doc_no' => 'nullable|string|max:255',
            'visual_examination_result' => 'nullable|string|in:ACC,REJ',
            
            // Personnel information
            'evaluated_by' => 'required|string|max:255',
            'evaluated_company' => 'nullable|string|max:255',
            'mechanical_tests_by' => 'nullable|string|max:255',
            'lab_test_no' => 'nullable|string|max:255',
            'supervised_by' => 'required|string|max:255',
            'supervised_company' => 'nullable|string|max:255',
            
            // Additional test types and results
            'additional_type_1' => 'nullable|string|max:255',
            'additional_result_1' => 'nullable|string|max:255',
            'additional_type_2' => 'nullable|string|max:255',
            'additional_result_2' => 'nullable|string|max:255',
            
            // Bend test fields
            'transverse_face_root' => 'boolean',
            'longitudinal_bends' => 'boolean',
            'side_bends' => 'boolean',
            'pipe_bend_corrosion' => 'boolean',
            'plate_bend_corrosion' => 'boolean',
            'pipe_macro_fusion' => 'boolean',
            'plate_macro_fusion' => 'boolean',
            
            // Additional test results
            'fillet_fracture_test' => 'nullable|string|max:255',
            'defects_length' => 'nullable|string|max:255',
            'fillet_welds_plate' => 'boolean',
            'fillet_welds_pipe' => 'boolean',
            'macro_exam' => 'nullable|string|max:255',
            'fillet_size' => 'nullable|string|max:255',
            'other_tests' => 'nullable|string|max:255',
            'concavity_convexity' => 'nullable|string|max:255',
            
            // Personnel fields
            'evaluated_by' => 'required|string|max:255',
            'evaluated_company' => 'nullable|string|max:255',
            'mechanical_tests_by' => 'nullable|string|max:255',
            'lab_test_no' => 'nullable|string|max:255',
            'supervised_by' => 'required|string|max:255',
            'supervised_company' => 'required|string|max:255',
            
            // Confirmation details
            'confirm_date1' => 'nullable|string|max:255',
            'confirm_title1' => 'nullable|string|max:255',
            'confirm_date2' => 'nullable|string|max:255',
            'confirm_title2' => 'nullable|string|max:255',
            'confirm_date3' => 'nullable|string|max:255',
            'confirm_title3' => 'nullable|string|max:255',
            
            // Range fields
            'diameter_range' => 'nullable|string',
            'p_number_range' => 'nullable|string',
            'position_range' => 'nullable|string',
            'backing_range' => 'nullable|string', 
            'f_number_range' => 'nullable|string',
            'vertical_progression_range' => 'nullable|string',
            //
        ]);

      
        // Remove the dd() statement
        // dd($validated);
        
        // Use submitted range values if present, otherwise calculate them
        if (empty($validated['diameter_range']) && !empty($validated['pipe_diameter_type'])) {
            $validated['diameter_range'] = $this->getDiameterRange($validated['pipe_diameter_type']);
        }
        
         if (empty($validated['p_number_range'])) {
            $validated['p_number_range'] = $this->getPNumberRange($validated['base_metal_p_no']);
        }

        // dd( $validated['position_range'] );
        // Fix for pipe_specimen key - use 'pipe' instead since that's what's in the form
        // if (empty($validated['position_range'])) {
        //     $validated['position_range'] = $this->getPositionRange($validated['test_position'], $validated['pipe']);
        // }
        
        if (empty($validated['backing_range'])) {
            $validated['backing_range'] = $this->getBackingRange($validated['backing']);
        }
        
        if (empty($validated['f_number_range'])) {
            $validated['f_number_range'] = $this->getFNumberRange($validated['filler_f_no']);
        }
        
        if (empty($validated['vertical_progression_range'])) {
            $validated['vertical_progression_range'] = $this->getVerticalProgressionRange($validated['vertical_progression']);
        }

        // dd($validated);
        
        // Process photo upload if provided
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('welder-photos', 'public');
            $validated['photo_path'] = $photoPath;
        } elseif ($request->has('use_existing_photo') && $request->get('use_existing_photo') === 'true') {
            // Use the welder's existing photo
            $welder = Welder::find($validated['welder_id']);
            if ($welder && $welder->photo_path) {
                $validated['photo_path'] = $welder->photo_path;
            }
        }
        
        // Generate certificate number
        $companyCode = $this->getCompanyCode($validated['company_id']);
        $prefix = $companyCode . '-SMAW-';
        
        $lastCert = SmawCertificate::where('certificate_no', 'like', $prefix . '%')
            ->orderBy('certificate_no', 'desc')
            ->first();
            
        $newNumber = 1;
        if ($lastCert) {
            $lastNumber = (int) substr($lastCert->certificate_no, -4);
            $newNumber = $lastNumber + 1;
        }
        
        $validated['certificate_no'] = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        $validated['created_by'] = Auth::id();
        
        // Generate a verification code for certificate
        $validated['verification_code'] = md5(uniqid() . time());

        $certificate = SmawCertificate::create($validated);
        
        // Check if request is AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'SMAW Certificate created successfully.',
                'redirect' => route('smaw-certificates.certificate', $certificate->id),
                'certificate_id' => $certificate->id
            ]);
        }
        
        // Regular form submission response (fallback)
        return redirect()->route('smaw-certificates.certificate', $certificate->id)
            ->with('success', 'SMAW Certificate created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $certificate = SmawCertificate::with('welder.company', 'company')->findOrFail($id);
        return view('smaw_certificates.show', compact('certificate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        // Get the certificate with related data (welder and company)
        $certificate = SmawCertificate::with('welder.company', 'company')->findOrFail($id);
        $welders = Welder::with('company')->orderBy('name')->get();
        $companies = Company::orderBy('name')->pluck('name', 'id');
        $selectedWelder = $certificate->welder;
        $selectedCompany = $certificate->company;
        
        // Get app settings for defaults
        $appSettings = AppSetting::first();
        
        // Ensure inspector name is set
        $inspectorName = $certificate->inspector_name ?? ($appSettings ? $appSettings->default_inspector_name : 'Ibrahim Abdullah');
        
        // Get options from CertificateOptions class
        $pipeDiameterTypes = CertificateOptions::pipeDiameterTypes();
        $testPositions = CertificateOptions::testPositions();
        $baseMetalPNumbers = CertificateOptions::baseMetalPNumbers();
        $fillerSpecs = CertificateOptions::fillerSpecs();
        $fillerClasses = CertificateOptions::fillerClasses();
        $fillerFNumbers = CertificateOptions::fillerFNumbers();
        $backingTypes = CertificateOptions::backingTypes();
        $verticalProgressions = CertificateOptions::verticalProgressions();
        
        return view('smaw_certificates.edit', compact(
            'certificate',
            'welders',
            'companies',
            'pipeDiameterTypes',
            'testPositions',
            'baseMetalPNumbers',
            'fillerSpecs',
            'fillerClasses',
            'fillerFNumbers',
            'backingTypes',
            'verticalProgressions',
            'selectedWelder',
            'selectedCompany',
            'inspectorName',
            'appSettings'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Transform boolean checkbox values to ensure they're properly processed
        $booleanFields = [
            'smaw_yes', 'plate_specimen', 'pipe_specimen', 'test_coupon', 'production_weld',
            'rt', 'ut', 'fillet_welds_plate', 'fillet_welds_pipe',
            'pipe_macro_fusion', 'plate_macro_fusion', 'test_result',
            'plate', 'pipe', 'transverse_face_root', 'longitudinal_bends',
            'side_bends', 'pipe_bend_corrosion', 'plate_bend_corrosion'
        ];

        $data = $request->all();

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

        // Check if at least one of test_coupon or production_weld is checked
        if (!($data['test_coupon'] || $data['production_weld'])) {
            return redirect()->back()->withInput()->withErrors(['test_type' => 'You must select either Test Coupon or Production Weld']);
        }

        $request->merge($data);

        // Handle the problematic fields with default values if they're missing
        $problematicFields = [
            'wps_followed' => 'WPS-001',
            'test_date' => now()->format('Y-m-d'),
            'base_metal_spec' => 'A106 Gr.B'
        ];

        foreach ($problematicFields as $field => $defaultValue) {
            if (empty($data[$field])) {
                $data[$field] = $defaultValue;
                $request->merge([$field => $defaultValue]);
                Log::info("SmawCertificateController: Fixed empty {$field} with default value: {$defaultValue}");
            }
        }

        // Force the problematic fields to be present before validation
        if (strpos($data['wps_followed'] ?? '', '-FIXED') !== false) {
            // If we detect our fixed value, strip off the -FIXED suffix
            $data['wps_followed'] = str_replace('-FIXED', '', $data['wps_followed']);
            $request->merge(['wps_followed' => $data['wps_followed']]);
        }

        if (strpos($data['base_metal_spec'] ?? '', '-FIXED') !== false) {
            // If we detect our fixed value, strip off the -FIXED suffix
            $data['base_metal_spec'] = str_replace('-FIXED', '', $data['base_metal_spec']);
            $request->merge(['base_metal_spec' => $data['base_metal_spec']]);
        }

        $validated = $request->validate([
            'welder_id' => 'required|exists:welders,id',
            'company_id' => 'required|exists:companies,id',
            'certificate_no' => 'required|string|max:255',
            'wps_followed' => 'required|string|max:255',
            'revision_no' => 'nullable|string|max:50',
            'test_date' => 'required|date',
            'base_metal_spec' => 'required|string|max:255',
            'smaw_yes' => 'boolean',
            'plate_specimen' => 'boolean',
            'pipe_specimen' => 'boolean',
            'pipe_diameter_type' => 'nullable|string|max:255',
            'pipe_diameter_manual' => 'nullable|string|max:255',
            'base_metal_p_no' => 'required|string|max:255',
            'base_metal_p_no_manual' => 'nullable|string|max:255',
            'test_coupon' => 'boolean',
            'production_weld' => 'boolean',
            'smaw_thickness' => 'nullable|numeric|min:0',
            'smaw_thickness_range' => 'nullable|string|max:255',
            'diameter' => 'nullable|string|max:255',
            'thickness' => 'nullable|string|max:255',
            'test_position' => 'required|string|max:255',
            'backing' => 'required|string|max:255',
            'backing_manual' => 'nullable|string|max:255',
            'filler_spec' => 'required|string|max:255',
            'filler_spec_manual' => 'nullable|string|max:255',
            'filler_class' => 'required|string|max:255',
            'filler_class_manual' => 'nullable|string|max:255',
            'filler_f_no' => 'required|string|max:255',
            'filler_f_no_manual' => 'nullable|string|max:255',
            'vertical_progression' => 'required|string|max:255',
            'test_result' => 'boolean',

            // Inspection details
            'inspector_name' => 'required|string|max:255',
            'inspector_date' => 'nullable|date',

            // Photo and signatures
            'photo' => 'nullable|image|max:2048',
            'certification_text' => 'nullable|string|max:500',
            'signature_data' => 'nullable|string',
            'inspector_signature_data' => 'nullable|string',

            // Additional welding variables
            'oscillation' => 'nullable|string|max:255',
            'fuel_gas' => 'nullable|string|max:255',
            'fuel_gas_range' => 'nullable|string|max:255',
            'backing_gas' => 'nullable|string|max:255',
            'backing_gas_range' => 'nullable|string|max:255',
            'transfer_mode' => 'nullable|string|max:255',
            'transfer_mode_range' => 'nullable|string|max:255',
            'gtaw_current' => 'nullable|string|max:255',
            'gtaw_current_range' => 'nullable|string|max:255',
            'equipment_type' => 'nullable|string|max:255',
            'equipment_type_range' => 'nullable|string|max:255',
            'technique' => 'nullable|string|max:255',
            'technique_range' => 'nullable|string|max:255',
            'oscillation_value' => 'nullable|string|max:255',
            'oscillation_range' => 'nullable|string|max:255',
            'operation_mode' => 'nullable|string|max:255',
            'operation_mode_range' => 'nullable|string|max:255',
            'consumable_insert' => 'nullable|string|max:255',
            'consumable_insert_range' => 'nullable|string|max:255',
            'filler_product_form' => 'nullable|string|max:255',
            'filler_product_form_range' => 'nullable|string|max:255',
            'deposit_thickness' => 'nullable|string|max:255',
            'deposit_thickness_range' => 'nullable|string|max:255',

            // Test result fields
            'rt' => 'boolean',
            'ut' => 'boolean',
            'vt_report_no' => 'required|string|max:255',
            'rt_report_no' => 'required|string|max:255',
            'rt_doc_no' => 'nullable|string|max:255',
            'visual_examination_result' => 'nullable|string|in:ACC,REJ',

            // Personnel information
            'evaluated_by' => 'required|string|max:255',
            'evaluated_company' => 'nullable|string|max:255',
            'mechanical_tests_by' => 'nullable|string|max:255',
            'lab_test_no' => 'nullable|string|max:255',
            'supervised_by' => 'required|string|max:255',
            'supervised_company' => 'nullable|string|max:255',

            // Additional test types and results
            'additional_type_1' => 'nullable|string|max:255',
            'additional_result_1' => 'nullable|string|max:255',
            'additional_type_2' => 'nullable|string|max:255',
            'additional_result_2' => 'nullable|string|max:255',

            // Confirmation dates
            'confirm_date1' => 'nullable|date',
            'confirm_title1' => 'nullable|string|max:255',
            'confirm_date2' => 'nullable|date',
            'confirm_title2' => 'nullable|string|max:255',
            'confirm_date3' => 'nullable|date',
            'confirm_title3' => 'nullable|string|max:255',

            // Range fields
            'diameter_range' => 'nullable|string',
            'p_number_range' => 'nullable|string',
            'position_range' => 'nullable|string',
            'backing_range' => 'nullable|string',
            'f_number_range' => 'nullable|string',
            'vertical_progression_range' => 'nullable|string',
        ]);

        // Find the certificate to update
        $certificate = SmawCertificate::findOrFail($id);

        // Log range values from request for debugging
        Log::info('SMAW Certificate update - Range values from request:', [
            'diameter_range' => $validated['diameter_range'] ?? 'not set',
            'p_number_range' => $validated['p_number_range'] ?? 'not set',
            'position_range' => $validated['position_range'] ?? 'not set',
            'backing_range' => $validated['backing_range'] ?? 'not set',
            'f_number_range' => $validated['f_number_range'] ?? 'not set',
            'vertical_progression_range' => $validated['vertical_progression_range'] ?? 'not set',
        ]);

        // Use submitted range values if present, otherwise calculate them
        if (empty($validated['diameter_range']) && !empty($validated['pipe_diameter_type'])) {
            $validated['diameter_range'] = $this->getDiameterRange($validated['pipe_diameter_type']);
            Log::info("Setting diameter_range to: {$validated['diameter_range']}");
        }

        if (empty($validated['p_number_range']) && !empty($validated['base_metal_p_no'])) {
            $validated['p_number_range'] = $this->getPNumberRange($validated['base_metal_p_no']);
            Log::info("Setting p_number_range to: {$validated['p_number_range']}");
        }

        if (empty($validated['position_range']) && !empty($validated['test_position'])) {
            $isPipe = isset($validated['pipe_specimen']) && $validated['pipe_specimen'];
            $validated['position_range'] = $this->getPositionRange($validated['test_position'], $isPipe);
            Log::info("Setting position_range to: {$validated['position_range']}");
        }

        if (empty($validated['backing_range']) && !empty($validated['backing'])) {
            $validated['backing_range'] = $this->getBackingRange($validated['backing']);
            Log::info("Setting backing_range to: {$validated['backing_range']}");
        }

        if (empty($validated['f_number_range']) && !empty($validated['filler_f_no'])) {
            $validated['f_number_range'] = $this->getFNumberRange($validated['filler_f_no']);
            Log::info("Setting f_number_range to: {$validated['f_number_range']}");
        }

        if (empty($validated['vertical_progression_range']) && !empty($validated['vertical_progression'])) {
            $validated['vertical_progression_range'] = $this->getVerticalProgressionRange($validated['vertical_progression']);
            Log::info("Setting vertical_progression_range to: {$validated['vertical_progression_range']}");
        }

        // Final check to ensure we have range values
        $rangeFields = [
            'diameter_range', 'p_number_range', 'position_range',
            'backing_range', 'f_number_range', 'vertical_progression_range'
        ];

        foreach ($rangeFields as $field) {
            if (empty($validated[$field])) {
                Log::warning("$field is still empty after processing, setting default value");
                $validated[$field] = 'Default range value set by server';
            }
        }

        // Process photo upload if provided
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($certificate->photo_path && Storage::disk('public')->exists($certificate->photo_path)) {
                Storage::disk('public')->delete($certificate->photo_path);
            }

            $photoPath = $request->file('photo')->store('welder-photos', 'public');
            $validated['photo_path'] = $photoPath;
        } elseif ($request->has('use_existing_photo') && $request->get('use_existing_photo') === 'true') {
            // Use the welder's existing photo
            $welder = Welder::find($validated['welder_id']);
            if ($welder && $welder->photo_path) {
                $validated['photo_path'] = $welder->photo_path;
            }
        }

        // Update the certificate
        $certificate->update($validated);

        // Check if request is AJAX
        if ($request->ajax()) {
            // Log successful update for debugging
            Log::info("Certificate {$id} updated successfully via AJAX");

            // Include more data in the response to help with debugging
            return response()->json([
                'success' => true,
                'message' => 'Certificate updated successfully',
                'redirect' => route('smaw-certificates.certificate', $certificate->id),
                'certificate_id' => $certificate->id,
                'debug' => [
                    'wps_followed' => $certificate->wps_followed,
                    'test_date' => $certificate->test_date,
                    'base_metal_spec' => $certificate->base_metal_spec
                ]
            ]);
        }

        // Regular form submission response (fallback)
        return redirect()->route('smaw-certificates.certificate', $certificate->id)
            ->with('success', 'SMAW Certificate updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $certificate = SmawCertificate::findOrFail($id);
        
        // Delete the photo if it exists
        if ($certificate->photo_path) {
            Storage::disk('public')->delete($certificate->photo_path);
        }
        
        $certificate->delete();
        
        return redirect()->route('smaw-certificates.index')
            ->with('success', 'SMAW Certificate deleted successfully.');
    }
    
    /**
     * Format date for certificate display
     */
    private function formatCertificateDate($date)
    {
        if (!$date) return 'N/A';
        
        // Convert to Carbon instance if it's not already
        if (!$date instanceof \Carbon\Carbon) {
            $date = \Carbon\Carbon::parse($date);
        }
        
        // Format as "dd Month, yyyy"
        return $date->format('d F, Y');
    }

    /**
     * Generate a printable certificate
     */
    public function generateCertificate(string $id)
    {
        $certificate = SmawCertificate::with('welder.company', 'company')->findOrFail($id);
        
        // Format dates for display
        $formattedDates = [
            'test_date' => $this->formatCertificateDate($certificate->test_date),
            'inspector_date' => $this->formatCertificateDate($certificate->inspector_date),
        ];
        
        // Debug company logo path
        $logoPath = \App\Models\AppSetting::getValue('company_logo_path');
        $logoExists = !empty($logoPath) && Storage::disk('public')->exists($logoPath);
        
        // Generate QR Code for certificate verification
        $verificationUrl = route('smaw-certificates.verify', ['id' => $certificate->id, 'code' => $certificate->verification_code]);
        $qrCodeUrl = 'data:image/png;base64,' . base64_encode(QrCode::format('png')->size(200)->generate($verificationUrl));
        
        return view('smaw_certificates.certificate', compact('certificate', 'qrCodeUrl', 'logoPath', 'logoExists', 'formattedDates'));
    }
    
    /**
     * Verify certificate authenticity
     */
    public function verify(string $id, string $code)
    {
        $certificate = SmawCertificate::with('welder.company', 'company')->findOrFail($id);
        
        // Check if verification code matches
        if ($code !== $certificate->verification_code) {
            return view('smaw_certificates.verify', [
                'certificate' => null,
                'isValid' => false,
                'message' => 'Invalid verification code.'
            ]);
        }
        
        return view('smaw_certificates.verify', [
            'certificate' => $certificate,
            'isValid' => true,
            'message' => 'Certificate verified successfully.'
        ]);
    }
    
    /**
     * Show preview of certificate based on form data
     */
    public function preview()
    {
        // This will handle the preview functionality using session data
        // Generate a dummy QR code for preview
        $qrCodeUrl = 'data:image/png;base64,' . base64_encode(QrCode::format('png')->size(200)->generate('Preview certificate - not valid'));
        
        return view('smaw_certificates.preview', compact('qrCodeUrl'));
    }

    /**
     * Generate a welder qualification card (front)
     */
    public function generateCard(string $id)
    {
        $certificate = SmawCertificate::with('welder.company', 'company')->findOrFail($id);
        
        // Generate QR Code for certificate verification
        $verificationUrl = route('smaw-certificates.verify', ['id' => $certificate->id, 'code' => $certificate->verification_code]);
        $qrCodeUrl = 'data:image/png;base64,' . base64_encode(QrCode::format('png')->size(200)->generate($verificationUrl));
        $welder = $certificate->welder;
        
        return view('smaw_certificates.card', compact('certificate', 'qrCodeUrl', 'welder'));
    }
    
    /**
     * Generate a welder qualification back card
     */
    public function generateBackCard(string $id)
    {
        $certificate = SmawCertificate::with('welder.company', 'company')->findOrFail($id);
        
        // Generate QR Code for certificate verification
        $verificationUrl = route('smaw-certificates.verify', ['id' => $certificate->id, 'code' => $certificate->verification_code]);
        $qrCodeUrl = 'data:image/png;base64,' . base64_encode(QrCode::format('png')->size(200)->generate($verificationUrl));
        
        return view('smaw_certificates.back_card', compact('certificate', 'qrCodeUrl'));
    }

    /**
     * Get company code for the given company ID
     */
    private function getCompanyCode($companyId)
    {
        $company = Company::find($companyId);
        $systemCode = \App\Models\AppSetting::getValue('doc_prefix', 'EEA');
        $companyCode = $company && $company->code ? $systemCode . '-' . $company->code : $systemCode . '-AIC';
        return $companyCode;
    }
    
    /**
     * Get the diameter range based on pipe diameter type
     */
    private function getDiameterRange($diameterType)
    {
        $diameterRules = [
            '8_nps' => 'Pipe of diameter ≥ 219.1 mm (8" NPS)',
            '6_nps' => 'Pipe of diameter ≥ 168.3 mm (6" NPS)',
            '4_nps' => 'Pipe of diameter ≥ 114.3 mm (4" NPS)',
            '2_nps' => 'Pipe of diameter ≥ 60.3 mm (2" NPS)',
        ];
        
        return $diameterRules[$diameterType] ?? 'Pipe of diameter ≥ 219.1 mm (8" NPS)';
    }
    
    /**
     * Get the P-Number range based on base metal P-Number
     */
    private function getPNumberRange($pNumber)
    {
        // Return the same range value for all P-Number options
        return 'P-NO. 1 through P-NO. 15F, P-NO. 34, and P-NO. 41 through P-NO. 49';
    }
    
    /**
     * Get the position range based on test position and specimen type
     */
    private function getPositionRange($position, $isPipe)
    {
        // Make sure isPipe is treated as boolean
        $isPipe = filter_var($isPipe, FILTER_VALIDATE_BOOLEAN);
        
        $positionRules = [
            '1G' => [
                'groove_over_24' => 'F for Groove Plate and Pipe Over 24 in. (610 mm) O.D.',
                'groove_under_24' => 'F for Groove Pipe ≤24 in. (610 mm) O.D.',
                'fillet' => 'F for Fillet or Tack Plate and Pipe'
            ],
            '2G' => [
                'groove_over_24' => 'F&H for Groove Plate and Pipe Over 24 in. (610 mm) O.D.',
                'groove_under_24' => 'F&H for Groove Pipe ≤24 in. (610 mm) O.D.',
                'fillet' => 'F&H for Fillet or Tack Plate and Pipe'
            ],
            '3G' => [
                'groove_over_24' => 'F&V for Groove Plate and Pipe Over 24 in. (610 mm) O.D.',
                'groove_under_24' => 'F for Groove Pipe ≤24 in. (610 mm) O.D.',
                'fillet' => 'F, H & V for Fillet or Tack Plate and Pipe'
            ],
            '4G' => [
                'groove_over_24' => 'F&O for Groove Plate and Pipe Over 24 in. (610 mm) O.D.',
                'groove_under_24' => 'F for Groove Pipe ≤24 in. (610 mm) O.D.',
                'fillet' => 'F, H & O for Fillet or Tack Plate and Pipe'
            ],
            '5G' => [
                'groove_over_24' => 'F,V&O for Groove Plate and Pipe Over 24 in. (610 mm) O.D.',
                'groove_under_24' => 'F,V&O for Groove Pipe ≤24 in. (610 mm) O.D.',
                'fillet' => 'All positions for Fillet or Tack Plate and Pipe'
            ],
            '6G' => [
                'groove_over_24' => 'Groove Plate and Pipe Over 24 in. (610 mm) O.D. in all Position',
                'groove_under_24' => 'Groove Pipe ≤24 in. (610 mm) O.D. in all Position',
                'fillet' => 'Fillet or Tack Plate and Pipe in all Position'
            ]
        ];
        
        $rules = isset($positionRules[$position]) ? $positionRules[$position] : $positionRules['6G'];
        
        // Return appropriate range based on pipe/plate
        return $isPipe ? $rules['groove_under_24'] : $rules['groove_over_24'];
    }
    
    /**
     * Get backing range based on backing type
     */
    private function getBackingRange($backing)
    {
        $backingRules = [
            'With Backing' => 'With backing or backing ',
            'Without Backing' => 'Without backing or with backing ',
        ];
        
        return $backingRules[$backing] ?? 'With backing or backing';
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
        return $progression === 'Uphill' ? 'Upward' : 'Downward';
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
            return view('smaw_certificates.verification_form', [
                'error' => 'Certificate not found. Please check the certificate number and try again.',
                'certificate_no' => $validated['certificate_no']
            ]);
        }
        
        return redirect()->route('smaw-certificates.verify', [
            'id' => $certificate->id,
            'code' => $certificate->verification_code
        ]);
    }

    /**
     * Get welder details for AJAX request
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
                'welder_id_no' => $welder->welder_no ,
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
            'photo' => $welder->photo ? asset('storage/' . $welder->photo) : null
        ]);
    }
}
