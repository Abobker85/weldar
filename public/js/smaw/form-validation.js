/**
 * SMAW Certificate Form Validation
 * Handles validation for the SMAW Certificate form
 */

// Immediately-invoked function expression (IIFE) to avoid polluting global scope
(function() {
    'use strict';
    
    /**
     * Validate all form fields
     * @returns {boolean} True if all fields are valid
     */
    function validateAllFormFields() {
        let isValid = true;
        const form = document.getElementById('certificate-form');
        
        if (!form) {
            console.error('Form not found for validation');
            return false;
        }
        
        // Check all form inputs
        const inputs = form.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            // Skip hidden, button, submit inputs
            if (input.type === 'hidden' || input.type === 'button' || input.type === 'submit') {
                return;
            }
            
            // Function to check if input has a valid value based on input type
            const hasValidValue = (input) => {
                if (input.type === 'checkbox' || input.type === 'radio') {
                    // For checkbox/radio, check if any in the group is checked
                    if (input.name) {
                        const group = document.querySelectorAll(`input[name="${input.name}"]`);
                        return Array.from(group).some(el => el.checked);
                    }
                    return input.checked;
                }
                
                // For other input types, check if they have a value
                return input.value && input.value.trim() !== '';
            };
            
            // Debug log the field and its value for problematic fields
            if (input.name === 'wps_followed' || input.name === 'test_date' || input.name === 'base_metal_spec') {
                console.log(`Validating problematic field ${input.name}:`, {
                    value: input.value,
                    required: input.required,
                    validity: input.validity,
                    hasValidValue: hasValidValue(input)
                });
            }
            
            // Check required fields
            if (input.required && !hasValidValue(input)) {
                input.classList.add('is-invalid');
                
                // Add error message if not already present
                if (!input.nextElementSibling || !input.nextElementSibling.classList.contains('invalid-feedback')) {
                    const errorElement = document.createElement('div');
                    errorElement.className = 'invalid-feedback';
                    errorElement.textContent = 'This field is required.';
                    input.parentNode.insertBefore(errorElement, input.nextSibling);
                }
                
                isValid = false;
                console.log(`Validation failed for required field: ${input.name || input.id}`);
            }
            
            // Special validation for email fields
            if (input.type === 'email' && input.value) {
                const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailPattern.test(input.value)) {
                    input.classList.add('is-invalid');
                    
                    const errorElement = document.createElement('div');
                    errorElement.className = 'invalid-feedback';
                    errorElement.textContent = 'Please enter a valid email address.';
                    input.parentNode.insertBefore(errorElement, input.nextSibling);
                    
                    isValid = false;
                }
            }
            
            // Check number inputs with min/max constraints
            if (input.type === 'number' && input.value) {
                if (input.hasAttribute('min') && parseFloat(input.value) < parseFloat(input.getAttribute('min'))) {
                    input.classList.add('is-invalid');
                    
                    const errorElement = document.createElement('div');
                    errorElement.className = 'invalid-feedback';
                    errorElement.textContent = `Value must be at least ${input.getAttribute('min')}.`;
                    input.parentNode.insertBefore(errorElement, input.nextSibling);
                    
                    isValid = false;
                }
                
                if (input.hasAttribute('max') && parseFloat(input.value) > parseFloat(input.getAttribute('max'))) {
                    input.classList.add('is-invalid');
                    
                    const errorElement = document.createElement('div');
                    errorElement.className = 'invalid-feedback';
                    errorElement.textContent = `Value must be no more than ${input.getAttribute('max')}.`;
                    input.parentNode.insertBefore(errorElement, input.nextSibling);
                    
                    isValid = false;
                }
            }
            
            // Check date fields for valid date format
            if (input.type === 'date' && input.value) {
                const datePattern = /^\d{4}-\d{2}-\d{2}$/;
                if (!datePattern.test(input.value)) {
                    input.classList.add('is-invalid');
                    
                    const errorElement = document.createElement('div');
                    errorElement.className = 'invalid-feedback';
                    errorElement.textContent = 'Please enter a valid date in YYYY-MM-DD format.';
                    input.parentNode.insertBefore(errorElement, input.nextSibling);
                    
                    isValid = false;
                }
            }
        });
        
        // If validation fails, scroll to first invalid element
        if (!isValid) {
            const firstInvalid = form.querySelector('.is-invalid');
            if (firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                setTimeout(() => {
                    firstInvalid.focus();
                }, 500);
            }
        }
        
        return isValid;
    }
    
    /**
     * Clear validation errors
     */
    function clearValidationErrors() {
        // Clear all invalid classes
        document.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
            console.log(`Cleared is-invalid class from:`, el.name || el.id || 'unnamed element');
        });
        
        // Remove all error messages
        document.querySelectorAll('.invalid-feedback').forEach(el => {
            el.remove();
        });
        
        // Special handling for problematic fields
        const problematicFields = ['wps_followed', 'test_date', 'base_metal_spec'];
        problematicFields.forEach(fieldName => {
            const field = document.getElementById(fieldName);
            if (field) {
                field.classList.remove('is-invalid');
                
                // Ensure there's no adjacent error message
                if (field.nextElementSibling && field.nextElementSibling.classList.contains('invalid-feedback')) {
                    field.nextElementSibling.remove();
                }
            }
        });
    }
    
    /**
     * Validate critical fields
     * @param {Array} fieldNames - Array of field names to validate
     * @returns {boolean} True if all fields are valid
     */
    function validateCriticalFields(fieldNames) {
        let isValid = true;
        
        for (const fieldName of fieldNames) {
            const field = document.getElementById(fieldName);
            if (!field) {
                console.warn(`Critical field "${fieldName}" not found in the DOM`);
                continue;
            }
            
            console.log(`Validating critical field ${fieldName}:`, {
                value: field.value,
                required: field.required,
                hasValue: field.value && field.value.trim() !== ''
            });
            
            // Check if field has a value
            if (!field.value || field.value.trim() === '') {
                field.classList.add('is-invalid');
                
                // Add error message
                const errorElement = document.createElement('div');
                errorElement.className = 'invalid-feedback';
                errorElement.textContent = 'This field is required.';
                field.parentNode.insertBefore(errorElement, field.nextSibling);
                
                isValid = false;
                console.log(`Validation failed for critical field: ${fieldName}`);
            } else {
                field.classList.remove('is-invalid');
                
                // Remove any error messages
                if (field.nextElementSibling && field.nextElementSibling.classList.contains('invalid-feedback')) {
                    field.nextElementSibling.remove();
                }
            }
        }
        
        return isValid;
    }
    
    // Expose functions to global scope
    window.validateAllFormFields = validateAllFormFields;
    window.clearValidationErrors = clearValidationErrors;
    window.validateCriticalFields = validateCriticalFields;
})();
