@extends('layouts.app')

@section('content')
    <div class="form-container">
        <form id="certificate-form" action="{{ route('gtaw-smaw-certificates.update', $certificate->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <meta name="csrf-token" content="{{ csrf_token() }}">
            
            <div class="section-header-row">
                <h2 class="section-title">Edit GTAW SMAW Certificate: {{ $certificate->certificate_no }}</h2>
                <div class="buttons">
                    <a href="{{ route('gtaw-smaw-certificates.show', $certificate->id) }}" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
            
            @include('gtaw_smaw_certificates.partials.certificate-details', ['edit' => true])
            @include('gtaw_smaw_certificates.partials.test-description', ['edit' => true])
            @include('gtaw_smaw_certificates.partials.welding-variables', ['edit' => true])
            @include('gtaw_smaw_certificates.partials.position-qualification', ['edit' => true])
            
            <!-- Results Section -->
            <div class="section-header-row">
                <h2 class="section-title">Test Results & Certification</h2>
            </div>
            @include('gtaw_smaw_certificates.partials.results-section', ['edit' => true])

            <div class="form-buttons">
                <button type="submit" class="btn btn-primary">Update Certificate</button>
                <a href="{{ route('gtaw-smaw-certificates.show', $certificate->id) }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('js/gtaw-smaw-certificate-form.js') }}?v={{ time() }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize the edit form with existing values
            // Pre-select values based on certificate data
            if (document.querySelector('#test_position')) {
                updatePositionRange();
            }
            
            if (document.querySelector('#backing')) {
                updateBackingRange();
            }
            
            if (document.querySelector('#pipe_diameter_type')) {
                updateDiameterRange();
            }
            
            if (document.querySelector('#base_metal_p_no')) {
                updatePNumberRange();
            }
            
            if (document.querySelector('#filler_f_no')) {
                updateFNumberRange();
            }
            
            if (document.querySelector('#vertical_progression')) {
                updateVerticalProgressionRange();
            }
        });
    </script>
@endsection
