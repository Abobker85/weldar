// Remove any require statements from the top of this file
// For example, if there was something like:
// const someModule = require('some-module');
// That needs to be removed or replaced with browser-compatible code

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
            if (welderIdField) welderIdField.value = welder.id || '';
            
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
            if (companyIdField) companyIdField.value = welder.company_id || '';

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
            Swal.fire({
                title: 'Error',
                text: 'Failed to load welder data. Please try again.',
                icon: 'error'
            });
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
        if (welderIdField) welderIdField.value = welder.id || '';
        
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
        companyIdField.value = welder.company_id || '';
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

// Set current date in the test date field
function setCurrentDate() {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');

    const formattedDate = `${year}-${month}-${day}`;

    // Find the test_date input field
    const dateField = document.querySelector('input[name="test_date"]');
    if (dateField) {
        dateField.value = formattedDate;

        // Update the formatted date display if it exists
        formatDateDisplay(dateField);
    }
}

// Format date display in a readable format
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

// Update dia-thickness value when diameter or thickness changes
function updateDiaThickness() {
    const diameter = document.getElementById('diameter').value;
    const thickness = document.getElementById('thickness').value;
    document.getElementById('dia_thickness').value = `${diameter} x ${thickness}`;
}

// Update P-Number range based on selected P-Number
function updatePNumberRange() {
    const pNo = document.getElementById('base_metal_p_no').value;
    const pNumberRange = document.getElementById('p_number_range_span');
    const manualInput = document.getElementById('base_metal_p_no_manual');
    const manualRangeInput = document.getElementById('p_number_range_manual');

    if (pNo === '__manual__') {
        manualInput.style.display = 'block';
        manualRangeInput.style.display = 'block';
        pNumberRange.style.display = 'none';
        manualInput.focus();
    } else {
        manualInput.style.display = 'none';
        manualRangeInput.style.display = 'none';
        pNumberRange.style.display = 'block';
        
        // Use a fixed range text for all P-Number options
        const pNumberRangeText = 'P-NO. 1 through P-NO. 15F, P-NO. 34, and P-NO. 41 through P-NO. 49';
        
        // Set the range text
        pNumberRange.textContent = pNumberRangeText;
        
        // Update the hidden field for form submission
        const pNumberRangeHidden = document.getElementById('p_number_range');
        if (pNumberRangeHidden) {
            pNumberRangeHidden.value = pNumberRangeText;
        }
    }
}

// Update F-Number range based on selected F-Number
function updateFNumberRange() {
    const fNo = document.getElementById('filler_f_no').value;
    const fNumberRange = document.getElementById('f_number_range_span');
    const manualInput = document.getElementById('filler_f_no_manual');
    const manualRangeInput = document.getElementById('f_number_range_manual');

    if (fNo === '__manual__') {
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

    if (fNumberRange) {
        fNumberRange.textContent = fNumberRanges[fNo] || 'All F-No. 6';
    }
    
    // Update hidden field
    const fNumberRangeHidden = document.getElementById('f_number_range');
    if (fNumberRangeHidden) {
        fNumberRangeHidden.value = fNumberRange ? fNumberRange.textContent : 'All F-No. 6';
    }
}

// Update position range based on selected position
function updatePositionRange() {
    const positionSelect = document.getElementById('test_position');
    if (!positionSelect) return;

    const position = positionSelect.value;
    const rangeCells = document.querySelectorAll('.var-range[style*="font-weight: bold"]');
    if (rangeCells.length !== 3) return;

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
    rangeCells[0].textContent = rules.groove_over_24;
    rangeCells[1].textContent = rules.groove_under_24;
    rangeCells[2].textContent = rules.fillet;
    
    // Update the hidden field for form submission
    const positionRangeHidden = document.getElementById('position_range');
    if (positionRangeHidden) {
        const isPipe = document.getElementById('pipe_specimen')?.checked;
        if (isPipe) {
            positionRangeHidden.value = rules.groove_over_24 + ' | ' + rules.groove_under_24 + ' | ' + rules.fillet;
        } else {
            positionRangeHidden.value = rules.groove_over_24 + ' | ' + rules.fillet;
        }
    }
}

// Update backing range based on selected backing
function updateBackingRange() {
    const backing = document.getElementById('backing').value;
    const backingRange = document.getElementById('backing_range_span');
    
    if (!backingRange) return;
    
    let backingRangeText = '';
    
    // Set backing range text based on backing selection
    if (backing === 'With Backing') {
        backingRangeText = 'With backing';
    } else if (backing === 'Without Backing') {
        backingRangeText = 'With or Without backing';
    } else {
        backingRangeText = backing;
    }
        
    // Set backing range text
    backingRange.textContent = backingRangeText;
    
    // Update hidden field
    const backingRangeHidden = document.getElementById('backing_range');
    if (backingRangeHidden) {
        backingRangeHidden.value = backingRangeText;
    }
}

// Update backing gas range based on selected backing gas option
function updateBackingGasRange() {
    const backingGas = document.getElementById('backing_gas').value;
    const backingGasRange = document.getElementById('backing_gas_range_span');
    
    if (!backingGasRange) return;
    
    // Always use the same range regardless of selection
    const backingGasRangeText = 'With or Without backing Gas';
        
    // Set backing gas range text
    backingGasRange.textContent = backingGasRangeText;
    
    // Update hidden field
    const backingGasRangeHidden = document.getElementById('backing_gas_range');
    if (backingGasRangeHidden) {
        backingGasRangeHidden.value = backingGasRangeText;
    }
}

// Toggle visibility of pipe diameter field based on specimen type
function toggleDiameterField() {
    const plateCheckbox = document.getElementById('plate_specimen');
    const pipeCheckbox = document.getElementById('pipe_specimen');
    const diameterTypeField = document.getElementById('pipe_diameter_type');
    const diameterRangeSpan = document.getElementById('diameter_range_span');
    const diameterRangeHidden = document.getElementById('diameter_range');
    
    // If both are checked or only plate is checked
    if ((plateCheckbox && pipeCheckbox && plateCheckbox.checked && pipeCheckbox.checked) || 
        (plateCheckbox && plateCheckbox.checked && pipeCheckbox && !pipeCheckbox.checked)) {
        
        if (diameterRangeSpan) {
            diameterRangeSpan.textContent = 'Plate & Pipe';
        }
        
        if (diameterRangeHidden) {
            diameterRangeHidden.value = 'Plate & Pipe';
        }
        
        // If both are checked or only pipe is checked, show the diameter field
        if (diameterTypeField) {
            if (pipeCheckbox && pipeCheckbox.checked) {
                diameterTypeField.parentElement.style.display = 'block';
                
                // Make sure to update all ranges when pipe is selected
                setTimeout(function() {
                    if (typeof updateAllRangeFields === 'function') {
                        updateAllRangeFields();
                    }
                }, 100);
            } else {
                diameterTypeField.parentElement.style.display = 'none';
            }
        }
        
    } else if (pipeCheckbox && pipeCheckbox.checked) {
        // If only pipe is checked
        if (diameterTypeField) {
            diameterTypeField.parentElement.style.display = 'block';
        }
        
        // Update the diameter range text based on the selected diameter type
        updateDiameterRange();
    } else {
        // If neither is checked (shouldn't happen in normal usage)
        if (diameterTypeField) {
            diameterTypeField.parentElement.style.display = 'none';
        }
        
        if (diameterRangeSpan) {
            diameterRangeSpan.textContent = '';
        }
        
        if (diameterRangeHidden) {
            diameterRangeHidden.value = '';
        }
    }
}

// Update diameter range based on pipe diameter type
function updateDiameterRange() {
    const plateCheckbox = document.getElementById('plate_specimen');
    const pipeCheckbox = document.getElementById('pipe_specimen');
    const diameterType = document.getElementById('pipe_diameter_type').value;
    const diameterRange = document.getElementById('diameter_range_span');
    
    if (!diameterRange) return;
    
    // If plate is checked, always show "Plate & Pipe"
    if (plateCheckbox && plateCheckbox.checked) {
        diameterRange.textContent = 'Plate & Pipe';
        
        // Update hidden field
        const diameterRangeHidden = document.getElementById('diameter_range');
        if (diameterRangeHidden) {
            diameterRangeHidden.value = 'Plate & Pipe';
        }
        return;
    }
    
    let diameterRangeText = '';
    
    // Set range text based on diameter type
    switch (diameterType) {
        case '2_nps':
            diameterRangeText = 'Diameters 2 3/8 inch (60 mm) or larger';
            break;
        case '2_7_8_nps':
            diameterRangeText = 'Outside diameter 2 7/8 inch (73 mm) to unlimited';
            break;
        case '8_nps':
            diameterRangeText = 'Outside diameter 8 inch (219 mm) or larger';
            break;
        case 'less_than_8_nps':
            diameterRangeText = 'Outside diameter less than 8 inch (219 mm)';
            break;
        default:
            diameterRangeText = '';
    }
    
    // Set diameter range text
    diameterRange.textContent = diameterRangeText;
    
    // Update hidden field
    const diameterRangeHidden = document.getElementById('diameter_range');
    if (diameterRangeHidden) {
        diameterRangeHidden.value = diameterRangeText;
    }
}

// Update vertical progression range
function updateVerticalProgressionRange() {
    const progression = document.getElementById('vertical_progression').value;
    const progressionRange = document.getElementById('vertical_progression_range_span');
    
    if (!progressionRange) return;
    
    // Set progression range text based on selection
    const progressionRangeText = (progression === 'Upward') ? 'Upward' : progression;
    
    // Set vertical progression range text
    progressionRange.textContent = progressionRangeText;
    
    // Update hidden field
    const progressionRangeHidden = document.getElementById('vertical_progression_range');
    if (progressionRangeHidden) {
        progressionRangeHidden.value = progressionRangeText;
    }
}

// Update transfer mode range
function updateTransferModeRange() {
    const transferMode = document.getElementById('transfer_mode').value;
    const transferModeRange = document.getElementById('transfer_mode_range_span');
    
    if (!transferModeRange) return;
    
    let transferModeRangeText = '';
    
    // Set range text based on transfer mode
    switch (transferMode) {
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
            transferModeRangeText = transferMode;
    }
    
    // Set transfer mode range text
    transferModeRange.textContent = transferModeRangeText;
    
    // Update hidden field
    const transferModeRangeHidden = document.getElementById('transfer_mode_range');
    if (transferModeRangeHidden) {
        transferModeRangeHidden.value = transferModeRangeText;
    }
}

// Update equipment type range
function updateEquipmentTypeRange() {
    const equipmentTypeElement = document.getElementById('equipment_type');
    if (!equipmentTypeElement) return; // Exit if equipment_type element doesn't exist
    
    const equipmentType = equipmentTypeElement.value;
    const equipmentTypeRange = document.getElementById('equipment_type_range_span');
    
    if (!equipmentTypeRange) return;
    
    // Set equipment type range text (same as selected)
    equipmentTypeRange.textContent = equipmentType;
    
    // Update hidden field
    const equipmentTypeRangeHidden = document.getElementById('equipment_type_range');
    if (equipmentTypeRangeHidden) {
        equipmentTypeRangeHidden.value = equipmentType;
    }
}

// Update technique range
function updateTechniqueRange() {
    const techniqueElement = document.getElementById('technique');
    if (!techniqueElement) return; // Exit if technique element doesn't exist
    
    const technique = techniqueElement.value;
    const techniqueRange = document.getElementById('technique_range_span');
    
    if (!techniqueRange) return;
    
    // Set technique range text (same as selected)
    techniqueRange.textContent = technique;
    
    // Update hidden field
    const techniqueRangeHidden = document.getElementById('technique_range');
    if (techniqueRangeHidden) {
        techniqueRangeHidden.value = technique;
    }
}

// Update oscillation range
function updateOscillationRange() {
    const oscillationYesCheckbox = document.getElementById('oscillation_yes');
    const oscillationNoCheckbox = document.getElementById('oscillation_no');
    const oscillationValue = document.getElementById('oscillation_value');
    const oscillationRange = document.getElementById('oscillation_range_span');
    
    if (!oscillationRange) return;
    // If checkboxes don't exist, exit early
    if (!oscillationYesCheckbox && !oscillationNoCheckbox) return;
    
    // Determine if oscillation is enabled
    const isOscillationEnabled = oscillationYesCheckbox && oscillationYesCheckbox.checked;
    
    // Set oscillation range text based on checkbox and value
    let oscillationRangeText = isOscillationEnabled ? 
        'YES - ' + (oscillationValue ? oscillationValue.value : '') : 
        'NO';
    
    // Set oscillation range text
    oscillationRange.textContent = oscillationRangeText;
    
    // Update hidden field
    const oscillationRangeHidden = document.getElementById('oscillation_range');
    if (oscillationRangeHidden) {
        oscillationRangeHidden.value = oscillationRangeText;
    }
}

// Update operation mode range
function updateOperationModeRange() {
    const operationModeElement = document.getElementById('operation_mode');
    if (!operationModeElement) return; // Exit early if element doesn't exist
    
    const operationMode = operationModeElement.value;
    const operationModeRange = document.getElementById('operation_mode_range_span');
    
    if (!operationModeRange) return;
    
    // Set operation mode range text (same as selected)
    operationModeRange.textContent = operationMode;
    
    // Update hidden field
    const operationModeRangeHidden = document.getElementById('operation_mode_range');
    if (operationModeRangeHidden) {
        operationModeRangeHidden.value = operationMode;
    }
}

// Update all range fields for form submission
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
        
        if (document.getElementById('equipment_type')) {
            updateEquipmentTypeRange();
        }
        
        if (document.getElementById('technique')) {
            updateTechniqueRange();
        }
        
        // For oscillation, check the radio buttons
        if (document.getElementById('oscillation_yes') || document.getElementById('oscillation_no')) {
            updateOscillationRange();
        }
        
        if (document.getElementById('operation_mode')) {
            updateOperationModeRange();
        }
    } catch (error) {
        console.error("Error in updateAllRangeFields:", error);
        // Continue with form submission despite errors
    }
}

// Function to enable/disable RT/UT fields based on checkbox selection
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

// Function to validate required form fields
function validateRequiredFields() {
    // Get all required fields
    const requiredFields = document.querySelectorAll('[required]');
    let isValid = true;
    let firstInvalidField = null;

    // Check each required field
    requiredFields.forEach(field => {
        // Skip disabled fields as they won't be submitted
        if (field.disabled) return;

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
    }

    return isValid;
}

// Initialize form on document ready
document.addEventListener('DOMContentLoaded', function() {
    setCurrentDate();
    
    // Initialize RT/UT fields state
    updateTestFields();
    
    // Set up event listeners if elements exist
    const testPosition = document.getElementById('test_position');
    if (testPosition) {
        testPosition.addEventListener('change', updatePositionRange);
    }
    
    const backing = document.getElementById('backing');
    if (backing) {
        backing.addEventListener('change', updateBackingRange);
    }
    
    const backingGas = document.getElementById('backing_gas');
    if (backingGas) {
        backingGas.addEventListener('change', updateBackingGasRange);
        // Initialize with default value
        updateBackingGasRange();
    }
    
    const baseMetalPNo = document.getElementById('base_metal_p_no');
    if (baseMetalPNo) {
        baseMetalPNo.addEventListener('change', updatePNumberRange);
    }
    
    const fillerFNo = document.getElementById('filler_f_no');
    if (fillerFNo) {
        fillerFNo.addEventListener('change', updateFNumberRange);
    }
    
    const pipeDiameterType = document.getElementById('pipe_diameter_type');
    if (pipeDiameterType) {
        pipeDiameterType.addEventListener('change', updateDiameterRange);
    }
    
    // Set up event listeners for new fields
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
    
    const equipmentType = document.getElementById('equipment_type');
    if (equipmentType) {
        equipmentType.addEventListener('change', updateEquipmentTypeRange);
        // Initialize with default value
        updateEquipmentTypeRange();
    }
    
    const technique = document.getElementById('technique');
    if (technique) {
        technique.addEventListener('change', updateTechniqueRange);
        // Initialize with default value
        updateTechniqueRange();
    }
    
    // Set up oscillation radio buttons
    const oscillationYes = document.getElementById('oscillation_yes');
    const oscillationNo = document.getElementById('oscillation_no');
    const oscillationValue = document.getElementById('oscillation_value');
    
    if (oscillationYes) {
        oscillationYes.addEventListener('change', updateOscillationRange);
    }
    
    if (oscillationNo) {
        oscillationNo.addEventListener('change', updateOscillationRange);
    }
    
    if (oscillationValue) {
        oscillationValue.addEventListener('input', updateOscillationRange);
    }
    
    // Initialize oscillation range
    updateOscillationRange();
    
    const operationMode = document.getElementById('operation_mode');
    if (operationMode) {
        operationMode.addEventListener('change', updateOperationModeRange);
        // Initialize with default value
        updateOperationModeRange();
    }
    
    // Register the FCAW certificate handler function globally
    // This allows welder-search.js to call this function when a welder is selected
    console.log('FCAW certificate form initialized, registered fcawLoadWelderData handler');
});

// Form submission handler
window.submitCertificateForm = function(event) {
    // Prevent default form submission
    if (event) event.preventDefault();
    
    // Update all range fields before validation
    updateAllRangeFields();
    
    // Validate all required fields
    if (!validateRequiredFields()) {
        // Show error message if validation fails
        Swal.fire({
            title: 'Form Incomplete',
            text: 'Please fill in all required fields before submitting.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
        return false;
    }
    
    // If validation passes, submit the form
    const form = document.getElementById('certificate-form');
    if (form) {
        // Get form data for AJAX submission
        const formData = new FormData(form);
        
        // Show loading indicator
        Swal.fire({
            title: 'Submitting...',
            text: 'Please wait while the certificate is being created.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });
        
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
            if (!response.ok) {
                // Handle error response with JSON data
                throw new Error(data.message || 'An error occurred while submitting the form.');
            }
            
            // Handle success
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
        })
        .catch(error => {
            console.error('Form submission error:', error);
            
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
                }
            } catch (e) {
                console.error('Error parsing error message:', e);
            }
            
            // Show error message with details if available
            Swal.fire({
                title: 'Error',
                html: errorMessage + (errorDetails ? '<br>' + errorDetails : ''),
                icon: 'error',
                confirmButtonText: 'OK'
            });
        });
    }
    
    return false; // Always return false to prevent default form submission
};
