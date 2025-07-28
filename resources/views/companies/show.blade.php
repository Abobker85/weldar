@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Company Details: {{ $company->name }}</h1>
        <div>
            <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-primary me-2">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('companies.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Companies
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Company Information</h6>
                </div>                <div class="card-body">
                    @if($company->logo_path)
                        <div class="text-center mb-4">
                            <img src="{{ asset('storage/' . $company->logo_path) }}" alt="{{ $company->name }} Logo" class="img-fluid" style="max-height: 150px;">
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th style="width: 30%">Name:</th>
                                <td>{{ $company->name }}</td>
                            </tr>
                            <tr>
                                <th>Code:</th>
                                <td>{{ $company->code }}</td>
                            </tr>
                            <tr>
                                <th>Address:</th>
                                <td>{{ $company->address ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Contact Person:</th>
                                <td>{{ $company->contact_person ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Phone:</th>
                                <td>{{ $company->phone ?? 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>Email:</th>
                                <td>{{ $company->email ?? 'N/A' }}</td>
                            </tr>
                            @if($company->additional_info)
                                <tr>
                                    <th>Additional Info:</th>
                                    <td>{{ $company->additional_info }}</td>
                                </tr>
                @endif
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Associated Projects</h6>
                    <a href="{{ route('companies.projects', $company->id) }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-tasks"></i> Manage Projects
                    </a>
                </div>
                <div class="card-body">
                    @if($company->projects->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Project Name</th>
                                        <th>Code</th>
                                        <th>View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($company->projects as $project)
                                    <tr>
                                        <td>{{ $project->name }}</td>
                                        <td>{{ $project->code }}</td>
                                        <td>
                                            <a href="{{ route('projects.show', $project->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center">No projects associated with this company.</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">Company Statistics</h6>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card bg-primary text-white shadow">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">Total Welders</div>
                                    <div class="h5 mb-0 font-weight-bold">{{ $company->welders->count() }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card bg-success text-white shadow">
                                <div class="card-body">
                                    <div class="text-xs font-weight-bold text-uppercase mb-1">Active Qualifications</div>
                                    <div class="h5 mb-0 font-weight-bold">
                                        {{ $company->welders->sum(function($welder) {
                                            return $welder->activeQualifications_count ?? 0;
                                        }) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Welders</h6>
            <a href="{{ route('welders.create') }}" class="btn btn-sm btn-primary">
                <i class="fas fa-plus"></i> Add New Welder
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Iqama No</th>
                            <th>Welder ID</th>
                            <th>Active Qualifications</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($company->welders as $welder)
                        <tr>
                            <td>{{ $welder->name }}</td>
                            <td>{{ $welder->iqama_no }}</td>
                            <td>{{ $welder->welder_no }}</td>
                            <td>{{ $welder->activeQualifications_count ?? '-' }}</td>
                            <td class="d-flex">
                                <a href="{{ route('welders.show', $welder->id) }}" class="btn btn-sm btn-info me-2">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('welders.edit', $welder->id) }}" class="btn btn-sm btn-primary me-2">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="{{ route('qualification-tests.create', ['welder_no' => $welder->id]) }}" class="btn btn-sm btn-success me-2">
                                    <i class="fas fa-certificate"></i> Add Qualification
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">No welders found for this company</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
