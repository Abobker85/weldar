@extends('layouts.app')

@section('title', 'View GTAW Certificate')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">GTAW Certificate: {{ $certificate->certificate_no }}</h1>
        <div>
            <a href="{{ route('gtaw-certificates.index') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Certificates
            </a>
            <a href="{{ route('gtaw-certificates.edit', $certificate->id) }}" class="btn btn-sm btn-info">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('gtaw-certificates.certificate', $certificate->id) }}" class="btn btn-sm btn-success" target="_blank">
                <i class="fas fa-file-pdf"></i> Generate Certificate
            </a>
            <a href="{{ route('gtaw-certificates.card', $certificate->id) }}" class="btn btn-sm btn-warning" target="_blank">
                <i class="fas fa-id-card"></i> Generate Card
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Certificate Details Card -->
        <div class="col-xl-8 col-lg-7">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Certificate Details</h6>
                    <span class="badge {{ $certificate->test_result ? 'badge-success' : 'badge-danger' }}">
                        {{ $certificate->test_result ? 'PASS' : 'FAIL' }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 font-weight-bold">Certificate No:</p>
                            <p>{{ $certificate->certificate_no }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 font-weight-bold">Test Date:</p>
                            <p>{{ $certificate->test_date ? $certificate->test_date->format('Y-m-d') : 'N/A' }}</p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 font-weight-bold">WPS Followed:</p>
                            <p>{{ $certificate->wps_followed }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 font-weight-bold">Base Metal Spec:</p>
                            <p>{{ $certificate->base_metal_spec }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 font-weight-bold">Base Metal P-No:</p>
                            <p>{{ $certificate->base_metal_p_no }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 font-weight-bold">GTAW Thickness:</p>
                            <p>{{ $certificate->gtaw_thickness }}</p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 font-weight-bold">Test Type:</p>
                            <p>
                                @if($certificate->test_coupon) Test Coupon @endif
                                @if($certificate->test_coupon && $certificate->production_weld), @endif
                                @if($certificate->production_weld) Production Weld @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 font-weight-bold">Specimen Type:</p>
                            <p>
                                @if($certificate->plate) Plate @endif
                                @if($certificate->plate && $certificate->pipe), @endif
                                @if($certificate->pipe) Pipe @endif
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 font-weight-bold">Test Position:</p>
                            <p>{{ $certificate->test_position }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 font-weight-bold">Backing:</p>
                            <p>{{ $certificate->backing }}</p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 font-weight-bold">Filler Spec:</p>
                            <p>{{ $certificate->filler_spec }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 font-weight-bold">Filler Class:</p>
                            <p>{{ $certificate->filler_class }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 font-weight-bold">Filler F-No:</p>
                            <p>{{ $certificate->filler_f_no }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 font-weight-bold">Vertical Progression:</p>
                            <p>{{ $certificate->vertical_progression }}</p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <!-- Test Results Section -->
                    <h5 class="font-weight-bold text-primary mb-3">Test Results</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 font-weight-bold">VT Report No:</p>
                            <p>{{ $certificate->vt_report_no }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 font-weight-bold">RT Report No:</p>
                            <p>{{ $certificate->rt_report_no }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 font-weight-bold">Visual Examination:</p>
                            <p>{{ $certificate->visual_examination_result ?? 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 font-weight-bold">RT:</p>
                            <p>{{ $certificate->rt ? 'Yes' : 'No' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 font-weight-bold">UT:</p>
                            <p>{{ $certificate->ut ? 'Yes' : 'No' }}</p>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <!-- Personnel Section -->
                    <h5 class="font-weight-bold text-primary mb-3">Personnel</h5>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 font-weight-bold">Inspector Name:</p>
                            <p>{{ $certificate->inspector_name }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 font-weight-bold">Inspector Date:</p>
                            <p>{{ $certificate->inspector_date ? $certificate->inspector_date->format('Y-m-d') : 'N/A' }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 font-weight-bold">Evaluated By:</p>
                            <p>{{ $certificate->evaluated_by }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 font-weight-bold">Evaluated Company:</p>
                            <p>{{ $certificate->evaluated_company }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 font-weight-bold">Mechanical Tests By:</p>
                            <p>{{ $certificate->mechanical_tests_by }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 font-weight-bold">Lab Test No:</p>
                            <p>{{ $certificate->lab_test_no }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 font-weight-bold">Supervised By:</p>
                            <p>{{ $certificate->supervised_by }}</p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <p class="mb-1 font-weight-bold">Supervised Company:</p>
                            <p>{{ $certificate->supervised_company }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Welder Info Card -->
        <div class="col-xl-4 col-lg-5">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Welder Information</h6>
                </div>
                <div class="card-body text-center">
                    @if($certificate->welder && $certificate->photo_path)
                        <img src="{{ asset('storage/' . $certificate->photo_path) }}" 
                             alt="{{ $certificate->welder->name }}" 
                             class="img-fluid rounded-circle mb-3" 
                             style="max-width: 150px; max-height: 150px;">
                    @else
                        <div class="rounded-circle bg-gray-300 mb-3 mx-auto d-flex align-items-center justify-content-center" 
                             style="width: 150px; height: 150px;">
                            <i class="fas fa-user fa-4x text-gray-500"></i>
                        </div>
                    @endif
                    
                    <h5 class="mb-0 font-weight-bold">
                        {{ $certificate->welder->name ?? 'Unknown Welder' }}
                    </h5>
                    <p class="text-muted mb-3">
                        ID: {{ $certificate->welder->welder_no ?? 'N/A' }}
                    </p>
                    
                    <hr>
                    
                    <div class="text-left">
                        <p>
                            <strong>Company:</strong> 
                            {{ $certificate->welder->company->name ?? $certificate->company->name ?? 'N/A' }}
                        </p>
                        <p>
                            <strong>Passport No:</strong> 
                            {{ $certificate->welder->passport_id_no ?? 'N/A' }}
                        </p>
                        <p>
                            <strong>Iqama No:</strong> 
                            {{ $certificate->welder->iqama_no ?? 'N/A' }}
                        </p>
                    </div>
                </div>
            </div>
            
            <!-- Verification Info Card -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Verification</h6>
                </div>
                <div class="card-body text-center">
                    <p>Scan the QR code to verify this certificate:</p>
                    <div class="mb-3">
                        {!! QrCode::size(150)->generate(route('gtaw-certificates.verify', ['id' => $certificate->id, 'code' => $certificate->verification_code])) !!}
                    </div>
                    <p class="mb-0 small text-muted">
                        Verification Code: {{ $certificate->verification_code }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
