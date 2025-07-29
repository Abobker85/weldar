@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/certificate-form.css') }}">
@endpush

@section('content')
        <div class="form-container">
            <form id="certificate-form" action="{{ route('gtaw-certificates.update', $certificate->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <meta name="csrf-token" content="{{ csrf_token() }}">

                <!-- Hidden fields to store range values -->
                <input type="hidden" name="diameter_range" id="diameter_range" value="{{ $certificate->diameter_range }}">
                <input type="hidden" name="p_number_range" id="p_number_range" value="{{ $certificate->p_number_range }}">
                <input type="hidden" name="position_range" id="position_range" value="{{ $certificate->position_range }}">
                <input type="hidden" name="backing_range" id="backing_range" value="{{ $certificate->backing_range }}">
                <input type="hidden" name="f_number_range" id="f_number_range" value="{{ $certificate->f_number_range }}">

                <!-- Use the same name as before but different ID to avoid conflict -->
                <input type="hidden" name="vertical_progression" id="vertical_progression_hidden" value="{{ $certificate->vertical_progression }}">
                <input type="hidden" name="vertical_progression_range" id="vertical_progression_range" value="{{ $certificate->vertical_progression_range }}">
                
                @include('gtaw_certificates.partials.header')
                @include('gtaw_certificates.partials.certificate-details', ['certificate' => $certificate, 'welders' => $welders, 'selectedWelder' => $selectedWelder])
                @include('gtaw_certificates.partials.test-description', ['certificate' => $certificate])
                @include('gtaw_certificates.partials.welding-variables', ['certificate' => $certificate])
                @include('gtaw_certificates.partials.position-qualification', ['certificate' => $certificate])
                
                

                <!-- Results Section -->
                <div class="section-header-row">
                    <h2 class="section-title">Test Results & Certification</h2>
                </div>
                @include('gtaw_certificates.partials.results-section', ['certificate' => $certificate])

                <div class="form-buttons">
                    <button type="button" onclick="submitCertificateForm()" class="btn btn-primary">Update Certificate</button>
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
                    
                    // Update hidden vertical_progression field from select before form submission
                    const verticalProgressionSelect = document.getElementById('vertical_progression');
                    const hiddenField = document.getElementById('vertical_progression_hidden');
                    if (verticalProgressionSelect && hiddenField) {
                        hiddenField.value = verticalProgressionSelect.value;
                        console.log('Form submission: Updated vertical_progression_hidden to: ' + verticalProgressionSelect.value);
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
                        title: 'Updating...',
                        text: 'Please wait while the certificate is being updated',
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
                                text: data.message || 'Certificate updated successfully',
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

                // Force set correct vertical progression values from database
                const dbValue = "{{ $certificate->vertical_progression }}"; // Get the actual database value
                console.log("Database vertical_progression value:", dbValue);
                
                // Get the elements
                const vpSelect = document.getElementById('vertical_progression');
                const vpHidden = document.getElementById('vertical_progression_hidden');
                const vpRange = document.getElementById('vertical_progression_range');
                
                if (vpSelect && vpHidden && vpRange) {
                    // First, manually set the hidden fields to the database value
                    vpHidden.value = dbValue;
                    vpRange.value = dbValue;
                    
                    // Then, force the select dropdown to show the correct option
                    if (dbValue === 'Downhill' || dbValue === 'Downward') {
                        // Find the Downhill option and select it
                        for (let i = 0; i < vpSelect.options.length; i++) {
                            if (vpSelect.options[i].value === 'Downhill') {
                                vpSelect.selectedIndex = i;
                                console.log('Forced selection of Downhill option');
                                break;
                            }
                        }
                    } else if (dbValue === 'Uphill' || dbValue === 'Upward') {
                        // Find the Uphill option and select it
                        for (let i = 0; i < vpSelect.options.length; i++) {
                            if (vpSelect.options[i].value === 'Uphill') {
                                vpSelect.selectedIndex = i;
                                console.log('Forced selection of Uphill option');
                                break;
                            }
                        }
                    } else if (dbValue === 'None') {
                        // Find the None option and select it
                        for (let i = 0; i < vpSelect.options.length; i++) {
                            if (vpSelect.options[i].value === 'None') {
                                vpSelect.selectedIndex = i;
                                console.log('Forced selection of None option');
                                break;
                            }
                        }
                    }
                    
                    // Update the vertical progression range
                    console.log("Calling updateVerticalProgressionRange");
                    updateVerticalProgressionRange();
                    
                    // Log the values after the forced update
                    console.log('After forced update:');
                    console.log('- select value:', vpSelect.value);
                    console.log('- hidden field value:', vpHidden.value);
                    console.log('- range field value:', vpRange.value);
                }
            });

            // Function to explicitly set range values
            function setExplicitRangeValues() {
                // P-Number range
                const pNo = document.getElementById('base_metal_p_no').value;

                // Update to use the same range for all P-Number options
                const pNumberRangeText = 'P-NO. 1 through P-NO. 15F, P-NO. 34, and P-NO. 41 through P-NO. 49';
                const pNumberRangeSpan = document.getElementById('p_number_range_span');
                const pNumberRangeInput = document.getElementById('p_number_range');

                if (pNumberRangeSpan) pNumberRangeSpan.textContent = pNumberRangeText;
                if (pNumberRangeInput) pNumberRangeInput.value = pNumberRangeText;
                
                // Make sure vertical_progression and filler_f_no have selected values
                const verticalProgressionSelect = document.getElementById('vertical_progression');
                const verticalProgressionHidden = document.getElementById('vertical_progression_hidden');
                const fillerFNoSelect = document.getElementById('filler_f_no');
                
                // Handle vertical progression selection
                if (verticalProgressionSelect) {
                    console.log("Current vertical progression select value:", verticalProgressionSelect.value);
                    console.log("Current vertical progression hidden value:", verticalProgressionHidden ? verticalProgressionHidden.value : 'not found');
                    
                    // If no option is selected or the selected value doesn't match any option
                    let valueMatched = false;
                    
                    if (verticalProgressionSelect.value) {
                        // Check if the current value matches any option
                        for (let i = 0; i < verticalProgressionSelect.options.length; i++) {
                            if (verticalProgressionSelect.options[i].value === verticalProgressionSelect.value) {
                                valueMatched = true;
                                break;
                            }
                        }
                    }
                    
                    // If no match, or empty value, select an appropriate option
                    if (!valueMatched || !verticalProgressionSelect.value) {
                        // Get the certificate value if available - from either hidden input or server-side variable
                        const certificateValue = verticalProgressionHidden ? 
                            verticalProgressionHidden.value : 
                            '{{ $certificate->vertical_progression ?? "" }}';
                            
                        console.log("Certificate vertical progression value:", certificateValue);
                        
                        if (certificateValue) {
                            // Try to find the certificate value in the options
                            for (let i = 0; i < verticalProgressionSelect.options.length; i++) {
                                // Match either exact or equivalent terminology (Uphill/Upward, Downhill/Downward)
                                const optValue = verticalProgressionSelect.options[i].value;
                                if (optValue === certificateValue || 
                                    (certificateValue === 'Uphill' && optValue === 'Upward') ||
                                    (certificateValue === 'Upward' && optValue === 'Uphill') ||
                                    (certificateValue === 'Downhill' && optValue === 'Downward') ||
                                    (certificateValue === 'Downward' && optValue === 'Downhill')) {
                                    verticalProgressionSelect.selectedIndex = i;
                                    // Also update hidden field to match
                                    if (verticalProgressionHidden) {
                                        verticalProgressionHidden.value = optValue;
                                    }
                                    break;
                                }
                            }
                        } else {
                            // No certificate value, select the first non-empty option
                            for (let i = 0; i < verticalProgressionSelect.options.length; i++) {
                                if (verticalProgressionSelect.options[i].value) {
                                    verticalProgressionSelect.selectedIndex = i;
                                    // Also update hidden field to match
                                    if (verticalProgressionHidden) {
                                        verticalProgressionHidden.value = verticalProgressionSelect.options[i].value;
                                    }
                                    break;
                                }
                            }
                        }
                    }
                }
                
                // Handle F-Number selection
                if (fillerFNoSelect) {
                    console.log("Current F-Number value:", fillerFNoSelect.value);
                    
                    // If no option is selected or the selected value doesn't match any option
                    let valueMatched = false;
                    
                    if (fillerFNoSelect.value) {
                        // Check if the current value matches any option
                        for (let i = 0; i < fillerFNoSelect.options.length; i++) {
                            if (fillerFNoSelect.options[i].value === fillerFNoSelect.value) {
                                valueMatched = true;
                                break;
                            }
                        }
                    }
                    
                    // If no match, or empty value, select the first appropriate option
                    if (!valueMatched || !fillerFNoSelect.value) {
                        const certificateValue = '{{ $certificate->filler_f_no ?? "" }}';
                        
                        if (certificateValue && certificateValue !== '__manual__') {
                            // Try to find the certificate value in the options
                            for (let i = 0; i < fillerFNoSelect.options.length; i++) {
                                if (fillerFNoSelect.options[i].value === certificateValue) {
                                    fillerFNoSelect.selectedIndex = i;
                                    break;
                                }
                            }
                        } else {
                            // No certificate value or manual value, select the first non-manual option
                            for (let i = 0; i < fillerFNoSelect.options.length; i++) {
                                if (fillerFNoSelect.options[i].value && fillerFNoSelect.options[i].value !== '__manual__') {
                                    fillerFNoSelect.selectedIndex = i;
                                    break;
                                }
                            }
                        }
                    }
                }

                // Call the update functions to set other range values
                updateDiameterRange();
                updateFNumberRange();
                updateVerticalProgressionRange();
                
                console.log("Explicit range values set successfully");
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
