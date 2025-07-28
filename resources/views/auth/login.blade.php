{{-- filepath: d:\WeldarProject\resources\views\auth\login.blade.php --}}
@extends('layouts.auth')

@section('content')
<div class="container vh-100 d-flex justify-content-center align-items-center">
    <div class="row justify-content-center w-100">
        <div class="col-md-12">
            <div class="card border-0 rounded-lg">
                <div class="card-header text-center bg-primary text-white border-0 pt-4 pb-3">
                    {{-- Placeholder for a logo or brand name --}}
                    <h2 class="fw-bold mb-0">{{ \App\Models\AppSetting::getValue('company_name') }}</h2>
                    <p class="mb-0">Welcome back! Please login to your account.</p>
                </div>

                <div class="card-body p-4 p-md-5">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0" id="email-addon"><i class="fas fa-envelope"></i></span>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror border-start-0" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus placeholder="Enter your email">
                            </div>
                            @error('email')
                                <div class="invalid-feedback d-block"> {{-- Ensure error shows --}}
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0" id="password-addon"><i class="fas fa-lock"></i></span>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror border-start-0" name="password" required autocomplete="current-password" placeholder="Enter your password">
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block"> {{-- Ensure error shows --}}
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-4 form-check">
                            <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Remember Me
                            </label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-sign-in-alt me-2"></i>Login
                            </button>
                        </div>
                      
                    </form>
                </div>
                <div class="card-footer text-center py-3 bg-light border-0">
                    <small class="text-muted">&copy; {{ date('Y') }} YourApp. All rights reserved.</small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Add Font Awesome if not already included in layouts.auth for icons --}}
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"> --}}
@endsection