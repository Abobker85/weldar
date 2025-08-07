<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>{{ config('app.name', 'Welder Qualification Management') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
      <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap4.min.css" rel="stylesheet">
    
    <!-- Custom styles -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }
        
        .sidebar {
            background-color: #343a40;
            min-height: calc(100vh - 56px);
            color: #fff;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.75);
            padding: 0.75rem 1rem;
            border-radius: 0.25rem;
            margin-bottom: 0.25rem;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .nav-link i {
            margin-right: 0.5rem;
        }
        
        .main-content {
            padding: 2rem;
        }
        
        .card {
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            margin-bottom: 1.5rem;
        }
        
        .stats-card {
            background-color: #fff;
            border-left: 4px solid #0d6efd;
        }
        
        .stats-card.active {
            border-left-color: #198754;
        }
        
        .stats-card.warning {
            border-left-color: #ffc107;
        }
        
        .stats-card.danger {
            border-left-color: #dc3545;
        }
    </style>

    @stack('styles')
</head>
<body>
    <header class="navbar navbar-dark bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="{{ route('dashboard') }}">Welder Qualification</a>
        <div class="d-flex flex-row-reverse flex-grow-1 pe-3">
            @auth
                <div class="dropdown text-white">
                    <a href="#" class="d-block text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        <span class="me-2">{{ Auth::user()->name }}</span>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end text-small" aria-labelledby="dropdownUser1">
                        @if(Auth::user()->isAdmin())
                        <li><a class="dropdown-item" href="{{ route('admin.users.index') }}">Users Management</a></li>
                        <li><a class="dropdown-item" href="{{ route('admin.settings.edit') }}">Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        @endif
                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Update Profile</a></li>
                        <li><a class="dropdown-item" href="{{ route('profile.password') }}">Change Password</a></li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="dropdown-item">Logout</button>
                            </form>
                        </li>
                    </ul>
                </div>
            @endauth
        </div>
        <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </header>

    <div class="container-fluid">
        <div class="row">
            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('companies.*') ? 'active' : '' }}" href="{{ route('companies.index') }}">
                                <i class="fas fa-building"></i> Companies
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('welders.*') ? 'active' : '' }}" href="{{ route('welders.index') }}">
                                <i class="fas fa-hard-hat"></i> Welders
                            </a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle {{ request()->routeIs('qualification-tests.*') || request()->routeIs('smaw-certificates.*') || request()->routeIs('gtaw-certificates.*') || request()->routeIs('gtaw-smaw-certificates.*') || request()->routeIs('fcaw-certificates.*') || request()->routeIs('saw-certificates.*') ? 'active' : '' }}" href="#" id="qualificationsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-certificate"></i> Qualifications
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="qualificationsDropdown">
                                {{-- <li><a class="dropdown-item {{ request()->routeIs('qualification-tests.*') ? 'active' : '' }}" href="{{ route('qualification-tests.index') }}">Standard Qualifications</a></li> --}}
                                <li><a class="dropdown-item {{ request()->routeIs('smaw-certificates.*') ? 'active' : '' }}" href="{{ route('smaw-certificates.index') }}">SMAW Certificates</a></li>
                                <li><a class="dropdown-item {{ request()->routeIs('gtaw-certificates.*') ? 'active' : '' }}" href="{{ route('gtaw-certificates.index') }}">GTAW Certificates</a></li>
                                <li><a class="dropdown-item {{ request()->routeIs('gtaw-smaw-certificates.*') ? 'active' : '' }}" href="{{ route('gtaw-smaw-certificates.index') }}">GTAW-SMAW Certificates</a></li>
                                <li><a class="dropdown-item {{ request()->routeIs('fcaw-certificates.*') ? 'active' : '' }}" href="{{ route('fcaw-certificates.index') }}">FCAW Certificates</a></li>
                                <li><a class="dropdown-item {{ request()->routeIs('saw-certificates.*') ? 'active' : '' }}" href="{{ route('saw-certificates.index') }}">SAW Certificates</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('projects.*') ? 'active' : '' }}" href="{{ route('projects.index') }}">
                                <i class="fas fa-project-diagram"></i> Projects
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 main-content">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.9/js/responsive.bootstrap4.min.js"></script>
    
    @stack('scripts')
    
    <!-- Handle certificate opening in new tab if available in session -->
    @if(session('open_certificate'))
    <script>
        // Open the certificate in a new tab
        (function() {
            console.log('Opening certificate from session data');
            const certificateUrl = "{{ session('open_certificate') }}";
            window.open(certificateUrl, '_blank');
        })();
    </script>
    @endif
</body>
</html>
