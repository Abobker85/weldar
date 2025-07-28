@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Manage Projects for {{ $company->name }}</h1>
        <a href="{{ route('companies.show', $company->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Company
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Associate Projects</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('companies.projects.update', $company->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-4">
                    <label class="form-label">Select Projects</label>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 50px;">Select</th>
                                    <th>Project Name</th>
                                    <th>Code</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($projects as $project)
                                <tr>
                                    <td class="text-center">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="projects[]" value="{{ $project->id }}" id="project-{{ $project->id }}" {{ in_array($project->id, $companyProjects) ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td>{{ $project->name }}</td>
                                    <td>{{ $project->code }}</td>
                                    <td>{{ $project->start_date ? date('Y-m-d', strtotime($project->start_date)) : 'N/A' }}</td>
                                    <td>{{ $project->end_date ? date('Y-m-d', strtotime($project->end_date)) : 'N/A' }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center">No projects available.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-3">
                        <p><a href="{{ route('projects.create') }}" class="btn btn-sm btn-outline-primary"><i class="fas fa-plus"></i> Create New Project</a></p>
                    </div>
                </div>
                
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
