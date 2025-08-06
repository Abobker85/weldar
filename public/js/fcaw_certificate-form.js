/**
 * Complete FCAW Certificate Form JavaScript
 * Handles all functionality for FCAW certificate forms
 */

// Global variables
let currentSignatureTarget = null;
let modalSignaturePad = null;
let inspectorSignaturePad = null;

// Register the certificate-specific handler globally
window.fcawLoadWelderData = function(welderId, directData = null) {
    console.log('fcawLoadWelderData called with ID:', welderId);
    
    if (directData) {
        // If data is directly provided (like from handleBackendWelderData)
        try {
            window.handleBackendWelderData(directData);
        } catch (error) {
            console.error('Error in handleBackendWelderData:', error);
        }
        return;
    }
    
    if (!welderId) {
        console.error('No welder ID provided to fcawLoadWelderData');
        return;
    }
    
    // Build URL with origin to ensure correct path resolution
    const baseUrl = (() => {
        // Get base domain
        const origin = window.location.origin;
        
        // Check if we're in a subfolder deployment
        if (window.location.pathname.includes('/Weldar/public')) {
            return `${origin}/Weldar/public`;
        }
        
        return origin;
    })();
    
    // Use web-based API route instead of API route
    const apiUrl = `${baseUrl}/api/welders/${welderId}?certificate_type=fcaw`;
    const url = apiUrl;
    
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('fcawLoadWelderData received data:', data);
            
            if (!data) {
                throw new Error('No data received from API');
            }
            
            // Handle welder data
            let welder;
            if (data.welder) {
                welder = data.welder;
            } else if (data.id) {
                welder = data;
            } else {
                throw new Error('Invalid data format received from API');
            }
            
            // Update welder fields - add null checks for each element
            const welderIdField = document.getElementById('welder_id');
            if (welderIdField) {
                // For Select2, trigger change event
                if (typeof $ !== 'undefined' && $.fn.select2) {
                    $(welderIdField).val(welder.id).trigger('change');
                } else {
                    welderIdField.value = welder.id || '';
                }
            }
            
            const welderNameField = document.getElementById('welder_name');
            if (welderNameField) welderNameField.value = welder.name || '';
            
            const welderIdNoField = document.getElementById('welder_id_no');
            if (welderIdNoField) welderIdNoField.value = welder.welder_id || '';
            
            const iqamaNoField = document.getElementById('iqama_no');
            if (iqamaNoField) iqamaNoField.value = welder.iqama_no || '';
            
            const passportNoField = document.getElementById('passport_no');
            if (passportNoField) passportNoField.value = welder.passport_no || '';
            
            const companyNameField = document.getElementById('company_name');
            if (companyNameField) companyNameField.value = welder.company_name || '';
            
            const companyIdField = document.getElementById('company_id');
            if (companyIdField) {
                // For Select2, trigger change event
                if (typeof $ !== 'undefined' && $.fn.select2) {
                    $(companyIdField).val(welder.company_id).trigger('change');
                } else {
                    companyIdField.value = welder.company_id || '';
                }
            }

            // Update photo if available
            const photoPreview = document.getElementById('photo-preview');
            if (photoPreview) {
                if (welder.photo_path) {
                    photoPreview.innerHTML = `<img src="${welder.photo_path}" class="welder-photo-preview">`;
                } else {
                    photoPreview.innerHTML = `<div>No photo<br>available for<br>${welder.name}</div>`;
                }
            }
            
            // Update certificate numbers
            if (data.fcaw_certificate) {
                const certField = document.getElementById('certificate_no');
                if (certField) {
                    certField.value = data.fcaw_certificate;
                }
            }
            
            // Update report numbers
            if (data.vt_report_no) {
                const vtField = document.getElementById('vt_report_no');
                if (vtField) {
                    vtField.value = data.vt_report_no;
                }
            }
            
            if (data.rt_report_no) {
                const rtField = document.getElementById('rt_report_no');
                if (rtField) {
                    rtField.value = data.rt_report_no;
                }
            }
            
            if (data.ut_report_no) {
                const utField = document.getElementById('ut_report_no');
                if (utField) {
                    utField.value = data.ut_report_no;
                }
            }
            
            // Generate certificate numbers if needed
            if (typeof generateCertificateNumber === 'function' && !data.fcaw_certificate) {
                generateCertificateNumber(welder.company_id || null);
            }
        })
        .catch(error => {
            console.error('Error in fcawLoadWelderData:', error);
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    title: 'Error',
                    text: 'Failed to load welder data. Please try again.',
                    icon: 'error'
                });
            }
        });
};

// Handler function for backend data
window.handleBackendWelderData = function(data) {
    try {
        console.log('Received welder data from backend:', data);
        
        if (!data || !data.welder) {
            console.error('Invalid welder data received from backend');
            return;
        }
        
        const welder = data.welder;
        
        // Fill form fields with welder data - add null checks for all elements
        const welderIdField = document.getElementById('welder_id');
        if (welderIdField) {
            // For Select2, trigger change event
            if (typeof $ !== 'undefined' && $.fn.select2) {
                $(welderIdField).val(welder.id).trigger('change');
            } else {
                welderIdField.value = welder.id || '';
            }
        }
        
        const welderIdNoField = document.getElementById('welder_id_no');
        if (welderIdNoField) welderIdNoField.value = welder.welder_id || '';
        
        const iqamaNoField = document.getElementById('iqama_no');
        if (iqamaNoField) iqamaNoField.value = welder.iqama_no || '';
        
        const passportNoField = document.getElementById('passport_no');
        if (passportNoField) passportNoField.value = welder.passport_no || '';
    
        // Set company name if field exists
        const companyField = document.getElementById('company_name');
        if (companyField) {
            companyField.value = welder.company_name || '';
        }
        
        // Set company ID if field exists
        const companyIdField = document.getElementById('company_id');
        if (companyIdField) {
            // For Select2, trigger change event
            if (typeof $ !== 'undefined' && $.fn.select2) {
                $(companyIdField).val(welder.company_id).trigger('change');
            } else {
                companyIdField.value = welder.company_id || '';
            }
        }
        
        // Set certificate numbers
        if (data.fcaw_certificate) {
            const certNumberField = document.getElementById('certificate_no');
            if (certNumberField) {
                certNumberField.value = data.fcaw_certificate;
            }
        }
        
        // Set report numbers
        if (data.vt_report_no) {
            const vtReportField = document.getElementById('vt_report_no');
            if (vtReportField) {
                vtReportField.value = data.vt_report_no;
            }
        }
        
        if (data.rt_report_no) {
            const rtReportField = document.getElementById('rt_report_no');
            if (rtReportField) {
                rtReportField.value = data.rt_report_no;
            }
        }
        
        // Display welder photo if available
        if (welder.photo_path) {
            const photoPreview = document.getElementById('photo-preview');
            if (photoPreview) {
                photoPreview.innerHTML = `<img src="${welder.photo_path}" alt="Welder Photo" style="max-width:100%; max-height:200px;">`;
                photoPreview.classList.add('has-photo');
            }
        }
        
        console.log('Welder data successfully applied to form');
    } catch (error) {
        console.error('Error in handleBackendWelderData:', error);
        // Optionally show a user-friendly error message
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Error',
                text: 'Failed to load welder data. Please try again.',
                icon: 'error'
            });
        }
    }
};

// Define ValidationError class for handling validation errors
class ValidationError extends Error {
    constructor(data) {
        super('Validation error');
        this.name = 'ValidationError';
        this.errors = data.errors || {};
    }
}

/**
 * Initialize Select2 for dropdowns
 */
function initializeSelect2() {
    if (typeof $ !== 'undefined' && $.fn.select2) {
        $('.select2').select2({
            theme: 'bootstrap4',
            width: '100%',
            placeholder: function() {
                return $(this).data('placeholder') || 'Select an option';
            },
            allowClear: true
        });
    }
}

/**
 * Handle plate/pipe specimen toggle logic
 */
function handleSpecimenToggle() {
    const plateCheckbox = document.getElementById('plate_specimen');
    const pipeCheckbox = document.getElementById('pipe_specimen');
    const pipeDiameterSelect = document.getElementById('pipe_diameter_type');
    const pipeDiameterManual = document.getElementById('pipe_diameter_manual');
    const diameterRangeSpan = document.getElementById('diameter_range_span');
    const diameterRangeHidden = document.getElementById('diameter_range');
    
    if (!plateCheckbox || !pipeCheckbox || !pipeDiameterSelect) return;
    
    const plateChecked = plateCheckbox.checked;
    const pipeChecked = pipeCheckbox.checked;
    
    if (plateChecked && pipeChecked) {
        // Both checked - disable pipe diameter controls
        pipeDiameterSelect.disabled = true;
        if (pipeDiameterManual) pipeDiameterManual.disabled = true;
        
        if (diameterRangeSpan) diameterRangeSpan.textContent = 'Plate & Pipe';
        if (diameterRangeHidden) diameterRangeHidden.value = 'Plate & Pipe';
        
    } else if (plateChecked && !pipeChecked) {
        // Only plate checked - disable pipe diameter controls
        pipeDiameterSelect.disabled = true;
        if (pipeDiameterManual) pipeDiameterManual.disabled = true;
        
        if (diameterRangeSpan) diameterRangeSpan.textContent = 'Plate';
        if (diameterRangeHidden) diameterRangeHidden.value = 'Plate';
        
    } else if (!plateChecked && pipeChecked) {
        // Only pipe checked - enable pipe diameter controls
        pipeDiameterSelect.disabled = false;
        if (pipeDiameterManual) pipeDiameterManual.disabled = false;
        
        updateDiameterRange();
        
    } else {
        // Neither checked - disable all and clear
        pipeDiameterSelect.disabled = true;
        if (pipeDiameterManual) pipeDiameterManual.disabled = true;
        
        if (diameterRangeSpan) diameterRangeSpan.textContent = '';
        if (diameterRangeHidden) diameterRangeHidden.value = '';
    }
}

/**
 * Update diameter range based on pipe diameter selection
 */
function updateDiameterRange() {
    const pipeDiameterSelect = document.getElementById('pipe_diameter_type');
    const diameterRangeSpan = document.getElementById('diameter_range_span');
    const diameterRangeHidden = document.getElementById('diameter_range');
    
    if (!pipeDiameterSelect) return;
    
    // Check if plate is also selected
    const plateCheckbox = document.getElementById('plate_specimen');
    if (plateCheckbox && plateCheckbox.checked) {
        if (diameterRangeSpan) diameterRangeSpan.textContent = 'Plate & Pipe';
        if (diameterRangeHidden) diameterRangeHidden.value = 'Plate & Pipe';
        return;
    }
    
    const selectedValue = pipeDiameterSelect.value;
    let rangeText = '';
    
    switch (selectedValue) {
        case '8_nps':
            rangeText = 'Outside diameter 219.1 mm (8 NPS) and larger';
            break;
        case '6_nps':
            rangeText = 'Outside diameter 168.3 mm (6 NPS) to unlimited';
            break;
        case '4_nps':
            rangeText = 'Outside diameter 114.3 mm (4 NPS) to unlimited';
            break;
        case '2_nps':
            rangeText = 'Outside diameter 60.3 mm (2 NPS) to unlimited';
            break;
        case '1_nps':
            rangeText = 'Outside diameter 33.4 mm (1 NPS) to unlimited';
            break;
        case '__manual__':
            const manualInput = document.getElementById('pipe_diameter_manual');
            rangeText = manualInput ? manualInput.value : '';
            break;
        default:
            rangeText = selectedValue;
    }
    
    if (diameterRangeSpan) diameterRangeSpan.textContent = rangeText;
    if (diameterRangeHidden) diameterRangeHidden.value = rangeText;
}

/**
 * Toggle manual entry fields
 */
function toggleManualEntry(fieldName) {
    const selectField = document.getElementById(fieldName);
    const manualField = document.getElementById(fieldName + '_manual');
    const rangeSpan = document.getElementById(fieldName + '_range_span');
    const rangeManualField = document.getElementById(fieldName + '_range_manual');
    
    if (!selectField || !manualField) return;
    
    if (selectField.value === '__manual__') {
        manualField.style.display = 'block';
        manualField.required = true;
        
        if (rangeManualField) {
            rangeManualField.style.display = 'block';
        }
        if (rangeSpan) {
            rangeSpan.style.display = 'none';
        }
    } else {
        manualField.style.display = 'none';
        manualField.required = false;
        
        if (rangeManualField) {
            rangeManualField.style.display = 'none';
        }
        if (rangeSpan) {
            rangeSpan.style.display = 'block';
        }
        
        // Update range when selection changes
        updateFieldRange(fieldName);
    }
}

/**
 * Update field ranges based on selection
 */
function updateFieldRange(fieldName) {
    const selectField = document.getElementById(fieldName);
    const rangeSpan = document.getElementById(fieldName + '_range_span');
    const rangeHidden = document.getElementById(fieldName + '_range');
    
    if (!selectField) return;
    
    let rangeText = '';
    
    switch (fieldName) {
        case 'filler_spec':
            rangeText = selectField.value;
            break;
        case 'filler_class':
            rangeText = selectField.value;
            break;
        case 'filler_f_no':
            if (selectField.value === 'F-No.6') {
                rangeText = 'All F-No. 6';
            }
            break;
        case 'base_metal_p_no':
            rangeText = 'P-NO. 1 through P-NO. 15F, P-NO. 34, and P-NO. 41 through P-NO. 49';
            break;
        case 'backing':
            if (selectField.value === 'With Backing') {
                rangeText = 'With backing';
            } else if (selectField.value === 'Without Backing') {
                rangeText = 'With or Without backing';
            }
            break;
    }
    
    if (rangeSpan) rangeSpan.textContent = rangeText;
    if (rangeHidden) rangeHidden.value = rangeText;
}

/**
 * Update P-Number range
 */
function updatePNumberRange() {
    const pNo = document.getElementById('base_metal_p_no');
    const pNumberRange = document.getElementById('p_number_range_span');
    const pNumberRangeInput = document.getElementById('p_number_range');
    const manualInput = document.getElementById('base_metal_p_no_manual');
    const manualRangeInput = document.getElementById('p_number_range_manual');

    if (!pNo) return;

    if (pNo.value === '__manual__') {
        if (manualInput) manualInput.style.display = 'block';
        if (manualRangeInput) manualRangeInput.style.display = 'block';
        if (pNumberRange) pNumberRange.style.display = 'none';
        if (manualInput) manualInput.focus();
    } else {
        if (manualInput) manualInput.style.display = 'none';
        if (manualRangeInput) manualRangeInput.style.display = 'none';
        if (pNumberRange) pNumberRange.style.display = 'block';
        
        // Use a fixed range text for all P-Number options
        const pNumberRangeText = 'P-NO. 1 through P-NO. 15F, P-NO. 34, and P-NO. 41 through P-NO. 49';
        
        // Set the range text
        if (pNumberRange) pNumberRange.textContent = pNumberRangeText;
        
        // Update the hidden field for form submission
        if (pNumberRangeInput) pNumberRangeInput.value = pNumberRangeText;
    }
}

/**
 * Update F-Number range
 */
function updateFNumberRange() {
    const fNo = document.getElementById('filler_f_no');
    const fNumberRange = document.getElementById('f_number_range_span');
    const fNumberRangeInput = document.getElementById('f_number_range');
    const manualInput = document.getElementById('filler_f_no_manual');
    const manualRangeInput = document.getElementById('f_number_range_manual');

    if (!fNo) return;

    if (fNo.value === '__manual__') {
        if (manualInput) manualInput.style.display = 'block';
        if (manualRangeInput) manualRangeInput.style.display = 'block';
        if (fNumberRange) fNumberRange.style.display = 'none';
        if (manualInput) manualInput.focus();
        return;
    }

    if (manualInput) manualInput.style.display = 'none';
    if (manualRangeInput) manualRangeInput.style.display = 'none';
    if (fNumberRange) fNumberRange.style.display = 'block';

    const fNumberRanges = {
        'F-No.6': 'All F-No. 6'
    };

    const rangeText = fNumberRanges[fNo.value] || 'All F-No. 6';
    
    if (fNumberRange) fNumberRange.textContent = rangeText;
    if (fNumberRangeInput) fNumberRangeInput.value = rangeText;
}

/**
 * Update position range based on selected position
 */
function updatePositionRange() {
    const positionSelect = document.getElementById('test_position');
    if (!positionSelect) return;

    const position = positionSelect.value;
    const positionRangeInput = document.getElementById('position_range');
    const isPipe = document.getElementById('pipe_specimen')?.checked;
    
    const positionRules = {
        '1G': {
            groove_over_24: 'F for Groove Plate and Pipe Over 24 in. (610 mm) O.D.',
            groove_under_24: 'F for Groove Pipe ≤24 in. (610 mm) O.D.',
            fillet: 'F for Fillet or Tack Plate and Pipe'
        },
        '2G': {
            groove_over_24: 'F&H for Groove Plate and Pipe Over 24 in. (610 mm) O.D.',
            groove_under_24: 'F&H for Groove Pipe ≤24 in. (610 mm) O.D.',
            fillet: 'F&H for Fillet or Tack Plate and Pipe'
        },
        '3G': {
            groove_over_24: 'F&V for Groove Plate and Pipe Over 24 in. (610 mm) O.D.',
            groove_under_24: 'F for Groove Pipe ≤24 in. (610 mm) O.D.',
            fillet: 'F, H & V for Fillet or Tack Plate and Pipe'
        },
        '4G': {
            groove_over_24: 'F&O for Groove Plate and Pipe Over 24 in. (610 mm) O.D.',
            groove_under_24: 'F for Groove Pipe ≤24 in. (610 mm) O.D.',
            fillet: 'F, H & O for Fillet or Tack Plate and Pipe'
        },
        '5G': {
            groove_over_24: 'F,V&O for Groove Plate and Pipe Over 24 in. (610 mm) O.D.',
            groove_under_24: 'F,V&O for Groove Pipe ≤24 in. (610 mm) O.D.',
            fillet: 'All positions for Fillet or Tack Plate and Pipe'
        },
        '6G': {
            groove_over_24: 'Groove Plate and Pipe Over 24 in. (610 mm) O.D. in all Position',
            groove_under_24: 'Groove Pipe ≤24 in. (610 mm) O.D. in all Position',
            fillet: 'Fillet or Tack Plate and Pipe in all Position'
        }
    };

    const rules = positionRules[position] || positionRules['6G'];
    let rangeText = '';
    
    if (isPipe) {
        rangeText = rules.groove_over_24 + ' | ' + rules.groove_under_24 + ' | ' + rules.fillet;
    } else {
        rangeText = rules.groove_over_24 + ' | ' + rules.fillet;
    }
    
    if (positionRangeInput) {
        positionRangeInput.value = rangeText;
    }
}

/**
 * Update backing range based on selected backing
 */
function updateBackingRange() {
    const backing = document.getElementById('backing');
    const backingRange = document.getElementById('backing_range_span');
    const backingRangeInput = document.getElementById('backing_range');
    
    if (!backing || !backingRange) return;
    
    let backingRangeText = '';
    
    // Set backing range text based on backing selection
    if (backing.value === 'With Backing') {
        backingRangeText = 'With backing';
    } else if (backing.value === 'Without Backing') {
        backingRangeText = 'With or Without backing';
    } else {
        backingRangeText = backing.value;
    }
        
    // Set backing range text
    backingRange.textContent = backingRangeText;
    
    // Update hidden field
    if (backingRangeInput) {
        backingRangeInput.value = backingRangeText;
    }
}

/**
 * Update backing gas range based on selected backing gas option
 */
function updateBackingGasRange() {
    const backingGas = document.getElementById('backing_gas');
    const backingGasRange = document.getElementById('backing_gas_range_span');
    const backingGasRangeInput = document.getElementById('backing_gas_range');
    
    if (!backingGasRange) return;
    
    // Always use the same range regardless of selection
    const backingGasRangeText = 'With or Without backing Gas';
        
    // Set backing gas range text
    backingGasRange.textContent = backingGasRangeText;
    
    // Update hidden field
    if (backingGasRangeInput) {
        backingGasRangeInput.value = backingGasRangeText;
    }
}

/**
 * Update vertical progression range
 */
function updateVerticalProgressionRange() {
    const progression = document.getElementById('vertical_progression');
    const progressionRange = document.getElementById('vertical_progression_range_span');
    const progressionRangeInput = document.getElementById('vertical_progression_range');
    
    if (!progression || !progressionRange) return;
    
    // Set progression range text based on selection
    const progressionRangeText = (progression.value === 'Upward') ? 'Upward' : progression.value;
    
    // Set vertical progression range text
    progressionRange.textContent = progressionRangeText;
    
    // Update hidden field
    if (progressionRangeInput) {
        progressionRangeInput.value = progressionRangeText;
    }
}

/**
 * Update transfer mode range
 */
function updateTransferModeRange() {
    const transferMode = document.getElementById('transfer_mode');
    const transferModeRange = document.getElementById('transfer_mode_range_span');
    const transferModeRangeInput = document.getElementById('transfer_mode_range');
    
    if (!transferMode || !transferModeRange) return;
    
    let transferModeRangeText = '';
    
    // Set range text based on transfer mode
    switch (transferMode.value) {
        case 'spray':
            transferModeRangeText = 'spray, globular, or pulsed Spray';
            break;
        case 'globular':
            transferModeRangeText = 'spray, globular, or pulsed Spray';
            break;
        case 'pulse':
            transferModeRangeText = 'spray, globular, or pulsed Spray';
            break;
        case 'short circuit':
            transferModeRangeText = 'short circuit';
            break;
        default:
            transferModeRangeText = transferMode.value;
    }
    
    // Set transfer mode range text
    transferModeRange.textContent = transferModeRangeText;
    
    // Update hidden field
    if (transferModeRangeInput) {
        transferModeRangeInput.value = transferModeRangeText;
    }
}

/**
 * Calculate thickness range based on actual thickness
 */
function calculateThicknessRange(thickness, processType = 'fcaw') {
    const thicknessValue = parseFloat(thickness);
    let rangeValue = '';
    
    if (!isNaN(thicknessValue)) {
        if (thicknessValue <= 12) {
            // If thickness is 0-12, multiply by 2
            rangeValue = (thicknessValue * 2).toFixed(2) + ' mm';
        } else {
            // If thickness is 13 or greater, use "Maximum to be welded"
            rangeValue = 'Maximum to be welded';
        }
    }
    
    const rangeField = document.getElementById(processType + '_thickness_range');
    if (rangeField) {
        rangeField.value = rangeValue;
    }
}

/**
 * Update all range fields for form submission
 */
function updateAllRangeFields() {
    try {
        // Call each update function safely
        if (document.getElementById('pipe_diameter_type')) {
            updateDiameterRange();
        }
        
        if (document.getElementById('base_metal_p_no')) {
            updatePNumberRange();
        }
        
        if (document.getElementById('filler_f_no')) {
            updateFNumberRange();
        }
        
        if (document.getElementById('test_position')) {
            updatePositionRange();
        }
        
        if (document.getElementById('backing')) {
            updateBackingRange();
        }
        
        if (document.getElementById('backing_gas')) {
            updateBackingGasRange();
        }
        
        if (document.getElementById('vertical_progression')) {
            updateVerticalProgressionRange();
        }
        
        if (document.getElementById('transfer_mode')) {
            updateTransferModeRange();
        }
        
    } catch (error) {
        console.error("Error in updateAllRangeFields:", error);
        // Continue with form submission despite errors
    }
}

/**
 * Function to enable/disable RT/UT fields based on checkbox selection
 */
function updateTestFields() {
    const rtCheckbox = document.getElementById('rt');
    const utCheckbox = document.getElementById('ut');
    const evaluatedCompanyField = document.querySelector('input[name="evaluated_company"]');
    const mechanicalTestsField = document.querySelector('input[name="mechanical_tests_by"]');
    const labTestNoField = document.querySelector('input[name="lab_test_no"]');
    
    if (!rtCheckbox || !utCheckbox) return;
    
    const updateField = () => {
        if (rtCheckbox.checked || utCheckbox.checked) {
            // For evaluated_company field
            if (evaluatedCompanyField) {
                evaluatedCompanyField.removeAttribute('required');
                evaluatedCompanyField.readOnly = false;
                evaluatedCompanyField.value = '';
                evaluatedCompanyField.placeholder = 'Not required with RT/UT';
                evaluatedCompanyField.disabled = true;
            }
            
            // For mechanical_tests_by field
            if (mechanicalTestsField) {
                mechanicalTestsField.removeAttribute('disabled');
                // Remove required attribute to prevent backend validation
                mechanicalTestsField.removeAttribute('required');
            }
            
            // For lab_test_no field
            if (labTestNoField) {
                labTestNoField.removeAttribute('disabled');
                // Remove required attribute to prevent backend validation
                labTestNoField.removeAttribute('required');
            }
        } else {
            // For evaluated_company field
            if (evaluatedCompanyField) {
                evaluatedCompanyField.setAttribute('required', 'required');
                evaluatedCompanyField.readOnly = true;
                evaluatedCompanyField.value = 'SOGEC';
                evaluatedCompanyField.placeholder = '';
                evaluatedCompanyField.disabled = false;
            }
            
            // For mechanical_tests_by field
            if (mechanicalTestsField) {
                mechanicalTestsField.setAttribute('disabled', 'disabled');
                // Already removed required attribute
                mechanicalTestsField.value = '';
            }
            
            // For lab_test_no field
            if (labTestNoField) {
                labTestNoField.setAttribute('disabled', 'disabled');
                // Already removed required attribute
                labTestNoField.value = '';
            }
        }
        
        // Also handle report fields
        const rtReportField = document.getElementById('rt_report_no');
        const rtDocField = document.getElementById('rt_doc_no');
        const utReportField = document.getElementById('ut_report_no');
        const utDocField = document.getElementById('ut_doc_no');
        
        // Handle RT fields
        if (rtCheckbox && rtReportField && rtDocField) {
            if (rtCheckbox.checked) {
                rtReportField.removeAttribute('readonly');
                rtDocField.removeAttribute('readonly');
            } else {
                rtReportField.setAttribute('readonly', 'readonly');
                rtDocField.setAttribute('readonly', 'readonly');
                // Clear fields if unchecked
                rtDocField.value = '';
            }
        }
        
        // Handle UT fields if they exist
        if (utCheckbox && utReportField && utDocField) {
            if (utCheckbox.checked) {
                utReportField.removeAttribute('readonly');
                utDocField.removeAttribute('readonly');
            } else {
                utReportField.setAttribute('readonly', 'readonly');
                utDocField.setAttribute('readonly', 'readonly');
                // Clear fields if unchecked
                utDocField.value = '';
            }
        }
    };
    
    // Add event listeners
    rtCheckbox.addEventListener('change', updateField);
    utCheckbox.addEventListener('change', updateField);
    
    // Initialize on page load
    updateField();
}

/**
 * Set current date in the test date field
 */
function setCurrentDate() {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');

    const formattedDate = `${year}-${month}-${day}`;

    // Find the test_date input field
    const dateField = document.querySelector('input[name="test_date"]');
    if (dateField && !dateField.value) {
        dateField.value = formattedDate;

        // Update the formatted date display if it exists
        formatDateDisplay(dateField);
    }
}

/**
 * Format date display in a readable format
 */
function formatDateDisplay(dateInput) {
    if (!dateInput || !dateInput.value) return;

    const date = new Date(dateInput.value);
    if (isNaN(date.getTime())) return;

    const months = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September',
        'October', 'November', 'December'
    ];
    const day = date.getDate();
    const month = months[date.getMonth()];
    const year = date.getFullYear();

    const formattedDate = `${day} of ${month} ${year}`;
    const formattedDateElement = document.getElementById('formatted_date');
    if (formattedDateElement) {
        formattedDateElement.textContent = formattedDate;
    }
}

/**
 * Update dia-thickness value when diameter or thickness changes
 */
function updateDiaThickness() {
    const diameter = document.getElementById('diameter');
    const thickness = document.getElementById('thickness');
    const diaThicknessField = document.getElementById('dia_thickness');
    
    if (diameter && thickness && diaThicknessField) {
        diaThicknessField.value = `${diameter.value} x ${thickness.value}`;
    }
}

/**
 * Function to validate required form fields
 */
function validateRequiredFields() {
    // Get all required fields
    const requiredFields = document.querySelectorAll('[required]:not([disabled])');
    let isValid = true;
    let firstInvalidField = null;

    // Check each required field
    requiredFields.forEach(field => {
        // Reset previous validation styling
        field.classList.remove('is-invalid');
        
        // Check if the field is empty
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            isValid = false;
            
            // Store the first invalid field to focus on it later
            if (!firstInvalidField) {
                firstInvalidField = field;
            }
            
            // Add error message if not already present
            const fieldId = field.id || field.name;
            const errorElement = document.getElementById(`${fieldId}-error`);
            if (!errorElement) {
                const errorMsg = document.createElement('div');
                errorMsg.id = `${fieldId}-error`;
                errorMsg.className = 'invalid-feedback';
                errorMsg.textContent = 'This field is required.';
                field.parentNode.appendChild(errorMsg);
            }
        }
    });

    // Focus on the first invalid field if any
    if (firstInvalidField) {
        firstInvalidField.focus();
        firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
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
 * Display validation errors
 */
function displayValidationErrors(errors) {
    Object.keys(errors).forEach(fieldName => {
        const field = document.querySelector(`[name="${fieldName}"]`);
        if (field) {
            field.classList.add('is-invalid');
            
            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            errorDiv.textContent = errors[fieldName][0];
            
            field.parentNode.insertBefore(errorDiv, field.nextSibling);
        }
    });
}

/**
 * Initialize signature pads
 */
function initializeSignaturePads() {
    // Initialize main signature pad
    const canvas = document.getElementById('signature-pad');
    const signatureDataInput = document.getElementById('signature_data');
    
    if (canvas && typeof SignaturePad !== 'undefined') {
        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgba(255, 255, 255, 0.8)',
            penColor: 'black'
        });
        
        // Clear signature button
        const clearBtn = document.getElementById('clear-signature');
        if (clearBtn) {
            clearBtn.addEventListener('click', function() {
                signaturePad.clear();
                signatureDataInput.value = '';
            });
        }
        
        // Update hidden input when signature changes
        signaturePad.addEventListener("endStroke", () => {
            signatureDataInput.value = signaturePad.toDataURL();
        });
        
        // If there's an existing signature, load it
        if (signatureDataInput && signatureDataInput.value) {
            signaturePad.fromDataURL(signatureDataInput.value);
        }
        
        // Handle window resize to maintain signature pad aspect ratio
        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            
            // Redraw signature if it exists
            if (signatureDataInput && signatureDataInput.value) {
                signaturePad.fromDataURL(signatureDataInput.value);
            } else {
                signaturePad.clear(); // Otherwise isEmpty() might return incorrect value
            }
        }
        
        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();
    }
    
    // Initialize inspector signature functionality
    initializeInspectorSignature();
}

/**
 * Initialize inspector signature functionality
 */
function initializeInspectorSignature() {
    const inspectorSignBtn = document.getElementById('inspector-sign-btn');
    const inspectorSignatureDataInput = document.getElementById('inspector_signature_data');
    const inspectorSignaturePreview = document.getElementById('inspector-signature-preview');
    
    if (inspectorSignBtn) {
        // Add inspector signature modal if it doesn't exist
        if (!document.getElementById('inspector-signature-modal')) {
            const modalHtml = `
            <div id="inspector-signature-modal" class="modal" style="display: none; position: fixed; z-index: 1050; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4);">
                <div class="modal-dialog" style="position: relative; width: 600px; margin: 60px auto;">
                    <div class="modal-content" style="background-color: #fefefe; padding: 0; border: 1px solid #888; border-radius: 5px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; padding: 15px; border-bottom: 1px solid #e9ecef;">
                            <h5 style="margin: 0;">Add Inspector Signature</h5>
                            <button type="button" class="close-modal" style="background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
                        </div>
                        <div class="modal-body" style="padding: 20px;">
                            <div class="signature-pad-wrapper" style="width: 100%; border: 1px solid #ccc; border-radius: 4px;">
                                <canvas id="inspector-signature-canvas" width="560" height="200" style="width: 100%; height: 200px;"></canvas>
                            </div>
                        </div>
                        <div class="modal-footer" style="display: flex; justify-content: flex-end; padding: 15px; border-top: 1px solid #e9ecef;">
                            <button type="button" id="clear-inspector-signature" class="btn btn-secondary" style="background-color: #6c757d; color: white; border: none; padding: 8px 16px; margin-right: 10px; border-radius: 4px; cursor: pointer;">Clear</button>
                            <button type="button" id="save-inspector-signature" class="btn btn-primary" style="background-color: #007bff; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">Save Signature</button>
                        </div>
                    </div>
                </div>
            </div>`;
            
            document.body.insertAdjacentHTML('beforeend', modalHtml);
        }
        
        const inspectorModal = document.getElementById('inspector-signature-modal');
        const inspectorCanvas = document.getElementById('inspector-signature-canvas');
        
        if (inspectorCanvas && typeof SignaturePad !== 'undefined') {
            // Initialize signature pad
            inspectorSignaturePad = new SignaturePad(inspectorCanvas, {
                backgroundColor: 'rgba(255, 255, 255, 0.8)',
                penColor: 'black'
            });
            
            // Show modal when sign button is clicked
            inspectorSignBtn.addEventListener('click', function() {
                inspectorModal.style.display = 'block';
                
                // Resize canvas
                setTimeout(() => {
                    const ratio = Math.max(window.devicePixelRatio || 1, 1);
                    inspectorCanvas.width = inspectorCanvas.offsetWidth * ratio;
                    inspectorCanvas.height = inspectorCanvas.offsetHeight * ratio;
                    inspectorCanvas.getContext("2d").scale(ratio, ratio);
                    
                    // If there's already a signature, load it
                    if (inspectorSignatureDataInput && inspectorSignatureDataInput.value) {
                        inspectorSignaturePad.fromDataURL(inspectorSignatureDataInput.value);
                    }
                }, 100);
            });
            
            // Hide modal when clicking close button
            const closeBtn = document.querySelector('.close-modal');
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    inspectorModal.style.display = 'none';
                });
            }
            
            // Hide modal when clicking outside
            window.addEventListener('click', function(event) {
                if (event.target === inspectorModal) {
                    inspectorModal.style.display = 'none';
                }
            });
            
            // Clear signature
            const clearBtn = document.getElementById('clear-inspector-signature');
            if (clearBtn) {
                clearBtn.addEventListener('click', function() {
                    inspectorSignaturePad.clear();
                });
            }
            
            // Save signature
            const saveBtn = document.getElementById('save-inspector-signature');
            if (saveBtn) {
                saveBtn.addEventListener('click', function() {
                    if (inspectorSignaturePad.isEmpty()) {
                        alert('Please provide a signature');
                        return;
                    }
                    
                    const signatureData = inspectorSignaturePad.toDataURL();
                    if (inspectorSignatureDataInput) {
                        inspectorSignatureDataInput.value = signatureData;
                    }
                    if (inspectorSignaturePreview) {
                        inspectorSignaturePreview.innerHTML = `<img src="${signatureData}" alt="Inspector Signature" style="max-height: 40px;">`;
                    }
                    inspectorModal.style.display = 'none';
                    
                    console.log('Inspector signature saved');
                });
            }
            
            // Load existing signature if available
            if (inspectorSignatureDataInput && inspectorSignatureDataInput.value && inspectorSignaturePreview) {
                inspectorSignaturePreview.innerHTML = `<img src="${inspectorSignatureDataInput.value}" alt="Inspector Signature" style="max-height: 40px;">`;
            }
        }
    }
}

/**
 * Set default values
 */
function setDefaultValues() {
    // Set supervised_company to default value
    const supervisedCompanyField = document.getElementById('supervised_company');
    if (supervisedCompanyField && !supervisedCompanyField.value) {
        supervisedCompanyField.value = 'Elite Engineering Arabia';
    }
    
    // Set current date if not already set
    setCurrentDate();
    
    // Set default inspector date
    const inspectorDateField = document.querySelector('input[name="inspector_date"]');
    if (inspectorDateField && !inspectorDateField.value) {
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        inspectorDateField.value = `${year}-${month}-${day}`;
    }
}

/**
 * Main form submission handler
 */
window.submitCertificateForm = function(event) {
    // Prevent default form submission
    if (event) event.preventDefault();
    
    // Update all range fields before validation
    updateAllRangeFields();
    
    // Validate all required fields
    if (!validateRequiredFields()) {
        // Show error message if validation fails
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Form Incomplete',
                text: 'Please fill in all required fields before submitting.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        } else {
            alert('Please fill in all required fields before submitting.');
        }
        return false;
    }
    
    // If validation passes, submit the form
    const form = document.getElementById('certificate-form');
    if (!form) {
        console.error('Form with ID "certificate-form" not found!');
        return false;
    }
    
    // Enable all disabled fields before submission
    const disabledFields = document.querySelectorAll('input:disabled, select:disabled, textarea:disabled');
    disabledFields.forEach(field => {
        field.dataset.wasDisabled = "true";
        field.disabled = false;
    });
    
    // Get form data for AJAX submission
    const formData = new FormData(form);
    
    // Show loading indicator
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Submitting...',
            text: 'Please wait while the certificate is being created.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
    }
    
    // Submit form via AJAX
    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
        },
        credentials: 'same-origin'
    })
    .then(response => {
        // Check if response is JSON
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json().then(data => {
                // Return both the data and the response object for further processing
                return { data, response };
            });
        } else {
            // Not JSON, handle HTML response (error page)
            return response.text().then(html => {
                throw new Error('Server returned an HTML response instead of JSON. There might be validation errors.');
            });
        }
    })
    .then(({ data, response }) => {
        if (typeof Swal !== 'undefined') {
            Swal.close();
        }
        
        if (!response.ok) {
            // Handle error response with JSON data
            throw new Error(data.message || 'An error occurred while submitting the form.');
        }
        
        // Handle success
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Success',
                text: 'Certificate has been created successfully.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                // Redirect to the show page or wherever the server suggests
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    // Default redirect if none provided
                    window.location.href = form.dataset.successRedirect || '/fcaw-certificates';
                }
            });
        } else {
            alert('Certificate has been created successfully.');
            if (data.redirect) {
                window.location.href = data.redirect;
            }
        }
    })
    .catch(error => {
        console.error('Form submission error:', error);
        
        if (typeof Swal !== 'undefined') {
            Swal.close();
        }
        
        // Try to parse error.message to see if it's JSON
        let errorMessage = error.message || 'There was a problem submitting the certificate. Please check the form and try again.';
        let errorDetails = '';
        
        try {
            // Check if there are validation errors in a JSON format
            if (error.data && error.data.errors) {
                errorDetails = '<ul class="text-left">';
                for (const field in error.data.errors) {
                    if (error.data.errors.hasOwnProperty(field)) {
                        errorDetails += `<li>${error.data.errors[field].join('<br>')}</li>`;
                    }
                }
                errorDetails += '</ul>';
                displayValidationErrors(error.data.errors);
            }
        } catch (e) {
            console.error('Error parsing error message:', e);
        }
        
        // Show error notification
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                title: 'Error',
                html: errorMessage + (errorDetails ? '<br>' + errorDetails : ''),
                icon: 'error',
                confirmButtonText: 'OK'
            });
        } else {
            alert('Error: ' + errorMessage);
        }
    })
    .finally(() => {
        // Re-disable fields that were originally disabled
        disabledFields.forEach(field => {
            if (field.dataset.wasDisabled === 'true') {
                field.disabled = true;
                delete field.dataset.wasDisabled;
            }
        });
    });
    
    return false; // Always return false to prevent default form submission
};

/**
 * Initialize form on document ready
 */
document.addEventListener('DOMContentLoaded', function() {
    console.log('FCAW Certificate Form JavaScript Loaded');
    
    // Initialize Select2
    initializeSelect2();
    
    // Set default values
    setDefaultValues();
    
    // Initialize RT/UT fields state
    updateTestFields();
    
    // Initialize signature pads
    initializeSignaturePads();
    
    // Set up event listeners for specimen toggles
    const plateCheckbox = document.getElementById('plate_specimen');
    const pipeCheckbox = document.getElementById('pipe_specimen');
    if (plateCheckbox && pipeCheckbox) {
        plateCheckbox.addEventListener('change', handleSpecimenToggle);
        pipeCheckbox.addEventListener('change', handleSpecimenToggle);
        // Initialize on page load
        handleSpecimenToggle();
    }
    
    // Set up event listeners for range updates
    const testPosition = document.getElementById('test_position');
    if (testPosition) {
        testPosition.addEventListener('change', updatePositionRange);
    }
    
    const backing = document.getElementById('backing');
    if (backing) {
        backing.addEventListener('change', function() {
            updateBackingRange();
            toggleManualEntry('backing');
        });
    }
    
    const backingGas = document.getElementById('backing_gas');
    if (backingGas) {
        backingGas.addEventListener('change', updateBackingGasRange);
        // Initialize with default value
        updateBackingGasRange();
    }
    
    const baseMetalPNo = document.getElementById('base_metal_p_no');
    if (baseMetalPNo) {
        baseMetalPNo.addEventListener('change', function() {
            updatePNumberRange();
            toggleManualEntry('base_metal_p_no');
        });
    }
    
    const fillerFNo = document.getElementById('filler_f_no');
    if (fillerFNo) {
        fillerFNo.addEventListener('change', function() {
            updateFNumberRange();
            toggleManualEntry('filler_f_no');
        });
    }
    
    const fillerSpec = document.getElementById('filler_spec');
    if (fillerSpec) {
        fillerSpec.addEventListener('change', function() {
            updateFieldRange('filler_spec');
            toggleManualEntry('filler_spec');
        });
    }
    
    const fillerClass = document.getElementById('filler_class');
    if (fillerClass) {
        fillerClass.addEventListener('change', function() {
            updateFieldRange('filler_class');
            toggleManualEntry('filler_class');
        });
    }
    
    const pipeDiameterType = document.getElementById('pipe_diameter_type');
    if (pipeDiameterType) {
        pipeDiameterType.addEventListener('change', updateDiameterRange);
    }
    
    const verticalProgression = document.getElementById('vertical_progression');
    if (verticalProgression) {
        verticalProgression.addEventListener('change', updateVerticalProgressionRange);
        // Initialize with default value
        updateVerticalProgressionRange();
    }
    
    const transferMode = document.getElementById('transfer_mode');
    if (transferMode) {
        transferMode.addEventListener('change', updateTransferModeRange);
        // Initialize with default value
        updateTransferModeRange();
    }
    
    // Initialize thickness range calculation
    const fcawThickness = document.getElementById('fcaw_thickness');
    if (fcawThickness) {
        fcawThickness.addEventListener('input', function() {
            calculateThicknessRange(this.value, 'fcaw');
        });
        // Initialize if there's already a value
        if (fcawThickness.value) {
            calculateThicknessRange(fcawThickness.value, 'fcaw');
        }
    }
    
    // Initialize diameter/thickness update
    const diameterField = document.getElementById('diameter');
    const thicknessField = document.getElementById('thickness');
    if (diameterField && thicknessField) {
        diameterField.addEventListener('input', updateDiaThickness);
        thicknessField.addEventListener('input', updateDiaThickness);
        // Initialize if there are already values
        updateDiaThickness();
    }
    
    // Initialize all range fields
    setTimeout(function() {
        updateAllRangeFields();
    }, 500);
    
    console.log('FCAW certificate form initialized, registered fcawLoadWelderData handler');
});

// Make functions globally available
window.handleSpecimenToggle = handleSpecimenToggle;
window.updateDiameterRange = updateDiameterRange;
window.toggleManualEntry = toggleManualEntry;
window.calculateThicknessRange = calculateThicknessRange;
window.updateAllRangeFields = updateAllRangeFields;
window.updateTestFields = updateTestFields;
window.updatePNumberRange = updatePNumberRange;
window.updateFNumberRange = updateFNumberRange;
window.updatePositionRange = updatePositionRange;
window.updateBackingRange = updateBackingRange;
window.updateBackingGasRange = updateBackingGasRange;
window.updateVerticalProgressionRange = updateVerticalProgressionRange;
window.updateTransferModeRange = updateTransferModeRange;