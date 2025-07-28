@extends('lay                        <a href="{{ route('fcaw-certificates.show', $certificate->id) }}" class="btn btn-sm btn-secondary me-2">
                            <i class="fas fa-eye"></i> View
                        </a>
                        <a href="{{ route('fcaw-certificates.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-list"></i> Back to List
                        </a>pp')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4><i class="fas fa-certificate"></i> Edit FCAW Welder Qualification Certificate</h4>
                        <p class="mb-0 text-muted">Update the FCAW certificate information below</p>
                    </div>
                    <div>
                        <a href="{{ route('smaw-certificates.show', $certificate->id) }}" class="btn btn-sm btn-secondary me-2">
                            <i class="fas fa-eye"></i> View Certificate
                        </a>
                        <a href="{{ route('smaw-certificates.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to List
                        </a>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-info mb-3">
                <div class="d-flex align-items-center">
                    <i class="fas fa-info-circle fa-lg me-3"></i>
                    <div>
                        <strong>Form Information</strong>
                        <p class="mb-0">You are editing a FCAW (Flux Cored Arc Welding) certificate based on ASME Section IX requirements.</p>
                        <p class="mb-0 small mt-1"><strong>Keyboard shortcuts:</strong> Ctrl+S to save | Ctrl+P to preview</p>
                    </div>
                </div>
            </div>

            <!-- Certificate form with template styling -->
            <div class="form-container bg-white border shadow-sm">
                <form id="welderQualificationForm" method="POST" action="{{ route('fcaw-certificates.update', $certificate->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Header with logos - exactly like certificate -->
                    <div class="row border border-dark m-0">
                        <div class="col-2 border-end border-dark p-2 text-center bg-light">
                            <div style="font-size: 14px; font-weight: bold; text-align: center; color: #0066cc;">
                                <div style="background: #0066cc; color: white; padding: 2px 8px; border-radius: 15px; margin-bottom: 3px;">ELITE</div>
                                <div style="font-size: 8px; color: #666;">ENGINEERING ARABIA</div>
                            </div>
                        </div>
                        <div class="col-8 p-2 text-center">
                            <h5 class="fw-bold mb-1">Elite Engineering Arabia</h5>
                            <div class="small text-muted">
                                e-mail: info@eliteengineeringarabia.com
                            </div>
                            <h5 class="fw-bold mt-2">WELDER PERFORMANCE QUALIFICATIONS</h5>
                        </div>
                        <div class="col-2 border-start border-dark p-2 text-center bg-light">
                            <div style="font-size: 14px; font-weight: bold; text-align: center;">
                                <span style="color: #dc3545; font-size: 16px;">AIC</span><span style="color: #999; font-size: 12px;">steel</span>
                            </div>
                        </div>
                    </div>

                    <!-- Certificate details rows -->
                    <div class="row border border-dark border-top-0 m-0 py-1 bg-light">
                        <div class="col-4 border-end border-dark">
                            <label class="fw-bold">Certificate No:</label>
                            <input type="text" class="form-control-plaintext d-inline-block ms-2 fw-bold" style="width: 120px" id="certificate_no_display" value="{{ $certificate->certificate_no }}" readonly>
                            <input type="hidden" name="certificate_no" value="{{ $certificate->certificate_no }}">
                        </div>
                        <div class="col-4 text-center">
                            <label class="fw-bold">Welder's name:</label>
                            <select class="form-select d-inline-block ms-2 fw-bold" style="width: auto" id="welder_id" name="welder_id" required onchange="loadWelderData()">
                                <option value="">-- Select Welder --</option>
                                @foreach($welders as $id => $name)
                                    <option value="{{ $id }}" {{ old('welder_id', $certificate->welder_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-4 border-start border-dark text-end">
                            <label class="fw-bold">Welder ID No:</label>
                            <span class="fw-bold ms-2" id="welder_id_display">{{ $certificate->welder->welder_no ?? '' }}</span>
                            <input type="hidden" id="welder_id_no" name="welder_id_no" value="{{ $certificate->welder->welder_no ?? '' }}">
                        </div>
                    </div>

                    <div class="row border border-dark border-top-0 m-0 py-1 bg-light">
                        <div class="col-4 border-end border-dark">
                            <label class="fw-bold">Gov ID Iqama number:</label>
                            <span class="fw-bold ms-2" id="iqama_display">{{ $certificate->welder->iqama_no ?? '' }}</span>
                        </div>
                        <div class="col-4 text-center">
                            <label class="fw-bold">Company:</label>
                            <select class="form-select d-inline-block ms-2 fw-bold" style="width: auto" id="company_id" name="company_id" required>
                                <option value="">-- Select Company --</option>
                                @foreach($companies as $id => $name)
                                    <option value="{{ $id }}" {{ old('company_id', $certificate->company_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-4 border-start border-dark text-end">
                            <label class="fw-bold">Passport No:</label>
                            <span class="fw-bold ms-2" id="passport_display">{{ $certificate->welder->passport_no ?? '' }}</span>
                        </div>
                    </div>
                    
                    <!-- Test Description header -->
                    <div class="row border border-dark border-top-0 m-0 py-1 bg-light">
                        <div class="col-12 text-center fw-bold">
                            Test Description
                        </div>
                    </div>

                    <!-- WPS and test coupon row -->
                    <div class="row border border-dark border-top-0 m-0 py-1 bg-light">
                        <div class="col-6 border-end border-dark">
                            <label class="fw-bold">Identification of WPS followed:</label>
                            <input type="text" class="form-control form-control-sm d-inline-block ms-2" style="width: 200px" id="wps_followed" name="wps_followed" value="{{ old('wps_followed', $certificate->wps_followed) }}" required>
                            @error('wps_followed')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-3 text-center border-end border-dark">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="test_coupon" name="test_coupon" value="1" checked>
                                <label class="form-check-label fw-bold" for="test_coupon">Test coupon</label>
                            </div>
                        </div>
                        <div class="col-3 text-center">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="production_weld" name="production_weld" value="1" {{ old('production_weld', $certificate->production_weld) ? 'checked' : '' }}>
                                <label class="form-check-label fw-bold" for="production_weld">Production weld</label>
                            </div>
                        </div>
                    </div>

                    <!-- Base metal spec and test date -->
                    <div class="row border border-dark border-top-0 m-0 py-1 bg-light">
                        <div class="col-6 border-end border-dark">
                            <label class="fw-bold">Base Metal Specification:</label>
                            <input type="text" class="form-control form-control-sm d-inline-block ms-2" style="width: 200px" id="base_metal_spec" name="base_metal_spec" value="{{ old('base_metal_spec', $certificate->base_metal_spec) }}" required>
                            @error('base_metal_spec')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-2 text-center border-end border-dark">
                            <label class="fw-bold">Date of Test:</label>
                        </div>
                        <div class="col-4 text-center">
                            <input type="date" class="form-control form-control-sm d-inline-block" style="width: 150px" id="test_date" name="test_date" value="{{ old('test_date', $certificate->test_date->format('Y-m-d')) }}" required>
                            @error('test_date')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Dia/thickness row and photo placement -->
                    <div class="row border border-dark border-top-0 m-0 py-1 bg-light">
                        <div class="col-6 border-end border-dark">
                            <label class="fw-bold">Dia / Thickness:</label>
                            <input type="text" class="form-control form-control-sm d-inline-block ms-2" style="width: 200px" id="dia_thickness_display" value="{{ old('dia_thickness_display', '8 inch/' . $certificate->smaw_thickness . ' mm') }}" readonly>
                        </div>
                        <div class="col-2 text-center border-end border-dark">
                            <!-- Empty cell -->
                        </div>
                        <div class="col-4 text-center">
                            <div id="photo-preview" class="mx-auto" style="width: 85px; height: 110px; border: 1px dashed #ccc; display: flex; align-items: center; justify-content: center; background: #f8f9fa; cursor: pointer;" onclick="document.getElementById('photo').click()">
                                @if($certificate->photo_path)
                                    <img src="{{ asset('storage/' . $certificate->photo_path) }}" alt="Welder Photo" style="max-height: 110px; max-width: 100%;">
                                @else
                                    <div>
                                        <i class="fas fa-camera fa-lg"></i><br>
                                        <small>Click to upload<br>welder photo</small>
                                    </div>
                                @endif
                            </div>
                            <input type="file" class="d-none" id="photo" name="photo" onchange="previewPhoto(this)">
                            @error('photo')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Main content area -->
                    <div class="card mb-3 border-primary">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-fire"></i> Welding Process & Specimen Type</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <div class="card border-success">
                                            <div class="card-header bg-success text-white">Process Type</div>
                                            <div class="card-body">
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" class="form-check-input" id="smaw_yes" name="smaw_yes" value="1" {{ old('smaw_yes', $certificate->smaw_yes) ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-bold" for="smaw_yes">SMAW (Shielded Metal Arc Welding)</label>
                                                </div>
                                                <small class="text-muted">This certificate is for SMAW process only</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <div class="card border-info">
                                            <div class="card-header bg-info text-white">Specimen Type</div>
                                            <div class="card-body">
                                                <div class="form-check mb-2">
                                                    <input type="checkbox" class="form-check-input" id="plate_specimen" name="plate_specimen" value="1" {{ old('plate_specimen', $certificate->plate_specimen) ? 'checked' : '' }} onchange="updateSpecimenType(this)">
                                                    <label class="form-check-label" for="plate_specimen">Plate Specimen</label>
                                                </div>
                                                <div class="form-check">
                                                    <input type="checkbox" class="form-check-input" id="pipe_specimen" name="pipe_specimen" value="1" {{ old('pipe_specimen', $certificate->pipe_specimen) ? 'checked' : '' }} onchange="updateSpecimenType(this)">
                                                    <label class="form-check-label" for="pipe_specimen">Pipe Specimen</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <div class="card border-secondary">
                                            <div class="card-header bg-secondary text-white">Thickness Details</div>
                                            <div class="card-body">
                                                <label for="smaw_thickness" class="form-label fw-bold">
                                                    <span class="text-danger">*</span> SMAW Thickness (mm)
                                                </label>
                                                <input type="text" class="form-control @error('smaw_thickness') is-invalid @enderror" id="smaw_thickness" name="smaw_thickness" value="{{ old('smaw_thickness', $certificate->smaw_thickness) }}" required onchange="updateDiaThickness()">
                                                @error('smaw_thickness')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                                <small class="text-muted">Deposit thickness for SMAW process</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Pipe Dimension and Materials Section -->
                    <div class="card mb-3 border-primary">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-ruler-combined"></i> Dimensions & Materials</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="pipe_diameter_type" class="form-label fw-bold">
                                            <span class="text-danger">*</span> Pipe Diameter
                                        </label>
                                        <select class="form-select @error('pipe_diameter_type') is-invalid @enderror" id="pipe_diameter_type" name="pipe_diameter_type" required onchange="updateDiameterRange(); toggleManualEntry('pipe_diameter_type'); updateDiaThickness();">
                                            @foreach($pipeDiameterTypes as $value => $label)
                                                <option value="{{ $value }}" {{ old('pipe_diameter_type', $certificate->pipe_diameter_type) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <input type="text" class="form-control mt-2 @error('pipe_diameter_manual') is-invalid @enderror" id="pipe_diameter_manual" name="pipe_diameter_manual" value="{{ old('pipe_diameter_manual', $certificate->pipe_diameter_manual) }}" style="{{ old('pipe_diameter_type', $certificate->pipe_diameter_type) == '__manual__' ? '' : 'display: none;' }}" placeholder="Manual pipe diameter entry" onchange="updateDiaThickness()">
                                        @error('pipe_diameter_type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        
                                        <div class="mt-3">
                                            <label class="form-label fw-bold">Qualified Diameter Range</label>
                                            <div id="diameter_range" class="form-control-plaintext p-2 border bg-light">{{ $certificate->diameter_range }}</div>
                                            <input type="hidden" name="diameter_range" id="diameter_range_input" value="{{ old('diameter_range', $certificate->diameter_range) }}">
                                            <input type="text" class="form-control @error('diameter_range_manual') is-invalid @enderror" id="diameter_range_manual" name="diameter_range_manual" value="{{ old('diameter_range_manual', $certificate->diameter_range_manual) }}" style="{{ old('pipe_diameter_type', $certificate->pipe_diameter_type) == '__manual__' ? '' : 'display: none;' }}" placeholder="Manual range entry">
                                        </div>
                                    </div>
                                </div>
                        
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="base_metal_p_no" class="form-label fw-bold">
                                            <span class="text-danger">*</span> Base Metal P-Number
                                        </label>
                                        <select class="form-select @error('base_metal_p_no') is-invalid @enderror" id="base_metal_p_no" name="base_metal_p_no" required onchange="updatePNumberRange(); toggleManualEntry('base_metal_p_no')">
                                            @foreach($baseMetalPNumbers as $value => $label)
                                                <option value="{{ $value }}" {{ old('base_metal_p_no', $certificate->base_metal_p_no) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <input type="text" class="form-control mt-2 @error('base_metal_p_no_manual') is-invalid @enderror" id="base_metal_p_no_manual" name="base_metal_p_no_manual" value="{{ old('base_metal_p_no_manual', $certificate->base_metal_p_no_manual) }}" style="{{ old('base_metal_p_no', $certificate->base_metal_p_no) == '__manual__' ? '' : 'display: none;' }}" placeholder="Manual P-Number entry">
                                        @error('base_metal_p_no')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        
                                        <div class="mt-3">
                                            <label class="form-label fw-bold">Qualified P-Number Range</label>
                                            <div id="p_number_range" class="form-control-plaintext p-2 border bg-light">{{ $certificate->p_number_range }}</div>
                                            <input type="hidden" name="p_number_range" id="p_number_range_input" value="{{ old('p_number_range', $certificate->p_number_range) }}">
                                            <input type="text" class="form-control @error('p_number_range_manual') is-invalid @enderror" id="p_number_range_manual" name="p_number_range_manual" value="{{ old('p_number_range_manual', $certificate->p_number_range_manual) }}" style="{{ old('base_metal_p_no', $certificate->base_metal_p_no) == '__manual__' ? '' : 'display: none;' }}" placeholder="Manual P-Number range entry">
                                        </div>
                                    </div>
                                </div>
                        
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="test_position" class="form-label fw-bold">
                                            <span class="text-danger">*</span> Test Position
                                        </label>
                                        <select class="form-select @error('test_position') is-invalid @enderror" id="test_position" name="test_position" required onchange="updatePositionRange()">
                                            @foreach($testPositions as $value => $label)
                                                <option value="{{ $value }}" {{ old('test_position', $certificate->test_position) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('test_position')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        
                                        <div class="mt-3">
                                            <label class="form-label fw-bold">Qualified Position Range</label>
                                            <div id="position_range" class="form-control-plaintext p-2 border bg-light">{{ $certificate->position_range }}</div>
                                            <input type="hidden" name="position_range" id="position_range_input" value="{{ old('position_range', $certificate->position_range) }}">
                                            <input type="text" class="form-control @error('position_range_manual') is-invalid @enderror" id="position_range_manual" name="position_range_manual" value="{{ old('position_range_manual', $certificate->position_range_manual) }}" style="{{ old('test_position', $certificate->test_position) == '__manual__' ? '' : 'display: none;' }}" placeholder="Manual position range entry">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Filler Metal & Backing Section -->
                    <div class="card mb-3 border-primary">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-tools"></i> Filler Metal & Backing</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="backing" class="form-label fw-bold">
                                            <span class="text-danger">*</span> Backing
                                        </label>
                                        <select class="form-select @error('backing') is-invalid @enderror" id="backing" name="backing" required onchange="updateBackingRange(); toggleManualEntry('backing')">
                                            @foreach($backingTypes as $value => $label)
                                                <option value="{{ $value }}" {{ old('backing', $certificate->backing) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <input type="text" class="form-control mt-2 @error('backing_manual') is-invalid @enderror" id="backing_manual" name="backing_manual" value="{{ old('backing_manual', $certificate->backing_manual) }}" style="{{ old('backing', $certificate->backing) == '__manual__' ? '' : 'display: none;' }}" placeholder="Manual backing entry">
                                        @error('backing')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        
                                        <div class="mt-3">
                                            <label class="form-label fw-bold">Qualified Backing Range</label>
                                            <div id="backing_range" class="form-control-plaintext p-2 border bg-light">{{ $certificate->backing_range }}</div>
                                            <input type="hidden" name="backing_range" id="backing_range_input" value="{{ old('backing_range', $certificate->backing_range) }}">
                                            <input type="text" class="form-control @error('backing_range_manual') is-invalid @enderror" id="backing_range_manual" name="backing_range_manual" value="{{ old('backing_range_manual', $certificate->backing_range_manual) }}" style="{{ old('backing', $certificate->backing) == '__manual__' ? '' : 'display: none;' }}" placeholder="Manual backing range entry">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">
                                            <span class="text-danger">*</span> Filler Metal Specification
                                        </label>
                                        <select class="form-select @error('filler_spec') is-invalid @enderror" id="filler_spec" name="filler_spec" required onchange="toggleManualEntry('filler_spec')">
                                            @foreach($fillerSpecs as $value => $label)
                                                <option value="{{ $value }}" {{ old('filler_spec', $certificate->filler_spec) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <input type="text" class="form-control mt-2 @error('filler_spec_manual') is-invalid @enderror" id="filler_spec_manual" name="filler_spec_manual" value="{{ old('filler_spec_manual', $certificate->filler_spec_manual) }}" style="{{ old('filler_spec', $certificate->filler_spec) == '__manual__' ? '' : 'display: none;' }}" placeholder="Manual filler specification entry">
                                        @error('filler_spec')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">
                                            <span class="text-danger">*</span> Filler Metal Classification
                                        </label>
                                        <select class="form-select @error('filler_class') is-invalid @enderror" id="filler_class" name="filler_class" required onchange="toggleManualEntry('filler_class')">
                                            @foreach($fillerClasses as $value => $label)
                                                <option value="{{ $value }}" {{ old('filler_class', $certificate->filler_class) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <input type="text" class="form-control mt-2 @error('filler_class_manual') is-invalid @enderror" id="filler_class_manual" name="filler_class_manual" value="{{ old('filler_class_manual', $certificate->filler_class_manual) }}" style="{{ old('filler_class', $certificate->filler_class) == '__manual__' ? '' : 'display: none;' }}" placeholder="Manual filler classification entry">
                                        @error('filler_class')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="filler_f_no" class="form-label fw-bold">
                                            <span class="text-danger">*</span> Filler F-Number
                                        </label>
                                        <select class="form-select @error('filler_f_no') is-invalid @enderror" id="filler_f_no" name="filler_f_no" required onchange="updateFNumberRange(); toggleManualEntry('filler_f_no')">
                                            @foreach($fillerFNumbers as $value => $label)
                                                <option value="{{ $value }}" {{ old('filler_f_no', $certificate->filler_f_no) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        <input type="text" class="form-control mt-2 @error('filler_f_no_manual') is-invalid @enderror" id="filler_f_no_manual" name="filler_f_no_manual" value="{{ old('filler_f_no_manual', $certificate->filler_f_no_manual) }}" style="{{ old('filler_f_no', $certificate->filler_f_no) == '__manual__' ? '' : 'display: none;' }}" placeholder="Manual F-Number entry">
                                        @error('filler_f_no')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        
                                        <div class="mt-3">
                                            <label class="form-label fw-bold">Qualified F-Number Range</label>
                                            <div id="f_number_range" class="form-control-plaintext p-2 border bg-light">{{ $certificate->f_number_range }}</div>
                                            <input type="hidden" name="f_number_range" id="f_number_range_input" value="{{ old('f_number_range', $certificate->f_number_range) }}">
                                            <input type="text" class="form-control @error('f_number_range_manual') is-invalid @enderror" id="f_number_range_manual" name="f_number_range_manual" value="{{ old('f_number_range_manual', $certificate->f_number_range_manual) }}" style="{{ old('filler_f_no', $certificate->filler_f_no) == '__manual__' ? '' : 'display: none;' }}" placeholder="Manual F-Number range entry">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Vertical Progression Section -->
                    <div class="card mb-3 border-primary">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-arrows-alt-v"></i> Vertical Progression</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="vertical_progression" class="form-label fw-bold">
                                            <span class="text-danger">*</span> Vertical Progression
                                        </label>
                                        <select class="form-select @error('vertical_progression') is-invalid @enderror" id="vertical_progression" name="vertical_progression" required onchange="updateVerticalProgressionRange()">
                                            @foreach($verticalProgressions as $value => $label)
                                                <option value="{{ $value }}" {{ old('vertical_progression', $certificate->vertical_progression) == $value ? 'selected' : '' }}>{{ $label }}</option>
                                            @endforeach
                                        </select>
                                        @error('vertical_progression')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-8">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Qualified Vertical Progression Range</label>
                                        <div id="vertical_progression_range" class="form-control-plaintext p-2 border bg-light">{{ $certificate->vertical_progression_range }}</div>
                                        <input type="hidden" name="vertical_progression_range" id="vertical_progression_range_input" value="{{ old('vertical_progression_range', $certificate->vertical_progression_range) }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Test Results Section -->
                    <div class="card mb-3 border-primary">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-check-circle"></i> Test Results & Authorization</h5>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label class="form-label fw-bold">Test Result</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="test_result" id="test_result_pass" value="1" {{ old('test_result', $certificate->test_result) ? 'checked' : '' }}>
                                            <label class="form-check-label text-success fw-bold" for="test_result_pass">
                                                <i class="fas fa-check-circle"></i> Pass
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="test_result" id="test_result_fail" value="0" {{ old('test_result', $certificate->test_result) === false ? 'checked' : '' }}>
                                            <label class="form-check-label text-danger fw-bold" for="test_result_fail">
                                                <i class="fas fa-times-circle"></i> Fail
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="inspector_name" class="form-label fw-bold">Inspector Name</label>
                                        <input type="text" class="form-control @error('inspector_name') is-invalid @enderror" id="inspector_name" name="inspector_name" value="{{ old('inspector_name', $certificate->inspector_name) }}">
                                        @error('inspector_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="inspector_date" class="form-label fw-bold">Inspection Date</label>
                                        <input type="date" class="form-control @error('inspector_date') is-invalid @enderror" id="inspector_date" name="inspector_date" value="{{ old('inspector_date', $certificate->inspector_date ? $certificate->inspector_date->format('Y-m-d') : now()->format('Y-m-d')) }}">
                                        @error('inspector_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row mt-3">
                                <div class="col-12">
                                    <div class="alert alert-secondary">
                                        <h6 class="fw-bold"><i class="fas fa-certificate"></i> Certification Statement</h6>
                                        <p class="mb-0 small">
                                            We certify that the statements in this record are correct and that the test welds were prepared, welded, and 
                                            tested in accordance with the requirements of ASME Section IX of the ASME BOILER AND PRESSURE VESSEL CODE.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form buttons -->
                    <div class="form-buttons bg-light border p-3 text-center">
                        <button type="submit" class="btn btn-primary" id="saveBtn">
                            <i class="fas fa-save"></i> Update Certificate
                        </button>
                        <button type="button" class="btn btn-secondary" id="previewBtn" onclick="previewForm()">
                            <i class="fas fa-eye"></i> Preview
                        </button>
                        <a href="{{ route('smaw-certificates.show', $certificate->id) }}" class="btn btn-info">
                            <i class="fas fa-file-alt"></i> View Certificate
                        </a>
                        <a href="{{ route('smaw-certificates.index') }}" class="btn btn-danger">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                        <div class="mt-2 text-muted small">
                            <span class="me-3"><i class="fas fa-keyboard"></i> Ctrl+S to save</span>
                            <span><i class="fas fa-keyboard"></i> Ctrl+P to preview</span>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize form fields and state
        updateDiameterRange();
        updatePNumberRange();
        updatePositionRange();
        updateBackingRange();
        updateFNumberRange();
        updateVerticalProgressionRange();
        updateDiaThickness();
        
        // Add keyboard shortcuts
        document.addEventListener('keydown', function(event) {
            // Ctrl+S to save
            if (event.ctrlKey && event.key === 's') {
                event.preventDefault();
                document.getElementById('saveBtn').click();
            }
            
            // Ctrl+P to preview
            if (event.ctrlKey && event.key === 'p') {
                event.preventDefault();
                previewForm();
            }
        });
    });
    
    /**
     * Preview the certificate form
     */
    function previewForm() {
        // Save form data to session storage
        let formData = new FormData(document.getElementById('welderQualificationForm'));
        let formDataObj = {};
        formData.forEach((value, key) => {
            formDataObj[key] = value;
        });
        sessionStorage.setItem('smawCertificateFormData', JSON.stringify(formDataObj));
        
        // Open preview in new window/tab
        window.open('{{ route("smaw-certificates.preview") }}', '_blank');
    }
    
    /**
     * Load welder data when a welder is selected
     */
    function loadWelderData() {
        const welderId = document.getElementById('welder_id').value;
        if (!welderId) {
            // Clear fields if no welder selected
            document.getElementById('welder_id_display').textContent = '';
            document.getElementById('iqama_display').textContent = '';
            document.getElementById('passport_display').textContent = '';
            return;
        }
        
        // Fetch welder data via AJAX
        fetch(`/api/welders/${welderId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('welder_id_display').textContent = data.welder_no || '';
                document.getElementById('welder_id_no').value = data.welder_no || '';
                document.getElementById('iqama_display').textContent = data.iqama_no || '';
                document.getElementById('passport_display').textContent = data.passport_no || '';
                
                // Auto-select company if not already selected
                const companySelect = document.getElementById('company_id');
                if (companySelect && !companySelect.value && data.company_id) {
                    companySelect.value = data.company_id;
                }
                
                // If welder has photo, show it in preview
                if (data.photo_path) {
                    document.getElementById('photo-preview').innerHTML = `<img src="/storage/${data.photo_path}" class="img-fluid" style="max-height: 110px;">`;
                }
            })
            .catch(error => {
                console.error('Error loading welder data:', error);
            });
    }
    
    /**
     * Update diameter range based on pipe diameter selection
     */
    function updateDiameterRange() {
        const diameterType = document.getElementById('pipe_diameter_type').value;
        const rangeDisplay = document.getElementById('diameter_range');
        const rangeInput = document.getElementById('diameter_range_input');
        
        let rangeText = '';
        switch (diameterType) {
            case '8_nps':
                rangeText = 'Pipe of diameter ≥ 219.1 mm (8" NPS)';
                break;
            case '6_nps':
                rangeText = 'Pipe of diameter ≥ 168.3 mm (6" NPS)';
                break;
            case '4_nps':
                rangeText = 'Pipe of diameter ≥ 114.3 mm (4" NPS)';
                break;
            case '2_nps':
                rangeText = 'Pipe of diameter ≥ 60.3 mm (2" NPS)';
                break;
            case '__manual__':
                document.getElementById('diameter_range_manual').style.display = 'block';
                rangeText = 'Manual entry - specify range';
                break;
            default:
                rangeText = 'Not specified';
        }
        
        rangeDisplay.textContent = rangeText;
        rangeInput.value = rangeText;
        updateDiaThickness();
    }
    
    /**
     * Update P-Number range based on base metal selection
     */
    function updatePNumberRange() {
        const pNumberType = document.getElementById('base_metal_p_no').value;
        const rangeDisplay = document.getElementById('p_number_range');
        const rangeInput = document.getElementById('p_number_range_input');
        
        let rangeText = '';
        switch (pNumberType) {
            case 'P NO.1 TO P NO.1':
                rangeText = 'P-No.1 Group 1 or 2';
                break;
            case 'P NO.3 TO P NO.3':
                rangeText = 'P-No.3 Group 1 or 2';
                break;
            case 'P NO.4 TO P NO.4':
                rangeText = 'P-No.4 Group 1 or 2';
                break;
            case 'P NO.5A TO P NO.5A':
                rangeText = 'P-No.5A Group 1 or 2';
                break;
            case 'P NO.8 TO P NO.8':
                rangeText = 'P-No.8 Group 1 or 2';
                break;
            case '__manual__':
                document.getElementById('p_number_range_manual').style.display = 'block';
                rangeText = 'Manual entry - specify range';
                break;
            default:
                rangeText = 'Not specified';
        }
        
        rangeDisplay.textContent = rangeText;
        rangeInput.value = rangeText;
    }
    
    /**
     * Update position range based on test position selection
     */
    function updatePositionRange() {
        const testPosition = document.getElementById('test_position').value;
        const isPipeSpecimen = document.getElementById('pipe_specimen').checked;
        const rangeDisplay = document.getElementById('position_range');
        const rangeInput = document.getElementById('position_range_input');
        
        let rangeText = '';
        if (isPipeSpecimen) {
            switch (testPosition) {
                case '1G':
                    rangeText = '1G Groove Pipe | All Position Fillet Pipe';
                    break;
                case '2G':
                    rangeText = '1G, 2G Groove Pipe | All Position Fillet Pipe';
                    break;
                case '5G':
                    rangeText = '1G, 5G Groove Pipe | All Position Fillet Pipe';
                    break;
                case '6G':
                    rangeText = 'All Position Groove Plate and Pipe Over 24 in. (610 mm) O.D. | All Position Groove Pipe ≤24 in. (610 mm) O.D. | All Position Fillet or Tack Plate and Pipe';
                    break;
                case '3G':
                    rangeText = '1G, 3G Groove Pipe | All Position Fillet Pipe';
                    break;
                case '4G':
                    rangeText = '1G, 4G Groove Pipe | All Position Fillet Pipe';
                    break;
                default:
                    rangeText = 'Not specified';
            }
        } else {
            // Plate specimen
            switch (testPosition) {
                case '1G':
                    rangeText = '1G Groove Plate | 1F Fillet Plate';
                    break;
                case '2G':
                    rangeText = '1G, 2G Groove Plate | 1F, 2F Fillet Plate';
                    break;
                case '3G':
                    rangeText = '1G, 3G Groove Plate | 1F, 2F, 3F Fillet Plate';
                    break;
                case '4G':
                    rangeText = '1G, 4G Groove Plate | 1F, 2F, 4F Fillet Plate';
                    break;
                default:
                    rangeText = 'Not specified';
            }
        }
        
        rangeDisplay.textContent = rangeText;
        rangeInput.value = rangeText;
    }
    
    /**
     * Update backing range based on backing selection
     */
    function updateBackingRange() {
        const backing = document.getElementById('backing').value;
        const rangeDisplay = document.getElementById('backing_range');
        const rangeInput = document.getElementById('backing_range_input');
        
        let rangeText = '';
        switch (backing) {
            case 'With Backing':
                rangeText = 'With backing or backing and gouging';
                break;
            case 'Without Backing':
                rangeText = 'With backing or backing and gouging | Without backing | Without backing and gouging';
                break;
            case '__manual__':
                document.getElementById('backing_range_manual').style.display = 'block';
                rangeText = 'Manual entry - specify range';
                break;
            default:
                rangeText = 'Not specified';
        }
        
        rangeDisplay.textContent = rangeText;
        rangeInput.value = rangeText;
    }
    
    /**
     * Update F-Number range based on filler F-Number selection
     */
    function updateFNumberRange() {
        const fillerFNo = document.getElementById('filler_f_no').value;
        const rangeDisplay = document.getElementById('f_number_range');
        const rangeInput = document.getElementById('f_number_range_input');
        
        let rangeText = '';
        switch (fillerFNo) {
            case 'F4_with_backing':
                rangeText = 'F-No.4 Only';
                break;
            case 'F5_with_backing':
                rangeText = 'F-No.5 Only';
                break;
            case 'F4_without_backing':
                rangeText = 'F-No.4 Only';
                break;
            case 'F5_without_backing':
                rangeText = 'F-No.5 Only';
                break;
            case 'F43':
                rangeText = 'F-No.43 Only';
                break;
            case '__manual__':
                document.getElementById('f_number_range_manual').style.display = 'block';
                rangeText = 'Manual entry - specify range';
                break;
            default:
                rangeText = 'Not specified';
        }
        
        rangeDisplay.textContent = rangeText;
        rangeInput.value = rangeText;
    }
    
    /**
     * Update vertical progression range
     */
    function updateVerticalProgressionRange() {
        const verticalProgression = document.getElementById('vertical_progression').value;
        const rangeDisplay = document.getElementById('vertical_progression_range');
        const rangeInput = document.getElementById('vertical_progression_range_input');
        
        let rangeText = '';
        switch (verticalProgression) {
            case 'Uphill':
                rangeText = 'Uphill only';
                break;
            case 'Downhill':
                rangeText = 'Downhill only';
                break;
            default:
                rangeText = 'Not specified';
        }
        
        rangeDisplay.textContent = rangeText;
        rangeInput.value = rangeText;
    }
    
    /**
     * Toggle manual entry fields based on dropdown selection
     */
    function toggleManualEntry(fieldName) {
        const selectElement = document.getElementById(fieldName);
        const manualElement = document.getElementById(fieldName + '_manual');
        
        if (selectElement.value === '__manual__') {
            manualElement.style.display = 'block';
        } else {
            manualElement.style.display = 'none';
            manualElement.value = '';
        }
    }
    
    /**
     * Update specimen type selection
     */
    function updateSpecimenType(checkbox) {
        const plateCheckbox = document.getElementById('plate_specimen');
        const pipeCheckbox = document.getElementById('pipe_specimen');
        
        // Ensure at least one specimen type is selected
        if (!plateCheckbox.checked && !pipeCheckbox.checked) {
            checkbox.checked = true;
            alert('At least one specimen type must be selected.');
        }
        
        // Update position range based on specimen type
        updatePositionRange();
    }
    
    /**
     * Preview the photo upload
     */
    function previewPhoto(input) {
        const preview = document.getElementById('photo-preview');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" class="img-fluid" style="max-height: 110px;">`;
            };
            
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    /**
     * Update diameter/thickness display
     */
    function updateDiaThickness() {
        const diameterType = document.getElementById('pipe_diameter_type').value;
        const diameterManual = document.getElementById('pipe_diameter_manual').value;
        const thickness = document.getElementById('smaw_thickness').value;
        const display = document.getElementById('dia_thickness_display');
        
        let diameter = '';
        
        switch (diameterType) {
            case '8_nps':
                diameter = '8 inch (219.1 mm)';
                break;
            case '6_nps':
                diameter = '6 inch (168.3 mm)';
                break;
            case '4_nps':
                diameter = '4 inch (114.3 mm)';
                break;
            case '2_nps':
                diameter = '2 inch (60.3 mm)';
                break;
            case '__manual__':
                diameter = diameterManual;
                break;
        }
        
        display.value = diameter + '/' + thickness + ' mm';
    }
</script>
@endpush
