@extends('layouts.app')

@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Welder Performance Qualifications - Edit</title>
        <link rel="stylesheet" href="{{ asset('css/certificate-form.css') }}">
    </head>

    <body>
        <div class="form-container">
            <form id="certificate-form" action="{{ route('fcaw-certificates.update', $certificate->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <meta name="csrf-token" content="{{ csrf_token() }}">

                <!-- Hidden fields to store range values -->
                <input type="hidden" name="diameter_range" id="diameter_range" value="{{ old('diameter_range', $certificate->diameter_range) }}">
                <input type="hidden" name="p_number_range" id="p_number_range" value="{{ old('p_number_range', $certificate->p_number_range) }}">
                <input type="hidden" name="position_range" id="position_range" value="{{ old('position_range', $certificate->position_range) }}">
                <input type="hidden" name="backing_range" id="backing_range" value="{{ old('backing_range', $certificate->backing_range) }}">
                <input type="hidden" name="f_number_range" id="f_number_range" value="{{ old('f_number_range', $certificate->f_number_range) }}">
                <input type="hidden" name="vertical_progression_range" id="vertical_progression_range" value="{{ old('vertical_progression_range', $certificate->vertical_progression_range) }}">

                @include('fcaw_certificates.partials.header', ['certificate' => $certificate])
                @include('fcaw_certificates.partials.certificate-details', ['certificate' => $certificate])
                @include('fcaw_certificates.partials.test-description', ['certificate' => $certificate])
                @include('fcaw_certificates.partials.welding-variables', ['certificate' => $certificate])
                @include('fcaw_certificates.partials.position-qualification', ['certificate' => $certificate])

                <!-- Results Section -->
                <div class="section-header-row">
                    <h2 class="section-title">Test Results & Certification</h2>
                </div>
                @include('fcaw_certificates.partials.results-section', ['certificate' => $certificate])

                <div class="form-buttons">
                    <button type="button" onclick="submitCertificateForm()" class="btn btn-primary">Update Certificate</button>
                    <a href="{{ route('fcaw-certificates.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>

        <!-- Add any required third-party libraries before your scripts -->
        <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>
        <script src="{{ asset('js/fcaw_certificate-form.js') }}?v={{ time() }}"></script>
        <script src="{{ asset('js/fcaw-certificate-helper.js') }}?v={{ time() }}"></script>
        <script src="{{ asset('js/fcaw-certificate-number.js') }}?v={{ time() }}"></script>
        <script src="{{ asset('js/welder-search.js') }}?v={{ time() }}"></script>
        <script src="{{ asset('js/form-validation.js') }}?v={{ time() }}"></script>
        <script>
            // Initialize form when DOM is loaded
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize any helper functions
                if (typeof updateVerticalProgressionTerminology === 'function') {
                    updateVerticalProgressionTerminology();
                }

                if (typeof updateTestFields === 'function') {
                    updateTestFields();
                }

                if (typeof handleSpecimenFields === 'function') {
                    handleSpecimenFields();
                }

                // Make sure the submitCertificateForm function is available globally
                window.submitCertificateForm = function() {
                    // Clear previous validation errors
                    clearValidationErrors();
                    
                    // Update all range fields before submission
                    if (typeof updateAllRangeFields === 'function') {
                        updateAllRangeFields();
                    }
                    
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
                
                // Make sure handleSpecimenToggle is called after DOM is fully loaded
                if (typeof handleSpecimenToggle === 'function') {
                    // Call it once on page load
                    handleSpecimenToggle();
                    
                    // Add extra call with a slight delay to ensure it takes effect
                    setTimeout(() => {
                        handleSpecimenToggle();
                        console.log('Called handleSpecimenToggle with delay');
                    }, 500);
                } else {
                    console.warn('handleSpecimenToggle function not found');
                }

                console.log('Certificate form initialized');
            });
            

            
                // Function to explicitly set range values
                function setExplicitRangeValues() {
                    // Call all of our update functions to set range values
                    if (typeof updateBackingRange === 'function') updateBackingRange();
                    if (typeof updateDiameterRange === 'function') updateDiameterRange();
                    if (typeof updateFNumberRange === 'function') updateFNumberRange();
                    if (typeof updatePositionRange === 'function') updatePositionRange();
                    if (typeof updateVerticalProgressionRange === 'function') updateVerticalProgressionRange();
                    
                    // P-Number range - use fixed range text instead of mapping
                    const pNumberRangeText = 'P-NO. 1 through P-NO. 15F, P-NO. 34, and P-NO. 41 through P-NO. 49';
                    const pNumberRangeSpan = document.getElementById('p_number_range_span');
                    const pNumberRangeInput = document.getElementById('p_number_range');

                    if (pNumberRangeSpan) pNumberRangeSpan.textContent = pNumberRangeText;
                    if (pNumberRangeInput) pNumberRangeInput.value = pNumberRangeText;
                    
                    // Debug - output all range values to console
                    console.log('Range values after explicit initialization:');
                    console.log('P-Number range:', document.getElementById('p_number_range') ? 
                                document.getElementById('p_number_range').value : 'not set');
                    console.log('Diameter range:', document.getElementById('diameter_range') ?
                                document.getElementById('diameter_range').value : 'not set');
                    console.log('F-Number range:', document.getElementById('f_number_range') ?
                                document.getElementById('f_number_range').value : 'not set');
                    console.log('Vertical progression range:', document.getElementById('vertical_progression_range') ?
                                document.getElementById('vertical_progression_range').value : 'not set');
                    console.log('Position range:', document.getElementById('position_range') ?
                                document.getElementById('position_range').value : 'not set');
                }
                
                // Function to validate required fields before form submission
                function validateRequiredFields() {
                    // Clear previous validation errors
                    clearValidationErrors();
                    
                    // First make sure handleSpecimenToggle has been applied
                    if (typeof handleSpecimenToggle === 'function') {
                        handleSpecimenToggle();
                    }
                    
                    let isValid = true;
                    
                    // Check if plate only is selected - if so, diameter and thickness are optional
                    const plateCheckbox = document.getElementById('plate_specimen');
                    const pipeCheckbox = document.getElementById('pipe_specimen');
                    const plateChecked = plateCheckbox ? plateCheckbox.checked : false;
                    const pipeChecked = pipeCheckbox ? pipeCheckbox.checked : false;
                    
                    console.log('Validating required fields - Plate:', plateChecked, 'Pipe:', pipeChecked);
                    
                    // Make diameter and thickness optional if only plate is checked
                    if (plateChecked && !pipeChecked) {
                        const diameterField = document.getElementById('diameter');
                        const thicknessField = document.getElementById('thickness');
                        
                        if (diameterField) {
                            console.log('Making diameter field optional');
                            diameterField.required = false;
                            diameterField.removeAttribute('required');
                            // Also update the label if it exists
                            const diameterLabel = diameterField.parentElement.querySelector('strong');
                            if (diameterLabel) {
                                diameterLabel.innerHTML = 'Diameter: <small class="text-muted">(Optional)</small>';
                            }
                        }
                        
                        if (thicknessField) {
                            console.log('Making thickness field optional');
                            thicknessField.required = false;
                            thicknessField.removeAttribute('required');
                            // Also update the label if it exists
                            const thicknessLabel = thicknessField.parentElement.querySelector('strong');
                            if (thicknessLabel) {
                                thicknessLabel.innerHTML = 'Thickness: <small class="text-muted">(Optional)</small>';
                            }
                        }
                    } else if (pipeChecked) {
                        // If pipe is checked, ensure diameter and thickness are required
                        const diameterField = document.getElementById('diameter');
                        const thicknessField = document.getElementById('thickness');
                        
                        if (diameterField) {
                            console.log('Making diameter field required');
                            diameterField.required = true;
                            diameterField.setAttribute('required', 'required');
                            // Also update the label if it exists
                            const diameterLabel = diameterField.parentElement.querySelector('strong');
                            if (diameterLabel) {
                                diameterLabel.innerHTML = 'Diameter: <span class="text-danger">*</span>';
                            }
                        }
                        
                        if (thicknessField) {
                            console.log('Making thickness field required');
                            thicknessField.required = true;
                            thicknessField.setAttribute('required', 'required');
                            // Also update the label if it exists
                            const thicknessLabel = thicknessField.parentElement.querySelector('strong');
                            if (thicknessLabel) {
                                thicknessLabel.innerHTML = 'Thickness: <span class="text-danger">*</span>';
                            }
                        }
                    }
                    
                    // Get all required form elements
                    const requiredElements = document.querySelectorAll('[required]');
                    
                    // Check if all required fields have values
                    requiredElements.forEach(function(element) {
                        if (!element.value.trim()) {
                            element.classList.add('is-invalid');
                            
                            const errorElement = document.createElement('div');
                            errorElement.className = 'invalid-feedback';
                            errorElement.textContent = 'This field is required.';
                            
                            element.parentNode.insertBefore(errorElement, element.nextSibling);
                            
                            isValid = false;
                        }
                    });
                    
                    if (!isValid) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Form Validation Error',
                            text: 'Please fill in all required fields.'
                        });
                    }
                    
                    return isValid;
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
    </body>

    </html>
@endsection
