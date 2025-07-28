@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Qualification Tests</h1>
        <a href="{{ route('qualification-tests.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Qualification
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Search Filters</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('qualification-tests.index') }}" method="GET" class="row g-3">                <div class="col-md-2">
                    <label for="cert_no" class="form-label">Certificate Number</label>
                    <input type="text" class="form-control" id="cert_no" name="cert_no" placeholder="Search by certificate no" value="{{ request('cert_no') }}">
                </div>
                <div class="col-md-2">
                    <label for="welder_no" class="form-label">Welder</label>
                    <select class="form-select" id="welder_no" name="welder_no">
                        <option value="">All Welders</option>
                        @foreach($welders as $id => $name)
                            <option value="{{ $id }}" {{ request('welder_no') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="company_id" class="form-label">Company</label>
                    <select class="form-select" id="company_id" name="company_id">
                        <option value="">All Companies</option>
                        @foreach($companies as $id => $name)
                            <option value="{{ $id }}" {{ request('company_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>                <div class="col-md-2">
                    <label for="welding_process" class="form-label">Process</label>
                    <select class="form-select" id="welding_process" name="welding_process">
                        <option value="">All Processes</option>
                        @foreach($processes as $process)
                            <option value="{{ $process }}" {{ request('welding_process') == $process ? 'selected' : '' }}>{{ $process }}</option>
                        @endforeach
                    </select>
                </div>                <div class="col-md-2">
                    <label for="qualification_code" class="form-label">Certification</label>
                    <select class="form-select" id="qualification_code" name="qualification_code">
                        <option value="">All Codes</option>
                        @foreach($certificationCodes as $code)
                            <option value="{{ $code }}" {{ request('qualification_code') == $code ? 'selected' : '' }}>{{ $code }}</option>
                        @endforeach
                    </select>
                </div><div class="col-md-2">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Expired</option>
                        <option value="expiring-soon" {{ request('status') == 'expiring-soon' ? 'selected' : '' }}>Expiring Soon</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="test_result" class="form-label">Test Result</label>
                    <select class="form-select" id="test_result" name="test_result">
                        <option value="">All Results</option>
                        <option value="pass" {{ request('test_result') == 'pass' ? 'selected' : '' }}>Pass</option>
                        <option value="fail" {{ request('test_result') == 'fail' ? 'selected' : '' }}>Fail</option>
                    </select>
                </div>
                <div class="col-md-12 d-flex align-items-end pt-2">
                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                    <a href="{{ route('qualification-tests.index') }}" class="btn btn-secondary">Reset</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Qualification Tests List</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="qualification-table" class="table table-bordered table-striped">
                    <thead>                        <tr>                            <th>Certificate No</th>
                            <th>Welder</th>
                            <th>Company</th>
                            <th>Qualification Type</th>
                            <th>Process</th>
                            <th>Position</th>
                            <th>Test Date</th>
                            <th>Test Result</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($qualifications as $qualification)                        <tr>
                            <td>
                                <a href="{{ route('qualification-tests.show', $qualification->id) }}">
                                    {{ $qualification->cert_no }}
                                </a>
                            </td>
                            <td>
                                <a href="{{ route('welders.show', $qualification->welder_id) }}">
                                    {{ $qualification->welder->name }}
                                </a>
                            </td>
                            <td>                                @if($qualification->company_id && $qualification->company)
                                    <a href="{{ route('companies.show', $qualification->company->id) }}">
                                        {{ $qualification->company->name }}
                                        @if($qualification->company->code)
                                            <span class="badge bg-info">{{ $qualification->company->code }}</span>
                                        @endif
                                    </a>
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>{{ $qualification->qualification_type ?? 'N/A' }}</td>
                            <td>{{ $qualification->welding_process }}</td>
                            <td>{{ $qualification->welding_positions }}</td>
                            <td>{{ $qualification->test_date ? $qualification->test_date->format('M d, Y') : ($qualification->vt_date ? $qualification->vt_date->format('M d, Y') : 'N/A') }}</td>
                            <td>
                                @if($qualification->test_result)
                                    <span class="badge bg-success">Pass</span>
                                @else
                                    <span class="badge bg-danger">Fail</span>
                                @endif
                            </td>
                            <td>
                                @if(!$qualification->is_active)
                                    <span class="badge bg-secondary">Inactive</span>                                @elseif(!$qualification->getTestDate())
                                    <span class="badge bg-secondary">No Test Date</span>
                                @elseif($qualification->getTestDate() < now()->subMonths(6))
                                    <span class="badge bg-danger">Expired</span>
                                @elseif($qualification->getTestDate() <= now()->addDays(30))
                                    <span class="badge bg-warning">Expiring Soon</span>
                                @else
                                    <span class="badge bg-success">Active</span>
                                @endif
                            </td>
                            <td>{{ $qualification->createdBy ? $qualification->createdBy->name : 'N/A' }}</td>
                            <td class="d-flex">
                                <a href="{{ route('qualification-tests.show', $qualification->id) }}" class="btn btn-sm btn-info me-1">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('qualification-tests.edit', $qualification->id) }}" class="btn btn-sm btn-primary me-1">
                                    <i class="fas fa-edit"></i>
                                </a>                                @if($qualification->test_result)
                                    @if(Auth::user()->role === 'qc' || Auth::user()->role === 'admin')                                        <div class="dropdown d-inline-block me-1">
                                            <button class="btn btn-sm btn-success dropdown-toggle" type="button" id="cardDropdown{{ $qualification->id }}" data-bs-toggle="dropdown" aria-expanded="false" title="Qualification Card Options">
                                                <i class="fas fa-id-card"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="cardDropdown{{ $qualification->id }}">                                                <li><a class="dropdown-item" href="{{ route('qualification-tests.card', $qualification->id) }}" target="_blank">Front Card (QR enabled)</a></li>
                                                <li><a class="dropdown-item" href="{{ route('qualification-tests.card', ['id' => $qualification->id, 'side' => 'back']) }}" target="_blank">Back Card (QR enabled)</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                {{-- <li><a class="dropdown-item" href="#" onclick="window.open('{{ route('qualification-tests.card', $qualification->id) }}', '_blank'); window.open('{{ route('qualification-tests.card', ['id' => $qualification->id, 'side' => 'back']) }}', '_blank'); return false;">Print Both</a></li> --}}
                                            </ul>
                                        </div>                                        <a href="{{ route('qualification-tests.certificate', $qualification->id) }}" class="btn btn-sm btn-warning me-1" target="_blank" title="View QR Enhanced Certificate">
                                            <i class="fas fa-certificate"></i>
                                        </a>
                                    @else                                    <div class="d-inline-block me-1">
                                        <button class="btn btn-sm btn-secondary" disabled title="Only QC personnel can generate qualification cards">
                                            <i class="fas fa-id-card"></i>
                                        </button>
                                    </div>
                                    <button class="btn btn-sm btn-secondary me-1" disabled title="Only QC personnel can generate certificates">
                                        <i class="fas fa-certificate"></i>
                                    </button>
                                    @endif
                                @else                                    <div class="d-inline-block me-1">
                                        <button class="btn btn-sm btn-secondary" disabled title="Test must be passed to generate qualification cards">
                                            <i class="fas fa-id-card"></i>
                                        </button>
                                    </div>
                                    <button class="btn btn-sm btn-secondary me-1" disabled title="Test must be passed to generate certificates">
                                        <i class="fas fa-certificate"></i>
                                    </button>
                                @endif
                                <form action="{{ route('qualification-tests.destroy', $qualification->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this qualification test?');">
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
                            <td colspan="11" class="text-center">No qualification tests found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Fix for DataTables error when table is empty --}}
                <script>
                $(document).ready(function() {
                    // Only initialize DataTable if there is at least one data row
                    if ($('#qualification-table tbody tr').not(':has(td[colspan])').length > 0) {
                        $('#qualification-table').DataTable({
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
            </div>
        </div>
    </div>
</div>
@endsection
