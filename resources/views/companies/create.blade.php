@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Create New Company</h1>
        <a href="{{ route('companies.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Companies
        </a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-body">            <form action="{{ route('companies.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Company Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>                <div class="mb-3">
                    <label for="code" class="form-label">Company Code <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}" required>
                    @error('code')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="logo" class="form-label">Company Logo</label>
                    <input type="file" class="form-control @error('logo') is-invalid @enderror" id="logo" name="logo">
                    <small class="form-text text-muted">Accepted formats: JPEG, PNG, JPG, GIF. Max size: 2MB.</small>
                    @error('logo')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address') }}</textarea>
                    @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="contact_person" class="form-label">Contact Person</label>
                            <input type="text" class="form-control @error('contact_person') is-invalid @enderror" id="contact_person" name="contact_person" value="{{ old('contact_person') }}">
                            @error('contact_person')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone') }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>                <div class="mb-3">
                    <label for="additional_info" class="form-label">Additional Information</label>
                    <textarea class="form-control @error('additional_info') is-invalid @enderror" id="additional_info" name="additional_info" rows="3">{{ old('additional_info') }}</textarea>
                    @error('additional_info')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Projects</label>
                    <p class="text-muted mb-2">You can associate projects with this company after creation.</p>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Create Company</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
