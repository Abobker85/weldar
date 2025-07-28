@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/certificate-form.css') }}">
@endpush

@section('content')
        <div class="form-container">
            <form id="certificate-form" action="{{ route('gtaw-certificates.store') }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                <meta name="csrf-token" content="{{ csrf_token() }}">
                
                <!-- Hidden fields to store range values -->
                <input type="hidden" name="diameter_range" id="diameter_range" value="">
                <input type="hidden" name="p_number_range" id="p_number_range" value="">
                <input type="hidden" name="position_range" id="position_range" value="">
                <input type="hidden" name="backing_range" id="backing_range" value="">
                <input type="hidden" name="f_number_range" id="f_number_range" value="">
                <input type="hidden" name="vertical_progression_range" id="vertical_progression_range" value="">
                
                @include('gtaw_certificates.partials.header')
                @include('gtaw_certificates.partials.certificate-details')
                @include('gtaw_certificates.partials.test-description')
                @include('gtaw_certificates.partials.welding-variables')
                @include('gtaw_certificates.partials.position-qualification')
                
                <!-- Results Section -->
                <div class="section-header-row">
                    <h2 class="section-title">Test Results & Certification</h2>
                </div>
                @include('gtaw_certificates.partials.results-section')

                <div class="form-buttons">
                    <button type="button" onclick="submitCertificateForm()" class="btn btn-primary">Save Certificate</button>
                    <a href="{{ route('gtaw-certificates.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>

        <!-- Add any required third-party libraries before your scripts -->
        <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>
        <script src="{{ asset('js/gtaw-certificate-form.js') }}?v={{ time() }}"></script>
        <script src="{{ asset('js/gtaw-certificate-helper.js') }}?v={{ time() }}"></script>
        <script src="{{ asset('js/welder-search.js') }}?v={{ time() }}"></script>
        <script src="{{ asset('js/form-validation.js') }}?v={{ time() }}"></script>
        <script>
            // Initialize form when DOM is loaded
            document.addEventListener('DOMContentLoaded', function() {
                // Make sure the submitCertificateForm function is available globally
                window.submitCertificateForm = function() {
                    // Clear previous validation errors
                    clearValidationErrors();
                    
                    // Update all range fields before submission
                    updateAllRangeFields();
                    
                    // Explicitly set range values based on current selections
                    setExplicitRangeValues();
                    
                    // Validate required fields first
                    if (!validateRequiredFields()) {
                        return false;
                    }
                    
                    // Get the form element
                    const form = document.getElementById('certificate-form');
                    if (!form) {
                        console.error('Form with ID "certificate-form" not found!');
                        return false;
                    }
                    
                    // Create FormData object from the form
                    const formData = new FormData(form);
                    
                    // Show loading indicator
                    Swal.fire({
                        title: 'Saving...',
                        text: 'Please wait while the certificate is being saved',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                    
                    // Get CSRF token
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    
                    // Send AJAX request
                    fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        Swal.close();
                        
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: data.message || 'Certificate saved successfully',
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                if (data.redirect) {
                                    window.location.href = data.redirect;
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'An error occurred'
                            });
                            
                            if (data.errors) {
                                displayValidationErrors(data.errors);
                            }
                        }
                    })
                    .catch(error => {
                        Swal.close();
                        console.error('Error:', error);
                        
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An unexpected error occurred. Please try again.'
                        });
                    });
                    
                    return false;
                };
                
                // Initialize range values on page load
                setExplicitRangeValues();
                
                console.log('Certificate form initialized');
            });
            
            // Function to explicitly set range values
            function setExplicitRangeValues() {
                // P-Number range
                const pNo = document.getElementById('base_metal_p_no').value;
                
                // Update to use the same range for all P-Number options
                const pNumberRangeText = 'P-NO. 1 through P-NO. 15F, P-NO. 34, and P-NO. 41 through P-NO. 49';
                const pNumberRangeSpan = document.getElementById('p_number_range');
                const pNumberRangeInput = document.getElementById('p_number_range');
                
                if (pNumberRangeSpan) pNumberRangeSpan.textContent = pNumberRangeText;
                if (pNumberRangeInput) pNumberRangeInput.value = pNumberRangeText;
                
                // Call the update functions to set other range values
                updateDiameterRange();
                updateFNumberRange();
                updateVerticalProgressionRange();
                updatePositionRange();
                
                // Debug - output all range values to console
                console.log('Range values after explicit initialization:');
                console.log('P-Number range:', pNumberRangeInput ? pNumberRangeInput.value : 'not set');
                console.log('Diameter range:', document.getElementById('diameter_range') ? 
                            document.getElementById('diameter_range').value : 'not set');
                console.log('F-Number range:', document.getElementById('f_number_range') ? 
                            document.getElementById('f_number_range').value : 'not set');
                console.log('Vertical progression range:', document.getElementById('vertical_progression_range') ? 
                            document.getElementById('vertical_progression_range').value : 'not set');
                console.log('Position range:', document.getElementById('position_range') ? 
                            document.getElementById('position_range').value : 'not set');
            }
            
            // Function to display validation errors
            function displayValidationErrors(errors) {
                for (const field in errors) {
                    const input = document.querySelector(`[name="${field}"]`);
                    if (input) {
                        input.classList.add('is-invalid');
                        
                        const errorElement = document.createElement('div');
                        errorElement.className = 'invalid-feedback';
                        errorElement.textContent = errors[field][0];
                        
                        input.parentNode.insertBefore(errorElement, input.nextSibling);
                    }
                }
            }
            
            // Function to clear validation errors
            function clearValidationErrors() {
                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
            }
        </script>
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('js/gtaw-certificate-form.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/gtaw-certificate-helper.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/welder-search.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/form-validation.js') }}?v={{ time() }}"></script>
@endpush
@endsection
