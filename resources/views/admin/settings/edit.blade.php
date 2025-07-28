@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Application Settings</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Company & Certificate Information</h6>
        </div>
        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="company_name" class="form-label">Company Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('company_name') is-invalid @enderror" id="company_name" name="company_name" value="{{ old('company_name', $settings['company_name']) }}" required>
                            <div class="form-text">This name will appear on all certificates and documents.</div>
                            @error('company_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="doc_prefix" class="form-label">Document Prefix <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('doc_prefix') is-invalid @enderror" id="doc_prefix" name="doc_prefix" value="{{ old('doc_prefix', $settings['doc_prefix']) }}" required>
                            <small class="form-text text-muted">Prefix used for all document and certificate numbers (e.g., WQT, EEA)</small>
                            @error('doc_prefix')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="system_name" class="form-label">System Name (for Certificates) <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('system_name') is-invalid @enderror" id="system_name" name="system_name" value="{{ old('system_name', $settings['system_name']) }}" required>
                    <small class="form-text text-muted">The formal name used on certificate headers (may be different from company name)</small>
                    @error('system_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="company_logo" class="form-label">Company Logo</label>
                    <div class="row">
                        <div class="col-md-8">
                            <input type="file" class="form-control @error('company_logo') is-invalid @enderror" id="company_logo" name="company_logo" accept="image/*">
                            <small class="form-text text-muted">Accepted formats: JPEG, PNG, JPG, GIF. Max size: 2MB.</small>
                            @error('company_logo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            @if($settings['company_logo_path'])
                                <div class="text-center">
                                    <img src="{{ asset('storage/' . $settings['company_logo_path']) }}" alt="Current Logo" class="img-thumbnail" style="max-height: 100px;">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="company_stamp" class="form-label">Company Stamp</label>
                    <div class="row">
                        <div class="col-md-8">
                            <input type="file" class="form-control @error('company_stamp') is-invalid @enderror" id="company_stamp" name="company_stamp" accept="image/*">
                            <small class="form-text text-muted">Upload your company stamp image. Accepted formats: JPEG, PNG, JPG, GIF. Max size: 2MB.</small>
                            @error('company_stamp')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            @if($settings['company_stamp_path'])
                                <div class="text-center">
                                    <img src="{{ asset('storage/' . $settings['company_stamp_path']) }}" alt="Company Stamp" class="img-thumbnail" style="max-height: 100px;">
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="address" class="form-label">Company Address</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" id="address" name="address" rows="3">{{ old('address', $settings['address']) }}</textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $settings['phone']) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $settings['email']) }}">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="website" class="form-label">Website</label>
                            <input type="text" class="form-control @error('website') is-invalid @enderror" id="website" name="website" value="{{ old('website', $settings['website']) }}">
                            @error('website')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">Update Settings</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection