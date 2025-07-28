@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Welder Details: {{ $welder->name }}</h1>
        <div>
            <a href="{{ route('qualification-tests.create', ['welder_no' => $welder->id]) }}" class="btn btn-success me-2">
                <i class="fas fa-certificate"></i> Add Qualification
            </a>
            <a href="{{ route('welders.edit', $welder->id) }}" class="btn btn-primary me-2">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('welders.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Welders
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Welder Information</h6>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        @if($welder->photo)
                            <img src="{{ asset('storage/' . $welder->photo) }}" alt="{{ $welder->name }}" class="img-profile rounded-circle img-thumbnail" style="width: 150px; height: 150px; object-fit: cover;">
                        @else
                            <div class="rounded-circle bg-secondary d-flex justify-content-center align-items-center mx-auto" style="width: 150px; height: 150px;">
                                <i class="fas fa-user fa-5x text-white"></i>
                            </div>
                        @endif
                        <h4 class="font-weight-bold mt-3">{{ $welder->name }}</h4>
                        <div class="badge bg-primary">{{ $welder->company->name }}</div>
                    </div>

                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th style="width: 40%;">Welder ID:</th>
                                <td>{{ $welder->welder_no }}</td>
                            </tr>
                            <tr>
                                <th>Iqama No:</th>
                                <td>{{ $welder->iqama_no }}</td>
                            </tr>
                            <tr>
                                <th>Passport ID No:</th>
                                <td>{{ $welder->passport_id_no }}</td>
                            </tr>
                            <tr>
                                <th>Nationality:</th>
                                <td>{{ $welder->nationality }}</td>
                            </tr>
                            <tr>
                                <th>Company:</th>
                                <td>{{ $welder->company->name }}</td>
                            </tr>
                            @if($welder->additional_info)
                                <tr>
                                    <th>Additional Info:</th>
                                    <td>{{ $welder->additional_info }}</td>
                                </tr>
                            @endif
                            <tr>
                                <th>RT Report Serial:</th>
                                <td>{{ $welder->rt_report_serial }}</td>
                            </tr>
                             @if($welder->rt_report)
                                    <a href="{{ asset('storage/' . $welder->rt_report) }}" class="btn btn-sm btn-warning me-1" target="_blank">
                                        <i class="fas fa-file-alt"></i> RT Report
                                    </a>
                                @endif
                                
                            @if($welder->ut_report_serial)
                            <tr>
                                <th>UT Report Serial:</th>
                                <td>{{ $welder->ut_report_serial }}</td>
                            </tr>
                            @endif
                             @if($welder->ut_report)
                                    <a href="{{ asset('storage/' . $welder->ut_report) }}" class="btn btn-sm btn-info me-1" target="_blank">
                                        <i class="fas fa-file-alt"></i> UT Report
                                    </a>
                                @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Qualification Status</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card border-left-success shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                                Active Qualifications</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $welder->qualificationTests->where('is_active', true)->where('date_of_expiry', '>=', now())->count() }}</div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 col-md-6 mb-4">
                            <div class="card border-left-warning shadow h-100 py-2">
                                <div class="card-body">
                                    <div class="row no-gutters align-items-center">
                                        <div class="col mr-2">
                                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                                Expiring Soon (30 Days)</div>
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                {{ $welder->qualificationTests->where('is_active', true)->where('date_of_expiry', '>=', now())->where('date_of_expiry', '<=', now()->addDays(30))->count() }}
                                            </div>
                                        </div>
                                        <div class="col-auto">
                                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Projects for Company</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        @php
                            $projects = $welder->company->projects ?? collect();
                        @endphp
                        @forelse($projects as $project)
                            <a href="{{ route('projects.show', $project->id) }}" class="badge bg-info mb-1 me-1 p-2">
                                {{ $project->name }} ({{ $project->code }})
                            </a>
                        @empty
                            <span class="badge bg-secondary mb-1 me-1 p-2">No projects for this company</span>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Qualification History</h6>
            <a href="{{ route('qualification-tests.create', ['welder_no' => $welder->id]) }}" class="btn btn-sm btn-success">
                <i class="fas fa-plus"></i> Add Qualification
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Card No</th>
                            <th>Process</th>
                            <th>Position</th>
                            <th>Test Date</th>
                            <th>Expiry Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($welder->qualificationTests as $test)
                        <tr>
                            <td>
                                <a href="{{ route('qualification-tests.show', $test->id) }}">
                                    {{ $test->cert_no }}
                                </a>
                            </td>
                            <td>{{ $test->welding_process }}</td>
                            <td>{{ $test->qualified_position }}</td>
                            <td>{{ $test->getTestDate() ? $test->getTestDate()->format('M d, Y') : 'N/A' }}</td>
                            <td>{{ $test->date_of_expiry ? \Carbon\Carbon::parse($test->date_of_expiry)->format('M d, Y') : 'N/A' }}</td>
                            <td>
                                @if(!$test->is_active)
                                    <span class="badge bg-secondary">Inactive</span>
                                @elseif($test->date_of_expiry < now())
                                    <span class="badge bg-danger">Expired</span>
                                @elseif($test->date_of_expiry <= now()->addDays(30))
                                    <span class="badge bg-warning">Expiring Soon</span>
                                @else
                                    <span class="badge bg-success">Active</span>
                                @endif
                            </td>
                            <td class="d-flex">
                                <a href="{{ route('qualification-tests.show', $test->id) }}" class="btn btn-sm btn-info me-1">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('qualification-tests.card', $test->id) }}" class="btn btn-sm btn-primary me-1" target="_blank">
                                    <i class="fas fa-id-card"></i>
                                </a>
                                <a href="{{ route('qualification-tests.certificate', $test->id) }}" class="btn btn-sm btn-success me-1" target="_blank">
                                    <i class="fas fa-certificate"></i>
                                </a>
                               
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">No qualification records found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
