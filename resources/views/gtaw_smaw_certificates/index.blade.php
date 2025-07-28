@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>{{ __('GTAW SMAW Certificates') }}</div>
                    <div>
                        <a href="{{ route('gtaw-smaw-certificates.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> {{ __('Create New Certificate') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Search filters -->
                    <form action="{{ route('gtaw-smaw-certificates.index') }}" method="GET" class="mb-4" id="search-form">
                        <div class="row">
                            <div class="col-md-3 mb-2">
                                <input type="text" class="form-control" name="certificate_no" id="certificate_no" placeholder="Certificate No." value="{{ request('certificate_no') }}">
                            </div>
                            <div class="col-md-2 mb-2">
                                <select class="form-control" name="welder_id" id="welder_id">
                                    <option value="">-- Select Welder --</option>
                                    @foreach ($welders as $id => $name)
                                        <option value="{{ $id }}" {{ request('welder_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mb-2">
                                <select class="form-control" name="company_id" id="company_id">
                                    <option value="">-- Select Company --</option>
                                    @foreach ($companies as $id => $name)
                                        <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mb-2">
                                <input type="date" class="form-control" name="date_from" id="date_from" placeholder="Date From" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-2 mb-2">
                                <input type="date" class="form-control" name="date_to" id="date_to" placeholder="Date To" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-1 mb-2">
                                <button type="button" id="btn-search" class="btn btn-primary"><i class="fas fa-search"></i></button>
                                <button type="button" id="btn-reset" class="btn btn-secondary"><i class="fas fa-sync"></i></button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="certificates-table">
                            <thead>
                                <tr>
                                    <th>Certificate No</th>
                                    <th>Welder</th>
                                    <th>Company</th>
                                    <th>Test Date</th>
                                    <th>Base Metal Spec</th>
                                    <th>Position</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
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
                url: "{{ route('gtaw-smaw-certificates.index') }}",
                data: function(d) {
                    d.certificate_no = $('#certificate_no').val();
                    d.welder_id = $('#welder_id').val();
                    d.company_id = $('#company_id').val();
                    d.date_from = $('#date_from').val();
                    d.date_to = $('#date_to').val();
                }
            },
            columns: [
                {data: 'certificate_no', name: 'certificate_no'},
                {data: 'welder_name', name: 'welder.name'},
                {data: 'company_name', name: 'company.name'},
                {data: 'test_date', name: 'test_date'},
                {data: 'base_metal_spec', name: 'base_metal_spec'},
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
