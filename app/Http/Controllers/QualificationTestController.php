<?php

namespace App\Http\Controllers;

use App\Models\QualificationTest;
use App\Models\Welder;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
       

class QualificationTestController extends Controller
{
    /**
     * Check if user can change test result to pass
     * 
     * @return bool
     */
    private function canChangeTestResultToPass()
    {
        return Auth::user()->role === 'qc' || Auth::user()->role === 'admin';
    }
    
    /**
     * Check if user can generate card or certificate
     * 
     * @return bool
     */
    private function canGenerateCardOrCertificate()
    {
        return Auth::user()->role === 'qc' || Auth::user()->role === 'admin';
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = QualificationTest::query()->with('welder.company', 'company');
        
        // Search by certificate number
        if ($request->has('cert_no') && !empty($request->cert_no)) {
            $query->where('cert_no', 'like', '%' . $request->cert_no . '%');
        }
        
        // Filter by welder
        if ($request->has('welder_no') && !empty($request->welder_no)) {
            $query->where('welder_no', $request->welder_no);
        }
        
        // Filter by company
        if ($request->has('company_id') && !empty($request->company_id)) {
            $query->where('company_id', $request->company_id);
        }
        
        // Filter by welding process
        if ($request->has('welding_process') && !empty($request->welding_process)) {
            $query->where('welding_process', 'like', '%' . $request->welding_process . '%');
        }
        
        // Filter by qualification code
        if ($request->has('qualification_code') && !empty($request->qualification_code)) {
            $query->where('qualification_code', $request->qualification_code);
        }
        
        // Filter by status
        if ($request->has('status') && !empty($request->status)) {
            if ($request->status === 'active') {
                $query->where('is_active', true)
                      ->where('test_result', true)
                      ->where(function($q) {
                          $q->where('vt_result', 'ACC')
                            ->orWhere('rt_result', 'ACC');
                      });
            } elseif ($request->status === 'expired') {
                $query->where(function($q) {
                    $q->where('test_date', '<', now()->subMonths(6))
                      ->orWhere('vt_date', '<', now()->subMonths(6));
                });
            } elseif ($request->status === 'expiring-soon') {
                $query->where(function($q) {
                    $q->where(function($q2) {
                        $q2->where('test_date', '>=', now()->subMonths(6))
                           ->where('test_date', '<=', now()->addDays(30));
                    })
                    ->orWhere(function($q2) {
                        $q2->where('vt_date', '>=', now()->subMonths(6))
                           ->where('vt_date', '<=', now()->addDays(30));
                    });
                });
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        }
        
        // Filter by test result
        if ($request->has('test_result') && !is_null($request->test_result)) {
            $query->where('test_result', $request->test_result == 'pass');
        }

       
        
        $qualifications = $query->orderBy('created_at', 'desc')->paginate(10);
        $welders = Welder::orderBy('name')->pluck('name', 'id');
        $companies = Company::orderBy('name')->pluck('name', 'id');
        
        // Get unique processes and certification codes for filtering
        $processes = QualificationTest::select('welding_process')->distinct()->pluck('welding_process');
        $certificationCodes = QualificationTest::select('qualification_code')->distinct()->pluck('qualification_code');
        
        return view('qualification_tests.index', compact(
            'qualifications', 
            'welders',
            'companies',
            'processes', 
            'certificationCodes'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
       
        

       
        $welders = Welder::orderBy('name')->pluck('name', 'id');
        $companies = Company::orderBy('name')->pluck('name', 'id');
        
        // Pre-select welder if provided in the request
        $selectedWelder = null;
        if ($request->has('welder_no')) {
            $selectedWelder = Welder::where('welder_no', $request->welder_no)->first();
        }
        
        // Get options from QualificationOptions class
        $processes = \App\Enums\QualificationOptions::weldingProcesses();
        $testPositions = \App\Enums\QualificationOptions::testPositions();
        $certificationCodes = \App\Enums\QualificationOptions::certificationCodes();
        
        // Additional options
        $couponMaterials = \App\Enums\QualificationOptions::couponMaterials();
        $qualifiedMaterials = \App\Enums\QualificationOptions::qualifiedMaterials();
        $qualifiedThicknessRanges = \App\Enums\QualificationOptions::qualifiedThicknessRanges();
        $electricCharacteristics = \App\Enums\QualificationOptions::electricCharacteristics();
        $testResults = \App\Enums\QualificationOptions::testResults();
        
        // Get the default company code and qualification type from app settings/request
        $systemCode = \App\Models\AppSetting::getValue('doc_prefix', 'EEA');
        $defaultCompanyCode = $systemCode . '-AIC';
        $qualificationType = $request->input('qualification_type', 'WQT');
        $newCardNo = $defaultCompanyCode . '-' . $qualificationType . '-0001';
        
        // If a company was pre-selected in the request, use its code for the initial cert number
        if ($request->has('company_id')) {
            $company = Company::find($request->company_id);
            if ($company && $company->code) {
                $companyCode = $systemCode . '-' . $company->code;
                $prefix = $companyCode . '-' . $qualificationType . '-';
                
                $lastCert = QualificationTest::where('cert_no', 'like', $prefix . '%')
                    ->orderBy('id', 'desc')
                    ->first();
                
                if ($lastCert && strpos($lastCert->cert_no, $prefix) === 0) {
                    $lastNumber = (int) substr($lastCert->cert_no, strlen($prefix));
                    $newNumber = $lastNumber + 1;
                } else {
                    $newNumber = 1;
                }
                
                $newCardNo = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
            }
        }
        
        // Additional dropdown options
        $verticalProgressions = [
            'Uphill' => 'Uphill',
            'Downhill' => 'Downhill',
            'GTAW-Uphill, SMAW-Uphill' => 'GTAW-Uphill, SMAW-Uphill',
            'N/A' => 'N/A',
        ];
        
        $testDias = [
            'PLATE' => 'PLATE',
            '2 Inch' => '2 Inch',
            '4 Inch' => '4 Inch',
            '6 Inch' => '6 Inch',
            '8 Inch' => '8 Inch',
        ];
        
        $testMethods = [
            'Radiography' => 'Radiography',
            'Ultrasonic' => 'Ultrasonic',
            'Bend Test' => 'Bend Test',
            'Visual' => 'Visual',
        ];
        
        return view('qualification_tests.create', compact(
            'welders',
            'selectedWelder',
            'companies',
            'processes',
            'testPositions',
            'certificationCodes',
            'couponMaterials',
            'qualifiedMaterials',
            'qualifiedThicknessRanges',
            'electricCharacteristics',
            'testResults',
            'newCardNo',
            'verticalProgressions',
            'testDias',
            'testMethods'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'welder_no' => 'required|exists:welders,id',
            'company_id' => 'required|exists:companies,id',
            'qualification_type' => 'required|string|in:WQT,PQR',
            'sr_no' => 'nullable|string',
            'work_order_no' => 'nullable|string',
            'location' => 'nullable|string',
            'passport_id_no' => 'nullable|string',
            'welder_no' => 'nullable|string',
            'wps_no' => 'required|string',
            'welding_process' => 'required|string',
            'test_coupon' => 'required|string',
            'dia_inch' => 'required|string',
            'qualified_dia_inch' => 'required|string',
            'coupon_material' => 'nullable|string',
            'qualified_material' => 'nullable|string',
            'coupon_thickness_mm' => 'nullable|numeric',
            'deposit_thickness' => 'nullable|string',
            'qualified_thickness_range' => 'nullable|string',
            'welding_positions' => 'required|string',
            'qualified_position' => 'required|string',
            'filler_metal_f_no' => 'nullable|string',
            'aws_spec_no' => 'nullable|string',
            'filler_metal_classif' => 'nullable|string',
            'backing' => 'nullable|string',
            'qualified_backing' => 'nullable|string',
            'electric_char' => 'nullable|string',
            'qualified_ec' => 'nullable|string',
            // Joint detail fields
            'joint_diagram' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'joint_type' => 'nullable|string',
            'joint_description' => 'nullable|string',
            'joint_angle' => 'nullable|string',
            'joint_total_angle' => 'nullable|string',
            'root_gap' => 'nullable|string',
            'root_face' => 'nullable|string',
            'pipe_outer_diameter' => 'nullable|string',
            'base_metal_p_no' => 'nullable|string',
            'filler_metal_form' => 'nullable|string',
            'inert_gas_backing' => 'nullable|string',
            'gtaw_thickness' => 'nullable|string',
            'smaw_thickness' => 'nullable|string',
            'vertical_progression' => 'nullable|string',
            // Test dates and results
            'test_date' => 'nullable|date',
            'vt_report_no' => 'nullable|string',
            'vt_result' => 'nullable|string',
            'rt_date' => 'nullable|date',
            'rt_report_no' => 'nullable|string',
            'rt_result' => 'nullable|string',
            'qualification_code' => 'nullable|string',
            'remarks' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'test_result' => 'nullable|string',
        ]);
        
        // Convert test_result string value to boolean
        $validated['test_result'] = $request->input('test_result') == '1';
        $validated['is_active'] = $request->has('is_active');
        
        // Check if user has permission to set test result to pass
        if ($validated['test_result'] && Auth::user()->role === 'user') {
            // Regular users cannot set test result to pass
            $validated['test_result'] = false;
            session()->flash('warning', 'Only QC personnel can set test result to Pass. The test has been saved as Failed.');
        }
        
        $validated['created_by'] = Auth::id();

        //weller_no is required, so we can set it directly
        $validated['welder_no'] = $request->input('welder_no');
        if (!$validated['welder_no']) {
            return back()->withErrors(['welder_no' => 'Welder is required.'])->withInput();
        }
        $welder = Welder::where('welder_no', $validated['welder_no'])->first();
        if (!$welder) {
            return back()->withErrors(['welder_no' => 'Welder not found.'])->withInput();
        }
        $validated['welder_id'] = $welder->id;
        
        // Handle joint diagram upload if provided
        if ($request->hasFile('joint_diagram')) {
            $path = $request->file('joint_diagram')->store('qualification_tests/joint_diagrams', 'public');
            $validated['joint_diagram_path'] = $path;
        }
        
        // Use test_date for all date fields if provided
        if (isset($validated['test_date'])) {
            $validated['vt_date'] = $validated['test_date'];
            // Optional: if RT date is not provided, use test date
            if (!isset($validated['rt_date'])) {
                $validated['rt_date'] = $validated['test_date'];
            }
        }
        
        // Get company code for report number generation
        $companyId = $validated['company_id'];
        
        // Generate VT report number if result is provided but report number is not
        if (isset($validated['vt_result']) && (!isset($validated['vt_report_no']) || empty($validated['vt_report_no']))) {
            $validated['vt_report_no'] = $this->generateReportNumber('VT', $companyId);
        }
        
        // Generate RT report number if result is provided but report number is not
        if (isset($validated['rt_result']) && (!isset($validated['rt_report_no']) || empty($validated['rt_report_no']))) {
            $validated['rt_report_no'] = $this->generateReportNumber('RT', $companyId);
        }

        // Process manual entry fields
        $manualFields = [
            'welding_process', 'coupon_material', 'qualified_material', 
            'qualified_thickness_range', 'welding_positions', 'qualified_position', 
            'electric_char', 'qualification_code'
        ];
        
        foreach ($manualFields as $field) {
            if ($request->has($field . '_manual') && $request->input($field) === '__manual__' ) {
                // Ensure we have a value for required fields
                if ($field === 'welding_positions' || $field === 'qualified_position' || $field === 'welding_process') {
                    if (empty($request->input($field . '_manual'))) {
                        return back()->withErrors([$field => 'The ' . str_replace('_', ' ', $field) . ' field is required.'])
                                    ->withInput();
                    }
                }
                $validated[$field] = $request->input($field . '_manual');
            }
        }
        
        // Generate certificate number
        $companyCode = $this->getCompanyCode($validated['company_id']);
        $qualificationType = $validated['qualification_type'];
        $prefix = $companyCode . '-' . $qualificationType . '-';
        $settings = \App\Models\AppSetting::getValue('doc_prefix', 'EEA');
        if ($settings) {
            $prefix = $settings . '-' . $companyCode . '-' . $qualificationType . '-';
        }
        
        $lastCert = QualificationTest::where('cert_no', 'like', $prefix . '%')
            ->orderBy('id', 'desc')
            ->first();
        
        if ($lastCert && strpos($lastCert->cert_no, $prefix) === 0) {
            $lastNumber = (int) substr($lastCert->cert_no, strlen($prefix));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        $validated['cert_no'] = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        
        $qualification = QualificationTest::create($validated);
        
        if ($request->has('generate_card') && $request->generate_card && $qualification->test_result) {
            return redirect()->route('qualification-tests.card', $qualification->id);
        }
        
        return redirect()->route('qualification-tests.index')
            ->with('success', 'Qualification test created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $qualification = QualificationTest::with('welder.company', 'company')->findOrFail($id);
        return view('qualification_tests.show', compact('qualification'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $qualification = QualificationTest::findOrFail($id);
        $welders = Welder::orderBy('name')->pluck('name', 'id');
        $companies = Company::orderBy('name')->pluck('name', 'id');
        
        // Get options from QualificationOptions class
        $processes = \App\Enums\QualificationOptions::weldingProcesses();
        $testPositions = \App\Enums\QualificationOptions::testPositions();
        $certificationCodes = \App\Enums\QualificationOptions::certificationCodes();
        
        // Additional options
        $couponMaterials = \App\Enums\QualificationOptions::couponMaterials();
        $qualifiedMaterials = \App\Enums\QualificationOptions::qualifiedMaterials();
        $qualifiedThicknessRanges = \App\Enums\QualificationOptions::qualifiedThicknessRanges();
        $electricCharacteristics = \App\Enums\QualificationOptions::electricCharacteristics();
        $testResults = \App\Enums\QualificationOptions::testResults();
        
        // Remaining dropdown options
        $verticalProgressions = [
            'Uphill' => 'Uphill',
            'Downhill' => 'Downhill',
            'GTAW-Uphill, SMAW-Uphill' => 'GTAW-Uphill, SMAW-Uphill',
            'N/A' => 'N/A',
        ];
        
        $testDias = [
            'PLATE' => 'PLATE',
            '2 Inch' => '2 Inch',
            '4 Inch' => '4 Inch',
            '6 Inch' => '6 Inch',
            '8 Inch' => '8 Inch',
        ];
        
        $testMethods = [
            'Radiography' => 'Radiography',
            'Ultrasonic' => 'Ultrasonic',
            'Bend Test' => 'Bend Test',
            'Visual' => 'Visual',
        ];
        
        return view('qualification_tests.edit', compact(
            'qualification',
            'welders',
            'processes',
            'testPositions',
            'certificationCodes',
            'couponMaterials',
            'qualifiedMaterials',
            'qualifiedThicknessRanges',
            'electricCharacteristics',
            'testResults',
            'verticalProgressions',
            'testDias',
            'testMethods',
            'companies'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $qualification = QualificationTest::findOrFail($id);
        
        $validated = $request->validate([
            'welder_no' => 'required|exists:welders,id',
            'company_id' => 'required|exists:companies,id',
            'qualification_type' => 'required|string|in:WQT,PQR',
            'sr_no' => 'nullable|string',
            'work_order_no' => 'nullable|string',
            'location' => 'nullable|string',
            'passport_id_no' => 'nullable|string',
            'welder_no' => 'nullable|string',
            'wps_no' => 'required|string',
            'welding_process' => 'required|string',
            'test_coupon' => 'required|string',
            'dia_inch' => 'required|string',
            'qualified_dia_inch' => 'required|string',
            'coupon_material' => 'nullable|string',
            'qualified_material' => 'nullable|string',
            'coupon_thickness_mm' => 'nullable|numeric',
            'deposit_thickness' => 'nullable|string',
            'qualified_thickness_range' => 'nullable|string',
            'welding_positions' => 'required|string',
            'qualified_position' => 'required|string',
            'filler_metal_f_no' => 'nullable|string',
            'aws_spec_no' => 'nullable|string',
            'filler_metal_classif' => 'nullable|string',
            'backing' => 'nullable|string',
            'qualified_backing' => 'nullable|string',
            'electric_char' => 'nullable|string',
            'qualified_ec' => 'nullable|string',
            // Joint detail fields
            'joint_diagram' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'joint_type' => 'nullable|string',
            'joint_description' => 'nullable|string',
            'joint_angle' => 'nullable|string',
            'joint_total_angle' => 'nullable|string',
            'root_gap' => 'nullable|string',
            'root_face' => 'nullable|string',
            'pipe_outer_diameter' => 'nullable|string',
            'base_metal_p_no' => 'nullable|string',
            'filler_metal_form' => 'nullable|string',
            'inert_gas_backing' => 'nullable|string',
            'gtaw_thickness' => 'nullable|string',
            'smaw_thickness' => 'nullable|string',
            'vertical_progression' => 'nullable|string',
            // Test dates and results
            'test_date' => 'nullable|date',
            'vt_report_no' => 'nullable|string',
            'vt_result' => 'nullable|string',
            'rt_date' => 'nullable|date',
            'rt_report_no' => 'nullable|string',
            'rt_result' => 'nullable|string',
            'qualification_code' => 'nullable|string',
            'remarks' => 'nullable|string',
            'is_active' => 'boolean',
            'test_result' => 'boolean',
        ]);
        
        // Convert test_result string value to boolean
        $validated['test_result'] = $request->input('test_result') == '1';
        $validated['is_active'] = $request->has('is_active');
        
        // Check if user has permission to set test result to pass
        if ($validated['test_result'] && !in_array(Auth::user()->role, ['qc', 'admin'])) {
            // Only QC personnel and admin can change test result from fail to pass
            $validated['test_result'] = false;
            session()->flash('warning', 'Only QC personnel or admin can change test result to Pass. The test remains Failed.');
        }
        
        // Handle joint diagram upload if provided
        if ($request->hasFile('joint_diagram')) {
            // Delete the old diagram if it exists
            if ($qualification->joint_diagram_path) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($qualification->joint_diagram_path);
            }
            
            $path = $request->file('joint_diagram')->store('qualification_tests/joint_diagrams', 'public');
            $validated['joint_diagram_path'] = $path;
        }
        
        // Use test_date for all date fields if provided
        if (isset($validated['test_date'])) {
            $validated['vt_date'] = $validated['test_date'];
            // Optional: if RT date is not provided, use test date
            if (!isset($validated['rt_date'])) {
                $validated['rt_date'] = $validated['test_date'];
            }
        }
        
        // Get company code for report number generation
        $companyId = $validated['company_id'];

        // Generate VT report number if result is provided but report number is not
        if (isset($validated['vt_result']) && (!isset($validated['vt_report_no']) || empty($validated['vt_report_no']))) {
            $validated['vt_report_no'] = $this->generateReportNumber('VT', $companyId);
        }
        
        // Generate RT report number if result is provided but report number is not
        if (isset($validated['rt_result']) && (!isset($validated['rt_report_no']) || empty($validated['rt_report_no']))) {
            $validated['rt_report_no'] = $this->generateReportNumber('RT', $companyId);
        }

        // Process manual entry fields
        $manualFields = [
            'welding_process', 'coupon_material', 'qualified_material', 
            'qualified_thickness_range', 'welding_positions', 'qualified_position', 
            'electric_char', 'qualification_code'
        ];
        
        foreach ($manualFields as $field) {
            if ($request->has($field . '_manual') && $request->input($field) === '__manual__' ) {
                // Ensure we have a value for required fields
                if ($field === 'welding_positions' || $field === 'qualified_position' || $field === 'welding_process') {
                    if (empty($request->input($field . '_manual'))) {
                        return back()->withErrors([$field => 'The ' . str_replace('_', ' ', $field) . ' field is required.'])
                                    ->withInput();
                    }
                }
                $validated[$field] = $request->input($field . '_manual');
            }
        }
        
        // Update certificate number if company or qualification type has changed
        if ($qualification->company_id != $validated['company_id'] || 
            $qualification->qualification_type != $validated['qualification_type']) {
            
            $companyCode = $this->getCompanyCode($validated['company_id']);
            $qualificationType = $validated['qualification_type'];
            $prefix = $companyCode . '-' . $qualificationType . '-';
            
            $lastCert = QualificationTest::where('cert_no', 'like', $prefix . '%')
                ->where('id', '!=', $qualification->id)  // Exclude current qualification
                ->orderBy('id', 'desc')
                ->first();
            
            if ($lastCert && strpos($lastCert->cert_no, $prefix) === 0) {
                $lastNumber = (int) substr($lastCert->cert_no, strlen($prefix));
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }
            
            $validated['cert_no'] = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        }
        
        $qualification->update($validated);
        
        if ($request->has('generate_card') && $request->generate_card && $qualification->test_result) {
            return redirect()->route('qualification-tests.card', $qualification->id);
        }
        
        return redirect()->route('qualification-tests.index')
            ->with('success', 'Qualification test updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $qualification = QualificationTest::findOrFail($id);
        $qualification->delete();
        
        return redirect()->route('qualification-tests.index')
            ->with('success', 'Qualification test deleted successfully.');
    }
    
    /**
     * Generate a PDF qualification card
     */
    public function generateCard(string $id)
    {
        $qualification = QualificationTest::with('welder.company', 'company')->findOrFail($id);
        
        // Check if test result is pass and if user has permission to generate card
        if (!$qualification->test_result) {
            return redirect()->route('qualification-tests.show', $id)
                ->with('error', 'Cannot generate qualification card for failed test.');
        }
        
        // Only QC and admin users can generate cards
        if (!$this->canGenerateCardOrCertificate()) {
            return redirect()->route('qualification-tests.show', $id)
                ->with('error', 'You do not have permission to generate qualification cards. Contact QC personnel.');
        }
        
        $welder = $qualification->welder;
        
        // Generate QR code for certificate number
        $barcodeData = null;
        $qrcodeData = null;
        
        if ($qualification->cert_no) {
            try {
                // Create a QR code using a data URL
                $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($qualification->cert_no);
                
                // We'll keep barcode data for backwards compatibility with existing templates
                $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
                $barcodeData = base64_encode($generator->getBarcode(
                    $qualification->cert_no, 
                    $generator::TYPE_CODE_128, 
                    2, 
                    30
                ));
            } catch (\Exception $e) {
                // If error in barcode generation, continue without barcode
            }
        }
        
        // Determine which view to render based on the request parameter
        $view = request()->has('side') && request()->side === 'back' 
            ? 'qualification_tests.back_card' 
            : 'qualification_tests.card';
        
        // Set QR Code URL with verification data
        $verifyData = [
            'cert_no' => $qualification->cert_no,
            'welder' => $welder->name,
            'test_date' => $qualification->test_date ? date('Y-m-d', strtotime($qualification->test_date)) : 'N/A',
            'company' => $qualification->company->name ?? 'N/A'
        ];
        
        $qrData = json_encode($verifyData);
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($qrData);
        
        return view($view, compact('qualification', 'welder', 'barcodeData', 'qrCodeUrl'));
    }

    /**
     * Generate a PDF qualification certificate
     */
    public function generateCertificate(string $id)
    {
        $qualification = QualificationTest::with('welder.company', 'company')->findOrFail($id);
        
        // Check if test result is pass and if user has permission to generate certificate
        if (!$qualification->test_result) {
            return redirect()->route('qualification-tests.show', $id)
                ->with('error', 'Cannot generate certificate for failed test.');
        }
        
        // Only QC and admin users can generate certificates
        if (!$this->canGenerateCardOrCertificate()) {
            return redirect()->route('qualification-tests.show', $id)
                ->with('error', 'You do not have permission to generate certificates. Contact QC personnel.');
        }
        
        $welder = $qualification->welder;

        // Generate barcode for qualification code
        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
        $barcodeData = null;
        
        if ($qualification->cert_no) {
            $barcodeData = base64_encode($generator->getBarcode($qualification->cert_no, $generator::TYPE_CODE_128));
        }
        
        // Create QR code URL with verification data
        $verifyData = [
            'cert_no' => $qualification->cert_no,
            'welder' => $welder->name,
            'test_date' => $qualification->test_date ? date('Y-m-d', strtotime($qualification->test_date)) : 'N/A',
            'company' => $qualification->company->name ?? 'N/A',
            'process' => $qualification->welding_process ?? 'N/A',
            'qualification_code' => $qualification->qualification_code ?? 'N/A'
        ];
        
        $qrData = json_encode($verifyData);
        $qrCodeUrl = 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($qrData);

        return view('qualification_tests.certificate_details', compact('qualification', 'welder', 'barcodeData', 'qrCodeUrl'));
    }
    
    /**
     * Generate a unique report number for VT or RT tests
     *
     * @param string $testType VT or RT
     * @param string $companyCode Company code
     * @return string Generated report number
     */
    private function generateReportNumber($testType, $companyCode = null)
    {
        // If no company code provided, use the default from app settings
        if (!$companyCode) {
            $companyCode = $this->getCompanyCode(Auth::user()->company_id);
        }
        $settings = \App\Models\AppSetting::getValue('doc_prefix', 'EEA');
        $companyCode = $settings . '-' . $companyCode;

        // Determine the prefix based on test type
        $prefix = $companyCode . '-' . $testType . '-';

        // Find the last report number with this prefix
        $lastReport = null;
        if ($testType == 'VT') {
            $lastReport = QualificationTest::where('vt_report_no', 'like', $prefix . '%')
                ->orderBy('id', 'desc')
                ->first();
        } else {
            $lastReport = QualificationTest::where('rt_report_no', 'like', $prefix . '%')
                ->orderBy('id', 'desc')
                ->first();
        }
        
        // Determine the next sequential number
        $nextNumber = 1;
        if ($lastReport) {
            $lastReportNumber = ($testType == 'VT') ? $lastReport->vt_report_no : $lastReport->rt_report_no;
            $lastNumber = (int) substr($lastReportNumber, strlen($prefix));
            $nextNumber = $lastNumber + 1;
        }
        
        // Format the number with leading zeros
        return $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get company code for the given company ID
     *
     * @param int $companyId
     * @return string Company code
     */
    private function getCompanyCode($companyId)
    {
        $company = Company::find($companyId);
        $companyCode = $company && $company->code ? $company->code : 'AIC';
        return $companyCode;

    }

    /**
     * Generate a certificate number based on company ID and qualification type (API endpoint)
     */
    public function generateCertNumber(Request $request)
    {
        $companyId = $request->input('company_id');
        $qualificationType = $request->input('qualification_type', 'WQT');
        $company = Company::find($companyId);
        $certNo = '';
        
        if ($company && $company->code) {
            $companyCode = $company->code;
            $settings = \App\Models\AppSetting::getValue('doc_prefix', 'EEA');
            $companyCode = $settings . '-' . $companyCode;
            $prefix = $companyCode . '-' . $qualificationType . '-';
            
            $lastCert = QualificationTest::where('cert_no', 'like', $prefix . '%')
                ->orderBy('id', 'desc')
                ->first();
            
            if ($lastCert && strpos($lastCert->cert_no, $prefix) === 0) {
                $lastNumber = (int) substr($lastCert->cert_no, strlen($prefix));
                $newNumber = $lastNumber + 1;
            } else {
                $newNumber = 1;
            }
            
            $certNo = $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
        } else {
            // Fallback to default company code
            $defaultCompanyCode = \App\Models\AppSetting::getValue('company_code', 'EEA-AIC');
            $certNo = $defaultCompanyCode . '-' . $qualificationType . '-0001';
        }
        
        return response()->json(['cert_no' => $certNo]);
    }
}
