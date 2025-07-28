@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/certificate-form.css') }}">
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h1 class="mb-4">Welder Performance Qualifications - Edit</h1>

                <div class="form-container">
                    <form id="certificate-form" action="{{ route('smaw-certificates.update', $certificate->id) }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <meta name="csrf-token" content="{{ csrf_token() }}">

                        <!-- Hidden fields to store range values -->
                        <input type="hidden" name="diameter_range" id="diameter_range"
                            value="{{ $certificate->diameter_range }}">
                        <input type="hidden" name="p_number_range" id="p_number_range"
                            value="{{ $certificate->p_number_range }}">
                        <input type="hidden" name="position_range" id="position_range"
                            value="{{ $certificate->position_range }}">

                        <input type="hidden" name="backing_range" id="backing_range"
                            value="{{ $certificate->backing_range }}">
                        <input type="hidden" name="f_number_range" id="f_number_range"
                            value="{{ $certificate->f_number_range }}">
                        <input type="hidden" name="vertical_progression_range" id="vertical_progression_range"
                            value="{{ $certificate->vertical_progression_range }}">

                        @include('smaw_certificates.partials.edit-header')
                        @include('smaw_certificates.partials.edit-certificate-details')
                        @include('smaw_certificates.partials.edit-test-description')
                        @include('smaw_certificates.partials.edit-welding-variables')
                        @include('smaw_certificates.partials.edit-position-qualification')

                        <!-- Results Section -->
                        <div class="section-header-row">
                            <h2 class="section-title">Test Results & Certification</h2>
                        </div>
                        @include('smaw_certificates.partials.edit-results-section')
                        @include('smaw_certificates.partials.edit-signature-section')


                        <div class="form-buttons">
                            <button type="button" onclick="submitCertificateForm()" class="btn btn-primary">Update
                                Certificate</button>
                            <a href="{{ route('smaw-certificates.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- Add any required third-party libraries before your scripts -->
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>

    <!-- Modular JS files for better organization -->
    <script src="{{ asset('js/smaw/form-validation.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/smaw/range-functions.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/smaw/signature-pads.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/smaw/error-handling.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/smaw/range-management.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/smaw/specimen-type-control.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/smaw/position-init.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/smaw/welder-info.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/smaw/form-submission.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/smaw/form-debug.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/smaw/form-init.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/welder-search.js') }}?v={{ time() }}"></script>
    <script>
        // Initialize form when DOM is loaded
        document.addEventListener('DOMContentLoaded', function() {
            console.group('SMAW Certificate Edit Initialization');
            
            // Fix problematic fields immediately on page load
            if (typeof window.fixProblematicFields === 'function') {
                window.fixProblematicFields();
                console.log('✅ Problematic fields fixed');
            }
            
            // Initialize welding variables with saved values
            if (typeof window.initializeWeldingVariables === 'function') {
                window.initializeWeldingVariables();
                console.log('✅ Welding variables initialized');
            }
            
            // Ensure proper specimen type initialization
            if (typeof window.toggleDiameterField === 'function') {
                window.toggleDiameterField();
                console.log('✅ Specimen type controls initialized');
            }
            
            // Force position range update
            if (typeof window.updatePositionRange === 'function') {
                window.updatePositionRange();
                console.log('✅ Position range updated');
            }
            
            // Debug current state
            const specimenState = {
                'plate_checked': document.getElementById('plate_specimen')?.checked,
                'pipe_checked': document.getElementById('pipe_specimen')?.checked,
                'position': document.getElementById('test_position')?.value,
                'position_range': document.getElementById('position_range')?.value,
            };
            console.log('Current specimen state:', specimenState);
            
            console.log('SMAW Certificate edit form initialized');
            console.groupEnd();
        });
    </script>
@endpush
