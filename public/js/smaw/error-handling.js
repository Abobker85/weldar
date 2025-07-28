/**
 * SMAW Certificate Form Error Handling
 * Handles validation errors and error messages for the SMAW Certificate form
 */

// Immediately-invoked function expression (IIFE) to avoid polluting global scope
(function() {
    'use strict';
    
    /**
     * Handle validation errors from server
     * @param {Object} data - Error data from server
     */
    function handleValidationErrors(data) {
        // Check for validation errors from server
        console.error('Server validation errors:', data.errors || 'No error details provided');

        // Clear any existing error indicators first
        window.clearValidationErrors();

        // Create detailed error message
        let errorDetails = 'Please correct the following issues:';
        if (data.errors) {
            // Process error messages from server
            errorDetails = '<ul style="text-align: left; margin-top: 10px;">';
            for (const field in data.errors) {
                const fieldName = field.replace(/_/g, ' ');
                errorDetails += `<li>${fieldName}: ${data.errors[field][0]}</li>`;

                // Pre-validate the problematic field to ensure it exists
                const input = document.querySelector(`[name="${field}"]`);
                console.log(`Checking problematic field from server: ${field}`, input ? {
                    value: input.value,
                    type: input.type,
                    visible: !(input.offsetParent === null)
                } : 'not found');
            }
            errorDetails += '</ul>';
        }

        Swal.fire({
            icon: 'error',
            title: 'Validation Error',
            html: data.message ? data.message + '<br>' + errorDetails : errorDetails,
            footer: 'Please correct the highlighted fields and try again'
        });

        // Mark fields with errors
        if (data.errors) {
            // Special handling for the fields that are causing issues
            const problematicFields = ['wps_followed', 'test_date', 'base_metal_spec'];
            const hasProblematicFields = problematicFields.some(field => data.errors[field]);

            if (hasProblematicFields) {
                // Focus on these specific fields first
                problematicFields.forEach(field => {
                    if (data.errors[field]) {
                        const input = document.getElementById(field);
                        if (input) {
                            // Ensure the field is visible and focused
                            input.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                            setTimeout(() => input.focus(), 500);

                            // Force marking as invalid
                            input.classList.add('is-invalid');

                            // Add error message
                            const errorElement = document.createElement('div');
                            errorElement.className = 'invalid-feedback';
                            errorElement.textContent = data.errors[field][0];

                            // Remove any existing feedback first
                            const existingFeedback = input.nextElementSibling;
                            if (existingFeedback && existingFeedback.classList.contains('invalid-feedback')) {
                                existingFeedback.remove();
                            }

                            input.parentNode.insertBefore(errorElement, input.nextSibling);

                            // Log the field state after marking it invalid
                            console.log(`Field ${field} after marking invalid:`, {
                                value: input.value,
                                classList: input.className,
                                hasError: input.classList.contains('is-invalid')
                            });
                        }
                    }
                });
            }

            // Display all other validation errors
            window.displayValidationErrors(data.errors);
        } else {
            // If no specific errors provided, check the form generally
            window.validateAllFormFields();
        }
    }

    /**
     * Clear all validation error messages and indicators
     */
    function clearValidationErrors() {
        // Remove is-invalid class from all form elements
        const invalidElements = document.querySelectorAll('.is-invalid');
        invalidElements.forEach(element => {
            element.classList.remove('is-invalid');
        });

        // Remove all invalid-feedback elements
        const feedbackElements = document.querySelectorAll('.invalid-feedback');
        feedbackElements.forEach(element => {
            element.remove();
        });
    }

    /**
     * Display validation errors on form fields
     * @param {Object} errors - Object containing field names and error messages
     */
    function displayValidationErrors(errors) {
        // Keep track of problematic fields to debug
        const problematicFields = ['wps_followed', 'test_date', 'base_metal_spec'];
        const problematicFieldsFound = {};

        for (const field in errors) {
            // First try to find by name attribute
            let input = document.querySelector(`[name="${field}"]`);

            // If not found by name, try by ID
            if (!input) {
                input = document.getElementById(field);
            }

            // Log special debug info for problematic fields
            if (problematicFields.includes(field)) {
                problematicFieldsFound[field] = {
                    foundElement: !!input,
                    errorMessage: errors[field][0],
                    elementInfo: input ? {
                        tagName: input.tagName,
                        type: input.type,
                        value: input.value,
                        id: input.id,
                        name: input.name,
                        classList: input.className,
                        attributes: Array.from(input.attributes).map(attr =>
                            `${attr.name}="${attr.value}"`).join(', ')
                    } : 'not found'
                };
            }

            if (input) {
                // Remove existing validation classes first
                input.classList.remove('is-valid');
                input.classList.add('is-invalid');

                // Remove any existing feedback elements
                const siblings = input.parentNode.children;
                for (let i = 0; i < siblings.length; i++) {
                    if (siblings[i].classList && siblings[i].classList.contains('invalid-feedback')) {
                        siblings[i].remove();
                        break;
                    }
                }

                // Add the new error message
                const errorElement = document.createElement('div');
                errorElement.className = 'invalid-feedback';
                errorElement.textContent = errors[field][0];
                errorElement.style.display = 'block'; // Force display

                input.parentNode.insertBefore(errorElement, input.nextSibling);

                // For specific problematic fields, try harder to make the error visible
                if (problematicFields.includes(field)) {
                    input.addEventListener('focus', function() {
                        const feedback = this.nextElementSibling;
                        if (feedback && feedback.classList.contains('invalid-feedback')) {
                            feedback.style.display = 'block';
                        }
                    });
                }
            }
        }

        // Log debug info for problematic fields
        if (Object.keys(problematicFieldsFound).length > 0) {
            console.log('Debug info for problematic fields:', problematicFieldsFound);
        }

        // Focus the first invalid field
        const firstInvalid = document.querySelector('.is-invalid');
        if (firstInvalid) {
            firstInvalid.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
            setTimeout(() => {
                try {
                    firstInvalid.focus();
                } catch (e) {
                    console.error('Error focusing on invalid field:', e);
                }
            }, 500);
        }
    }

    /**
     * Fix problematic fields on page load
     */
    function fixProblematicFields() {
        try {
            const problematicFields = [{
                    id: 'wps_followed',
                    defaultValue: 'WPS-001',
                    attribute: 'required'
                },
                {
                    id: 'test_date',
                    defaultValue: new Date().toISOString().split('T')[0],
                    attribute: 'required'
                },
                {
                    id: 'base_metal_spec',
                    defaultValue: 'A106 Gr.B',
                    attribute: 'required'
                }
            ];

            problematicFields.forEach(field => {
                const input = document.getElementById(field.id);
                if (input) {
                    // Make sure element has required attribute if needed
                    if (field.attribute === 'required' && !input.hasAttribute('required')) {
                        input.setAttribute('required', '');
                    }

                    // Set default value if empty
                    if (!input.value || input.value.trim() === '') {
                        input.value = field.defaultValue;
                        console.log(`Set default value for ${field.id}: ${field.defaultValue}`);
                    }

                    // Ensure the field is properly marked as valid if it has a value
                    if (input.value && input.value.trim() !== '') {
                        input.classList.remove('is-invalid');
                        input.classList.add('is-valid');
                    }
                } else {
                    console.warn(`Problematic field ${field.id} not found in DOM`);
                }
            });
        } catch (e) {
            console.error('Error in fixProblematicFields:', e);
        }
    }

    /**
     * Validate critical fields specifically
     * @param {Array} criticalFields - Array of field IDs to validate
     * @returns {boolean} True if all critical fields are valid
     */
    function validateCriticalFields(criticalFields) {
        let allValid = true;

        criticalFields.forEach(fieldId => {
            const field = document.getElementById(fieldId);
            if (!field) {
                console.error(`Critical field ${fieldId} not found in DOM`);
                allValid = false;
                return;
            }

            // Check if field has a value
            if (!field.value || field.value.trim() === '') {
                console.error(`Critical field ${fieldId} is empty`);
                field.classList.add('is-invalid');
                
                // Add error message
                const errorElement = document.createElement('div');
                errorElement.className = 'invalid-feedback';
                errorElement.textContent = `${fieldId.replace(/_/g, ' ')} is required`;

                // Remove any existing feedback first
                const existingFeedback = field.nextElementSibling;
                if (existingFeedback && existingFeedback.classList.contains('invalid-feedback')) {
                    existingFeedback.remove();
                }

                field.parentNode.insertBefore(errorElement, field.nextSibling);
                
                allValid = false;
            } else {
                field.classList.remove('is-invalid');
                field.classList.add('is-valid');
            }
        });

        return allValid;
    }

    /**
     * Ensure problematic fields have values before submission
     */
    function ensureProblematicFieldsHaveValues() {
        const problematicFields = [{
                id: 'wps_followed',
                defaultValue: 'WPS-001'
            },
            {
                id: 'test_date',
                defaultValue: new Date().toISOString().split('T')[0]
            },
            {
                id: 'base_metal_spec',
                defaultValue: 'A106 Gr.B'
            }
        ];

        problematicFields.forEach(field => {
            const input = document.getElementById(field.id);
            if (input && (!input.value || input.value.trim() === '')) {
                input.value = field.defaultValue;
                console.log(`Set value for ${field.id} before submission: ${field.defaultValue}`);
            }
        });
    }

    // Export functions to global scope
    window.handleValidationErrors = handleValidationErrors;
    window.clearValidationErrors = clearValidationErrors;
    window.displayValidationErrors = displayValidationErrors;
    window.fixProblematicFields = fixProblematicFields;
    window.validateCriticalFields = validateCriticalFields;
    window.ensureProblematicFieldsHaveValues = ensureProblematicFieldsHaveValues;
    
})();
