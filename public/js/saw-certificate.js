// ==================================================
// FRONTEND VALIDATION FIXES
// ==================================================

// 1. ENHANCED JAVASCRIPT VALIDATION
// File: public/js/saw-certificate-validation.js (NEW FILE)

/**
 * SAW Certificate Form Validation - Enhanced Version
 */
class SawCertificateValidator {
    constructor() {
        this.requiredFields = [
            'certificate_no', 'welder_id', 'company_id', 'wps_followed', 
            'test_date', 'base_metal_spec', 'dia_thickness', 
            'welding_supervised_by', 'inspector_name', 'inspector_date',
            // ADDED MISSING REQUIRED FIELDS:
            'welding_type', 'welding_process', 'visual_control_type',
            'joint_tracking', 'passes_per_side', 'witness_name', 'witness_date'
        ];
        
        this.booleanFields = [
            'test_coupon', 'production_weld', 'plate_specimen', 'pipe_specimen',
            'rt', 'ut', 'rt_selected', 'ut_selected', 'fillet_welds_plate', 
            'fillet_welds_pipe', 'pipe_macro_fusion', 'plate_macro_fusion',
            'transverse_face_root_bends', 'longitudinal_bends', 'side_bends',
            'pipe_bend_corrosion', 'plate_bend_corrosion'
        ];

        this.conditionalFields = {
            'pipe_specimen': ['diameter', 'pipe_diameter_type'],
            'rt_selected': ['rt_report_no'],
            'ut_selected': ['mechanical_tests_by']
        };

        this.mutuallyExclusiveFields = [
            ['rt_selected', 'ut_selected'] // At least one must be selected
        ];
        
        this.init();
    }

    init() {
        this.bindEventListeners();
        this.initializeRangeCalculations();
        console.log('SAW Certificate Validator initialized');
    }

    bindEventListeners() {
        // Form submission validation
        const form = document.getElementById('certificate-form');
        if (form) {
            form.addEventListener('submit', (e) => this.handleFormSubmit(e));
        }

        // Real-time validation for required fields
        this.requiredFields.forEach(fieldName => {
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (field) {
                field.addEventListener('blur', () => this.validateField(fieldName));
                field.addEventListener('change', () => this.validateField(fieldName));
            }
        });

        // Boolean field validation
        this.booleanFields.forEach(fieldName => {
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (field) {
                field.addEventListener('change', () => this.validateBooleanConstraints());
            }
        });

        // Conditional field validation
        Object.keys(this.conditionalFields).forEach(triggerField => {
            const field = document.querySelector(`[name="${triggerField}"]`);
            if (field) {
                field.addEventListener('change', () => this.validateConditionalFields(triggerField));
            }
        });

        // Range calculation triggers
        const positionField = document.getElementById('test_position');
        const pipeSpecimenField = document.getElementById('pipe_specimen');
        const plateSpecimenField = document.getElementById('plate_specimen');
        
        if (positionField) positionField.addEventListener('change', () => this.updatePositionRange());
        if (pipeSpecimenField) pipeSpecimenField.addEventListener('change', () => this.updatePositionRange());
        if (plateSpecimenField) plateSpecimenField.addEventListener('change', () => this.updatePositionRange());
    }

    handleFormSubmit(event) {
        event.preventDefault();
        
        this.clearAllErrors();
        
        const validationResult = this.validateForm();
        
        if (validationResult.isValid) {
            this.submitForm();
        } else {
            this.displayErrors(validationResult.errors);
            this.focusFirstError();
        }
    }

    validateForm() {
        const errors = [];
        
        // Validate required fields
        const requiredFieldErrors = this.validateRequiredFields();
        errors.push(...requiredFieldErrors);
        
        // Validate boolean constraints
        const booleanErrors = this.validateBooleanConstraints();
        errors.push(...booleanErrors);
        
        // Validate conditional fields
        const conditionalErrors = this.validateAllConditionalFields();
        errors.push(...conditionalErrors);
        
        // Validate data consistency
        const consistencyErrors = this.validateDataConsistency();
        errors.push(...consistencyErrors);
        
        // Validate signature requirements
        const signatureErrors = this.validateSignatures();
        errors.push(...signatureErrors);

        return {
            isValid: errors.length === 0,
            errors: errors
        };
    }

    validateRequiredFields() {
        const errors = [];
        
        this.requiredFields.forEach(fieldName => {
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (!field) {
                console.warn(`Required field ${fieldName} not found in form`);
                return;
            }
            
            let value = '';
            if (field.type === 'checkbox') {
                value = field.checked;
            } else if (field.type === 'radio') {
                const checkedRadio = document.querySelector(`[name="${fieldName}"]:checked`);
                value = checkedRadio ? checkedRadio.value : '';
            } else {
                value = field.value ? field.value.trim() : '';
            }
            
            if (!value || (typeof value === 'string' && value.length === 0)) {
                errors.push({
                    field: fieldName,
                    message: this.getFieldLabel(fieldName) + ' is required',
                    type: 'required'
                });
                this.markFieldError(field);
            } else {
                this.clearFieldError(field);
            }
        });
        
        return errors;
    }

    validateBooleanConstraints() {
        const errors = [];
        
        // Validate mutually exclusive fields
        this.mutuallyExclusiveFields.forEach(fieldGroup => {
            const checkedFields = fieldGroup.filter(fieldName => {
                const field = document.querySelector(`[name="${fieldName}"]`);
                return field && field.checked;
            });
            
            if (checkedFields.length === 0) {
                errors.push({
                    field: fieldGroup[0],
                    message: `At least one of ${fieldGroup.map(f => this.getFieldLabel(f)).join(' or ')} must be selected`,
                    type: 'mutually_exclusive'
                });
            }
        });

        // Validate specimen type selection
        const plateField = document.querySelector('[name="plate_specimen"]');
        const pipeField = document.querySelector('[name="pipe_specimen"]');
        
        if (plateField && pipeField && !plateField.checked && !pipeField.checked) {
            errors.push({
                field: 'plate_specimen',
                message: 'Either Plate or Pipe specimen must be selected',
                type: 'specimen_required'
            });
        }

        return errors;
    }

    validateAllConditionalFields() {
        const errors = [];
        
        Object.keys(this.conditionalFields).forEach(triggerField => {
            const triggerElement = document.querySelector(`[name="${triggerField}"]`);
            if (triggerElement && triggerElement.checked) {
                const requiredFields = this.conditionalFields[triggerField];
                
                requiredFields.forEach(requiredField => {
                    const requiredElement = document.querySelector(`[name="${requiredField}"]`);
                    if (requiredElement && !requiredElement.value.trim()) {
                        errors.push({
                            field: requiredField,
                            message: `${this.getFieldLabel(requiredField)} is required when ${this.getFieldLabel(triggerField)} is selected`,
                            type: 'conditional'
                        });
                        this.markFieldError(requiredElement);
                    }
                });
            }
        });
        
        return errors;
    }

    validateConditionalFields(triggerField) {
        if (this.conditionalFields[triggerField]) {
            const triggerElement = document.querySelector(`[name="${triggerField}"]`);
            const requiredFields = this.conditionalFields[triggerField];
            
            requiredFields.forEach(requiredField => {
                const requiredElement = document.querySelector(`[name="${requiredField}"]`);
                if (requiredElement) {
                    if (triggerElement && triggerElement.checked) {
                        requiredElement.setAttribute('required', 'required');
                        if (requiredElement.closest('.form-group')) {
                            requiredElement.closest('.form-group').style.display = 'block';
                        }
                    } else {
                        requiredElement.removeAttribute('required');
                        if (requiredElement.closest('.form-group')) {
                            requiredElement.closest('.form-group').style.display = 'none';
                        }
                        this.clearFieldError(requiredElement);
                    }
                }
            });
        }
    }

    validateDataConsistency() {
        const errors = [];
        
        // Validate diameter consistency
        const pipeSpecimen = document.querySelector('[name="pipe_specimen"]');
        const diameter = document.querySelector('[name="diameter"]');
        const pipeDiameterType = document.querySelector('[name="pipe_diameter_type"]');
        
        if (pipeSpecimen && pipeSpecimen.checked) {
            if ((!diameter || !diameter.value.trim()) && 
                (!pipeDiameterType || !pipeDiameterType.value.trim())) {
                errors.push({
                    field: 'diameter',
                    message: 'Pipe diameter must be specified when pipe specimen is selected',
                    type: 'consistency'
                });
            }
        }

        // Validate position range consistency
        this.validatePositionRangeConsistency(errors);

        return errors;
    }

    validatePositionRangeConsistency(errors) {
        const position = document.getElementById('test_position')?.value;
        const calculatedRange = this.calculatePositionRange(position);
        const hiddenRangeField = document.getElementById('position_range');
        
        if (hiddenRangeField && calculatedRange !== hiddenRangeField.value) {
            console.warn('Position range inconsistency detected, updating...');
            hiddenRangeField.value = calculatedRange;
            this.updatePositionRangeDisplay(calculatedRange);
        }
    }

    validateSignatures() {
        const errors = [];
        
        const inspectorSignature = document.getElementById('inspector_signature_data');
        if (!inspectorSignature || !inspectorSignature.value.trim()) {
            errors.push({
                field: 'inspector_signature_data',
                message: 'Inspector signature is required',
                type: 'signature'
            });
        }

        return errors;
    }

    validateField(fieldName) {
        const field = document.querySelector(`[name="${fieldName}"]`);
        if (!field) return;

        this.clearFieldError(field);
        
        let value = field.type === 'checkbox' ? field.checked : field.value.trim();
        
        if (!value || (typeof value === 'string' && value.length === 0)) {
            this.markFieldError(field, this.getFieldLabel(fieldName) + ' is required');
            return false;
        }
        
        return true;
    }

    // RANGE CALCULATION METHODS - CONSISTENT WITH BACKEND
    updatePositionRange() {
        const position = document.getElementById('test_position')?.value || '1G';
        const isPipe = document.getElementById('pipe_specimen')?.checked || false;
        
        const calculatedRange = this.calculatePositionRange(position, isPipe);
        
        // Update display
        const rangeDisplay = document.getElementById('position_range_display');
        if (rangeDisplay) {
            rangeDisplay.innerHTML = calculatedRange.split(' | ').join('<br>');
        }
        
        // Update hidden field
        const hiddenField = document.getElementById('position_range');
        if (hiddenField) {
            hiddenField.value = calculatedRange;
        }
        
        console.log('Position range updated:', calculatedRange);
    }

    calculatePositionRange(position, isPipe = false) {
        const positionRules = {
            '1G': {
                ranges: [
                    'F for Groove Plate and Pipe Over 24 in. (610 mm) O.D.',
                    'F for Fillet or Tack Plate and Pipe'
                ],
                pipeSpecific: 'F for Groove Pipe ≥ 2 7∕8 in. (73 mm) O.D.'
            },
            '2G': {
                ranges: [
                    'F & H for Groove Plate and Pipe Over 24 in. (610 mm) O.D.',
                    'F & H for Fillet or Tack Plate and Pipe'
                ],
                pipeSpecific: 'F & H for Groove Pipe ≥ 2 7∕8 in. (73 mm) O.D.'
            },
            '3G': {
                ranges: [
                    'F & V for Groove Plate and Pipe Over 24 in. (610 mm) O.D.',
                    'F, H & V for Fillet or Tack Plate and Pipe'
                ],
                pipeSpecific: 'F & V for Groove Pipe ≥ 2 7∕8 in. (73 mm) O.D.'
            },
            '4G': {
                ranges: [
                    'F & O for Groove Plate and Pipe Over 24 in. (610 mm) O.D.',
                    'F, H & O for Fillet or Tack Plate and Pipe'
                ],
                pipeSpecific: 'F & O for Groove Pipe ≥ 2 7∕8 in. (73 mm) O.D.'
            },
            '5G': {
                ranges: [
                    'F, V & O for Groove Plate and Pipe Over 24 in. (610 mm) O.D.',
                    'All positions for Fillet or Tack Plate and Pipe'
                ],
                pipeSpecific: 'F, V & O for Groove Pipe ≥ 2 7∕8 in. (73 mm) O.D.'
            },
            '6G': {
                ranges: [
                    'Groove Plate and Pipe Over 24 in. (610 mm) O.D. in all Position',
                    'Fillet or Tack Plate and Pipe in all Position'
                ],
                pipeSpecific: 'Groove Pipe ≤24 in. (610 mm) O.D. in all Position'
            }
        };

        if (!positionRules[position]) {
            position = '6G'; // Default fallback
        }

        const rules = positionRules[position];
        let ranges = [...rules.ranges];
        
        if (isPipe && rules.pipeSpecific) {
            // Insert pipe-specific range after first range
            ranges.splice(1, 0, rules.pipeSpecific);
        }

        return ranges.join(' | ');
    }

    initializeRangeCalculations() {
        // Initialize all range calculations on page load
        this.updatePositionRange();
        this.updateBackingRange();
        this.updateAllOtherRanges();
    }

    updateBackingRange() {
        const backing = document.getElementById('backing')?.value;
        const rangeDisplay = document.getElementById('backing_range_display');
        const hiddenField = document.getElementById('backing_range');
        
        let range = '';
        switch (backing) {
            case 'With backing':
                range = 'With backing';
                break;
            case 'Without backing':
                range = 'With or Without backing';
                break;
            default:
                range = 'With backing';
        }
        
        if (rangeDisplay) rangeDisplay.textContent = range;
        if (hiddenField) hiddenField.value = range;
    }

    updateAllOtherRanges() {
        // Update other range fields as needed
        const rangeMethods = [
            'updateVisualControlRange',
            'updateJointTrackingRange', 
            'updatePassesRange',
            'updateEquipmentTypeRange',
            'updateTechniqueRange',
            'updateOscillationRange',
            'updateOperationModeRange'
        ];

        rangeMethods.forEach(methodName => {
            if (typeof this[methodName] === 'function') {
                this[methodName]();
            }
        });
    }

    updateVisualControlRange() {
        const visualControl = document.querySelector('[name="visual_control_type"]')?.value;
        const rangeSpan = document.getElementById('visual_control_range');
        const hiddenField = document.querySelector('[name="visual_control_range"]');
        
        if (rangeSpan) rangeSpan.textContent = visualControl || 'Direct Visual Control';
        if (hiddenField) hiddenField.value = visualControl || 'Direct Visual Control';
    }

    updateJointTrackingRange() {
        const jointTracking = document.querySelector('[name="joint_tracking"]')?.value;
        const rangeSpan = document.getElementById('joint_tracking_range');
        const hiddenField = document.querySelector('[name="joint_tracking_range"]');
        
        let range = '';
        if (jointTracking === 'With Automatic joint tracking') {
            range = 'With Automatic joint tracking';
        } else {
            range = 'With & Without Automatic joint tracking';
        }
        
        if (rangeSpan) rangeSpan.textContent = range;
        if (hiddenField) hiddenField.value = range;
    }

    updatePassesRange() {
        const passes = document.querySelector('[name="passes_per_side"]')?.value;
        const rangeDisplay = document.getElementById('passes_range_display');
        const hiddenField = document.querySelector('[name="passes_range"]');
        
        let range = '';
        if (passes === 'Single passes per side') {
            range = 'Single passes per side';
        } else {
            range = 'Single & multiple passes per side';
        }
        
        if (rangeDisplay) rangeDisplay.textContent = range;
        if (hiddenField) hiddenField.value = range;
    }

    // UTILITY METHODS
    getFieldLabel(fieldName) {
        const field = document.querySelector(`[name="${fieldName}"]`);
        if (!field) return fieldName;
        
        const label = document.querySelector(`label[for="${field.id}"]`);
        if (label) {
            return label.textContent.replace(':', '').trim();
        }
        
        // Fallback: convert field name to readable format
        return fieldName.replace(/_/g, ' ')
                       .replace(/\b\w/g, l => l.toUpperCase());
    }

    markFieldError(field, message = '') {
        field.classList.add('is-invalid');
        
        // Remove existing error message
        const existingError = field.parentNode.querySelector('.invalid-feedback');
        if (existingError) {
            existingError.remove();
        }
        
        // Add new error message
        if (message) {
            const errorElement = document.createElement('div');
            errorElement.className = 'invalid-feedback';
            errorElement.textContent = message;
            field.parentNode.appendChild(errorElement);
        }
    }

    clearFieldError(field) {
        field.classList.remove('is-invalid');
        const errorElement = field.parentNode.querySelector('.invalid-feedback');
        if (errorElement) {
            errorElement.remove();
        }
    }

    clearAllErrors() {
        document.querySelectorAll('.is-invalid').forEach(el => {
            el.classList.remove('is-invalid');
        });
        document.querySelectorAll('.invalid-feedback').forEach(el => {
            el.remove();
        });
        
        // Clear any alert messages
        const alerts = document.querySelectorAll('.alert-danger');
        alerts.forEach(alert => alert.remove());
    }

    displayErrors(errors) {
        if (errors.length === 0) return;
        
        // Group errors by type for better display
        const errorsByType = errors.reduce((acc, error) => {
            if (!acc[error.type]) acc[error.type] = [];
            acc[error.type].push(error);
            return acc;
        }, {});

        // Create error summary
        let errorHtml = '<div class="alert alert-danger"><ul>';
        errors.forEach(error => {
            errorHtml += `<li>${error.message}</li>`;
        });
        errorHtml += '</ul></div>';

        // Insert at top of form
        const form = document.getElementById('certificate-form');
        if (form) {
            form.insertAdjacentHTML('afterbegin', errorHtml);
        }

        // Show notification
        if (typeof showNotification === 'function') {
            showNotification('error', `Please fix ${errors.length} validation error(s)`);
        }

        console.log('Validation errors:', errors);
    }

    focusFirstError() {
        const firstErrorField = document.querySelector('.is-invalid');
        if (firstErrorField) {
            firstErrorField.focus();
            firstErrorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    submitForm() {
        const form = document.getElementById('certificate-form');
        if (!form) {
            console.error('Form element not found');
            return;
        }

        // Update all range calculations before submission
        this.updateAllRangeCalculations();

        // Create FormData object
        const formData = new FormData(form);
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        // Show loading state
        if (typeof Swal !== 'undefined') {
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
        }

        // Submit form via AJAX
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken || ''
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (typeof Swal !== 'undefined') {
                Swal.close();
            }
            
            if (data.success) {
                if (typeof Swal !== 'undefined') {
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
                    alert(data.message || 'Certificate saved successfully');
                    if (data.redirect) {
                        window.location.href = data.redirect;
                    }
                }
            } else {
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: data.message || 'An error occurred'
                    });
                } else {
                    alert(data.message || 'An error occurred');
                }
                
                if (data.errors) {
                    this.displayBackendErrors(data.errors);
                }
            }
        })
        .catch(error => {
            if (typeof Swal !== 'undefined') {
                Swal.close();
            }
            console.error('Error:', error);
            
            const errorMsg = 'An unexpected error occurred. Please try again.';
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: errorMsg
                });
            } else {
                alert(errorMsg);
            }
        });
    }

    updateAllRangeCalculations() {
        this.updatePositionRange();
        this.updateBackingRange();
        this.updateVisualControlRange();
        this.updateJointTrackingRange();
        this.updatePassesRange();
    }

    displayBackendErrors(errors) {
        // Display backend validation errors
        Object.keys(errors).forEach(fieldName => {
            const field = document.querySelector(`[name="${fieldName}"]`);
            if (field) {
                this.markFieldError(field, errors[fieldName][0]);
            }
        });
        
        this.focusFirstError();
    }
}

// 2. INITIALIZE VALIDATOR ON PAGE LOAD
document.addEventListener('DOMContentLoaded', function() {
    // Initialize SAW Certificate Validator
    window.sawCertificateValidator = new SawCertificateValidator();
    
    console.log('SAW Certificate Validation System loaded');
});

// 3. ENHANCED FORM SUBMISSION FUNCTION
function submitCertificateForm() {
    if (window.sawCertificateValidator) {
        // The validator will handle the submission
        const form = document.getElementById('certificate-form');
        if (form) {
            const event = new Event('submit', { bubbles: true, cancelable: true });
            form.dispatchEvent(event);
        }
    } else {
        console.error('SAW Certificate Validator not initialized');
    }
}

// 4. COMPATIBILITY FUNCTIONS FOR EXISTING CODE
function validateRequiredFields() {
    if (window.sawCertificateValidator) {
        const result = window.sawCertificateValidator.validateForm();
        return result.isValid;
    }
    return false;
}

function clearValidationErrors() {
    if (window.sawCertificateValidator) {
        window.sawCertificateValidator.clearAllErrors();
    }
}

function displayValidationErrors(errors) {
    if (window.sawCertificateValidator) {
        window.sawCertificateValidator.displayBackendErrors(errors);
    }
}

// Export for use in other modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = SawCertificateValidator;
}