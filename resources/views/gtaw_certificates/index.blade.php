@extends('layouts.app')

@section('title', 'GTAW Certificates')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">GTAW Certificates</h1>
        <a href="{{ route('gtaw-certificates.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New GTAW Certificate
        </a>
    </div>
    
    <!-- Search and Filter Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Search & Filter</h6>
        </div>
        <div class="card-body">
            <form id="search-form">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="certificate_no">Certificate Number</label>
                        <input type="text" class="form-control" id="certificate_no" name="certificate_no" 
                               value="{{ request('certificate_no') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="welder_id">Welder</label>
                        <select class="form-control" id="welder_id" name="welder_id">
                            <option value="">All Welders</option>
                            @foreach($welders as $id => $name)
                                <option value="{{ $id }}" {{ request('welder_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="company_id">Company</label>
                        <select class="form-control" id="company_id" name="company_id">
                            <option value="">All Companies</option>
                            @foreach($companies as $id => $name)
                                <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="test_result">Test Result</label>
                        <select class="form-control" id="test_result" name="test_result">
                            <option value="">All Results</option>
                            <option value="1" {{ request('test_result') === '1' ? 'selected' : '' }}>Pass</option>
                            <option value="0" {{ request('test_result') === '0' ? 'selected' : '' }}>Fail</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="date_from">Test Date From</label>
                        <input type="date" class="form-control" id="date_from" name="date_from" 
                               value="{{ request('date_from') }}">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="date_to">Test Date To</label>
                        <input type="date" class="form-control" id="date_to" name="date_to" 
                               value="{{ request('date_to') }}">
                    </div>
                    <div class="col-md-6 mb-3 align-self-end">
                        <button type="button" id="btn-search" class="btn btn-primary mr-2">
                            <i class="fas fa-search"></i> Search
                        </button>
                        <button type="button" id="btn-reset" class="btn btn-secondary">
                            <i class="fas fa-sync"></i> Reset
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Certificates List Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">GTAW Certificates List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="certificates-table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Certificate No.</th>
                            <th>Welder Name</th>
                            <th>Company</th>
                            <th>Test Date</th>
                            <th>Position</th>
                      
                            <th>Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(function() {
        var table = $('#certificates-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('gtaw-certificates.index') }}",
                data: function(d) {
                    d.certificate_no = $('#certificate_no').val();
                    d.welder_id = $('#welder_id').val();
                    d.company_id = $('#company_id').val();
                    d.test_result = $('#test_result').val();
                    d.date_from = $('#date_from').val();
                    d.date_to = $('#date_to').val();
                }
            },
            columns: [
                {data: 'certificate_no', name: 'certificate_no'},
                {data: 'welder_name', name: 'welder.name'},
                {data: 'company_name', name: 'company.name'},
                {data: 'test_date', name: 'test_date'},
                {data: 'test_position', name: 'test_position'},
                {data: 'actions', name: 'actions', orderable: false, searchable: false}
            ]
        });

        $('#btn-search').click(function() {
            table.draw();
        });

        $('#btn-reset').click(function() {
            $('#search-form')[0].reset();
            table.draw();
        });
    });
</script>
@endpush
