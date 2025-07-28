<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Welder Qualification Management') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <!-- Custom styles -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        .auth-container {
            flex: 1;
            display: flex;
            align-items: center;
        }
        
        .auth-footer {
            padding: 1rem;
            text-align: center;
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .auth-logo {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .auth-logo h1 {
            font-weight: 700;
            color: #212529;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="auth-logo">
                        <h1>Welder Qualification Management</h1>
                    </div>
                    
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
    
    <footer class="auth-footer">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    &copy; {{ date('Y') }} Welder Qualification Management System
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
