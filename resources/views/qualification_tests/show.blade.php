@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Qualification Details: {{ $qualification->cert_no }}</h1>        <div>
            @if($qualification->test_result)
                @if(Auth::user()->role === 'qc' || Auth::user()->role === 'admin')
                <a href="{{ route('qualification-tests.card', $qualification->id) }}" class="btn btn-success me-2" target="_blank">
                    <i class="fas fa-id-card"></i> View Qualification Card
                </a>
                <a href="{{ route('qualification-tests.certificate', $qualification->id) }}" class="btn btn-warning me-2" target="_blank">
                    <i class="fas fa-certificate"></i> View Certificate
                </a>
                @else
                <button class="btn btn-secondary me-2" disabled title="Only QC personnel can generate qualification cards">
                    <i class="fas fa-id-card"></i> Qualification Card (Requires QC Approval)
                </button>
                <button class="btn btn-secondary me-2" disabled title="Only QC personnel can generate certificates">
                    <i class="fas fa-certificate"></i> Certificate (Requires QC Approval)
                </button>
                @endif
            @elseif(Auth::user()->role === 'user')
                <button class="btn btn-secondary me-2" disabled title="Test result must be Pass to generate qualification card">
                    <i class="fas fa-id-card"></i> Qualification Card (Pending QC Approval)
                </button>
                <button class="btn btn-secondary me-2" disabled title="Test result must be Pass to generate certificate">
                    <i class="fas fa-certificate"></i> Certificate (Pending QC Approval)
                </button>
            @endif
            <a href="{{ route('qualification-tests.edit', $qualification->id) }}" class="btn btn-primary me-2">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('qualification-tests.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Qualifications
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Welder Information -->
        <div class="col-md-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-primary text-white py-3">
                    <h6 class="m-0 font-weight-bold">Welder Information</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        @if($qualification->welder->photo)
                            <img src="{{ asset('storage/' . $qualification->welder->photo) }}" alt="{{ $qualification->welder->name }}" class="img-profile rounded-circle img-thumbnail" style="width: 120px; height: 120px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-secondary d-flex justify-content-center align-items-center mx-auto" style="width: 120px; height: 120px;">
                                <i class="fas fa-user fa-4x text-white"></i>
                            </div>
                        @endif
                        <h4 class="font-weight-bold mt-3">{{ $qualification->welder->name }}</h4>
                        <div class="badge bg-primary">{{ $qualification->welder->company->name ?? 'No Company' }}</div>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th style="width: 40%;">Welder No:</th>
                                <td>{{ $qualification->welder_no ?? $qualification->welder->welder_no ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Passport/ID No:</th>
                                <td>{{ $qualification->passport_id_no ?? $qualification->welder->iqama_no ?? 'N/A' }}</td>
                            </tr>
                            @if(isset($qualification->welder->company))
                            <tr>
                                <th>Company:</th>
                                <td>
                                    <a href="{{ route('companies.show', $qualification->welder->company->id) }}">
                                        {{ $qualification->welder->company->name }}
                                    </a>
                                </td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    
                    <div class="d-grid">
                        <a href="{{ route('welders.show', $qualification->welder->id) }}" class="btn btn-outline-primary">
                            <i class="fas fa-user"></i> View Welder Details
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Qualification Status -->
        <div class="col-md-8 mb-4">
            <div class="card shadow h-100">
                <div class="card-header bg-primary text-white py-3">
                    <h6 class="m-0 font-weight-bold">Qualification Status</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card border-left-primary shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                                Certificate Number</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $qualification->cert_no }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-id-card fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card h-100 py-2 
                                @if(!$qualification->is_active)
                                    border-left-secondary                                @elseif($qualification->getTestDate() && $qualification->getTestDate()->addMonths(24) < now())
                                    border-left-danger
                                @elseif($qualification->getTestDate() && $qualification->getTestDate()->addMonths(24) <= now()->addDays(30))
                                    border-left-warning
                                @else
                                    border-left-success
                                @endif
                                shadow">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-uppercase mb-1
                                                @if(!$qualification->is_active)
                                                    text-secondary
                                                @elseif($qualification->vt_date && $qualification->vt_date->addMonths(24) < now())
                                                    text-danger
                                                @elseif($qualification->vt_date && $qualification->vt_date->addMonths(24) <= now()->addDays(30))
                                                    text-warning
                                                @else
                                                    text-success
                                                @endif">
                                                Status</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                @if(!$qualification->is_active)
                                                    Inactive                                                @elseif($qualification->getTestDate() && $qualification->getTestDate()->addMonths(24) < now())
                                                    Expired
                                                @elseif($qualification->getTestDate() && $qualification->getTestDate()->addMonths(24) <= now()->addDays(30))
                                                    Expiring Soon
                                                @else
                                                    Active
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            @if(!$qualification->is_active)
                                                <i class="fas fa-ban fa-2x text-gray-300"></i>
                                            @elseif($qualification->vt_date && $qualification->vt_date->addMonths(24) < now())
                                                <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                                            @elseif($qualification->vt_date && $qualification->vt_date->addMonths(24) <= now()->addDays(30))
                                                <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                                            @else
                                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-4">
                                <h6 class="font-weight-bold">Test Date</h6>
                                <p>{{ $qualification->getTestDate() ? $qualification->getTestDate()->format('M d, Y') : 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-4">                                <h6 class="font-weight-bold">Expiry Date</h6>
                                <p>{{ $qualification->getTestDate() ? $qualification->getTestDate()->addMonths(24)->format('M d, Y') : 'N/A' }}</p>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-4">
                                <h6 class="font-weight-bold">Qualification Code</h6>
                                <p>{{ $qualification->qualification_code ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Test Details -->
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h6 class="m-0 font-weight-bold">Test Details</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th style="width: 40%;">WPS No:</th>
                                <td>{{ $qualification->wps_no ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Welding Process:</th>
                                <td>{{ $qualification->welding_process ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Test Coupon:</th>
                                <td>{{ $qualification->test_coupon ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Test Position:</th>
                                <td>{{ $qualification->welding_positions ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Position Qualified:</th>
                                <td>{{ $qualification->qualified_position ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Dia (inch):</th>
                                <td>{{ $qualification->dia_inch ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Test Thickness (mm):</th>
                                <td>{{ $qualification->coupon_thickness_mm ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Deposit Thickness:</th>
                                <td>{{ $qualification->deposit_thickness ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Filler Metal F No:</th>
                                <td>{{ $qualification->filler_metal_f_no ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>AWS Spec No:</th>
                                <td>{{ $qualification->aws_spec_no ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Filler Metal Classification:</th>
                                <td>{{ $qualification->filler_metal_classif ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Joint Details -->
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h6 class="m-0 font-weight-bold">Joint Details</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-12 text-center">
                            <h6 class="font-weight-bold mb-3">Joint Diagram</h6>
                            <div style="border:1px solid #ddd; padding:10px; background:#f9f9f9; margin-bottom:15px; display:flex; justify-content:center; min-height:200px;">
                            @if(isset($qualification->joint_diagram_path))
                                <img src="{{ asset('storage/' . $qualification->joint_diagram_path) }}" alt="Joint Diagram" style="max-height:200px; max-width:100%; object-fit:contain;">
                            @else
                                <svg viewBox="0 0 120 170" xmlns="http://www.w3.org/2000/svg" style="height:180px; max-width:100%;">
                                    <rect x="15" y="20" width="18" height="120" fill="none" stroke="black" stroke-width="1.5"/>
                                    <rect x="87" y="20" width="18" height="120" fill="none" stroke="black" stroke-width="1.5"/>
                                    <path d="M 33 20 L 45 35 L 45 125 L 33 140 Z" fill="none" stroke="black" stroke-width="1.5"/>
                                    <path d="M 87 20 L 75 35 L 75 125 L 87 140 Z" fill="none" stroke="black" stroke-width="1.5"/>
                                    <path d="M 45 35 L 75 35 L 75 125 L 45 125 Z" fill="#e0e0e0" stroke="black" stroke-width="1"/>
                                    <line x1="45" y1="80" x2="75" y2="80" stroke="white" stroke-width="1.5"/> 
                                    <line x1="45" y1="81.5" x2="75" y2="81.5" stroke="white" stroke-width="1.5"/>
                                    <line x1="10" y1="20" x2="10" y2="140" stroke="black" stroke-width="0.5"/>
                                    <line x1="7" y1="20" x2="13" y2="20" stroke="black" stroke-width="0.5"/>
                                    <line x1="7" y1="140" x2="13" y2="140" stroke="black" stroke-width="0.5"/>
                                    <path d="M 45 35 q 10 5 10 15" fill="none" stroke="red" stroke-width="0.7"/>
                                    <text x="50" y="45" font-size="6" fill="red" dominant-baseline="middle" text-anchor="start">{{ $qualification->joint_angle ?? '30°' }}</text>
                                    <text x="60" y="30" font-size="6" fill="red" dominant-baseline="middle" text-anchor="middle">{{ $qualification->joint_total_angle ?? '60° Total' }}</text>
                                    <text x="60" y="15" text-anchor="middle" font-size="8" font-weight="bold">{{ $qualification->joint_type ?? 'SINGLE V-GROOVE' }}</text>
                                    <text x="60" y="158" text-anchor="middle" font-size="7">{{ $qualification->joint_description ?? 'BUTT JOINT (PIPE)' }}</text>
                                    <text x="3" y="80" text-anchor="middle" font-size="5" transform="rotate(-90 3 80)">{{ $qualification->coupon_thickness_mm ?? '14.27' }}mm (T)</text>
                                    <text x="60" y="78" text-anchor="middle" font-size="5" fill="blue">Root Gap: {{ $qualification->root_gap ?? '2-3mm' }}</text>
                                    <text x="45" y="135" text-anchor="end" font-size="5" fill="blue">Root Face: {{ $qualification->root_face ?? '1-2mm' }}</text>
                                </svg>
                            @endif
                            </div>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th style="width: 40%;">Joint Type:</th>
                                <td>{{ $qualification->joint_type ?? 'Single V-Groove' }}</td>
                            </tr>
                            <tr>
                                <th>Joint Description:</th>
                                <td>{{ $qualification->joint_description ?? 'Butt Joint (Pipe)' }}</td>
                            </tr>
                            <tr>
                                <th>Joint Angle:</th>
                                <td>{{ $qualification->joint_angle ?? '30°' }}</td>
                            </tr>
                            <tr>
                                <th>Total Angle:</th>
                                <td>{{ $qualification->joint_total_angle ?? '60°' }}</td>
                            </tr>
                            <tr>
                                <th>Root Gap:</th>
                                <td>{{ $qualification->root_gap ?? '2-3mm' }}</td>
                            </tr>
                            <tr>
                                <th>Root Face:</th>
                                <td>{{ $qualification->root_face ?? '1-2mm' }}</td>
                            </tr>
                            <tr>
                                <th>Pipe Outer Diameter:</th>
                                <td>{{ $qualification->pipe_outer_diameter ?? '168.28 mm (6 Inch Sch.80)' }}</td>
                            </tr>
                            <tr>
                                <th>Base Metal P-No:</th>
                                <td>{{ $qualification->base_metal_p_no ?? 'P-No.1 Gr.1 to P-No.1 Gr.1' }}</td>
                            </tr>
                            <tr>
                                <th>Filler Metal Form:</th>
                                <td>{{ $qualification->filler_metal_form ?? 'Solid Wire / Coated Electrode' }}</td>
                            </tr>
                            <tr>
                                <th>Inert Gas Backing:</th>
                                <td>{{ $qualification->inert_gas_backing ?? 'Not Used' }}</td>
                            </tr>
                            <tr>
                                <th>Vertical Progression:</th>
                                <td>{{ $qualification->vertical_progression ?? 'Uphill' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Qualification Ranges -->
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h6 class="m-0 font-weight-bold">Qualification Ranges</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th style="width: 40%;">Coupon Material:</th>
                                <td>{{ $qualification->coupon_material ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Qualified Material:</th>
                                <td>{{ $qualification->qualified_material ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Qualified Dia (inch):</th>
                                <td>{{ $qualification->qualified_dia_inch ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Qualified Thickness Range:</th>
                                <td>{{ $qualification->qualified_thickness_range ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Backing:</th>
                                <td>{{ $qualification->backing ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Qualified Backing:</th>
                                <td>{{ $qualification->qualified_backing ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Electric Characteristics:</th>
                                <td>{{ $qualification->electric_char ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Qualified Electric Char.:</th>
                                <td>{{ $qualification->qualified_ec ?? 'N/A' }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            
            <!-- Test Result Information -->
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white py-3">
                    <h6 class="m-0 font-weight-bold">Test Result Information</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">                            <tr>
                                <th style="width: 40%;">Test Date:</th>
                                <td>{{ $qualification->test_date ? $qualification->test_date->format('M d, Y') : ($qualification->vt_date ? $qualification->vt_date->format('M d, Y') : 'N/A') }}</td>
                            </tr>
                            <tr>
                                <th>VT Report No:</th>
                                <td>{{ $qualification->vt_report_no ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>VT Result:</th>
                                <td>
                                    @if($qualification->vt_result)
                                        <span class="badge {{ $qualification->vt_result === 'ACC' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $qualification->vt_result }}
                                        </span>
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>RT Date:</th>
                                <td>{{ $qualification->rt_date ? $qualification->rt_date->format('M d, Y') : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>RT Report No:</th>
                                <td>{{ $qualification->rt_report_no ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>RT Result:</th>
                                <td>
                                    @if($qualification->rt_result)
                                        <span class="badge {{ $qualification->rt_result === 'ACC' ? 'bg-success' : 'bg-danger' }}">
                                            {{ $qualification->rt_result }}
                                        </span>
                                    @else
                                        N/A
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Test Result:</th>
                                <td>
                                    <span class="badge {{ $qualification->test_result ? 'bg-success' : 'bg-danger' }}">
                                        {{ $qualification->test_result ? 'Pass' : 'Fail' }}
                                    </span>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($qualification->remarks)
    <div class="card shadow mb-4">
        <div class="card-header bg-primary text-white py-3">
            <h6 class="m-0 font-weight-bold">Remarks</h6>
        </div>
        <div class="card-body">
            {{ $qualification->remarks }}
        </div>
    </div>
    @endif

</div>
@endsection
