/**
 * SAW Certificate Form JavaScript
 * Handles form interactions, validation, and dynamic updates for SAW certificates
 */

// Global variables
let welderData = {};
let companyData = {};

/**
 * Initialize SAW form functionality
 */
function initializeSawForm() {
    console.log('Initializing SAW Certificate Form...');
    
    // Initialize welder selection
    initializeWelderSelection();
    
    // Initialize company selection
    initializeCompanySelection();
    
    // Initialize specimen type toggles
    initializeSpecimenTypes();
    
    // Initialize position and range updates
    initializePositionUpdates();
    
    // Initialize backing and welding parameters
    initializeWeldingParameters();
    
    // Initialize test result fields
    initializeTestResults();
    
    // Initialize certificate numbering
    initializeCertificateNumbering();
    
    // Initialize photo upload
    initializePhotoUpload();
    
    // Initialize date formatting
    initializeDateFormatting();
    
    console.log('SAW Certificate Form initialized successfully');
}

/**
 * Initialize welder selection functionality
 */
function initializeWelderSelection() {
    const welderSelect = document.getElementById('welder_id');
    if (!welderSelect) return;
    
    welderSelect.addEventListener('change', function() {
        const welderId = this.value;
        
        if (!welderId) {
            clearWelderFields();
            return;
        }
        
        // Show loading state
        showFieldLoading('welder_id_no');
        showFieldLoading('iqama_no');
        showFieldLoading('passport_no');
        
        // Fetch welder details
        fetch(`/welders/${welderId}/details`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.welder) {
                    updateWelderFields(data.welder);
                    welderData = data.welder;
                }
                
                if (data.company) {
                    updateCompanyDisplay(data.company);
                    companyData = data.company;
                }
            })
            .catch(error => {
                console.error('Error fetching welder details:', error);
                showNotification('error', 'Failed to load welder details');
                clearWelderFields();
            })
            .finally(() => {
                hideFieldLoading('welder_id_no');
                hideFieldLoading('iqama_no');
                hideFieldLoading('passport_no');
            });
    });
}

/**
 * Update welder fields with fetched data
 */
function updateWelderFields(welder) {
    const fields = {
        'welder_id_no': welder.welder_id_no || welder.welder_no || '',
        'iqama_no': welder.iqama_no || '',
        'passport_no': welder.passport_no || welder.passport_id_no || ''
    };
    
    Object.keys(fields).forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.value = fields[fieldId];
        }
    });
    
    // Update photo if available
    updateWelderPhoto(welder);
}

/**
 * Update welder photo
 */
function updateWelderPhoto(welder) {
    const photoPreview = document.getElementById('photo-preview');
    if (!photoPreview) return;
    
    if (welder.photo_path) {
        photoPreview.innerHTML = `<img src="${welder.photo_path}" alt="Welder Photo" class="preview-image">`;
    } else if (welder.photo) {
        photoPreview.innerHTML = `<img src="/storage/${welder.photo}" alt="Welder Photo" class="preview-image">`;
    } else {
        photoPreview.innerHTML = '<div class="photo-placeholder">No Photo</div>';
    }
}

/**
 * Clear welder fields
 */
function clearWelderFields() {
    const fields = ['welder_id_no', 'iqama_no', 'passport_no'];
    fields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.value = '';
        }
    });
    
    // Clear photo
    const photoPreview = document.getElementById('photo-preview');
    if (photoPreview) {
        photoPreview.innerHTML = '<div class="photo-placeholder">No Photo</div>';
    }
    
    welderData = {};
}

/**
 * Initialize company selection
 */
function initializeCompanySelection() {
    const companySelect = document.getElementById('company_id');
    if (!companySelect) return;
    
    companySelect.addEventListener('change', function() {
        const companyId = this.value;
        
        if (!companyId) {
            clearCompanyDisplay();
            return;
        }
        
        // You can fetch company details here if needed
        // For now, we'll just update based on the selected option
        const selectedOption = this.options[this.selectedIndex];
        const companyName = selectedOption.text;
        
        // Update company display (this would be enhanced with actual company data)
        updateCompanyDisplay({
            id: companyId,
            name: companyName,
            code: 'AIC' // Default code, should come from actual data
        });
    });
}

/**
 * Update company display
 */
function updateCompanyDisplay(company) {
    const companyCodeDisplay = document.getElementById('company-code-display');
    if (companyCodeDisplay) {
        companyCodeDisplay.innerHTML = `
            <span style="color: #dc3545; font-size: 16px;">${company.code || 'AIC'}</span>
            <span style="color: #999; font-size: 12px;">${company.short_name || 'steel'}</span>
        `;
    }
}

/**
 * Clear company display
 */
function clearCompanyDisplay() {
    const companyCodeDisplay = document.getElementById('company-code-display');
    if (companyCodeDisplay) {
        companyCodeDisplay.innerHTML = `
            <span style="color: #dc3545; font-size: 16px;">AIC</span>
            <span style="color: #999; font-size: 12px;">steel</span>
        `;
    }
    companyData = {};
}

/**
 * Initialize specimen type toggles
 */
function initializeSpecimenTypes() {
    const plateCheckbox = document.getElementById('plate_specimen');
    const pipeCheckbox = document.getElementById('pipe_specimen');
    
    if (plateCheckbox) {
        plateCheckbox.addEventListener('change', handleSpecimenTypeChange);
    }
    
    if (pipeCheckbox) {
        pipeCheckbox.addEventListener('change', handleSpecimenTypeChange);
    }
    
    // Initial check
    handleSpecimenTypeChange();
}

/**
 * Handle specimen type changes
 */
function handleSpecimenTypeChange() {
    const plateCheckbox = document.getElementById('plate_specimen');
    const pipeCheckbox = document.getElementById('pipe_specimen');
    const pipeField = document.getElementById('pipe_diameter_field');
    
    if (!plateCheckbox || !pipeCheckbox) return;
    
    // Show/hide pipe diameter field
    if (pipeField) {
        if (pipeCheckbox.checked) {
            pipeField.style.display = 'inline-block';
        } else {
            pipeField.style.display = 'none';
        }
    }
    
    // Update position range based on specimen type
    updatePositionRange();
}

/**
 * Initialize position updates
 */
function initializePositionUpdates() {
    const positionSelect = document.getElementById('test_position');
    if (positionSelect) {
        positionSelect.addEventListener('change', updatePositionRange);
    }
    
    // Initial update
    updatePositionRange();
}

/**
 * Update position qualification range
 */
function updatePositionRange() {
    const position = document.getElementById('test_position')?.value || '1G';
    const isPipe = document.getElementById('pipe_specimen')?.checked || false;
    const rangeDisplay = document.getElementById('position_range_display');
    const hiddenField = document.getElementById('position_range');
    
    if (!rangeDisplay) return;
    
    const ranges = getPositionRanges(position, isPipe);
    
    // Update display
    rangeDisplay.innerHTML = ranges.join('<br>');
    
    // Update hidden field
    if (hiddenField) {
        hiddenField.value = ranges.join(' | ');
    }
    
    // Also update the position display in machine variables section
    const positionDisplay = document.getElementById('position_display');
    if (positionDisplay) {
        positionDisplay.textContent = position;
    }
    
    const positionActualField = document.querySelector('[name="position_actual"]');
    if (positionActualField) {
        positionActualField.value = position;
    }
}

/**
 * Get position qualification ranges
 */
function getPositionRanges(position, isPipe) {
    const ranges = [];
    
    switch (position) {
        case '1G':
            ranges.push('F for Groove Plate and Pipe Over 24 in. (610 mm) O.D.');
            if (isPipe) {
                ranges.push('F for Groove Pipe ≥ 2 7∕8 in. (73 mm) O.D.');
            }
            ranges.push('F for Fillet or Tack Plate and Pipe');
            break;
            
        case '2G':
            ranges.push('F & H for Groove Plate and Pipe Over 24 in. (610 mm) O.D.');
            if (isPipe) {
                ranges.push('F & H for Groove Pipe ≥ 2 7∕8 in. (73 mm) O.D.');
            }
            ranges.push('F & H for Fillet or Tack Plate and Pipe');
            break;
            
        case '3G':
            ranges.push('F & V for Groove Plate and Pipe Over 24 in. (610 mm) O.D.');
            if (isPipe) {
                ranges.push('F & V for Groove Pipe ≥ 2 7∕8 in. (73 mm) O.D.');
            }
            ranges.push('F, H & V for Fillet or Tack Plate and Pipe');
            break;
            
        case '4G':
            ranges.push('F & O for Groove Plate and Pipe Over 24 in. (610 mm) O.D.');
            if (isPipe) {
                ranges.push('F & O for Groove Pipe ≥ 2 7∕8 in. (73 mm) O.D.');
            }
            ranges.push('F, H & O for Fillet or Tack Plate and Pipe');
            break;
            
        case '5G':
            ranges.push('F, V & O for Groove Plate and Pipe Over 24 in. (610 mm) O.D.');
            if (isPipe) {
                ranges.push('F, V & O for Groove Pipe ≥ 2 7∕8 in. (73 mm) O.D.');
            }
            ranges.push('All positions for Fillet or Tack Plate and Pipe');
            break;
            
        case '6G':
            ranges.push('Groove Plate and Pipe Over 24 in. (610 mm) O.D. in all Position');
            if (isPipe) {
                ranges.push('Groove Pipe ≤24 in. (610 mm) O.D. in all Position');
            }
            ranges.push('Fillet or Tack Plate and Pipe in all Position');
            break;
            
        default:
            ranges.push('F for Groove Plate and Pipe Over 24 in. (610 mm) O.D.');
            ranges.push('F for Fillet or Tack Plate and Pipe');
    }
    
    return ranges;
}

/**
 * Initialize welding parameters
 */
function initializeWeldingParameters() {
    // Initialize backing range updates
    const backingSelect = document.getElementById('backing');
    if (backingSelect) {
        backingSelect.addEventListener('change', updateBackingRange);
        updateBackingRange(); // Initial update
    }
    
    // Initialize visual control updates
    const visualControlSelect = document.querySelector('[name="visual_control_type"]');
    if (visualControlSelect) {
        visualControlSelect.addEventListener('change', updateVisualControlRange);
        updateVisualControlRange(); // Initial update
    }
    
    // Initialize joint tracking updates
    const jointTrackingSelect = document.querySelector('[name="joint_tracking"]');
    if (jointTrackingSelect) {
        jointTrackingSelect.addEventListener('change', updateJointTrackingRange);
        updateJointTrackingRange(); // Initial update
    }
    
    // Initialize passes updates
    const passesSelect = document.querySelector('[name="passes_per_side"]');
    if (passesSelect) {
        passesSelect.addEventListener('change', updatePassesRange);
        updatePassesRange(); // Initial update
    }
}

/**
 * Update backing range
 */
function updateBackingRange() {
    const backing = document.getElementById('backing')?.value || 'With backing';
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

/**
 * Update visual control range
 */
function updateVisualControlRange() {
    const visualControl = document.querySelector('[name="visual_control_type"]')?.value || 'Direct Visual Control';
    const rangeSpan = document.getElementById('visual_control_range');
    const hiddenField = document.querySelector('[name="visual_control_range"]');
    
    // For SAW, the range is typically the same as the actual value
    if (rangeSpan) rangeSpan.textContent = visualControl;
    if (hiddenField) hiddenField.value = visualControl;
}

/**
 * Update joint tracking range
 */
function updateJointTrackingRange() {
    const jointTracking = document.querySelector('[name="joint_tracking"]')?.value || 'With Automatic joint tracking';
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

/**
 * Update passes range
 */
function updatePassesRange() {
    const passes = document.querySelector('[name="passes_per_side"]')?.value || 'multiple passes per side';
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

/**
 * Initialize test results functionality
 */
function initializeTestResults() {
    // Initialize RT/UT checkbox behavior
    const rtCheckbox = document.getElementById('rt_selected');
    const utCheckbox = document.getElementById('ut_selected');
    
    if (rtCheckbox) {
        rtCheckbox.addEventListener('change', handleTestMethodChange);
    }
    
    if (utCheckbox) {
        utCheckbox.addEventListener('change', handleTestMethodChange);
    }
    
    // Initial check
    handleTestMethodChange();
}

/**
 * Handle test method changes (RT/UT)
 */
function handleTestMethodChange() {
    const rtChecked = document.getElementById('rt_selected')?.checked || false;
    const utChecked = document.getElementById('ut_selected')?.checked || false;
    
    // You can add logic here to show/hide related fields based on test methods selected
    console.log('RT selected:', rtChecked, 'UT selected:', utChecked);
}

/**
 * Initialize certificate numbering
 */
function initializeCertificateNumbering() {
    const companySelect = document.getElementById('company_id');
    const certNoField = document.getElementById('certificate_no');
    
    if (!companySelect || !certNoField) return;
    
    companySelect.addEventListener('change', function() {
        // You could implement automatic certificate number generation here
        // based on the selected company
        console.log('Company changed, could update certificate number');
    });
}

/**
 * Initialize photo upload functionality
 */
function initializePhotoUpload() {
    const photoInput = document.getElementById('photo');
    if (photoInput) {
        photoInput.addEventListener('change', handlePhotoUpload);
    }
}

/**
 * Handle photo upload preview
 */
function handlePhotoUpload(event) {
    const file = event.target.files[0];
    const preview = document.getElementById('photo-preview');
    
    if (!file || !preview) return;
    
    // Validate file type
    const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png'];
    if (!allowedTypes.includes(file.type)) {
        showNotification('error', 'Please select a valid image file (JPEG, JPG, or PNG)');
        event.target.value = '';
        return;
    }
    
    // Validate file size (2MB max)
    const maxSize = 2 * 1024 * 1024; // 2MB
    if (file.size > maxSize) {
        showNotification('error', 'Image file size must be less than 2MB');
        event.target.value = '';
        return;
    }
    
    // Show preview
    const reader = new FileReader();
    reader.onload = function(e) {
        preview.innerHTML = `<img src="${e.target.result}" alt="Photo Preview" class="preview-image">`;
    };
    reader.readAsDataURL(file);
}

/**
 * Initialize date formatting
 */
function initializeDateFormatting() {
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        input.addEventListener('change', function() {
            formatDateDisplay(this);
        });
    });
}

/**
 * Format date display
 */
function formatDateDisplay(input) {
    if (!input.value) return;
    
    const date = new Date(input.value);
    const formattedDiv = document.getElementById('formatted_date');
    
    if (formattedDiv) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        formattedDiv.textContent = date.toLocaleDateString('en-US', options);
    }
}

/**
 * Show field loading state
 */
function showFieldLoading(fieldId) {
    const field = document.getElementById(fieldId);
    if (field) {
        field.value = 'Loading...';
        field.disabled = true;
        field.classList.add('loading');
    }
}

/**
 * Hide field loading state
 */
function hideFieldLoading(fieldId) {
    const field = document.getElementById(fieldId);
    if (field) {
        field.disabled = false;
        field.classList.remove('loading');
        if (field.value === 'Loading...') {
            field.value = '';
        }
    }
}

/**
 * Show notification to user
 */
function showNotification(type, message) {
    // Create notification element if it doesn't exist
    let notificationContainer = document.getElementById('notification-container');
    
    if (!notificationContainer) {
        notificationContainer = document.createElement('div');
        notificationContainer.id = 'notification-container';
        notificationContainer.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            max-width: 400px;
        `;
        document.body.appendChild(notificationContainer);
    }
    
    // Create notification
    const notification = document.createElement('div');
    const bgColor = type === 'success' ? '#d1e7dd' : type === 'error' ? '#f8d7da' : '#d1ecf1';
    const textColor = type === 'success' ? '#0f5132' : type === 'error' ? '#842029' : '#055160';
    const borderColor = type === 'success' ? '#badbcc' : type === 'error' ? '#f5c2c7' : '#b8daff';
    
    notification.style.cssText = `
        background-color: ${bgColor};
        color: ${textColor};
        border: 1px solid ${borderColor};
        border-radius: 4px;
        padding: 12px 16px;
        margin-bottom: 10px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        opacity: 0;
        transition: opacity 0.3s ease-in-out;
    `;
    
    notification.innerHTML = `
        <div style="display: flex; justify-content: between; align-items: center;">
            <span>${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" style="background: none; border: none; color: ${textColor}; font-size: 18px; cursor: pointer; margin-left: 10px;">&times;</button>
        </div>
    `;
    
    // Add to container
    notificationContainer.appendChild(notification);
    
    // Fade in
    setTimeout(() => {
        notification.style.opacity = '1';
    }, 10);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.style.opacity = '0';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 5000);
}

/**
 * Validate required fields
 */
function validateRequiredFields() {
    const requiredFields = [
        'certificate_no',
        'welder_id',
        'company_id',
        'wps_followed',
        'test_date',
        'base_metal_spec',
        'dia_thickness',
        'welding_supervised_by',
        'witness_date'
    ];
    
    let isValid = true;
    const missingFields = [];
    
    requiredFields.forEach(fieldName => {
        const field = document.querySelector(`[name="${fieldName}"]`);
        if (field) {
            const value = field.type === 'checkbox' ? field.checked : field.value?.trim();
            
            if (!value) {
                field.classList.add('is-invalid');
                isValid = false;
                
                // Get field label for better error message
                const label = document.querySelector(`label[for="${field.id}"]`);
                const fieldLabel = label ? label.textContent.replace(':', '') : fieldName;
                missingFields.push(fieldLabel);
            } else {
                field.classList.remove('is-invalid');
            }
        }
    });
    
    if (!isValid && missingFields.length > 0) {
        showNotification('error', `Please fill in the following required fields: ${missingFields.join(', ')}`);
        
        // Scroll to first invalid field
        const firstInvalidField = document.querySelector('.is-invalid');
        if (firstInvalidField) {
            firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            firstInvalidField.focus();
        }
    }
    
    return isValid;
}

/**
 * Clear validation errors
 */
function clearValidationErrors() {
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
}

/**
 * Initialize form on document ready
 */
document.addEventListener('DOMContentLoaded', function() {
    initializeSawForm();
    
    console.log('SAW Certificate Form JavaScript loaded and initialized');
});

// Export functions for use in other scripts
window.SawCertificateForm = {
    validateRequiredFields,
    clearValidationErrors,
    showNotification,
    updatePositionRange,
    updateBackingRange,
    handleSpecimenTypeChange
};