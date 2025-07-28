<?php

namespace App\Http\Controllers;

use App\Models\SmawCertificate;
use App\Models\Welder;
use App\Models\Company;
use App\Enums\CertificateOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SmawCertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = SmawCertificate::query()->with('welder.company', 'company');
        
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
        
        // Filter by test result
        if ($request->has('test_result') && $request->test_result !== '') {
            $query->where('test_result', $request->test_result);
        }
        
        $certificates = $query->orderBy('created_at', 'desc')->paginate(10);
        $welders = Welder::orderBy('name')->pluck('name', 'id');
        $companies = Company::orderBy('name')->pluck('name', 'id');
        
        return view('smaw_certificates.index', compact('certificates', 'welders', 'companies'));
    }

    /**
     * Show the form for creating a new resource.
     */
   /**
 * Show the form for creating a new resource.
 */
public function create(Request $request)
{
    // Get welders with nested data structure
    $welders = Welder::with('company')->orderBy('name')->get()->mapWithKeys(function ($welder) {
        return [$welder->id => [
            'name' => $welder->name,
            'welder_no' => $welder->welder_no,
            'iqama_no' => $welder->iqama_no,
            'passport_no' => $welder->passport_no,
            'company_id' => $welder->company_id,
            'company_name' => $welder->company ? $welder->company->name : '',
            'photo_path' => $welder->photo_path
        ]];
    });
    
    // Simple companies array
    $companies = Company::orderBy('name')->pluck('name', 'id');
    
    // Pre-select welder if provided in the request
    $selectedWelder = null;
    if ($request->has('welder_id') && !empty($request->welder_id)) {
        $selectedWelder = Welder::find($request->welder_id);
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
    
    // Generate certificate number
    $systemCode = \App\Models\AppSetting::getValue('doc_prefix', 'EEA');
    $defaultCompanyCode = $systemCode . '-AIC';
    $certificatePrefix = $defaultCompanyCode . '-WQT-';
    
    $lastCert = SmawCertificate::where('certificate_no', 'like', $certificatePrefix . '%')
        ->orderBy('certificate_no', 'desc')
        ->first();
        
    $newNumber = 1;
    if ($lastCert) {
        $lastNumber = (int) substr($lastCert->certificate_no, -4);
        $newNumber = $lastNumber + 1;
    }
    
    $newCertNo = $certificatePrefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    
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
        'newCertNo'
    ));
}

/**
 * Store a newly created resource in storage.
 */
public function store(Request $request)
{
        $validated = $request->validate([
            // Basic Information
            'welder_id' => 'required|exists:welders,id',
            'company_id' => 'required|exists:companies,id',
            'certificate_no' => 'required|string|max:255',
            'wps_followed' => 'required|string|max:255',
            'test_date' => 'required|date',
            'base_metal_spec' => 'required|string|max:255',
            'dia_thickness' => 'nullable|string|max:255',
            
            // Process and Specimen
            'smaw_yes' => 'boolean',
            'test_coupon' => 'boolean',
            'production_weld' => 'boolean',
            'plate_specimen' => 'boolean',
            'pipe_specimen' => 'boolean',
            
            // Pipe and Material Details
            'pipe_diameter_type' => 'required|string|max:255',
            'pipe_diameter_manual' => 'nullable|string|max:255',
            'base_metal_p_no' => 'required|string|max:255',
            'base_metal_p_no_manual' => 'nullable|string|max:255',
            'p_number_range_manual' => 'nullable|string',
            
            // Thickness and Position
            'smaw_thickness' => 'required|string|max:255',
            'test_position' => 'required|string|max:255',
            'position_range_manual' => 'nullable|string',
            
            // Backing and Filler
            'backing' => 'required|string|max:255',
            'backing_manual' => 'nullable|string|max:255',
            'backing_range_manual' => 'nullable|string',
            'filler_spec' => 'required|string|max:255',
            'filler_spec_manual' => 'nullable|string|max:255',
            'filler_spec_range' => 'nullable|string',
            'filler_class' => 'required|string|max:255',
            'filler_class_manual' => 'nullable|string|max:255',
            'filler_class_range' => 'nullable|string',
            'filler_f_no' => 'required|string|max:255',
            'filler_f_no_manual' => 'nullable|string|max:255',
            
            // Range Qualified Fields
            'consumable_insert' => 'nullable|string|max:255',
            'consumable_insert_range' => 'nullable|string',
            'filler_product_form' => 'nullable|string|max:255',
            'filler_product_form_range' => 'nullable|string',
            'deposit_thickness' => 'nullable|string|max:255',
            'deposit_thickness_range' => 'nullable|string',
            'gtaw_yes' => 'boolean',
            'gtaw_no' => 'boolean',
            'gtaw_thickness' => 'nullable|string|max:255',
            'gtaw_thickness_range' => 'nullable|string',
            'smaw_thickness_range' => 'nullable|string',
            
            // Vertical Progression
            'vertical_progression' => 'required|string|max:255',
            'vertical_progression_manual' => 'nullable|string|max:255',
            'vertical_progression_range_manual' => 'nullable|string',
            
            // Additional Process Parameters
            'fuel_gas' => 'nullable|string|max:255',
            'fuel_gas_range' => 'nullable|string',
            'backing_gas' => 'nullable|string|max:255',
            'backing_gas_range' => 'nullable|string',
            'transfer_mode' => 'nullable|string|max:255',
            'transfer_mode_range' => 'nullable|string',
            'gtaw_current' => 'nullable|string|max:255',
            'gtaw_current_range' => 'nullable|string',
            'equipment_type' => 'nullable|string|max:255',
            'equipment_type_range' => 'nullable|string',
            'technique' => 'nullable|string|max:255',
            'technique_range' => 'nullable|string',
            'oscillation_yes' => 'boolean',
            'oscillation_no' => 'boolean',
            'oscillation' => 'nullable|string|max:255',
            'oscillation_range' => 'nullable|string',
            'operation_mode' => 'nullable|string|max:255',
            'operation_mode_range' => 'nullable|string',
            
            // Results Section
            'visual_examination_result' => 'nullable|string|max:50',
            'transverse_face_root' => 'boolean',
            'longitudinal_bends' => 'boolean',
            'side_bends' => 'boolean',
            'pipe_bend_corrosion' => 'boolean',
            'plate_bend_corrosion' => 'boolean',
            'pipe_macro_fusion' => 'boolean',
            'plate_macro_fusion' => 'boolean',
            
            // Additional TYPE/RESULT Fields
            'additional_type_1' => 'nullable|string',
            'additional_result_1' => 'nullable|string',
            'additional_type_2' => 'nullable|string',
            'additional_result_2' => 'nullable|string',
            'rt_result' => 'nullable|string',
            'rt_checked' => 'boolean',
            'ut_checked' => 'boolean',
            
            // Test Details
            'fillet_fracture_test' => 'nullable|string',
            'defects_length' => 'nullable|string',
            'fillet_welds_plate' => 'boolean',
            'fillet_welds_pipe' => 'boolean',
            'macro_exam' => 'nullable|string',
            'fillet_size' => 'nullable|string',
            'other_tests' => 'nullable|string',
            'concavity_convexity' => 'nullable|string',
            
            // Personnel Information
            'evaluated_by' => 'nullable|string|max:100',
            'evaluated_company' => 'nullable|string|max:100',
            'mechanical_tests_by' => 'nullable|string|max:100',
            'lab_test_no' => 'nullable|string|max:50',
            'supervised_by' => 'nullable|string|max:100',
            'supervised_company' => 'nullable|string|max:100',
            
            // Confirmation Fields
            'confirm_date1' => 'nullable|date',
            'confirm_title1' => 'nullable|string|max:100',
            'confirm_date2' => 'nullable|date',
            'confirm_title2' => 'nullable|string|max:100',
            'confirm_date3' => 'nullable|date',
            'confirm_title3' => 'nullable|string|max:100',
            
            // Inspector Details
            'inspector_name' => 'nullable|string|max:255',
            'inspector_date' => 'nullable|date',
            'test_result' => 'boolean',
            'photo' => 'nullable|image|max:2048',
        ]);
        
        // Handle ranges based on selections
        $validated['diameter_range'] = $this->getDiameterRange($validated['pipe_diameter_type']);
        $validated['p_number_range'] = $this->getPNumberRange($validated['base_metal_p_no']);
        $validated['position_range'] = $this->getPositionRange($validated['test_position'], $validated['pipe_specimen'] ?? false);
        $validated['backing_range'] = $this->getBackingRange($validated['backing']);
        $validated['f_number_range'] = $this->getFNumberRange($validated['filler_f_no']);
        $validated['vertical_progression_range'] = $this->getVerticalProgressionRange($validated['vertical_progression']);
        
        // Process photo upload if provided
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('welder-photos', 'public');
            $validated['photo_path'] = $photoPath;
        }
        
        // Generate certificate number if not provided
        if (empty($validated['certificate_no'])) {
            $companyCode = $this->getCompanyCode($validated['company_id']);
            $prefix = $companyCode . '-WQT-';
            
            $lastCert = SmawCertificate::where('certificate_no', 'like', $prefix . '%')
                ->orderBy('certificate_no', 'desc')
                ->first();
                
            $newNumber = 1;
            if ($lastCert) {
                $lastNumber = (int) substr($lastCert->certificate_no, -4);
                $newNumber = $lastNumber + 1;
            }
            
            $validated['certificate_no'] = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        }
        
        $validated['created_by'] = Auth::id();
        $validated['verification_code'] = \Str::random(32);

        $certificate = SmawCertificate::create($validated);
        
        return redirect()->route('smaw-certificates.show', $certificate->id)
            ->with('success', 'SMAW Certificate created successfully.');
    }

    // ... (keep all existing methods: show, edit, update, destroy, etc.)

    /**
     * Get the P-Number range based on base metal P-Number (Updated)
     */
    private function getPNumberRange($pNumber)
    {
        $pNumberRules = [
            'P NO.1 TO P NO.1' => 'P-NO. 1 through P-NO. 15F, P-NO. 34, and P-NO. 41 through P-NO. 49',
            'P NO.1 TO P NO.8' => 'P-NO. 1 through P-NO. 15F, P-NO. 34, and P-NO. 41 through P-NO. 49',
            'P NO.8 TO P NO.8' => 'P-NO. 1 through P-NO. 15F, P-NO. 34, and P-NO. 41 through P-NO. 49',
            'P NO.43 TO P NO.43' => 'P-No. 1 through P-Nr. 15F, P-Nr. 34, and P-Nr. 41 through P-Nr. 49',
        ];
        
        return $pNumberRules[$pNumber] ?? 'P-NO. 1 through P-NO. 15F, P-NO. 34, and P-NO. 41 through P-NO. 49';
    }
    
    /**
     * Get the position range based on test position and specimen type (Updated)
     */
    private function getPositionRange($position, $isPipe)
    {
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
        
        if (isset($positionRules[$position])) {
            $rules = $positionRules[$position];
            return $rules['groove_over_24'] . ' | ' . $rules['groove_under_24'] . ' | ' . $rules['fillet'];
        }
        
        return 'All positions';
    }
    
    /**
     * Get vertical progression range (Updated)
     */
    private function getVerticalProgressionRange($progression)
    {
        return $progression === 'Uphill' ? 'Upward' : 'Downward';
    }

    // ... (keep all other existing private methods)
}