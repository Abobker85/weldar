@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Projects</h1>
        <a href="{{ route('projects.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Project
        </a>
    </div>    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Project List</h6>
        </div>
        <div class="card-body">
            <!-- Filter Form -->
            <form method="GET" action="{{ route('projects.index') }}" class="mb-4">
                <div class="row align-items-end">
                    <div class="col-md-4">
                        <label for="company_id" class="form-label">Filter by Company</label>
                        <select name="company_id" id="company_id" class="form-control select2">
                            <option value="">All Companies</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ request('company_id') == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary mb-3">Apply Filter</button>
                        @if(request()->has('company_id'))
                            <a href="{{ route('projects.index') }}" class="btn btn-secondary mb-3 ms-2">Clear</a>
                        @endif
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Project Name</th>
                            <th>Code</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Created By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($projects as $project)
                        <tr>
                            <td>{{ $project->name }}</td>
                            <td>{{ $project->code }}</td>
                            <td>{{ $project->start_date ? date('Y-m-d', strtotime($project->start_date)) : 'N/A' }}</td>
                            <td>{{ $project->end_date ? date('Y-m-d', strtotime($project->end_date)) : 'N/A' }}</td>
                            <td>{{ $project->createdBy ? $project->createdBy->name : 'N/A' }}</td>
                            <td class="d-flex">
                                <a href="{{ route('projects.show', $project->id) }}" class="btn btn-sm btn-info me-2">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('projects.edit', $project->id) }}" class="btn btn-sm btn-primary me-2">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('projects.destroy', $project->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this project?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">No projects found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                {{ $projects->appends(request()->query())->links() }}
            </div>
        </div>    </div>
</div>

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" />
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
$(document).ready(function() {
    // Only initialize DataTable if there is at least one data row
    if ($('.table tbody tr').not(':has(td[colspan])').length > 0) {
        $('.table').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            lengthChange: true,
            pageLength: 10,
            language: {
                search: "Filter:"
            }
        });
    }
});
</script>
<script>
    $(document).ready(function() {
        // Initialize Select2
        $('.select2').select2({
            theme: 'bootstrap-5',
            placeholder: "Select a company",
            allowClear: true,
            width: '100%'
        });
    });
</script>
@endpush

@endsection
