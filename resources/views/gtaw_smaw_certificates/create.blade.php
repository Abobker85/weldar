@extends('layouts.app')

@section('content')
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Welder Performance Qualifications - Create GTAW & SMAW</title>
        <link rel="stylesheet" href="{{ asset('css/certificate-form.css') }}">
    </head>
    <div class="form-container">
        <form id="certificate-form" action="{{ route('gtaw-smaw-certificates.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <meta name="csrf-token" content="{{ csrf_token() }}">
            
            @include('gtaw_smaw_certificates.partials.header')
            @include('gtaw_smaw_certificates.partials.certificate-details')
            @include('gtaw_smaw_certificates.partials.test-description')
            @include('gtaw_smaw_certificates.partials.welding-variables')
            @include('gtaw_smaw_certificates.partials.position-qualification')
            
            <!-- Results Section -->
            <div class="section-header-row">
                <h2 class="section-title">Test Results & Certification</h2>
            </div>
            @include('gtaw_smaw_certificates.partials.results-section')

            <div class="form-buttons">
                <button type="button" onclick="submitCertificateForm()" class="btn btn-primary">Save Certificate</button>
                <a href="{{ route('gtaw-smaw-certificates.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('js/gtaw-smaw-certificate-form.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/welder-search.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/form-validation.js') }}?v={{ time() }}"></script>
@endsection
