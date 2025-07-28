@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Project Details: {{ $project->name }}</h1>
        <div>
            <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-primary me-2">
                <i class="fas fa-edit"></i> Edit
            </a>
            <a href="{{ route('projects.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Back to Projects
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Project Information</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <tr>
                                <th style="width: 30%">Name:</th>
                                <td>{{ $project->name }}</td>
                            </tr>
                            <tr>
                                <th>Code:</th>
                                <td>{{ $project->code }}</td>
                            </tr>
                            <tr>
                                <th>Start Date:</th>
                                <td>{{ $project->start_date ? date('Y-m-d', strtotime($project->start_date)) : 'N/A' }}</td>
                            </tr>
                            <tr>
                                <th>End Date:</th>
                                <td>{{ $project->end_date ? date('Y-m-d', strtotime($project->end_date)) : 'N/A' }}</td>
                            </tr>
                            @if($project->description)
                                <tr>
                                    <th>Description:</th>
                                    <td>{{ $project->description }}</td>
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
                    <h6 class="m-0 font-weight-bold text-primary">Associated Companies</h6>
                </div>
                <div class="card-body">
                    @if($project->companies->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Company Name</th>
                                        <th>Contact Person</th>
                                        <th>View</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($project->companies as $company)
                                    <tr>
                                        <td>{{ $company->name }}</td>
                                        <td>{{ $company->contact_person ?? 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('companies.show', $company->id) }}" class="btn btn-sm btn-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-center">No companies associated with this project.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
