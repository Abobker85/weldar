<?php

namespace App\Http\Controllers;

use App\Models\GtawCertificate;
use App\Models\Welder;
use App\Models\Company;
use App\Enums\CertificateOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Yajra\DataTables\Facades\DataTables; // Add this import

class GtawCertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // $certificate = GtawCertificate::with('welder', 'company')->findOrFail($id);
        $welders = Welder::orderBy('name')->get();
        $companies = Company::orderBy('name')->get();

        if ($request->ajax()) {
            $query = GtawCertificate::with('welder', 'company');
            
            // Filter by certificate number
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
            ->editColumn('test_date', function ($certificate) {
                return $certificate->test_date ? $certificate->test_date->format('Y-m-d') : 'N/A';
            })
            ->addColumn('actions', function ($certificate) {
                $actions = '<div class="btn-group" role="group">';
                $actions .= '<a href="' . route('gtaw-certificates.certificate', $certificate->id) . '" class="btn btn-sm btn-success" target="_blank"><i class="fas fa-certificate"></i></a>';
                //edit 
                $actions .= '<a href="' . route('gtaw-certificates.edit', $certificate->id) . '" class="btn btn-sm btn-primary" target="_blank"><i class="fas fa-edit"></i></a>';
                $actions .= '<a href="' . route('gtaw-certificates.card', $certificate->id) . '" class="btn btn-sm btn-info" target="_blank"><i class="fas fa-id-card"></i></a>';
                $actions .= '<a href="' . route('gtaw-certificates.back-card', $certificate->id) . '" class="btn btn-sm btn-warning" target="_blank"><i class="fas fa-id-card-alt"></i></a>';
                $actions .= '<form action="' . route('gtaw-certificates.destroy', $certificate->id) . '" method="POST" onsubmit="return confirm(\'Are you sure you want to delete this certificate?\');" style="display:inline">';
                $actions .= csrf_field();
                $actions .= method_field('DELETE');
                $actions .= '<button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>';
                $actions .= '</form>';
                $actions .= '</div>';

                return $actions;
            })
            ->rawColumns(['actions'])
            ->make(true);
        }

      
        
        return view('gtaw_certificates.index', compact('welders', 'companies'));
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
        $certificatePrefix = $defaultCompanyCode . '-GTAW-';
        
        // If a company was pre-selected in the request, use its code for the initial cert number
        if ($request->has('company_id') && !empty($request->company_id)) {
            $company = Company::find($request->company_id);
            if ($company && $company->code) {
                $certificatePrefix = $systemCode . '-' . $company->code . '-GTAW-';
            }
        }
        
        $lastCert = GtawCertificate::where('certificate_no', 'like', $certificatePrefix . '%')
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
        $lastVTReport = GtawCertificate::where('vt_report_no', 'like', $vtReportPrefix . '%')
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
        $lastRTReport = GtawCertificate::where('rt_report_no', 'like', $rtReportPrefix . '%')
            ->orderBy('rt_report_no', 'desc')
            ->first();
            
        $newRTNumber = 1;
        if ($lastRTReport) {
            $lastNumber = (int) substr($lastRTReport->rt_report_no, -4);
            $newRTNumber = $lastNumber + 1;
        }
        
        $rtReportNo = $rtReportPrefix . str_pad($newRTNumber, 4, '0', STR_PAD_LEFT);


        return view('gtaw_certificates.create', compact(
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
            'rtReportNo'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
      $booleanFields = [
            'test_coupon', 'production_weld', 'plate_specimen', 'pipe_specimen', 'rt', 'ut',
            'fillet_welds_plate', 'fillet_welds_pipe', 'pipe_macro_fusion', 'plate_macro_fusion',
            'transverse_face_root', 'longitudinal_bends', 'side_bends',
            'pipe_bend_corrosion', 'plate_bend_corrosion','gtaw_process','smaw_process', 'gtaw_yes', 'gtaw_no',
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

            'gtaw_process' => 'required',
            'gtaw_thickness' => 'required|string|max:255',

            // Position and backing information
            'test_position' => 'required|string|max:255',
            'position_range' => 'nullable|string|max:255',
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
            'signature_data' => 'nullable|string',
            'inspector_signature_data' => 'nullable|string',
            
            // Additional welding variables - all optional
            'oscillation' => 'nullable|string|max:255',
            'fuel_gas' => 'nullable|string|max:255',
            'fuel_gas_range' => 'nullable|string|max:255',
            'backing_gas' => 'required|string|max:255',
            'backing_gas_range' => 'required|string|max:255',
            'gtaw_polarity' => 'required|string|max:255',
            'gtaw_polarity_range' => 'required|string|max:255',
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
            'certification_text' => 'required|string|max:500',
            
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
        ]);
        
        // Use submitted range values if present, otherwise calculate them
        if (empty($validated['diameter_range']) && !empty($validated['pipe_diameter_type'])) {
            $validated['diameter_range'] = $this->getDiameterRange($validated['pipe_diameter_type']);
        }
        
        if (empty($validated['p_number_range'])) {
            $validated['p_number_range'] = $this->getPNumberRange($validated['base_metal_p_no']);
        }
        
        // Fix for pipe_specimen key - use 'pipe' instead since that's what's in the form
        // if (empty($validated['position_range'])) {
        //    // $validated['position_range'] = $this->getPositionRange($validated['test_position'], $validated['pipe']);
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

         $validated['consumable_insert'] = 'Not Applicable';
        $validated['consumable_insert_range'] = 'Not Applicable';
        
        // If these fields were somehow passed in the request, override them
        if (isset($validated['consumable_insert']) && $validated['consumable_insert'] !== 'Not Applicable') {
            $validated['consumable_insert'] = 'Not Applicable';
        }
        if (isset($validated['consumable_insert_range']) && $validated['consumable_insert_range'] !== 'Not Applicable') {
            $validated['consumable_insert_range'] = 'Not Applicable';
        }

        
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
        $prefix = $companyCode . '-GTAW-';
        
        $lastCert = GtawCertificate::where('certificate_no', 'like', $prefix . '%')
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

        $certificate = GtawCertificate::create($validated);
        
        // Check if request is AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'GTAW Certificate created successfully.',
                'redirect' => route('gtaw-certificates.certificate', $certificate->id),
                'certificate_id' => $certificate->id
            ]);
        }
        
        // Regular form submission response (fallback)
        return redirect()->route('gtaw-certificates.certificate', $certificate->id)
            ->with('success', 'GTAW Certificate created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $certificate = GtawCertificate::with('welder.company', 'company')->findOrFail($id);
        return view('gtaw_certificates.show', compact('certificate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $certificate = GtawCertificate::with('welder', 'company')->findOrFail($id);
       // dd($certificate->vertical_progression_range);


    
        $welders = Welder::orderBy('name')->get();
        $companies = Company::orderBy('name')->get();
        $selectedWelder = $certificate->welder;
        
        // Get options from CertificateOptions class
        $pipeDiameterTypes = CertificateOptions::pipeDiameterTypes();
        $testPositions = CertificateOptions::testPositions();
        $baseMetalPNumbers = CertificateOptions::baseMetalPNumbers();
        $fillerSpecs = CertificateOptions::fillerSpecs();
        $fillerClasses = CertificateOptions::fillerClasses();
        $fillerFNumbers = CertificateOptions::fillerFNumbers();
        $backingTypes = CertificateOptions::backingTypes();
        $verticalProgressions = CertificateOptions::verticalProgressions();
        
        return view('gtaw_certificates.edit', compact(
            'certificate',
            'welders',
            'selectedWelder',
            'companies',
            'pipeDiameterTypes',
            'testPositions',
            'baseMetalPNumbers',
            'fillerSpecs',
            'fillerClasses',
            'fillerFNumbers',
            'backingTypes',
            'verticalProgressions'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $certificate = GtawCertificate::findOrFail($id);

        $booleanFields = [
            'test_coupon', 'production_weld', 'plate_specimen', 'pipe_specimen', 'rt', 'ut',
            'fillet_welds_plate', 'fillet_welds_pipe', 'pipe_macro_fusion', 'plate_macro_fusion',
            'transverse_face_root', 'longitudinal_bends', 'side_bends',
            'pipe_bend_corrosion', 'plate_bend_corrosion','gtaw_process','smaw_process', 'gtaw_yes', 'gtaw_no',
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

            'gtaw_process' => 'required',
            'gtaw_thickness' => 'required|string|max:255',

            // Position and backing information
            'test_position' => 'required|string|max:255',
            'position_range' => 'nullable|string|max:255',
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
            'certification_text' => 'required|string|max:500',
            'signature_data' => 'nullable|string',
            'inspector_signature_data' => 'nullable|string',

            // Additional welding variables - all optional
            'oscillation' => 'nullable|string|max:255',
            'fuel_gas' => 'nullable|string|max:255',
            'fuel_gas_range' => 'nullable|string|max:255',
            'backing_gas' => 'required|string|max:255',
            'backing_gas_range' => 'required|string|max:255',
            'gtaw_polarity' => 'required|string|max:255',
            'gtaw_polarity_range' => 'required|string|max:255',
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
        ]);

        // Use submitted range values if present, otherwise calculate them
        if (empty($validated['diameter_range']) && !empty($validated['pipe_diameter_type'])) {
            $validated['diameter_range'] = $this->getDiameterRange($validated['pipe_diameter_type']);
        }

        if (empty($validated['p_number_range'])) {
            $validated['p_number_range'] = $this->getPNumberRange($validated['base_metal_p_no']);
        }

        // Fix for pipe_specimen key - use 'pipe' instead since that's what's in the form
        // if (empty($validated['position_range'])) {
        //    // $validated['position_range'] = $this->getPositionRange($validated['test_position'], $validated['pipe']);
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

         $validated['consumable_insert'] = 'Not Applicable';
        $validated['consumable_insert_range'] = 'Not Applicable';
        
        // If these fields were somehow passed in the request, override them
        if (isset($validated['consumable_insert']) && $validated['consumable_insert'] !== 'Not Applicable') {
            $validated['consumable_insert'] = 'Not Applicable';
        }
        if (isset($validated['consumable_insert_range']) && $validated['consumable_insert_range'] !== 'Not Applicable') {
            $validated['consumable_insert_range'] = 'Not Applicable';
        }


        // Process photo upload if provided
        if ($request->hasFile('photo')) {
            // Delete old photo if it exists
            if ($certificate->photo_path) {
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

        $certificate->update($validated);

        // Check if request is AJAX
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'GTAW Certificate updated successfully.',
                'redirect' => route('gtaw-certificates.certificate', $certificate->id),
                'certificate_id' => $certificate->id
            ]);
        }

        // Regular form submission response (fallback)
        return redirect()->route('gtaw-certificates.certificate', $certificate->id)
            ->with('success', 'GTAW Certificate updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $certificate = GtawCertificate::findOrFail($id);
        
        // Delete the photo if it exists
        if ($certificate->photo_path) {
            Storage::disk('public')->delete($certificate->photo_path);
        }
        
        $certificate->delete();
        
        return redirect()->route('gtaw-certificates.index')
            ->with('success', 'GTAW Certificate deleted successfully.');
    }
    
    /**
     * Generate a printable certificate
     */
    public function generateCertificate(string $id)
    {
        $certificate = GtawCertificate::with('welder.company', 'company')->findOrFail($id);
        
        // Debug company logo path
        $logoPath = \App\Models\AppSetting::getValue('company_logo_path');
        $logoExists = !empty($logoPath) && Storage::disk('public')->exists($logoPath);
        
        // Generate QR Code for certificate verification
        $verificationUrl = route('gtaw-certificates.verify', ['id' => $certificate->id, 'code' => $certificate->verification_code]);
        $qrCodeUrl = 'data:image/png;base64,' . base64_encode(QrCode::format('png')->size(200)->generate($verificationUrl));
        
        return view('gtaw_certificates.certificate', compact('certificate', 'qrCodeUrl', 'logoPath', 'logoExists'));
    }
    
    /**
     * Verify certificate authenticity
     */
    public function verify(string $id, string $code)
    {
        $certificate = GtawCertificate::with('welder.company', 'company')->findOrFail($id);
        
        // Check if verification code matches
        if ($code !== $certificate->verification_code) {
            return view('gtaw_certificates.verify', [
                'certificate' => null,
                'isValid' => false,
                'message' => 'Invalid verification code.'
            ]);
        }
        
        return view('gtaw_certificates.verify', [
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
        
        return view('gtaw_certificates.preview', compact('qrCodeUrl'));
    }

    /**
     * Generate a welder qualification card (front)
     */
    public function generateCard(string $id)
    {
        $certificate = GtawCertificate::with('welder.company', 'company')->findOrFail($id);
        
        // Generate QR Code for certificate verification
        $verificationUrl = route('gtaw-certificates.verify', ['id' => $certificate->id, 'code' => $certificate->verification_code]);
        $qrCodeUrl = 'data:image/png;base64,' . base64_encode(QrCode::format('png')->size(200)->generate($verificationUrl));
        $welder = $certificate->welder;
        
        return view('gtaw_certificates.card', compact('certificate', 'qrCodeUrl', 'welder'));
    }
    
    /**
     * Generate a welder qualification back card
     */
    public function generateBackCard(string $id)
    {
        $certificate = GtawCertificate::with('welder.company', 'company')->findOrFail($id);
        
        // Generate QR Code for certificate verification
        $verificationUrl = route('gtaw-certificates.verify', ['id' => $certificate->id, 'code' => $certificate->verification_code]);
        $qrCodeUrl = 'data:image/png;base64,' . base64_encode(QrCode::format('png')->size(200)->generate($verificationUrl));
        
        return view('gtaw_certificates.back_card', compact('certificate', 'qrCodeUrl'));
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
        
        if (!$isPipe) {
            // Plate rules
            $plateRules = [
                '1G' => 'Flat position groove plate',
                '2G' => 'Horizontal position groove plate',
                '3G' => 'Vertical position groove plate',
                '4G' => 'Overhead position groove plate',
            ];
            return $plateRules[$position] ?? 'All positions groove plate';
        }
        
        // Pipe rules
        $pipeRules = [
            '1G' => 'Flat position groove pipe',
            '2G' => 'Horizontal position groove pipe',
            '5G' => 'Horizontal fixed position groove pipe',
            '6G' => 'All Position Groove Plate and Pipe Over 24 in. (610 mm) O.D. | All Position Groove Pipe ≤24 in. (610 mm) O.D. | All Position Fillet or Tack Plate and Pipe',
        ];
        
        return $pipeRules[$position] ?? 'All positions groove pipe';
    }
    
    /**
     * Get backing range based on backing type
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
            'F-No.6' => 'All F-No. 6',
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
    
    /**
     * Show certificate verification form for public users
     */
    public function showVerificationForm()
    {
        return view('gtaw_certificates.verification_form');
    }
    
    /**
     * Verify certificate by certificate number
     */
    public function verifyByCertificateNo(Request $request)
    {
        $validated = $request->validate([
            'certificate_no' => 'required|string',
        ]);
        
        $certificate = GtawCertificate::where('certificate_no', $validated['certificate_no'])->first();
        
        if (!$certificate) {
            return view('gtaw_certificates.verification_form', [
                'error' => 'Certificate not found. Please check the certificate number and try again.',
                'certificate_no' => $validated['certificate_no']
            ]);
        }
        
        return redirect()->route('gtaw-certificates.verify', [
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
        
        // Generate certificate and report numbers based on company
        $systemCode = \App\Models\AppSetting::getValue('doc_prefix', 'EEA');
        $companyCode = '';
        
        if ($welder->company) {
            $companyCode = $welder->company->code ? $systemCode . '-' . $welder->company->code : $systemCode . '-AIC';
        } else {
            $companyCode = $systemCode . '-AIC';
        }
        
        // Generate certificate number
        $certificatePrefix = $companyCode . '-GTAW-';
        $lastCert = GtawCertificate::where('certificate_no', 'like', $certificatePrefix . '%')
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
        $lastVTReport = GtawCertificate::where('vt_report_no', 'like', $vtReportPrefix . '%')
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
        $lastRTReport = GtawCertificate::where('rt_report_no', 'like', $rtReportPrefix . '%')
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
