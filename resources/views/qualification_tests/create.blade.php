@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create New Welder Performance Qualification</h1>
        <a href="{{ route('qualification-tests.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Qualifications
        </a>
    </div>

    <!-- Progress Bar -->
    <div class="progress mb-4" style="height: 30px;">
        <div class="progress-bar bg-primary" role="progressbar" style="width: 12.5%;" id="step-progress-bar" 
             aria-valuenow="12.5" aria-valuemin="0" aria-valuemax="100">
            <span class="fw-bold">Step 1 of 8</span>
        </div>
    </div>

    <!-- Main Form Container -->
    <div class="card shadow-lg">
        <div class="card-body p-0">
            <form action="{{ route('qualification-tests.store') }}" method="POST" enctype="multipart/form-data" id="qualification-form">
                @csrf
                
                <!-- Step 1: Welder Selection & Certificate Type -->
                <div class="step-section" id="step-1">
                    <div class="card border-0">
                        <div class="card-header bg-primary text-white py-3">
                            <div class="d-flex align-items-center">
                                <div class="step-icon me-3">
                                    <i class="fas fa-user-hard-hat fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">Step 1: Welder Information & Certificate Type</h5>
                                    <small>Select the welder and type of welding process qualification</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <!-- Welder Selection -->
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label for="welder_id" class="form-label fw-bold">
                                            <i class="fas fa-user text-primary me-2"></i>Select Welder 
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select form-select-lg @error('welder_id') is-invalid @enderror" 
                                                id="welder_id" name="welder_id" required>
                                            <option value="">-- Select a welder --</option>
                                            @foreach($welders as $id => $welder)
                                                <option value="{{ $id }}" 
                                                        {{ old('welder_id') == $id ? 'selected' : '' }}
                                                        data-welder-no="{{ $welder->welder_no ?? '' }}"
                                                        data-iqama-no="{{ $welder->iqama_no ?? '' }}"
                                                        data-passport-no="{{ $welder->passport_no ?? '' }}"
                                                        data-company-id="{{ $welder->company_id ?? '' }}"
                                                        data-company-name="{{ $welder->company->name ?? '' }}">
                                                    {{ $welder->name }} (ID: {{ $welder->welder_no ?? 'N/A' }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('welder_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            <i class="fas fa-info-circle text-info me-1"></i>
                                            Can't find the welder? <a href="{{ route('welders.create') }}" target="_blank" class="text-decoration-none">Add a new welder</a>
                                        </div>
                                    </div>
                                </div>

                                <!-- Certificate Type Selection -->
                                <div class="col-md-6 mb-4">
                                    <div class="form-group">
                                        <label for="welding_process_type" class="form-label fw-bold">
                                            <i class="fas fa-fire text-warning me-2"></i>Welding Process Type 
                                            <span class="text-danger">*</span>
                                        </label>
                                        <select class="form-select form-select-lg @error('welding_process_type') is-invalid @enderror" 
                                                id="welding_process_type" name="welding_process_type" required>
                                            <option value="">-- Select Process Type --</option>
                                            <option value="SMAW" {{ old('welding_process_type') == 'SMAW' ? 'selected' : '' }}>
                                                SMAW (Shielded Metal Arc Welding)
                                            </option>
                                            <option value="GTAW" {{ old('welding_process_type') == 'GTAW' ? 'selected' : '' }}>
                                                GTAW (Gas Tungsten Arc Welding)
                                            </option>
                                            <option value="SMAW-GTAW" {{ old('welding_process_type') == 'SMAW-GTAW' ? 'selected' : '' }}>
                                                SMAW-GTAW (Combination)
                                            </option>
                                            <option value="FCAW" {{ old('welding_process_type') == 'FCAW' ? 'selected' : '' }}>
                                                FCAW (Flux Cored Arc Welding)
                                            </option>
                                        </select>
                                        @error('welding_process_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <div class="form-text">
                                            <i class="fas fa-info-circle text-info me-1"></i>
                                            This determines the qualification variables and testing requirements
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Auto-populated Welder Information Display -->
                            <div id="welder-info-display" class="row" style="display: none;">
                                <div class="col-12">
                                    <div class="alert alert-info border-0 shadow-sm">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-info-circle text-info me-2"></i>
                                            <h6 class="mb-0">Selected Welder Information</h6>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-3">
                                                <strong>Welder No:</strong>
                                                <span id="display-welder-no" class="text-primary">-</span>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Iqama No:</strong>
                                                <span id="display-iqama-no" class="text-primary">-</span>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Passport No:</strong>
                                                <span id="display-passport-no" class="text-primary">-</span>
                                            </div>
                                            <div class="col-md-3">
                                                <strong>Company:</strong>
                                                <span id="display-company-name" class="text-primary">-</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Company Selection -->
                            <div class="row">
                                <div class="col-md-12 mb-4">
                                    <label for="company_id" class="form-label fw-bold">
                                        <i class="fas fa-building text-info me-2"></i>Company 
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select class="form-select @error('company_id') is-invalid @enderror" 
                                            id="company_id" name="company_id" required>
                                        <option value="">-- Select a company --</option>
                                        @foreach($companies as $id => $name)
                                            <option value="{{ $id }}" {{ old('company_id') == $id ? 'selected' : '' }}>
                                                {{ $name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('company_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Navigation -->
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <div class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Complete this step to proceed with qualification details
                                </div>
                                <button type="button" class="btn btn-primary btn-lg px-4 next-step">
                                    Next Step <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 2: WPS and Base Material Information -->
                <div class="step-section" id="step-2" style="display: none;">
                    <div class="card border-0">
                        <div class="card-header bg-success text-white py-3">
                            <div class="d-flex align-items-center">
                                <div class="step-icon me-3">
                                    <i class="fas fa-file-alt fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">Step 2: WPS and Base Material Specification</h5>
                                    <small>Define the welding procedure specification and base material details</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="wps_no" class="form-label fw-bold">
                                        <i class="fas fa-file-contract text-primary me-2"></i>WPS Number 
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('wps_no') is-invalid @enderror" 
                                           id="wps_no" name="wps_no" value="{{ old('wps_no', 'AIC-WPS-SCM-041 Rev.01') }}" 
                                           placeholder="e.g., AIC-WPS-SCM-041 Rev.01" required>
                                    @error('wps_no')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="base_metal_spec" class="form-label fw-bold">
                                        <i class="fas fa-cube text-secondary me-2"></i>Base Metal Specification 
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" class="form-control @error('base_metal_spec') is-invalid @enderror" 
                                           id="base_metal_spec" name="base_metal_spec" value="{{ old('base_metal_spec', 'ASTM A106 Gr B') }}" 
                                           placeholder="e.g., ASTM A106 Gr B" required>
                                    @error('base_metal_spec')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="dia_thickness" class="form-label fw-bold">
                                        <i class="fas fa-ruler text-info me-2"></i>Diameter/Thickness
                                    </label>
                                    <input type="text" class="form-control @error('dia_thickness') is-invalid @enderror" 
                                           id="dia_thickness" name="dia_thickness" value="{{ old('dia_thickness', '8 inch/18.26 mm') }}" 
                                           placeholder="e.g., 8 inch/18.26 mm">
                                    @error('dia_thickness')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="test_date" class="form-label fw-bold">
                                        <i class="fas fa-calendar text-warning me-2"></i>Test Date
                                    </label>
                                    <input type="date" class="form-control @error('test_date') is-invalid @enderror" 
                                           id="test_date" name="test_date" value="{{ old('test_date', date('Y-m-d')) }}">
                                    @error('test_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="test_coupon_type" class="form-label fw-bold">
                                        <i class="fas fa-vial text-success me-2"></i>Test Coupon Type
                                    </label>
                                    <select class="form-select @error('test_coupon_type') is-invalid @enderror" 
                                            id="test_coupon_type" name="test_coupon_type">
                                        <option value="production_weld" {{ old('test_coupon_type') == 'production_weld' ? 'selected' : '' }}>
                                            Production Weld
                                        </option>
                                        <option value="test_coupon" {{ old('test_coupon_type', 'test_coupon') == 'test_coupon' ? 'selected' : '' }}>
                                            Test Coupon
                                        </option>
                                    </select>
                                    @error('test_coupon_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Navigation -->
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <button type="button" class="btn btn-outline-secondary px-4 prev-step">
                                    <i class="fas fa-arrow-left me-2"></i> Previous Step
                                </button>
                                <button type="button" class="btn btn-success btn-lg px-4 next-step">
                                    Next Step <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 3: Welding Variables -->
                <div class="step-section" id="step-3" style="display: none;">
                    <div class="card border-0">
                        <div class="card-header bg-warning text-dark py-3">
                            <div class="d-flex align-items-center">
                                <div class="step-icon me-3">
                                    <i class="fas fa-cogs fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">Step 3: Welding Variables</h5>
                                    <small>Configure welding process parameters and qualifications</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <!-- Process-specific fields will be dynamically shown based on selection -->
                            <div id="welding-variables-container">
                                <!-- SMAW Variables -->
                                <div class="process-variables" id="smaw-variables" style="display: none;">
                                    <h6 class="text-primary mb-3">
                                        <i class="fas fa-bolt me-2"></i>SMAW Process Variables
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="smaw_backing" class="form-label">Backing (with/without)</label>
                                            <select class="form-select" name="smaw_backing">
                                                <option value="With Backing" {{ old('smaw_backing') == 'With Backing' ? 'selected' : '' }}>With Backing</option>
                                                <option value="Without Backing" {{ old('smaw_backing') == 'Without Backing' ? 'selected' : '' }}>Without Backing</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="smaw_pipe_diameter" class="form-label">Pipe Diameter (NPS)</label>
                                            <input type="text" class="form-control" name="smaw_pipe_diameter" 
                                                   value="{{ old('smaw_pipe_diameter', 'NPS 8') }}" placeholder="e.g., NPS 8">
                                        </div>
                                    </div>
                                </div>

                                <!-- GTAW Variables -->
                                <div class="process-variables" id="gtaw-variables" style="display: none;">
                                    <h6 class="text-info mb-3">
                                        <i class="fas fa-fire me-2"></i>GTAW Process Variables
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="gtaw_backing" class="form-label">Backing Type</label>
                                            <select class="form-select" name="gtaw_backing">
                                                <option value="Without" {{ old('gtaw_backing') == 'Without' ? 'selected' : '' }}>Without Backing</option>
                                                <option value="With" {{ old('gtaw_backing') == 'With' ? 'selected' : '' }}>With Backing</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="gtaw_thickness" class="form-label">GTAW Layer Thickness</label>
                                            <input type="text" class="form-control" name="gtaw_thickness" 
                                                   value="{{ old('gtaw_thickness', 'Approx. 4 mm') }}" placeholder="e.g., Approx. 4 mm">
                                        </div>
                                    </div>
                                </div>

                                <!-- FCAW Variables -->
                                <div class="process-variables" id="fcaw-variables" style="display: none;">
                                    <h6 class="text-success mb-3">
                                        <i class="fas fa-layer-group me-2"></i>FCAW Process Variables
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="fcaw_shielding" class="form-label">Shielding Gas</label>
                                            <input type="text" class="form-control" name="fcaw_shielding" 
                                                   value="{{ old('fcaw_shielding', 'CO2') }}" placeholder="e.g., CO2, Ar+CO2">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="fcaw_wire_type" class="form-label">Wire Type</label>
                                            <input type="text" class="form-control" name="fcaw_wire_type" 
                                                   value="{{ old('fcaw_wire_type', 'E71T-1') }}" placeholder="e.g., E71T-1">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Common Variables -->
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="base_metal_p_no" class="form-label fw-bold">Base Metal P-Number</label>
                                    <input type="text" class="form-control" name="base_metal_p_no" 
                                           value="{{ old('base_metal_p_no', 'P NO.1 TO P NO.1') }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="filler_metal_f_no" class="form-label fw-bold">Filler Metal F-Number</label>
                                    <input type="text" class="form-control" name="filler_metal_f_no" 
                                           value="{{ old('filler_metal_f_no', 'F4') }}">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="vertical_progression" class="form-label fw-bold">Vertical Progression</label>
                                    <select class="form-select" name="vertical_progression">
                                        <option value="Uphill" {{ old('vertical_progression', 'Uphill') == 'Uphill' ? 'selected' : '' }}>Uphill</option>
                                        <option value="Downhill" {{ old('vertical_progression') == 'Downhill' ? 'selected' : '' }}>Downhill</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Navigation -->
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <button type="button" class="btn btn-outline-secondary px-4 prev-step">
                                    <i class="fas fa-arrow-left me-2"></i> Previous Step
                                </button>
                                <button type="button" class="btn btn-warning btn-lg px-4 next-step">
                                    Next Step <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 4: Position Qualification -->
                <div class="step-section" id="step-4" style="display: none;">
                    <div class="card border-0">
                        <div class="card-header bg-info text-white py-3">
                            <div class="d-flex align-items-center">
                                <div class="step-icon me-3">
                                    <i class="fas fa-compass fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">Step 4: Position Qualification</h5>
                                    <small>Define welding positions and qualified ranges</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="card bg-light">
                                        <div class="card-header bg-primary text-white">
                                            <h6 class="mb-0">
                                                <i class="fas fa-pipe me-2"></i>Groove Plate and Pipe Over 24 in. (610 mm) O.D.
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <label for="test_position" class="form-label fw-bold">Test Position</label>
                                            <select class="form-select @error('test_position') is-invalid @enderror" 
                                                    id="test_position" name="test_position" required>
                                                <option value="">-- Select Position --</option>
                                                <option value="1G" {{ old('test_position') == '1G' ? 'selected' : '' }}>1G (Flat)</option>
                                                <option value="2G" {{ old('test_position') == '2G' ? 'selected' : '' }}>2G (Horizontal)</option>
                                                <option value="3G" {{ old('test_position') == '3G' ? 'selected' : '' }}>3G (Vertical)</option>
                                                <option value="4G" {{ old('test_position') == '4G' ? 'selected' : '' }}>4G (Overhead)</option>
                                                <option value="5G" {{ old('test_position') == '5G' ? 'selected' : '' }}>5G (Horizontal Fixed)</option>
                                                <option value="6G" {{ old('test_position', '6G') == '6G' ? 'selected' : '' }}>6G (Inclined Fixed)</option>
                                            </select>
                                            @error('test_position')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="card bg-light">
                                        <div class="card-header bg-success text-white">
                                            <h6 class="mb-0">
                                                <i class="fas fa-layer-group me-2"></i>Groove Pipe ≤24 in. (610 mm) O.D.
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="fillet_qualification" 
                                                       id="fillet_qualification" value="1" {{ old('fillet_qualification') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="fillet_qualification">
                                                    Fillet or Tack Plate and Pipe in all Position
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Position Variables -->
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="qualified_positions" class="form-label fw-bold">Qualified Positions</label>
                                    <textarea class="form-control" name="qualified_positions" rows="2" 
                                              placeholder="e.g., All Positions (F, H, V, O) for Pipe & Plate">{{ old('qualified_positions', 'All Positions (F, H, V, O) for Pipe & Plate') }}</textarea>
                                </div>
                            </div>

                            <!-- Navigation -->
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <button type="button" class="btn btn-outline-secondary px-4 prev-step">
                                    <i class="fas fa-arrow-left me-2"></i> Previous Step
                                </button>
                                <button type="button" class="btn btn-info btn-lg px-4 next-step">
                                    Next Step <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 5: Test Results -->
                <div class="step-section" id="step-5" style="display: none;">
                    <div class="card border-0">
                        <div class="card-header bg-danger text-white py-3">
                            <div class="d-flex align-items-center">
                                <div class="step-icon me-3">
                                    <i class="fas fa-clipboard-check fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">Step 5: Test Results</h5>
                                    <small>Record visual examination and radiographic test results</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="card border-primary">
                                        <div class="card-header bg-primary text-white">
                                            <h6 class="mb-0">
                                                <i class="fas fa-eye me-2"></i>Visual Examination (QW-302.4)
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="vt_result" class="form-label fw-bold">Result</label>
                                                <select class="form-select @error('vt_result') is-invalid @enderror" 
                                                        id="vt_result" name="vt_result" required>
                                                    <option value="">-- Select Result --</option>
                                                    <option value="ACC" {{ old('vt_result', 'ACC') == 'ACC' ? 'selected' : '' }}>
                                                        ACC (Acceptable)
                                                    </option>
                                                    <option value="REJ" {{ old('vt_result') == 'REJ' ? 'selected' : '' }}>
                                                        REJ (Rejected)
                                                    </option>
                                                </select>
                                                @error('vt_result')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="vt_report_no" class="form-label">Report Number</label>
                                                <input type="text" class="form-control" id="vt_report_no" name="vt_report_no" 
                                                       value="{{ old('vt_report_no') }}" placeholder="Auto-generated" readonly>
                                                <small class="text-muted">Will be auto-generated (e.g., EEA-AIC-VT-0566)</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="card border-warning">
                                        <div class="card-header bg-warning text-dark">
                                            <h6 class="mb-0">
                                                <i class="fas fa-radiation me-2"></i>Radiographic Test (QW-302.2)
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="rt_result" class="form-label fw-bold">Result</label>
                                                <select class="form-select @error('rt_result') is-invalid @enderror" 
                                                        id="rt_result" name="rt_result" required>
                                                    <option value="">-- Select Result --</option>
                                                    <option value="ACC" {{ old('rt_result', 'ACC') == 'ACC' ? 'selected' : '' }}>
                                                        ACC (Acceptable)
                                                    </option>
                                                    <option value="REJ" {{ old('rt_result') == 'REJ' ? 'selected' : '' }}>
                                                        REJ (Rejected)
                                                    </option>
                                                </select>
                                                @error('rt_result')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <div class="mb-3">
                                                <label for="rt_report_no" class="form-label">Report Number</label>
                                                <input type="text" class="form-control" id="rt_report_no" name="rt_report_no" 
                                                       value="{{ old('rt_report_no') }}" placeholder="Auto-generated" readonly>
                                                <small class="text-muted">Will be auto-generated (e.g., EEA-AIC-RT-0566)</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Overall Test Result -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card bg-light">
                                        <div class="card-body">
                                            <label for="overall_result" class="form-label fw-bold text-center d-block">
                                                <i class="fas fa-trophy text-warning me-2"></i>Overall Test Result
                                            </label>
                                            @if(Auth::user()->role === 'qc' || Auth::user()->role === 'admin')
                                                <div class="d-flex justify-content-center">
                                                    <div class="btn-group" role="group">
                                                        <input type="radio" class="btn-check" name="overall_result" id="result_pass" value="1" 
                                                               {{ old('overall_result', '1') == '1' ? 'checked' : '' }}>
                                                        <label class="btn btn-outline-success btn-lg" for="result_pass">
                                                            <i class="fas fa-check-circle me-2"></i>PASS
                                                        </label>

                                                        <input type="radio" class="btn-check" name="overall_result" id="result_fail" value="0" 
                                                               {{ old('overall_result') == '0' ? 'checked' : '' }}>
                                                        <label class="btn btn-outline-danger btn-lg" for="result_fail">
                                                            <i class="fas fa-times-circle me-2"></i>FAIL
                                                        </label>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="text-center">
                                                    <div class="alert alert-warning">
                                                        <i class="fas fa-lock me-2"></i>
                                                        Only QC personnel can approve test results
                                                    </div>
                                                    <input type="hidden" name="overall_result" value="0">
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Navigation -->
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <button type="button" class="btn btn-outline-secondary px-4 prev-step">
                                    <i class="fas fa-arrow-left me-2"></i> Previous Step
                                </button>
                                <button type="button" class="btn btn-danger btn-lg px-4 next-step">
                                    Next Step <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 6: Inspector Information -->
                <div class="step-section" id="step-6" style="display: none;">
                    <div class="card border-0">
                        <div class="card-header bg-secondary text-white py-3">
                            <div class="d-flex align-items-center">
                                <div class="step-icon me-3">
                                    <i class="fas fa-user-tie fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">Step 6: Inspector & Supervisor Information</h5>
                                    <small>Record inspector and welding supervisor details</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <div class="card border-primary">
                                        <div class="card-header bg-primary text-white">
                                            <h6 class="mb-0">
                                                <i class="fas fa-user-shield me-2"></i>Inspector Information
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="inspector_name" class="form-label fw-bold">Inspector Name</label>
                                                <input type="text" class="form-control" id="inspector_name" name="inspector_name" 
                                                       value="{{ old('inspector_name', Auth::user()->name) }}" readonly>
                                            </div>
                                            <div class="mb-3">
                                                <label for="inspector_cert_no" class="form-label fw-bold">Certificate Number</label>
                                                <input type="text" class="form-control" id="inspector_cert_no" name="inspector_cert_no" 
                                                       value="{{ old('inspector_cert_no', Auth::user()->cert_no ?? 'CSWIP/CWI XXXXX') }}">
                                            </div>
                                            <div class="mb-3">
                                                <label for="inspector_designation" class="form-label fw-bold">Designation</label>
                                                <input type="text" class="form-control" id="inspector_designation" name="inspector_designation" 
                                                       value="{{ old('inspector_designation', Auth::user()->role == 'qc' ? 'Welding Inspector' : 'Administrator') }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-4">
                                    <div class="card border-success">
                                        <div class="card-header bg-success text-white">
                                            <h6 class="mb-0">
                                                <i class="fas fa-hard-hat me-2"></i>Welding Supervisor Information
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="supervisor_name" class="form-label fw-bold">Supervisor Name</label>
                                                <input type="text" class="form-control" id="supervisor_name" name="supervisor_name" 
                                                       value="{{ old('supervisor_name') }}" placeholder="Enter supervisor name">
                                            </div>
                                            <div class="mb-3">
                                                <label for="supervisor_cert_no" class="form-label fw-bold">Certificate Number</label>
                                                <input type="text" class="form-control" id="supervisor_cert_no" name="supervisor_cert_no" 
                                                       value="{{ old('supervisor_cert_no') }}" placeholder="Enter certificate number">
                                            </div>
                                            <div class="mb-3">
                                                <label for="supervisor_designation" class="form-label fw-bold">Designation</label>
                                                <input type="text" class="form-control" id="supervisor_designation" name="supervisor_designation" 
                                                       value="{{ old('supervisor_designation', 'Welding Supervisor') }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Personnel -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="evaluated_by" class="form-label fw-bold">Film/Specimens Evaluated By</label>
                                    <input type="text" class="form-control" id="evaluated_by" name="evaluated_by" 
                                           value="{{ old('evaluated_by', 'Kalith Majeedh') }}" placeholder="Enter evaluator name">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="supervised_by" class="form-label fw-bold">Welding Supervised By</label>
                                    <input type="text" class="form-control" id="supervised_by" name="supervised_by" 
                                           value="{{ old('supervised_by', 'Ahmed Yousry') }}" placeholder="Enter supervisor name">
                                </div>
                            </div>

                            <!-- Navigation -->
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <button type="button" class="btn btn-outline-secondary px-4 prev-step">
                                    <i class="fas fa-arrow-left me-2"></i> Previous Step
                                </button>
                                <button type="button" class="btn btn-secondary btn-lg px-4 next-step">
                                    Next Step <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 7: Additional Variables -->
                <div class="step-section" id="step-7" style="display: none;">
                    <div class="card border-0">
                        <div class="card-header bg-dark text-white py-3">
                            <div class="d-flex align-items-center">
                                <div class="step-icon me-3">
                                    <i class="fas fa-sliders-h fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">Step 7: Additional Welding Variables</h5>
                                    <small>Configure remaining process parameters and specifications</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="filler_metal_spec" class="form-label fw-bold">Filler Metal AWS Specification</label>
                                    <input type="text" class="form-control" id="filler_metal_spec" name="filler_metal_spec" 
                                           value="{{ old('filler_metal_spec', 'A5.18 / A5.1') }}" placeholder="e.g., A5.18 / A5.1">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="filler_metal_classification" class="form-label fw-bold">Filler Metal Classification</label>
                                    <input type="text" class="form-control" id="filler_metal_classification" name="filler_metal_classification" 
                                           value="{{ old('filler_metal_classification', 'ER70S-2 (GTAW) / E7018-1 (SMAW)') }}" 
                                           placeholder="e.g., ER70S-2 / E7018-1">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="current_type_polarity" class="form-label fw-bold">Current Type/Polarity</label>
                                    <input type="text" class="form-control" id="current_type_polarity" name="current_type_polarity" 
                                           value="{{ old('current_type_polarity', 'GTAW: DCEN, SMAW: DCEP') }}" 
                                           placeholder="e.g., DCEN, DCEP">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="pipe_outer_diameter" class="form-label fw-bold">Pipe Outer Diameter</label>
                                    <input type="text" class="form-control" id="pipe_outer_diameter" name="pipe_outer_diameter" 
                                           value="{{ old('pipe_outer_diameter', '168.28 mm (6 Inch Sch.80)') }}" 
                                           placeholder="e.g., 168.28 mm">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="coupon_thickness" class="form-label fw-bold">Coupon Thickness (mm)</label>
                                    <input type="number" step="0.01" class="form-control" id="coupon_thickness" name="coupon_thickness" 
                                           value="{{ old('coupon_thickness', '14.27') }}" placeholder="e.g., 14.27">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="deposit_thickness" class="form-label fw-bold">Total Deposit Thickness</label>
                                    <input type="text" class="form-control" id="deposit_thickness" name="deposit_thickness" 
                                           value="{{ old('deposit_thickness', '14.27 mm') }}" placeholder="e.g., 14.27 mm">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="inert_gas_backing" class="form-label fw-bold">Inert Gas Backing</label>
                                    <input type="text" class="form-control" id="inert_gas_backing" name="inert_gas_backing" 
                                           value="{{ old('inert_gas_backing', 'Not Used (GTAW root without internal purge)') }}" 
                                           placeholder="Used/Not Used">
                                </div>
                            </div>

                            <!-- Navigation -->
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <button type="button" class="btn btn-outline-secondary px-4 prev-step">
                                    <i class="fas fa-arrow-left me-2"></i> Previous Step
                                </button>
                                <button type="button" class="btn btn-dark btn-lg px-4 next-step">
                                    Next Step <i class="fas fa-arrow-right ms-2"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Step 8: Final Review & Submit -->
                <div class="step-section" id="step-8" style="display: none;">
                    <div class="card border-0">
                        <div class="card-header bg-success text-white py-3">
                            <div class="d-flex align-items-center">
                                <div class="step-icon me-3">
                                    <i class="fas fa-check-circle fa-lg"></i>
                                </div>
                                <div>
                                    <h5 class="mb-0">Step 8: Final Review & Submit</h5>
                                    <small>Review your qualification details and submit for approval</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-4">
                            <!-- Certificate Number Display -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="alert alert-info border-0 shadow-sm">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-certificate text-primary fa-2x me-3"></i>
                                            <div>
                                                <h6 class="mb-1">Generated Certificate Number</h6>
                                                <div class="h4 text-primary mb-0" id="final-cert-number">
                                                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                                                    Generating...
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Qualification Code -->
                            <div class="row mb-4">
                                <div class="col-md-6 mb-3">
                                    <label for="qualification_code" class="form-label fw-bold">
                                        <i class="fas fa-code text-info me-2"></i>Qualification Code
                                    </label>
                                    <select class="form-select @error('qualification_code') is-invalid @enderror" 
                                            id="qualification_code" name="qualification_code">
                                        <option value="">-- Select Qualification Code --</option>
                                        <option value="ASME IX" {{ old('qualification_code', 'ASME IX') == 'ASME IX' ? 'selected' : '' }}>
                                            ASME Boiler and Pressure Vessel Code Section IX
                                        </option>
                                        <option value="AWS D1.1" {{ old('qualification_code') == 'AWS D1.1' ? 'selected' : '' }}>
                                            AWS D1.1 Structural Welding Code
                                        </option>
                                        <option value="API 1104" {{ old('qualification_code') == 'API 1104' ? 'selected' : '' }}>
                                            API 1104 Pipeline Welding
                                        </option>
                                    </select>
                                    @error('qualification_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="is_active" class="form-label fw-bold">
                                        <i class="fas fa-toggle-on text-success me-2"></i>Status
                                    </label>
                                    <div class="form-check form-switch form-control d-flex align-items-center">
                                        <input class="form-check-input me-2" type="checkbox" id="is_active" name="is_active" 
                                               value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="is_active">
                                            <span class="text-success">Active Qualification</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Remarks -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <label for="remarks" class="form-label fw-bold">
                                        <i class="fas fa-comment-alt text-secondary me-2"></i>Remarks (Optional)
                                    </label>
                                    <textarea class="form-control @error('remarks') is-invalid @enderror" 
                                              id="remarks" name="remarks" rows="3" 
                                              placeholder="Enter any additional remarks or notes...">{{ old('remarks') }}</textarea>
                                    @error('remarks')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Review Summary -->
                            <div class="row mb-4">
                                <div class="col-12">
                                    <div class="card bg-light">
                                        <div class="card-header">
                                            <h6 class="mb-0">
                                                <i class="fas fa-clipboard-list me-2"></i>Qualification Summary
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="row" id="review-summary">
                                                <div class="col-md-3 mb-2">
                                                    <strong>Welder:</strong> <span id="summary-welder">-</span>
                                                </div>
                                                <div class="col-md-3 mb-2">
                                                    <strong>Process:</strong> <span id="summary-process">-</span>
                                                </div>
                                                <div class="col-md-3 mb-2">
                                                    <strong>Position:</strong> <span id="summary-position">-</span>
                                                </div>
                                                <div class="col-md-3 mb-2">
                                                    <strong>Result:</strong> <span id="summary-result">-</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Final Actions -->
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <button type="button" class="btn btn-outline-secondary px-4 prev-step">
                                    <i class="fas fa-arrow-left me-2"></i> Previous Step
                                </button>
                                <div class="btn-group">
                                    <button type="submit" class="btn btn-primary btn-lg px-4">
                                        <i class="fas fa-save me-2"></i>Save Qualification
                                    </button>
                                    @if(Auth::user()->role === 'qc' || Auth::user()->role === 'admin')
                                        <button type="submit" name="generate_certificate" value="1" class="btn btn-success btn-lg px-4">
                                            <i class="fas fa-certificate me-2"></i>Save & Generate Certificate
                                        </button>
                                    @else
                                        <button type="button" class="btn btn-outline-secondary btn-lg px-4" disabled 
                                                title="Only QC personnel can generate certificates">
                                            <i class="fas fa-lock me-2"></i>Generate Certificate (Requires QC)
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .step-icon {
        width: 50px;
        height: 50px;
        background: rgba(255,255,255,0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .process-variables {
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 1rem;
        margin-bottom: 1rem;
        background: #f8f9fa;
    }
    
    .card {
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        border: 1px solid rgba(0, 0, 0, 0.125);
    }
    
    .progress-bar {
        transition: width 0.6s ease;
    }
    
    .btn-group .btn {
        position: relative;
        z-index: 1;
    }
    
    .form-check-input:checked {
        background-color: #198754;
        border-color: #198754;
    }
    
    .alert {
        border: none;
        border-radius: 0.5rem;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const totalSteps = 8;
    let currentStep = 1;
    const progressBar = document.getElementById('step-progress-bar');
    
    // Initialize: Hide all steps except first
    for (let i = 2; i <= totalSteps; i++) {
        const stepElement = document.getElementById(`step-${i}`);
        if (stepElement) {
            stepElement.style.display = 'none';
        }
    }
    
    // Auto-populate welder information
    const welderSelect = document.getElementById('welder_id');
    const welderInfoDisplay = document.getElementById('welder-info-display');
    const companySelect = document.getElementById('company_id');
    
    welderSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        
        if (selectedOption && selectedOption.value) {
            // Show welder info
            welderInfoDisplay.style.display = 'block';
            
            // Populate display fields
            document.getElementById('display-welder-no').textContent = 
                selectedOption.getAttribute('data-welder-no') || 'N/A';
            document.getElementById('display-iqama-no').textContent = 
                selectedOption.getAttribute('data-iqama-no') || 'N/A';
            document.getElementById('display-passport-no').textContent = 
                selectedOption.getAttribute('data-passport-no') || 'N/A';
            document.getElementById('display-company-name').textContent = 
                selectedOption.getAttribute('data-company-name') || 'N/A';
                
            // Auto-select company if available
            const companyId = selectedOption.getAttribute('data-company-id');
            if (companyId && companySelect) {
                companySelect.value = companyId;
            }
        } else {
            welderInfoDisplay.style.display = 'none';
        }
        
        updateSummary();
    });
    
    // Show/hide process-specific variables based on welding process type
    const processTypeSelect = document.getElementById('welding_process_type');
    if (processTypeSelect) {
        processTypeSelect.addEventListener('change', function() {
            const processType = this.value;
            
            // Hide all process variable sections
            document.querySelectorAll('.process-variables').forEach(section => {
                section.style.display = 'none';
            });
            
            // Show relevant sections based on selection
            if (processType === 'SMAW') {
                const smawSection = document.getElementById('smaw-variables');
                if (smawSection) smawSection.style.display = 'block';
            } else if (processType === 'GTAW') {
                const gtawSection = document.getElementById('gtaw-variables');
                if (gtawSection) gtawSection.style.display = 'block';
            } else if (processType === 'SMAW-GTAW') {
                const smawSection = document.getElementById('smaw-variables');
                const gtawSection = document.getElementById('gtaw-variables');
                if (smawSection) smawSection.style.display = 'block';
                if (gtawSection) gtawSection.style.display = 'block';
            } else if (processType === 'FCAW') {
                const fcawSection = document.getElementById('fcaw-variables');
                if (fcawSection) fcawSection.style.display = 'block';
            }
            
            updateSummary();
        });
    }
    
    // Next step navigation
    document.querySelectorAll('.next-step').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            if (validateCurrentStep(currentStep)) {
                navigateToStep(currentStep + 1);
            }
        });
    });
    
    // Previous step navigation
    document.querySelectorAll('.prev-step').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            navigateToStep(currentStep - 1);
        });
    });
    
    function navigateToStep(targetStep) {
        if (targetStep < 1 || targetStep > totalSteps) return;
        
        // Hide current step
        document.getElementById(`step-${currentStep}`).style.display = 'none';
        
        // Show target step
        document.getElementById(`step-${targetStep}`).style.display = 'block';
        
        // Update current step
        currentStep = targetStep;
        
        // Update progress bar
        const progressPercentage = Math.round((currentStep / totalSteps) * 100);
        progressBar.style.width = `${progressPercentage}%`;
        progressBar.setAttribute('aria-valuenow', progressPercentage);
        progressBar.innerHTML = `<span class="fw-bold">Step ${currentStep} of ${totalSteps}</span>`;
        
        // Update progress bar color based on step
        progressBar.className = 'progress-bar';
        if (currentStep <= 2) progressBar.classList.add('bg-primary');
        else if (currentStep <= 4) progressBar.classList.add('bg-success');
        else if (currentStep <= 6) progressBar.classList.add('bg-warning');
        else progressBar.classList.add('bg-info');
        
        // Scroll to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
        
        // Update certificate number and summary on final step
        if (currentStep === totalSteps) {
            updateCertificateNumber();
            updateSummary();
        }
    }
    
    function validateCurrentStep(step) {
        const currentStepElement = document.getElementById(`step-${step}`);
        if (!currentStepElement) return true;
        
        const requiredFields = currentStepElement.querySelectorAll('[required]');
        let isValid = true;
        
        requiredFields.forEach(field => {
            if (field.disabled) return;
            
            if (!field.value || field.value.trim() === '') {
                field.classList.add('is-invalid');
                isValid = false;
                
                // Show validation message
                let errorElement = field.nextElementSibling;
                if (!errorElement || !errorElement.classList.contains('invalid-feedback')) {
                    errorElement = document.createElement('div');
                    errorElement.className = 'invalid-feedback';
                    field.parentNode.appendChild(errorElement);
                }
                
                const fieldLabel = document.querySelector(`label[for="${field.id}"]`)?.textContent.replace(' *', '') || field.name;
                errorElement.textContent = `${fieldLabel} is required`;
            } else {
                field.classList.remove('is-invalid');
                const errorElement = field.nextElementSibling;
                if (errorElement && errorElement.classList.contains('invalid-feedback')) {
                    errorElement.remove();
                }
            }
        });
        
        // Show validation message if step is invalid
        if (!isValid) {
            showAlert('Please fill in all required fields before proceeding.', 'warning');
        }
        
        return isValid;
    }
    
    function updateCertificateNumber() {
        const companyId = companySelect?.value;
        const processType = processTypeSelect?.value;
        const certNumberElement = document.getElementById('final-cert-number');
        
        if (companyId && processType && certNumberElement) {
            // Generate certificate number based on company and process
            fetch(`{{ route('api.generateCertNumber') }}?company_id=${companyId}&process_type=${processType}`)
                .then(response => response.json())
                .then(data => {
                    certNumberElement.innerHTML = `<i class="fas fa-certificate me-2"></i>${data.cert_no}`;
                })
                .catch(error => {
                    console.error('Error generating certificate number:', error);
                    certNumberElement.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i>Error generating number`;
                });
        }
    }
    
    function updateSummary() {
        // Update summary in final step
        const welderText = welderSelect.options[welderSelect.selectedIndex]?.text || '-';
        const processText = processTypeSelect?.value || '-';
        const positionText = document.getElementById('test_position')?.value || '-';
        const resultText = document.querySelector('input[name="overall_result"]:checked')?.nextElementSibling?.textContent?.trim() || '-';
        
        const summaryElements = {
            'summary-welder': welderText.split(' (ID:')[0], // Remove ID part for cleaner display
            'summary-process': processText,
            'summary-position': positionText,
            'summary-result': resultText
        };
        
        Object.entries(summaryElements).forEach(([id, text]) => {
            const element = document.getElementById(id);
            if (element) element.textContent = text;
        });
    }
    
    function showAlert(message, type = 'info') {
        // Create and show alert
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
        alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(alertDiv);
        
        // Auto remove after 5 seconds
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }
    
    // Auto-generate report numbers based on test date and company
    function generateReportNumbers() {
        const testDate = document.getElementById('test_date')?.value;
        const companyId = companySelect?.value;
        
        if (testDate && companyId) {
            // Generate sequential report numbers
            const date = new Date(testDate);
            const year = date.getFullYear().toString().substr(-2);
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            
            // Get company code from selection
            const companyOption = companySelect.options[companySelect.selectedIndex];
            const companyName = companyOption?.text || 'EEA';
            const companyCode = companyName.split(' ')[0] || 'EEA';
            
            // Generate report numbers (in real implementation, these should be sequential from database)
            const baseNumber = Math.floor(Math.random() * 9000) + 1000; // Random 4-digit number for demo
            
            const vtReportNo = `${companyCode}-AIC-VT-${baseNumber}`;
            const rtReportNo = `${companyCode}-AIC-RT-${baseNumber + 1}`;
            
            document.getElementById('vt_report_no').value = vtReportNo;
            document.getElementById('rt_report_no').value = rtReportNo;
        }
    }
    
    // Generate report numbers when test date or company changes
    const testDateInput = document.getElementById('test_date');
    if (testDateInput) {
        testDateInput.addEventListener('change', generateReportNumbers);
    }
    
    if (companySelect) {
        companySelect.addEventListener('change', function() {
            generateReportNumbers();
            updateCertificateNumber();
        });
    }
    
    // Initialize process variables display
    if (processTypeSelect && processTypeSelect.value) {
        processTypeSelect.dispatchEvent(new Event('change'));
    }
    
    // Initialize welder info if already selected
    if (welderSelect && welderSelect.value) {
        welderSelect.dispatchEvent(new Event('change'));
    }
    
    // Form submission handling
    const qualificationForm = document.getElementById('qualification-form');
    if (qualificationForm) {
        qualificationForm.addEventListener('submit', function(e) {
            // Validate all steps before submission
            let allValid = true;
            for (let i = 1; i <= totalSteps; i++) {
                if (!validateCurrentStep(i)) {
                    allValid = false;
                    break;
                }
            }
            
            if (!allValid) {
                e.preventDefault();
                showAlert('Please complete all required fields in all steps before submitting.', 'danger');
                return false;
            }
            
            // Show loading state
            const submitButtons = qualificationForm.querySelectorAll('button[type="submit"]');
            submitButtons.forEach(btn => {
                btn.disabled = true;
                const originalText = btn.innerHTML;
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Processing...';
                
                // Restore button after 10 seconds (fallback)
                setTimeout(() => {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }, 10000);
            });
        });
    }
    
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey) {
            if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
                e.preventDefault();
                if (currentStep < totalSteps && validateCurrentStep(currentStep)) {
                    navigateToStep(currentStep + 1);
                }
            } else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
                e.preventDefault();
                if (currentStep > 1) {
                    navigateToStep(currentStep - 1);
                }
            }
        }
    });
    
    // Add keyboard navigation hint
    const keyboardHint = document.createElement('div');
    keyboardHint.className = 'position-fixed bottom-0 end-0 p-3 text-muted small';
    keyboardHint.innerHTML = '<i class="fas fa-keyboard me-1"></i>Use Ctrl + Arrow keys for navigation';
    keyboardHint.style.zIndex = '1000';
    document.body.appendChild(keyboardHint);
    
    // Hide keyboard hint after 10 seconds
    setTimeout(() => {
        keyboardHint.style.opacity = '0';
        keyboardHint.style.transition = 'opacity 0.5s';
        setTimeout(() => keyboardHint.remove(), 500);
    }, 10000);
});
</script>
@endpush

@endsection