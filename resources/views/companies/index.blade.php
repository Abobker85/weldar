@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Companies</h1>
        <a href="{{ route('companies.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Company
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Company List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">                    <thead>
                        <tr>
                            <th>Logo</th>
                            <th>Name</th>
                            <th>Code</th>
                            <th>Contact Person</th>
                            <th>Phone</th>
                            <th>Email</th>
                            <th>Created By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($companies as $company)
                        <tr>
                            <td class="text-center">
                                @if($company->logo_path)
                                    <img src="{{ asset('storage/' . $company->logo_path) }}" alt="{{ $company->name }} Logo" class="img-thumbnail" style="max-height: 50px;">
                                @else
                                    <span class="text-muted"><i class="fas fa-building"></i></span>
                                @endif
                            </td>
                            <td>{{ $company->name }}</td>
                            <td>{{ $company->code }}</td>
                            <td>{{ $company->contact_person }}</td>
                            <td>{{ $company->phone }}</td>
                            <td>{{ $company->email }}</td>
                            <td>{{ $company->createdBy ? $company->createdBy->name : 'N/A' }}</td>
                            <td class="d-flex">
                                <a href="{{ route('companies.show', $company->id) }}" class="btn btn-sm btn-info me-2">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('companies.edit', $company->id) }}" class="btn btn-sm btn-primary me-2">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('companies.destroy', $company->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this company?');">
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
                            <td colspan="7" class="text-center">No companies found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                {{ $companies->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

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
@endpush
