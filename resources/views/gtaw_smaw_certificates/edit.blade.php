@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/certificate-form.css') }}">
@endpush

@section('content')
    <div class="form-container">
        <form id="certificate-form" action="{{ route('gtaw-smaw-certificates.update', $certificate->id) }}" method="POST" enctype="multipart/form-data">
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
            
            <div class="section-header-row">
                <h2 class="section-title">Edit GTAW SMAW Certificate: {{ $certificate->certificate_no }}</h2>
                <div class="buttons">
                    <a href="{{ route('gtaw-smaw-certificates.show', $certificate->id) }}" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
            
            @include('gtaw_smaw_certificates.partials.certificate-details', ['certificate' => $certificate, 'welders' => $welders, 'selectedWelder' => $selectedWelder])
            @include('gtaw_smaw_certificates.partials.test-description', ['certificate' => $certificate])
            @include('gtaw_smaw_certificates.partials.welding-variables', ['certificate' => $certificate])
            @include('gtaw_smaw_certificates.partials.position-qualification', ['certificate' => $certificate])
            
            <!-- Results Section -->
            <div class="section-header-row">
                <h2 class="section-title">Test Results & Certification</h2>
            </div>
            @include('gtaw_smaw_certificates.partials.results-section', ['certificate' => $certificate])

            <div class="form-buttons">
                <button type="button" onclick="submitCertificateForm()" class="btn btn-primary">Update Certificate</button>
                <a href="{{ route('gtaw-smaw-certificates.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <!-- Add required third-party libraries before scripts -->
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('js/gtaw-smaw-certificate-form.js') }}?v={{ time() }}"></script>
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
                    // Don't set Content-Type header when sending FormData
                    // The browser will automatically set the correct Content-Type with boundary
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => {
                    // Check if the response is JSON by getting the content-type header
                    const contentType = response.headers.get("content-type");
                    if (!response.ok) {
                        if (contentType && contentType.includes("application/json")) {
                            // If it's JSON, parse it to get the error message
                            return response.json().then(data => {
                                throw new Error(data.message || `Server responded with ${response.status}`);
                            });
                        } else {
                            // Otherwise just throw a generic error
                            throw new Error(`Server responded with ${response.status}`);
                        }
                    }
                    
                    // For successful responses, check if it's JSON and parse it
                    if (contentType && contentType.includes("application/json")) {
                        return response.json();
                    } else {
                        throw new Error("Server response was not JSON");
                    }
                })
                .then(data => {
                    Swal.close();

                    if (data.success) {
                        Swal.fire({
                            title: 'Success!',
                            text: data.message || 'Certificate updated successfully',
                            icon: 'success',
                            confirmButtonText: 'View Certificate'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = data.redirect || window.location.href;
                            }
                        });
                    } else {
                        if (data.errors) {
                            displayValidationErrors(data.errors);
                        }
                        
                        Swal.fire({
                            title: 'Error!',
                            text: data.message || 'Failed to update certificate',
                            icon: 'error'
                        });
                    }
                })
                .catch(error => {
                    Swal.close();
                    console.error('Error:', error);

                    // Show a more user-friendly error message
                    Swal.fire({
                        title: 'Update Failed',
                        html: 'There was a problem updating the certificate:<br><br>' + 
                              '<strong>' + error.message + '</strong><br><br>' +
                              'Please try again or contact support if the problem persists.',
                        icon: 'error',
                        confirmButtonText: 'Try Again'
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
                        if (vpSelect.options[i].value === 'Downhill' || vpSelect.options[i].value === 'Downward') {
                            vpSelect.selectedIndex = i;
                            break;
                        }
                    }
                } else if (dbValue === 'Uphill' || dbValue === 'Upward') {
                    // Find the Uphill option and select it
                    for (let i = 0; i < vpSelect.options.length; i++) {
                        if (vpSelect.options[i].value === 'Uphill' || vpSelect.options[i].value === 'Upward') {
                            vpSelect.selectedIndex = i;
                            break;
                        }
                    }
                } else if (dbValue === 'None') {
                    // Find the None option and select it
                    for (let i = 0; i < vpSelect.options.length; i++) {
                        if (vpSelect.options[i].value === 'None') {
                            vpSelect.selectedIndex = i;
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
                    const certificateValue = verticalProgressionHidden ? verticalProgressionHidden.value : "{{ $certificate->vertical_progression }}";
                    
                    console.log("Certificate vertical progression value:", certificateValue);
                    
                    if (certificateValue) {
                        for (let i = 0; i < verticalProgressionSelect.options.length; i++) {
                            if (verticalProgressionSelect.options[i].value === certificateValue) {
                                verticalProgressionSelect.selectedIndex = i;
                                break;
                            }
                        }
                    } else {
                        // Default to first option if no value is found
                        verticalProgressionSelect.selectedIndex = 0;
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
                        for (let i = 0; i < fillerFNoSelect.options.length; i++) {
                            if (fillerFNoSelect.options[i].value === certificateValue) {
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
            updatePositionRange();
            updateBackingRange();

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
            console.log('Backing range:', document.getElementById('backing_range') ?
                        document.getElementById('backing_range').value : 'not set');
        }

        // Function to update all range fields before form submission
        function updateAllRangeFields() {
            updateDiameterRange();
            updatePNumberRange();
            updatePositionRange();
            updateBackingRange();
            updateFNumberRange();
            updateVerticalProgressionRange();
        }

        // Function to display validation errors
        function displayValidationErrors(errors) {
            for (const field in errors) {
                const input = document.querySelector(`[name="${field}"]`);
                if (input) {
                    input.classList.add('is-invalid');
                    
                    // Add error message
                    const errorMsg = document.createElement('div');
                    errorMsg.className = 'invalid-feedback';
                    errorMsg.textContent = errors[field][0];
                    
                    if (input.parentNode) {
                        input.parentNode.appendChild(errorMsg);
                    }
                    
                    // Scroll to first error
                    if (input === document.querySelector('.is-invalid')) {
                        input.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            }
        }

        // Function to clear validation errors
        function clearValidationErrors() {
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
        }
        
        // Function to validate required fields
        function validateRequiredFields() {
            const requiredFields = document.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.classList.add('is-invalid');
                    
                    // Add error message
                    const errorMsg = document.createElement('div');
                    errorMsg.className = 'invalid-feedback';
                    errorMsg.textContent = 'This field is required';
                    
                    if (field.parentNode) {
                        field.parentNode.appendChild(errorMsg);
                    }
                    
                    // Scroll to first error if needed
                    if (isValid) {
                        field.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        isValid = false;
                    }
                }
            });
            
            return isValid;
        }
    </script>
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('js/gtaw-smaw-certificate-form.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/gtaw-certificate-helper.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/welder-search.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/form-validation.js') }}?v={{ time() }}"></script>
@endpush
@endsection
