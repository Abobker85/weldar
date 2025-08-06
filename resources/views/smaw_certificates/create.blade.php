@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/certificate-form.css') }}">
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h1 class="mb-4">Welder Performance Qualifications - Create</h1>
                
                <div class="form-container">
                    <form id="certificate-form" action="{{ route('smaw-certificates.store') }}" method="POST"
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
                <input type="hidden" name="smaw_thickness_range" id="smaw_thickness_range_form" value="">
                
                @include('smaw_certificates.partials.header')
                @include('smaw_certificates.partials.certificate-details')
                @include('smaw_certificates.partials.test-description')
                @include('smaw_certificates.partials.welding-variables')
                @include('smaw_certificates.partials.position-qualification')
                
                <!-- Results Section -->
                <div class="section-header-row">
                    <h2 class="section-title">Test Results & Certification</h2>
                </div>
                @include('smaw_certificates.partials.results-section')

                <div class="form-buttons">
                    <button type="button" onclick="submitCertificateForm()" class="btn btn-primary">Save Certificate</button>
                    <a href="{{ route('smaw-certificates.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>

@push('scripts')
        <!-- Add any required third-party libraries before your scripts -->
        <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>
        
        <!-- Range calculation and form handling scripts -->
        <script src="{{ asset('js/smaw/range-functions.js') }}"></script>
        <script src="{{ asset('js/smaw/thickness-span-capture.js') }}"></script>
        <script src="{{ asset('js/smaw/vertical-progression-span-capture.js') }}"></script>
        <script src="{{ asset('js/certificate-form.js') }}?v={{ time() }}"></script>
        <script src="{{ asset('js/welder-search.js') }}?v={{ time() }}"></script>
        <script src="{{ asset('js/form-validation.js') }}?v={{ time() }}"></script>
        <script src="{{ asset('js/smaw/p-number-span-capture.js') }}?v={{ time() }}"></script>
        <script src="{{ asset('js/smaw/f-number-span-capture.js') }}?v={{ time() }}"></script>
        
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
                
                // Make sure specimen checkboxes are properly initialized first
                initializeSpecimenCheckboxes();
                
                // Initialize range values on page load
                setExplicitRangeValues();
                
                console.log('Certificate form initialized');
            });
            
            // Function to explicitly set range values
            function setExplicitRangeValues() {
                // P-Number range
                const pNo = document.getElementById('base_metal_p_no').value;
                const pNumberRules = {
                    'P NO.1 TO P NO.1': 'P-No.1 Group 1 or 2',
                    'P NO.3 TO P NO.3': 'P-No.3 Group 1 or 2',
                    'P NO.4 TO P NO.4': 'P-No.4 Group 1 or 2',
                    'P NO.5A TO P NO.5A': 'P-No.5A Group 1 or 2',
                    'P NO.8 TO P NO.8': 'P-No.8 Group 1 or 2',
                    'P NO.1 TO P NO.8': 'P-No.1 to P-No.8',
                    'P NO.43 TO P NO.43': 'P-No.43'
                };
                
                const pNumberRangeText = pNumberRules[pNo] || 'P-NO. 1 through P-NO. 15F, P-NO. 34, and P-NO. 41 through P-NO. 49';
                const pNumberRangeSpan = document.getElementById('p_number_range');
                const pNumberRangeInput = document.getElementById('p_number_range');
                
                if (pNumberRangeSpan) pNumberRangeSpan.textContent = pNumberRangeText;
                if (pNumberRangeInput) pNumberRangeInput.value = pNumberRangeText;
                
                // Call the update functions to set other range values
                updateDiameterRange();
                updateFNumberRange();
                updatePositionRange();
                
                // Make sure vertical progression range is explicitly set
                const verticalProgression = document.getElementById('vertical_progression');
                if (verticalProgression) {
                    const verticalProgressionValue = verticalProgression.value;
                    const verticalProgressionRange = document.getElementById('vertical_progression_range');
                    const verticalProgressionSpan = document.getElementById('vertical_progression_range_span');
                    
                    // Set the value based on the current selection
                    if (verticalProgressionValue === '__manual__') {
                        const verticalProgressionManual = document.getElementById('vertical_progression_manual');
                        const manualValue = verticalProgressionManual?.value || 'Uphill';
                        
                        if (verticalProgressionRange) verticalProgressionRange.value = manualValue;
                        if (verticalProgressionSpan) verticalProgressionSpan.textContent = manualValue;
                    } else {
                        const value = verticalProgressionValue === 'Downhill' ? 'Downhill' : 'Uphill';
                        
                        if (verticalProgressionRange) verticalProgressionRange.value = value;
                        if (verticalProgressionSpan) verticalProgressionSpan.textContent = value;
                    }
                    
                    console.log('Set vertical progression range to:', verticalProgressionRange?.value);
                }
                
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
                console.log('Thickness range:', document.getElementById('smaw_thickness_range') ?
                            document.getElementById('smaw_thickness_range').value : 'not set');
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
@endpush
            </div>
        </div>
    </div>
@endsection

