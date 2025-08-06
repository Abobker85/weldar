@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/certificate-form.css') }}">
@endpush

@push('scripts')
    <script>
        // Safety check for thickness range on form submission
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('certificate-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    // Get the thickness field
                    const thicknessField = document.getElementById('smaw_thickness');
                    if (!thicknessField) return; // Skip if not found
                    
                    const thickness = parseFloat(thicknessField.value);
                    
                    // Calculate what the range should be
                    let expectedRange = '';
                    if (isNaN(thickness)) {
                        console.warn('Thickness is not a valid number:', thicknessField.value);
                        return; // Continue with submission
                    }
                    
                    if (thickness <= 3) {
                        expectedRange = thickness + 'mm to ' + (thickness * 2) + 'mm';
                    } else if (thickness <= 12) {
                        expectedRange = thickness + 'mm to ' + Math.round(thickness * 2) + 'mm';
                    } else {
                        expectedRange = 'Maximum to be welded';
                    }
                    
                    console.log('Form submission - Thickness:', thickness, 'Expected range:', expectedRange);
                    
                    // Make sure we use the expected range value
                    const rangeField = document.getElementById('smaw_thickness_range');
                    const hiddenRangeField = document.getElementById('smaw_thickness_range_hidden');
                    
                    if (rangeField) {
                        rangeField.value = expectedRange;
                        console.log('Set visible thickness range field to:', expectedRange);
                    }
                    
                    if (hiddenRangeField) {
                        hiddenRangeField.value = expectedRange;
                        console.log('Set hidden thickness range field to:', expectedRange);
                    }
                });
            }
        });
    </script>
@endpush

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <h1 class="mb-4">Welder Performance Qualifications - Edit</h1>

                <div class="form-container">
                    <form id="certificate-form"
                        action="{{ route('smaw-certificates.update', $certificate->id) }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <meta name="csrf-token" content="{{ csrf_token() }}">

                        <!-- Hidden fields to store range values -->
                        <input type="hidden" name="diameter_range" id="diameter_range"
                            value="{{ old('diameter_range', $certificate->diameter_range) }}">
                        <input type="hidden" name="p_number_range" id="p_number_range"
                            value="{{ old('p_number_range', $certificate->p_number_range) }}">
                        <input type="hidden" name="position_range" id="position_range"
                            value="{{ old('position_range', $certificate->position_range) }}">
                        <input type="hidden" name="backing_range" id="backing_range"
                            value="{{ old('backing_range', $certificate->backing_range) }}">
                        <input type="hidden" name="f_number_range" id="f_number_range"
                            value="{{ old('f_number_range', $certificate->f_number_range) }}">
                        <input type="hidden" name="vertical_progression_range" id="vertical_progression_range"
                            value="{{ old('vertical_progression_range', $certificate->vertical_progression_range) }}">
                        <input type="hidden" name="smaw_thickness_range" id="smaw_thickness_range_form"
                            value="{{ old('smaw_thickness_range', $certificate->smaw_thickness_range) }}">

                        @include('smaw_certificates.partials.edit.header', [
                            'certificate' => $certificate,
                        ])
                        @include('smaw_certificates.partials.edit.certificate-details', [
                            'certificate' => $certificate,
                        ])
                        @include('smaw_certificates.partials.edit.test-description', [
                            'certificate' => $certificate,
                        ])
                        @include('smaw_certificates.partials.edit.welding-variables', [
                            'certificate' => $certificate,
                        ])
                        @include('smaw_certificates.partials.edit.position-qualification', [
                            'certificate' => $certificate,
                        ])

                        <!-- Results Section -->
                        <div class="section-header-row">
                            <h2 class="section-title">Test Results & Certification</h2>
                        </div>
                        @include('smaw_certificates.partials.edit.results-section', [
                            'certificate' => $certificate,
                        ])

                        <div class="form-buttons">
                            <button type="button" onclick="submitCertificateForm()" class="btn btn-primary">Update
                                Certificate</button>
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
    <script src="{{ asset('js/smaw/p-number-span-capture.js') }}"></script>
    <script src="{{ asset('js/smaw/f-number-span-capture.js') }}"></script>
    <!-- Script for initializing edit form fields -->
    <script src="{{ asset('js/smaw/edit-form-init.js') }}?v={{ time() }}"></script>
    
    <!-- Direct fix for test_position -->
    <script>
        // Execute immediately to ensure it runs before other scripts
        (function() {
            document.addEventListener('DOMContentLoaded', function() {
                // Direct test position selection fix - runs after other scripts
                setTimeout(function() {
                    const testPositionSelect = document.getElementById('test_position');
                    const savedTestPosition = "{{ $certificate->test_position }}";
                    
                    console.log('DIRECT FIX: Certificate test_position value is:', savedTestPosition);
                    
                    if (testPositionSelect && savedTestPosition && savedTestPosition.trim() !== '') {
                        // Set the value directly
                        testPositionSelect.value = savedTestPosition;
                        
                        // Find and set the selected attribute on the correct option
                        for (let i = 0; i < testPositionSelect.options.length; i++) {
                            testPositionSelect.options[i].selected = (testPositionSelect.options[i].value === savedTestPosition);
                            
                            if (testPositionSelect.options[i].value === savedTestPosition) {
                                console.log('DIRECT FIX: Selected option at index', i);
                            }
                        }
                        
                        // Force a change event to update dependent fields
                        const event = new Event('change');
                        testPositionSelect.dispatchEvent(event);
                        
                        // Also manually call updatePositionRange if available
                        if (typeof updatePositionRange === 'function') {
                            console.log('DIRECT FIX: Manually calling updatePositionRange()');
                            updatePositionRange();
                        }
                    }
                }, 500); // Give other scripts time to initialize
            });
        })();
    </script>
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
                
                // Ensure thickness range is correctly set
                const thicknessField = document.getElementById('smaw_thickness');
                if (thicknessField) {
                    const thickness = parseFloat(thicknessField.value);
                    console.log('Form submission check - Current thickness:', thickness);
                    
                    if (!isNaN(thickness)) {
                        // Calculate what the range should be
                        let expectedRange = '';
                        if (thickness <= 3) {
                            expectedRange = thickness + 'mm to ' + (thickness * 2) + 'mm';
                        } else if (thickness <= 12) {
                            expectedRange = thickness + 'mm to ' + Math.round(thickness * 2) + 'mm';
                        } else {
                            expectedRange = 'Maximum to be welded';
                        }
                        
                        console.log('Form submission - Expected thickness range:', expectedRange);
                        
                        // Force-set the thickness range values
                        const rangeHiddenInput = document.getElementById('smaw_thickness_range');
                        if (rangeHiddenInput) {
                            rangeHiddenInput.value = expectedRange;
                            console.log('Set hidden thickness range input to:', expectedRange);
                        }
                        
                        // Also try to update any visible fields
                        try {
                            if (window.forceUpdateThicknessRange) {
                                window.forceUpdateThicknessRange(expectedRange);
                                console.log('Called forceUpdateThicknessRange with:', expectedRange);
                            }
                        } catch (e) {
                            console.error('Error calling forceUpdateThicknessRange:', e);
                        }
                    }
                }

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

            const pNumberRangeText = pNumberRules[pNo] ||
                'P-NO. 1 through P-NO. 15F, P-NO. 34, and P-NO. 41 through P-NO. 49';
            const pNumberRangeSpan = document.getElementById('p_number_range');
            const pNumberRangeInput = document.getElementById('p_number_range');

            if (pNumberRangeSpan) pNumberRangeSpan.textContent = pNumberRangeText;
            if (pNumberRangeInput) pNumberRangeInput.value = pNumberRangeText;

            // Call the update functions to set other range values
            updateDiameterRange();
            updateFNumberRange();
            updatePositionRange();
            
            // Make sure thickness range is explicitly calculated
            const smawThickness = document.getElementById('smaw_thickness');
            if (smawThickness && typeof calculateThicknessRange === 'function') {
                console.log('Explicitly calculating thickness range from:', smawThickness.value);
                calculateThicknessRange(smawThickness.value);
            }
            
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

        // Explicitly set test_position on page load - direct fix for selection issue
        document.addEventListener('DOMContentLoaded', function() {
            // Force-set the test position to match database value
            const testPositionSelect = document.getElementById('test_position');
            const savedValue = "{{ $certificate->test_position }}";
            
            if (testPositionSelect && savedValue) {
                console.log('Direct fix: Setting test_position to:', savedValue);
                
                // First try to find the option that matches the saved value and select it
                let found = false;
                for (let i = 0; i < testPositionSelect.options.length; i++) {
                    if (testPositionSelect.options[i].value === savedValue) {
                        testPositionSelect.selectedIndex = i;
                        testPositionSelect.options[i].selected = true;
                        found = true;
                        break;
                    }
                }
                
                // If option was found, also set the value property
                if (found) {
                    testPositionSelect.value = savedValue;
                } else {
                    console.warn('Could not find option with value:', savedValue);
                }
                
                // Force update position range after setting
                setTimeout(function() {
                    if (typeof updatePositionRange === 'function') {
                        console.log('Forcing updatePositionRange call');
                        updatePositionRange();
                    }
                }, 100); // Small delay to ensure DOM updates are processed
            }
        });
    </script>
@endpush
            </div>
        </div>
    </div>
@endsection
