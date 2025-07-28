@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Edit Qualification Test: {{ $qualification->cert_no }}</h1>
        <a href="{{ route('qualification-tests.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Qualifications
        </a>
    </div>

    <div class="progress mb-4" style="height: 25px;">
        <div class="progress-bar" role="progressbar" style="width: 12.5%;" id="step-progress-bar" aria-valuenow="12.5" aria-valuemin="0" aria-valuemax="100">Step 1 of 8</div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('qualification-tests.update', $qualification->id) }}" method="POST" id="qualification-form">
                @csrf
                @method('PUT')
                
                <!-- Step 1: Welder Selection Section -->
                <div class="step-section" id="step-1">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Step 1: Welder Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="welder_selector" class="form-label">Select Welder <span class="text-danger">*</span></label>
                                <select class="form-select @error('welder_no') is-invalid @enderror" id="welder_selector" name="welder_no" required>
                                    <option value="">-- Select a welder --</option>
                                    @foreach($welders as $id => $name)
                                        <option value="{{ $id }}" {{ old('welder_no', $qualification->welder_no) == $id ? 'selected' : '' }}
                                            data-welder-id="{{ \App\Models\Welder::find($id)->welder_no ?? '' }}"
                                            data-passport-id="{{ \App\Models\Welder::find($id)->passport_id_no ?? \App\Models\Welder::find($id)->iqama_no ?? '' }}">
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('welder_no')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="company_id" class="form-label">Company <span class="text-danger">*</span></label>
                                <select class="form-select @error('company_id') is-invalid @enderror" id="company_id" name="company_id" required>
                                    <option value="">-- Select a company --</option>
                                    @foreach($companies as $id => $name)
                                        <option value="{{ $id }}" {{ old('company_id', $qualification->company_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @error('company_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="qualification_type" class="form-label">Qualification Type <span class="text-danger">*</span></label>
                                <select class="form-select @error('qualification_type') is-invalid @enderror" id="qualification_type" name="qualification_type" required>
                                    <option value="">-- Select type --</option>
                                    <option value="WQT" {{ old('qualification_type', $qualification->qualification_type) == 'WQT' ? 'selected' : '' }}>WQT</option>
                                    <option value="PQR" {{ old('qualification_type', $qualification->qualification_type) == 'PQR' ? 'selected' : '' }}>PQR</option>
                                </select>
                                @error('qualification_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <span></span>
                                <button type="button" class="btn btn-primary next-step">Next Step <i class="fas fa-arrow-right"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Step 2: General Information Section -->
                <div class="step-section" id="step-2" style="display: none;">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Step 2: General Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="sr_no" class="form-label">SR No.</label>
                                    <input type="text" class="form-control @error('sr_no') is-invalid @enderror" id="sr_no" name="sr_no" value="{{ old('sr_no', $qualification->sr_no) }}">
                                    @error('sr_no')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="work_order_no" class="form-label">Work Order No.</label>
                                    <input type="text" class="form-control @error('work_order_no') is-invalid @enderror" id="work_order_no" name="work_order_no" value="{{ old('work_order_no', $qualification->work_order_no) }}">
                                    @error('work_order_no')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="location" class="form-label">Location</label>
                                    <input type="text" class="form-control @error('location') is-invalid @enderror" id="location" name="location" value="{{ old('location', $qualification->location) }}">
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                              <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="passport_id_no" class="form-label">Passport/ID No.</label>
                                    <input type="text" class="form-control @error('passport_id_no') is-invalid @enderror" id="passport_id_no" name="passport_id_no" value="{{ old('passport_id_no', $qualification->passport_id_no) }}" readonly>
                                    @error('passport_id_no')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Auto-populated from welder record</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="welder_input" class="form-label">Welder No.</label>
                                    <input type="text" class="form-control @error('welder_no') is-invalid @enderror" id="welder_input" name="welder_input" value="{{ old('welder_input', $qualification->welder_no) }}" readonly>
                                    <input type="hidden" name="welder_id" value="{{ old('welder_id', $qualification->welder_id) }}">
                                    @error('welder_no')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted">Auto-populated from welder record</small>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary prev-step"><i class="fas fa-arrow-left"></i> Previous Step</button>
                                <button type="button" class="btn btn-primary next-step">Next Step <i class="fas fa-arrow-right"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Step 3: Welding Process Details Section -->
                <div class="step-section" id="step-3" style="display: none;">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Step 3: Welding Process Details</h5>
                        </div>
                        <div class="card-body">                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="wps_no" class="form-label">WPS No. <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('wps_no') is-invalid @enderror" id="wps_no" name="wps_no" value="{{ old('wps_no', $qualification->wps_no) }}" required>
                                    @error('wps_no')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="welding_process" class="form-label">Welding Process <span class="text-danger">*</span></label>
                                    {!! \App\Enums\QualificationOptions::selectWithManualEntry('welding_process', $processes, old('welding_process', $qualification->welding_process), true) !!}
                                    @error('welding_process')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-4 mb-3">                                    <label for="test_coupon" class="form-label">Test Coupon <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('test_coupon') is-invalid @enderror" id="test_coupon" name="test_coupon" value="{{ old('test_coupon', $qualification->test_coupon) }}" required>
                                    @error('test_coupon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4 mb-3">                                    <label for="dia_inch" class="form-label">Diameter (inch) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('dia_inch') is-invalid @enderror" id="dia_inch" name="dia_inch" value="{{ old('dia_inch', $qualification->dia_inch) }}" required>
                                    @error('dia_inch')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4 mb-3">                                    <label for="qualified_dia_inch" class="form-label">Qualified Diameter (inch) <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('qualified_dia_inch') is-invalid @enderror" id="qualified_dia_inch" name="qualified_dia_inch" value="{{ old('qualified_dia_inch', $qualification->qualified_dia_inch) }}" required>
                                    @error('qualified_dia_inch')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="welding_positions" class="form-label">Test Position <span class="text-danger">*</span></label>
                                    {!! \App\Enums\QualificationOptions::selectWithManualEntry('welding_positions', $testPositions, old('welding_positions', $qualification->welding_positions), true) !!}
                                    @error('welding_positions')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                  <div class="col-md-6 mb-3">
                                    <label for="qualified_position" class="form-label">Qualified Position <span class="text-danger">*</span></label>
                                    {!! \App\Enums\QualificationOptions::selectWithManualEntry('qualified_position', $testPositions, old('qualified_position', $qualification->qualified_position), true) !!}
                                    @error('qualified_position')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="electric_char" class="form-label">Electric Characteristics</label>
                                    {!! \App\Enums\QualificationOptions::selectWithManualEntry('electric_char', $electricCharacteristics, old('electric_char', $qualification->electric_char)) !!}
                                    @error('electric_char')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="qualified_ec" class="form-label">Qualified Electric Characteristics</label>
                                    <input type="text" class="form-control @error('qualified_ec') is-invalid @enderror" id="qualified_ec" name="qualified_ec" value="{{ old('qualified_ec', $qualification->qualified_ec) }}">
                                    @error('qualified_ec')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary prev-step"><i class="fas fa-arrow-left"></i> Previous Step</button>
                                <button type="button" class="btn btn-primary next-step">Next Step <i class="fas fa-arrow-right"></i></button>
                            </div>
                        </div>
                    </div>                </div>
                
                <!-- Step 4: Joint Details Section -->
                <div class="step-section" id="step-4" style="display: none;">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Step 4: Joint Details</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="joint_diagram" class="form-label">Joint Diagram (Optional)</label>
                                    <input type="file" class="form-control @error('joint_diagram') is-invalid @enderror" id="joint_diagram" name="joint_diagram" accept="image/*">
                                    <div class="form-text">Upload an image of the joint diagram (max: 2MB)</div>
                                    @if($qualification->joint_diagram_path)
                                        <div class="mt-2">
                                            <strong>Current Joint Diagram:</strong>
                                            <a href="{{ asset('storage/' . $qualification->joint_diagram_path) }}" target="_blank">
                                                <img src="{{ asset('storage/' . $qualification->joint_diagram_path) }}" alt="Joint Diagram" style="max-height: 100px; max-width: 100px;">
                                            </a>
                                        </div>
                                    @endif
                                    @error('joint_diagram')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="joint_type" class="form-label">Joint Type</label>
                                    <input type="text" class="form-control @error('joint_type') is-invalid @enderror" id="joint_type" name="joint_type" value="{{ old('joint_type', $qualification->joint_type ?? 'SINGLE V-GROOVE') }}">
                                    @error('joint_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="joint_description" class="form-label">Joint Description</label>
                                    <input type="text" class="form-control @error('joint_description') is-invalid @enderror" id="joint_description" name="joint_description" value="{{ old('joint_description', $qualification->joint_description ?? 'BUTT JOINT (PIPE)') }}">
                                    @error('joint_description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="pipe_outer_diameter" class="form-label">Pipe Outer Diameter</label>
                                    <input type="text" class="form-control @error('pipe_outer_diameter') is-invalid @enderror" id="pipe_outer_diameter" name="pipe_outer_diameter" value="{{ old('pipe_outer_diameter', $qualification->pipe_outer_diameter ?? '168.28 mm (6 Inch Sch.80)') }}">
                                    @error('pipe_outer_diameter')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="base_metal_p_no" class="form-label">Base Metal P-No.</label>
                                    <input type="text" class="form-control @error('base_metal_p_no') is-invalid @enderror" id="base_metal_p_no" name="base_metal_p_no" value="{{ old('base_metal_p_no', $qualification->base_metal_p_no ?? 'P-No.1 Gr.1 to P-No.1 Gr.1') }}">
                                    @error('base_metal_p_no')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="joint_angle" class="form-label">Joint Angle</label>
                                    <input type="text" class="form-control @error('joint_angle') is-invalid @enderror" id="joint_angle" name="joint_angle" value="{{ old('joint_angle', $qualification->joint_angle ?? '30°') }}">
                                    @error('joint_angle')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="joint_total_angle" class="form-label">Joint Total Angle</label>
                                    <input type="text" class="form-control @error('joint_total_angle') is-invalid @enderror" id="joint_total_angle" name="joint_total_angle" value="{{ old('joint_total_angle', $qualification->joint_total_angle ?? '60° Total') }}">
                                    @error('joint_total_angle')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="root_gap" class="form-label">Root Gap</label>
                                    <input type="text" class="form-control @error('root_gap') is-invalid @enderror" id="root_gap" name="root_gap" value="{{ old('root_gap', $qualification->root_gap ?? '2-3mm') }}">
                                    @error('root_gap')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="root_face" class="form-label">Root Face</label>
                                    <input type="text" class="form-control @error('root_face') is-invalid @enderror" id="root_face" name="root_face" value="{{ old('root_face', $qualification->root_face ?? '1-2mm') }}">
                                    @error('root_face')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="inert_gas_backing" class="form-label">Inert Gas Backing (QW-408)</label>
                                    <input type="text" class="form-control @error('inert_gas_backing') is-invalid @enderror" id="inert_gas_backing" name="inert_gas_backing" value="{{ old('inert_gas_backing', $qualification->inert_gas_backing ?? 'Not Used (GTAW root without internal purge)') }}">
                                    @error('inert_gas_backing')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="filler_metal_form" class="form-label">Filler Metal Product Form</label>
                                    <input type="text" class="form-control @error('filler_metal_form') is-invalid @enderror" id="filler_metal_form" name="filler_metal_form" value="{{ old('filler_metal_form', $qualification->filler_metal_form ?? 'Solid Wire / Coated Electrode') }}">
                                    @error('filler_metal_form')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="vertical_progression" class="form-label">Vertical Progression (QW-405.3)</label>
                                    <select class="form-select @error('vertical_progression') is-invalid @enderror" id="vertical_progression" name="vertical_progression">
                                        @foreach($verticalProgressions as $key => $value)
                                            <option value="{{ $key }}" {{ old('vertical_progression', $qualification->vertical_progression ?? 'Uphill') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                        @endforeach
                                    </select>
                                    @error('vertical_progression')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="gtaw_thickness" class="form-label">GTAW Thickness (t1)</label>
                                    <input type="text" class="form-control @error('gtaw_thickness') is-invalid @enderror" id="gtaw_thickness" name="gtaw_thickness" value="{{ old('gtaw_thickness', $qualification->gtaw_thickness ?? 'Approx. 4 mm') }}">
                                    @error('gtaw_thickness')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="smaw_thickness" class="form-label">SMAW Thickness (t2)</label>
                                    <input type="text" class="form-control @error('smaw_thickness') is-invalid @enderror" id="smaw_thickness" name="smaw_thickness" value="{{ old('smaw_thickness', $qualification->smaw_thickness ?? 'Approx. 10.27 mm') }}">
                                    @error('smaw_thickness')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary prev-step"><i class="fas fa-arrow-left"></i> Previous Step</button>
                                <button type="button" class="btn btn-primary next-step">Next Step <i class="fas fa-arrow-right"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                  <!-- Step 5: Material and Dimensions Section -->
                <div class="step-section" id="step-5" style="display: none;">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Step 5: Material and Dimensions</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="coupon_material" class="form-label">Coupon Material</label>
                                    {!! \App\Enums\QualificationOptions::selectWithManualEntry('coupon_material', $couponMaterials, old('coupon_material', $qualification->coupon_material)) !!}
                                    @error('coupon_material')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="qualified_material" class="form-label">Qualified Material</label>
                                    {!! \App\Enums\QualificationOptions::selectWithManualEntry('qualified_material', $qualifiedMaterials, old('qualified_material', $qualification->qualified_material)) !!}
                                    @error('qualified_material')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="coupon_thickness_mm" class="form-label">Coupon Thickness (mm)</label>
                                    <input type="number" step="0.01" class="form-control @error('coupon_thickness_mm') is-invalid @enderror" id="coupon_thickness_mm" name="coupon_thickness_mm" value="{{ old('coupon_thickness_mm', $qualification->coupon_thickness_mm) }}">
                                    @error('coupon_thickness_mm')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="deposit_thickness" class="form-label">Deposit Thickness</label>
                                    <input type="text" class="form-control @error('deposit_thickness') is-invalid @enderror" id="deposit_thickness" name="deposit_thickness" value="{{ old('deposit_thickness', $qualification->deposit_thickness) }}">
                                    @error('deposit_thickness')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="qualified_thickness_range" class="form-label">Qualified Thickness Range</label>
                                    {!! \App\Enums\QualificationOptions::selectWithManualEntry('qualified_thickness_range', $qualifiedThicknessRanges, old('qualified_thickness_range', $qualification->qualified_thickness_range)) !!}
                                    @error('qualified_thickness_range')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary prev-step"><i class="fas fa-arrow-left"></i> Previous Step</button>
                                <button type="button" class="btn btn-primary next-step">Next Step <i class="fas fa-arrow-right"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                  <!-- Step 6: Filler Material Section -->
                <div class="step-section" id="step-6" style="display: none;">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Step 6: Filler Material</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="filler_metal_f_no" class="form-label">Filler Metal F No.</label>
                                    <input type="text" class="form-control @error('filler_metal_f_no') is-invalid @enderror" id="filler_metal_f_no" name="filler_metal_f_no" value="{{ old('filler_metal_f_no', $qualification->filler_metal_f_no) }}">
                                    @error('filler_metal_f_no')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="aws_spec_no" class="form-label">AWS Specification No.</label>
                                    <input type="text" class="form-control @error('aws_spec_no') is-invalid @enderror" id="aws_spec_no" name="aws_spec_no" value="{{ old('aws_spec_no', $qualification->aws_spec_no) }}">
                                    @error('aws_spec_no')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-4 mb-3">
                                    <label for="filler_metal_classif" class="form-label">Filler Metal Classification</label>
                                    <input type="text" class="form-control @error('filler_metal_classif') is-invalid @enderror" id="filler_metal_classif" name="filler_metal_classif" value="{{ old('filler_metal_classif', $qualification->filler_metal_classif) }}">
                                    @error('filler_metal_classif')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="backing" class="form-label">Backing</label>
                                    <input type="text" class="form-control @error('backing') is-invalid @enderror" id="backing" name="backing" value="{{ old('backing', $qualification->backing) }}">
                                    @error('backing')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="qualified_backing" class="form-label">Qualified Backing</label>
                                    <input type="text" class="form-control @error('qualified_backing') is-invalid @enderror" id="qualified_backing" name="qualified_backing" value="{{ old('qualified_backing', $qualification->qualified_backing) }}">
                                    @error('qualified_backing')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary prev-step"><i class="fas fa-arrow-left"></i> Previous Step</button>
                                <button type="button" class="btn btn-primary next-step">Next Step <i class="fas fa-arrow-right"></i></button>
                            </div>
                        </div>
                    </div>
                </div>                <!-- Step 7: Testing Results Section -->
                <div class="step-section" id="step-7" style="display: none;">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Step 7: Testing Results</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">                                <div class="col-md-12 mb-4">
                                    <div class="row">
                                        <div class="col-md-4 mb-3">
                                            <label for="test_date" class="form-label">Test Date</label>
                                            <input type="date" class="form-control @error('test_date') is-invalid @enderror" id="test_date" name="test_date" value="{{ old('test_date', $qualification->test_date ? $qualification->test_date->format('Y-m-d') : ($qualification->vt_date ? $qualification->vt_date->format('Y-m-d') : '')) }}">
                                            @error('test_date')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">This date will be used for both VT and RT reports.</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <h6 class="mb-3">Visual Testing</h6>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="vt_result" class="form-label">VT Result</label>
                                            <select class="form-select @error('vt_result') is-invalid @enderror" id="vt_result" name="vt_result">
                                                <option value="">-- Select Result --</option>
                                                @foreach($testResults as $key => $value)
                                                    <option value="{{ $key }}" {{ old('vt_result', $qualification->vt_result) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                            @error('vt_result')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Current Report No: {{ $qualification->vt_report_no ?? 'Not set yet' }}</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <h6 class="mb-3">Radiographic Testing</h6>
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label for="rt_result" class="form-label">RT Result</label>
                                            <select class="form-select @error('rt_result') is-invalid @enderror" id="rt_result" name="rt_result">
                                                <option value="">-- Select Result --</option>
                                                @foreach($testResults as $key => $value)
                                                    <option value="{{ $key }}" {{ old('rt_result', $qualification->rt_result) == $key ? 'selected' : '' }}>{{ $value }}</option>
                                                @endforeach
                                            </select>
                                            @error('rt_result')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Current Report No: {{ $qualification->rt_report_no ?? 'Not set yet' }}</small>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Hidden fields for report numbers - they will be auto-generated if needed -->
                                <input type="hidden" name="vt_report_no" value="{{ old('vt_report_no', $qualification->vt_report_no) }}">
                                <input type="hidden" name="rt_report_no" value="{{ old('rt_report_no', $qualification->rt_report_no) }}">
                                <input type="hidden" name="rt_date" value="{{ old('rt_date', $qualification->rt_date ? $qualification->rt_date->format('Y-m-d') : '') }}">

</div>
</div>
</div>

<div class="row mt-3">                                <div class="col-12">
                                    <div class="mb-3">                                      
                                          <label for="test_result" class="form-label">Test Result</label>
                                          @if(Auth::user()->role === 'qc' || Auth::user()->role === 'admin')
                                            <select class="form-select" id="test_result" name="test_result">
                                                <option value="1" {{ old('test_result', $qualification->test_result) ? 'selected' : '' }}>Pass</option>
                                                <option value="0" {{ old('test_result', $qualification->test_result) ? '' : 'selected' }}>Fail</option>
                                            </select>
                                        @else
                                            <select class="form-select" id="test_result" name="test_result" {{ Auth::user()->role === 'user' ? 'disabled' : '' }}>
                                                <option value="1" {{ old('test_result', $qualification->test_result) ? 'selected' : '' }}>Pass</option>
                                                <option value="0" {{ old('test_result', $qualification->test_result) ? '' : 'selected' }}>Fail</option>
                                            </select>
                                            @if(Auth::user()->role === 'user')
                                                <div class="form-text text-danger">Only QC personnel can change the test result</div>
                                            @endif
                                            <!-- Hidden input to ensure value is submitted when disabled -->
                                            <input type="hidden" name="test_result" value="{{ old('test_result', $qualification->test_result) ? '1' : '0' }}">
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary prev-step"><i class="fas fa-arrow-left"></i> Previous Step</button>
                                <button type="button" class="btn btn-primary next-step">Next Step <i class="fas fa-arrow-right"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                  <!-- Step 8: Additional Information Section -->
                <div class="step-section" id="step-8" style="display: none;">
                    <div class="card mb-4">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0">Step 8: Additional Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="qualification_code" class="form-label">Qualification Code</label>
                                    {!! \App\Enums\QualificationOptions::selectWithManualEntry('qualification_code', $certificationCodes, old('qualification_code', $qualification->qualification_code)) !!}
                                    @error('qualification_code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                              <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="certificate_number" class="form-label">Certificate Number</label>
                                    <div class="alert alert-info">
                                        <strong class="cert-no-display">{{ $qualification->cert_no }}</strong>
                                        <div><small>This was originally generated based on company code and qualification type. 
                                        It may be updated if you change the company or qualification type.</small></div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="remarks" class="form-label">Remarks</label>
                                    <textarea class="form-control @error('remarks') is-invalid @enderror" id="remarks" name="remarks" rows="3">{{ old('remarks', $qualification->remarks) }}</textarea>
                                    @error('remarks')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active', $qualification->is_active) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">Is Active</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary prev-step"><i class="fas fa-arrow-left"></i> Previous Step</button>
                                <div>                                    <button type="submit" class="btn btn-primary">Update Qualification</button>
                                    @if(Auth::user()->role === 'qc' || Auth::user()->role === 'admin')
                                        <button type="submit" name="generate_card" value="1" class="btn btn-success">Update & Generate Card</button>
                                    @else
                                        <button type="button" class="btn btn-secondary" disabled title="Only QC personnel can generate cards">Generate Card (Requires QC Approval)</button>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const totalSteps = 8;
    let currentStep = 1;
    const progressBar = document.getElementById('step-progress-bar');
    
    // Auto-populate welder information
    const welderSelect = document.getElementById('welder_selector');
    const welderNoInput = document.getElementById('welder_input');
    const passportIdInput = document.getElementById('passport_id_no');
    const welderIdInput = document.querySelector('input[name="welder_id"]');
    
    if (welderSelect && welderNoInput && passportIdInput) {
        welderSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption) {
                const welderId = selectedOption.getAttribute('data-welder-id') || '';
                const passportId = selectedOption.getAttribute('data-passport-id') || '';
                
                welderNoInput.value = welderId;
                passportIdInput.value = passportId;
                welderIdInput.value = this.value; // Store the welder ID
            }
        });
        
        // Initialize fields if welder is already selected
        if (welderSelect.value) {
            const selectedOption = welderSelect.options[welderSelect.selectedIndex];
            if (selectedOption) {
                const welderId = selectedOption.getAttribute('data-welder-id') || '';
                const passportId = selectedOption.getAttribute('data-passport-id') || '';
                
                welderNoInput.value = welderId;
                passportIdInput.value = passportId;
                welderIdInput.value = welderSelect.value;
            }
        }
    }
    
    // Fix form submission with enctype
    const qualificationForm = document.getElementById('qualification-form');
    if (qualificationForm) {
        qualificationForm.setAttribute('enctype', 'multipart/form-data');
    }
    
    // Next step button handlers
    document.querySelectorAll('.next-step').forEach(button => {
        button.addEventListener('click', function() {
            // Validate current step (optional)
            if (validateCurrentStep(currentStep)) {
                // Hide current step
                document.getElementById(`step-${currentStep}`).style.display = 'none';
                
                // Show next step
                currentStep++;
                document.getElementById(`step-${currentStep}`).style.display = 'block';
                
                // Update progress bar
                const progressPercentage = Math.round((currentStep / totalSteps) * 100);
                progressBar.style.width = `${progressPercentage}%`;
                progressBar.setAttribute('aria-valuenow', progressPercentage);
                progressBar.innerText = `Step ${currentStep} of ${totalSteps}`;
            }
        });
    });
    
    // Previous step button handlers
    document.querySelectorAll('.prev-step').forEach(button => {
        button.addEventListener('click', function() {
            // Hide current step
            document.getElementById(`step-${currentStep}`).style.display = 'none';
            
            // Show previous step
            currentStep--;
            document.getElementById(`step-${currentStep}`).style.display = 'block';
            
            // Update progress bar
            const progressPercentage = Math.round((currentStep / totalSteps) * 100);
            progressBar.style.width = `${progressPercentage}%`;
            progressBar.setAttribute('aria-valuenow', progressPercentage);
            progressBar.innerText = `Step ${currentStep} of ${totalSteps}`;
        });
    });    // Form validation function
    function validateCurrentStep(step) {
        // Basic validation for required fields in the current step
        const currentStepElement = document.getElementById(`step-${step}`);
        const requiredFields = currentStepElement.querySelectorAll('[required]');
        
        let isValid = true;
        
        // Check required fields
        requiredFields.forEach(field => {
            if (!field.value) {
                field.classList.add('is-invalid');
                isValid = false;
                // Add error message for empty required fields
                const label = document.querySelector(`label[for="${field.id}"]`).textContent.trim().replace(' *', '');
                if (!field.nextElementSibling || !field.nextElementSibling.classList.contains('invalid-feedback')) {
                    const errorMsg = document.createElement('div');
                    errorMsg.className = 'invalid-feedback d-block';
                    errorMsg.innerText = `The ${label} field is required.`;
                    field.parentNode.insertBefore(errorMsg, field.nextSibling);
                }
            } else {
                field.classList.remove('is-invalid');
                // Remove error messages if they exist
                const nextSibling = field.nextElementSibling;
                if (nextSibling && nextSibling.classList.contains('invalid-feedback')) {
                    nextSibling.remove();
                }
            }
        });
        
        // Check for manual fields that are required
        if (step === 3) {
            // Check welding_process - required
            const weldingProcessSelect = document.getElementById('welding_process');
            const weldingProcessManual = document.getElementById('welding_process_manual');
            
            if (weldingProcessSelect.value === '__manual__' && (!weldingProcessManual.value || weldingProcessManual.value.trim() === '')) {
                weldingProcessManual.classList.add('is-invalid');
                isValid = false;
                const errorMsg = document.createElement('div');
                errorMsg.className = 'invalid-feedback d-block';
                errorMsg.innerText = 'The welding process field is required.';
                if (!weldingProcessManual.nextElementSibling || !weldingProcessManual.nextElementSibling.classList.contains('invalid-feedback')) {
                    weldingProcessManual.parentNode.appendChild(errorMsg);
                }
            }
            
            // Check welding_positions - required
            const weldingPositionsSelect = document.getElementById('welding_positions');
            const weldingPositionsManual = document.getElementById('welding_positions_manual');
            
            if (weldingPositionsSelect.value === '__manual__' && (!weldingPositionsManual.value || weldingPositionsManual.value.trim() === '')) {
                weldingPositionsManual.classList.add('is-invalid');
                isValid = false;
                const errorMsg = document.createElement('div');
                errorMsg.className = 'invalid-feedback d-block';
                errorMsg.innerText = 'The welding positions field is required.';
                if (!weldingPositionsManual.nextElementSibling || !weldingPositionsManual.nextElementSibling.classList.contains('invalid-feedback')) {
                    weldingPositionsManual.parentNode.appendChild(errorMsg);
                }
            }
            
            // Check qualified_position - required
            const qualifiedPositionSelect = document.getElementById('qualified_position');
            const qualifiedPositionManual = document.getElementById('qualified_position_manual');
            
            if (qualifiedPositionSelect.value === '__manual__' && (!qualifiedPositionManual.value || qualifiedPositionManual.value.trim() === '')) {
                qualifiedPositionManual.classList.add('is-invalid');
                isValid = false;
                const errorMsg = document.createElement('div');
                errorMsg.className = 'invalid-feedback d-block';
                errorMsg.innerText = 'The qualified position field is required.';
                if (!qualifiedPositionManual.nextElementSibling || !qualifiedPositionManual.nextElementSibling.classList.contains('invalid-feedback')) {
                    qualifiedPositionManual.parentNode.appendChild(errorMsg);
                }
            }
        }
        
        return isValid;
    }
    
    // Company selection handling to update certificate number
    const companySelect = document.getElementById('company_id');
    const qualificationTypeSelect = document.getElementById('qualification_type');
    
    function updateCertificateNumber() {
        const companyId = companySelect.value;
        const qualificationType = qualificationTypeSelect ? qualificationTypeSelect.value : 'WQT';
        
        if (companyId) {
            // Show loading indicator
            const certNoDisplay = document.querySelector('.cert-no-display');
            if (certNoDisplay) {
                certNoDisplay.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Generating...';
            }
            
            // Use AJAX to get the certificate number based on selected company
            fetch(`/api/generate-cert-number?company_id=${companyId}&qualification_type=${qualificationType}`)
                .then(response => response.json())
                .then(data => {
                    // Update the certificate number display
                    if (certNoDisplay) {
                        certNoDisplay.textContent = data.cert_no;
                    }
                })
                .catch(error => {
                    console.error('Error fetching certificate number:', error);
                    if (certNoDisplay) {
                        certNoDisplay.textContent = 'Error generating number';
                    }
                });
        }
    }
    
    // Update certificate number when company changes
    if (companySelect) {
        companySelect.addEventListener('change', updateCertificateNumber);
    }
    
    // Update certificate number when qualification type changes
    if (qualificationTypeSelect) {
        qualificationTypeSelect.addEventListener('change', updateCertificateNumber);
    }
});
</script>
@endsection
