@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Welders</h1>
        <a href="{{ route('welders.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Welder
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Search Filters</h6>
        </div>
        <div class="card-body">
            <form id="filter-form" class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Search</label>
                    <input type="text" class="form-control" id="search" name="search" placeholder="Search by name, ID or Iqama" value="{{ request('search') }}">
                </div>
                <div class="col-md-4">
                    <label for="company_id" class="form-label">Company</label>
                    <select class="form-select select2" id="company_id" name="company_id">
                        <option value="">All Companies</option>
                        @foreach($companies as $id => $name)
                            <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="button" id="filter-btn" class="btn btn-primary me-2">Filter</button>
                    <button type="button" id="reset-btn" class="btn btn-secondary">Reset</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Welder List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="welders-table" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Welder ID</th>
                            <th>Iqama No</th>
                            <th>Company</th>
                            <th>Nationality</th>
                            <th>Gender</th>
                            <th>Created By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Table body will be filled by DataTables -->
                    </tbody>
                </table>
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
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });
    
    // Initialize DataTable with server-side processing
    var table = $('#welders-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "{{ route('welders.index') }}",
            data: function(d) {
                d.search = $('#search').val();
                d.company_id = $('#company_id').val();
            }
        },
        columns: [
            { data: 'name', name: 'name' },
            { data: 'welder_no', name: 'welder_no' },
            { data: 'iqama_no', name: 'iqama_no' },
            { data: 'company_name', name: 'company.name' },
            { data: 'nationality', name: 'nationality' },
            { data: 'gender', name: 'gender' },
            { data: 'created_by', name: 'created_by' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        order: [[0, 'asc']],
        pageLength: 10,
        language: {
            search: "Filter:",
            processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span>'
        }
    });
    
    // Filter button click event
    $('#filter-btn').click(function() {
        table.draw();
    });
    
    // Reset button click event
    $('#reset-btn').click(function() {
        $('#search').val('');
        $('#company_id').val('').trigger('change');
        table.draw();
    });
    
    // Search input keyup event
    $('#search').keyup(function(e) {
        if(e.keyCode == 13) { // Enter key
            table.draw();
        }
    });
});
</script>
@endpush
