@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Dashboard</h1>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stats-card h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Welders</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalWelders }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-hard-hat fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stats-card active h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Active/Pass Qualifications</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $activeQualifications }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stats-card warning h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Failed Qualifications</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $failedQualifications }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stats-card danger h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                    Expired Qualifications</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $expiredQualifications }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-times-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Qualification Status Chart -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Qualifications by Status</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height:300px;">
                            <canvas id="qualificationStatusChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Projects by Company Chart -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Projects by Company</h6>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height:300px;">
                            <canvas id="projectsByCompanyChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Welders -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Recently Added Welders</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Iqama No</th>
                                        <th>Company</th>
                                        <th>Date Added</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentWelders as $welder)
                                        <tr>
                                            <td>
                                                <a href="{{ route('welders.show', $welder->id) }}">{{ $welder->name }}</a>
                                            </td>
                                            <td>{{ $welder->iqama_no }}</td>
                                            <td>{{ $welder->company->name }}</td>
                                            <td>{{ $welder->created_at->format('M d, Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4">No welders added recently.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Tests -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Recent Qualification Tests</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>CERTIFICATE No</th>
                                        <th>Welder</th>
                                        <th>Process</th>
                                        <th>Test Date</th>
                                        <th>Expiry Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($recentTests as $test)
                                        <tr>
                                            <td>
                                                <a href="{{ route('qualification-tests.show', $test->id) }}">
                                                    {{ $test->cert_no }}
                                                </a>
                                            </td>
                                            <td>{{ $test->welder->name }}</td>
                                            <td>{{ $test->welding_process }}</td>
                                            <td>{{ \Carbon\Carbon::parse($test->date_of_test)->format('M d, Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($test->date_of_expiry)->format('M d, Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">No qualification tests added recently.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
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
    // Status Chart
    const statusCtx = document.getElementById('qualificationStatusChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Active', 'Expiring Soon', 'Expired', 'Failed'],
            datasets: [{
                data: [
                    {{ $activeQualifications - $expiringIn30Days }}, 
                    {{ $expiringIn30Days }}, 
                    {{ $expiredQualifications }},
                    {{ $failedQualifications }}
                ],
                backgroundColor: ['#1cc88a', '#f6c23e', '#e74a3b', '#858796'],
                hoverBackgroundColor: ['#17a673', '#dda20a', '#be2617', '#60616f'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
            datasets: [{
                data: [
                    {{ $activeQualifications - $expiringIn30Days }},
                    {{ $expiringIn30Days }},
                    {{ $expiredQualifications }},
                    {{ $failedQualifications }}
                ],
                backgroundColor: ['#1cc88a', '#f6c23e', '#e74a3b', '#858796'],
                hoverBackgroundColor: ['#17a673', '#dda20a', '#be2617', '#60616f'],
                hoverBorderColor: "rgba(234, 236, 244, 1)",
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                }
            },
            cutout: '70%',
        }
    });

    // Projects by Company Chart
    const projectsByCompanyCtx = document.getElementById('projectsByCompanyChart').getContext('2d');
    new Chart(projectsByCompanyCtx, {
        type: 'bar',
        data: {
            labels: [
                @foreach($projectsByCompany as $company)
                    "{{ $company->name }}",
                @endforeach
            ],
            datasets: [{
                label: 'Projects',
                data: [
                    @foreach($projectsByCompany as $company)
                        {{ $company->projects_count }},
                    @endforeach
                ],
                backgroundColor: '#4e73df',
                borderColor: '#2e59d9',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: { display: true, text: 'Number of Projects' }
                },
                x: {
                    title: { display: true, text: 'Company' }
                }
            }
        }
    });
</script>
@endpush
