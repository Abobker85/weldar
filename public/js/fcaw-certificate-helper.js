/**
 * Helper functions for FCAW certificate form
 */

/**
 * Helper function to ensure a hidden input exists with the correct value
 */
function ensureHiddenInput(form, name, value) {
    // Check if hidden input already exists
    let input = form.querySelector(`input[name="${name}"][type="hidden"]`);
    
    // If input doesn't exist, create it
    if (!input) {
        input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        form.appendChild(input);
    }
    
    // Set the value
    input.value = value;
}

/**
 * Updates test fields based on RT/UT selection
 * Makes evaluated_company field always editable with no default value
 */
function updateTestFields() {
    const rtCheckbox = document.getElementById('rt');
    const utCheckbox = document.getElementById('ut');
    const evaluatedCompanyField = document.querySelector('input[name="evaluated_company"]');
    
    if (!rtCheckbox || !utCheckbox || !evaluatedCompanyField) return;
    
    const updateField = () => {
        // Always keep evaluated_company editable
        evaluatedCompanyField.readOnly = false;
        
        // Remove required attribute if RT or UT is checked
        if (rtCheckbox.checked || utCheckbox.checked) {
            evaluatedCompanyField.removeAttribute('required');
            // Don't set any default value, leave as is
        } else {
            evaluatedCompanyField.setAttribute('required', 'required');
            // Don't set any default value, leave as is
        }
    };
    
    // Add event listeners
    rtCheckbox.addEventListener('change', updateField);
    utCheckbox.addEventListener('change', updateField);
    
    // Initialize on page load
    updateField();
}

/**
 * Updates vertical progression terminology from Upward/Downward to Uphill/Downhill
 */
function updateVerticalProgressionTerminology() {
    const verticalProgression = document.getElementById('vertical_progression');
    if (!verticalProgression) return;
    
    // Get all options
    const options = verticalProgression.options;
    
    // Update terminology
    for (let i = 0; i < options.length; i++) {
        if (options[i].value === 'Upward') {
            options[i].value = 'Uphill';
            options[i].text = 'Uphill';
        } else if (options[i].value === 'Downward') {
            options[i].value = 'Downhill';
            options[i].text = 'Downhill';
        }
    }
    
    // Initial update
    updateVerticalProgressionRange();
    
    // Add event listener to update the range span when selection changes
    verticalProgression.addEventListener('change', updateVerticalProgressionRange);
}

/**
 * Update vertical progression range based on selected vertical progression
 * This function needs to be globally accessible
 */
function updateVerticalProgressionRange() {
    const verticalProgression = document.getElementById('vertical_progression');
    if (!verticalProgression) return;
    
    const verticalProgressionRangeSpan = document.getElementById('vertical_progression_range_span');
    const verticalProgressionRangeInput = document.getElementById('vertical_progression_range');
    
    if (!verticalProgressionRangeSpan && !verticalProgressionRangeInput) return;
    
    let rangeText = '';
    if (verticalProgression.value === 'Uphill' || verticalProgression.value === 'Upward') {
        rangeText = 'Uphill';
    } else if (verticalProgression.value === 'Downhill' || verticalProgression.value === 'Downward') {
        rangeText = 'Downhill';
    } else {
        rangeText = verticalProgression.value;
    }
    
    // Update span if it exists
    if (verticalProgressionRangeSpan) {
        verticalProgressionRangeSpan.textContent = rangeText;
    }
    
    // Update hidden input if it exists
    if (verticalProgressionRangeInput) {
        verticalProgressionRangeInput.value = rangeText;
    }
}

/**
 * Update F-Number range based on selected filler F-Number
 * This function needs to be globally accessible
 */
function updateFNumberRange() {
    const fillerFNo = document.getElementById('filler_f_no');
    if (!fillerFNo) return;
    
    const fNumberRangeSpan = document.getElementById('f_number_range_span');
    const fNumberRangeInput = document.getElementById('f_number_range');
    
    if (!fNumberRangeInput) return;
    
    let rangeText = '';
    
    // Set F-Number range based on selection
    if (fillerFNo.value === '__manual__') {
        // If manual entry is selected, use the manual input value
        const manualInput = document.getElementById('filler_f_no_manual');
        if (manualInput && manualInput.value) {
            rangeText = manualInput.value;
        } else {
            rangeText = '';
        }
    } else if (fillerFNo.value === 'F-No.6') {
        rangeText = 'F-No.6 electrodes and welding processes only';
    } else {
        rangeText = fillerFNo.value;
    }
    
    // Update span if it exists
    if (fNumberRangeSpan) {
        fNumberRangeSpan.textContent = rangeText;
    }
    
    // Update hidden input
    fNumberRangeInput.value = rangeText;
    
    console.log('F-Number range updated:', rangeText);
}

/**
 * Toggle manual backing entry field
 */
function toggleManualBackingEntry() {
    const backing = document.getElementById('backing');
    const backingManual = document.getElementById('backing_manual');
    
    if (!backing || !backingManual) return;
    
    if (backing.value === '__manual__') {
        backingManual.style.display = 'block';
        backingManual.required = true;
    } else {
        backingManual.style.display = 'none';
        backingManual.required = false;
    }
}

/**
 * Update backing range based on selected backing type
 */
function updateBackingRange() {
    const backing = document.getElementById('backing');
    if (!backing) return;
    
    const backingRangeSpan = document.getElementById('backing_range_span');
    const backingRangeInput = document.getElementById('backing_range');
    
    if (!backingRangeInput) return;
    
    let rangeText = '';
    
    if (backing.value === '__manual__') {
        // If manual entry is selected, use the manual input value
        const manualInput = document.getElementById('backing_manual');
        if (manualInput && manualInput.value) {
            rangeText = manualInput.value;
        } else {
            rangeText = '';
        }
    } else if (backing.value === 'With Backing') {
        rangeText = 'With backing or backing and gouging';
    } else if (backing.value === 'Without Backing') {
        rangeText = 'Without backing or with backing and gouging';
    } else {
        rangeText = backing.value;
    }
    
    // Update span if it exists
    if (backingRangeSpan) {
        backingRangeSpan.textContent = rangeText;
    }
    
    // Update hidden input
    backingRangeInput.value = rangeText;
    
    console.log('Backing range updated:', rangeText);
}

/**
 * Handle plate/pipe specimen fields
 */
function handleSpecimenFields() {
    const plateSpecimenCheckbox = document.getElementById('plate_specimen');
    const pipeSpecimenCheckbox = document.getElementById('pipe_specimen');
    const pipeDiameterTypeSelect = document.getElementById('pipe_diameter_type');
    
    if (!plateSpecimenCheckbox || !pipeSpecimenCheckbox) return;

    // Add hidden input fields to ensure unchecked values are submitted
    const addHiddenInput = (checkbox) => {
        const hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = checkbox.name;
        hiddenInput.value = '0';
        checkbox.parentNode.insertBefore(hiddenInput, checkbox);
    };

    addHiddenInput(plateSpecimenCheckbox);
    addHiddenInput(pipeSpecimenCheckbox);
    
    // Function to handle specimen type toggle
    const handleSpecimenToggle = () => {
        // Enable/disable pipe diameter select based on pipe checkbox
        if (pipeDiameterTypeSelect) {
            pipeDiameterTypeSelect.disabled = !pipeSpecimenCheckbox.checked;
        }
        
        // Update diameter range span
        updateDiameterRange();
        
        // Update position options based on specimen type
        updatePositionOptions();
    };
    
    // Update diameter range span when plate/pipe selections change
    const updateDiameterRangeSpan = () => {
        const diameterRangeSpan = document.getElementById('diameter_range_span');
        if (!diameterRangeSpan) return;
        
        if (plateSpecimenCheckbox.checked) {
            diameterRangeSpan.textContent = '';
        }
    };
    
    // Add event listeners
    plateSpecimenCheckbox.addEventListener('change', handleSpecimenToggle);
    pipeSpecimenCheckbox.addEventListener('change', handleSpecimenToggle);
    
    // Expose the handleSpecimenToggle function globally
    window.handleSpecimenToggle = handleSpecimenToggle;
    
    // Initial update
    handleSpecimenToggle();
}

/**
 * Update position options based on plate/pipe selection
 */
function updatePositionOptions() {
    const plateCheckbox = document.getElementById('plate_specimen');
    const pipeCheckbox = document.getElementById('pipe_specimen');
    const positionSelect = document.getElementById('test_position');
    
    // Check if required elements exist
    if (!plateCheckbox || !pipeCheckbox || !positionSelect) {
        console.error('Required elements for updatePositionOptions not found');
        return;
    }

    // Store current value if possible
    const currentValue = positionSelect.value;

    // Clear existing options
    while (positionSelect.options.length > 0) {
        positionSelect.remove(0);
    }

    // Add default option
    const defaultOption = new Option('-- Select Position --', '');
    positionSelect.add(defaultOption);

    if (plateCheckbox.checked) {
        // For plate, show 1G, 2G, 3G, 4G
        const platePositions = ['1G', '2G', '3G', '4G'];
        platePositions.forEach(pos => {
            positionSelect.add(new Option(pos, pos));
        });
    } else if (pipeCheckbox.checked) {
        // For pipe, show 1G, 5G, 6G
        const pipePositions = ['1G', '5G', '6G'];
        pipePositions.forEach(pos => {
            positionSelect.add(new Option(pos, pos));
        });
    }

    // Try to restore the previous value if it still exists in the new options
    if (currentValue) {
        for (let i = 0; i < positionSelect.options.length; i++) {
            if (positionSelect.options[i].value === currentValue) {
                positionSelect.selectedIndex = i;
                break;
            }
        }
    }
}

/**
 * Handle form submission to ensure all fields are properly submitted
 */
function handleFormSubmission() {
    // Get the form
    const form = document.querySelector('form');
    if (!form) return;
    
    // Add event listener to the form submit event
    form.addEventListener('submit', function(e) {
        // Enable all disabled fields before submission
        const disabledFields = form.querySelectorAll('input[disabled], select[disabled], textarea[disabled]');
        disabledFields.forEach(field => {
            field.disabled = false;
        });
    });
}

/**
 * Update position range based on selected test position and specimen type
 * This function needs to be globally accessible
 */
function updatePositionRange() {
    const testPosition = document.getElementById('test_position');
    const pipeSpecimen = document.getElementById('pipe_specimen');
    const plateSpecimen = document.getElementById('plate_specimen');
    const positionRangeSpan = document.getElementById('position_range_span');
    const positionRangeInput = document.getElementById('position_range');
    
    if (!testPosition || !positionRangeInput) return;
    
    const position = testPosition.value;
    const isPipe = pipeSpecimen && pipeSpecimen.checked;
    
    console.log('Updating position range for position:', position);
    
    // Define position rules similar to GTAW certificate
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

    // Get the rules for the selected position, default to 6G if not found
    const rules = positionRules[position] || positionRules['6G'];
    
    // Format the HTML with proper styling for display
    let htmlRangeText = `
        <div style="margin-bottom:4px"><strong>Groove Plate and Pipe Over 24 in.:</strong> ${rules.groove_over_24}</div>
        <div style="margin-bottom:4px"><strong>Groove Pipe ≤24 in.:</strong> ${rules.groove_under_24}</div>
        <div><strong>Fillet or Tack:</strong> ${rules.fillet}</div>
    `;
    
    // Create the plain text version for form submission
    let rangeText = `${rules.groove_over_24} | ${rules.groove_under_24} | ${rules.fillet}`;
    
    // Update the range span for display
    if (positionRangeSpan) {
        positionRangeSpan.innerHTML = htmlRangeText;
    }
    
    // Update the hidden input for form submission
    positionRangeInput.value = rangeText;
    
    console.log('Position range updated:', rangeText);
}

/**
 * Update the diameter range based on pipe selection
 */
function updateDiameterRange() {
    const pipeSpecimen = document.getElementById('pipe_specimen');
    const pipeDiameterType = document.getElementById('pipe_diameter_type');
    const diameterRangeSpan = document.getElementById('diameter_range_span');
    const diameterRangeInput = document.getElementById('diameter_range');
    const pipeDiameterManual = document.getElementById('pipe_diameter_manual');
    
    if (!pipeSpecimen || !diameterRangeInput) return;
    
    let rangeText = '';
    
    if (pipeSpecimen.checked && pipeDiameterType) {
        const diameter = pipeDiameterType.value;
        
        // Show/hide manual input field based on selection
        if (pipeDiameterManual) {
            pipeDiameterManual.style.display = (diameter === '__manual__') ? 'block' : 'none';
        }
        
        switch (diameter) {
            case '8_nps':
                rangeText = 'Qualified for pipe diameter 8 inch (219.1 mm) and larger';
                break;
            case '6_nps':
                rangeText = 'Qualified for pipe diameter 6 inch (168.3 mm) and larger';
                break;
            case '4_nps':
                rangeText = 'Qualified for pipe diameter 4 inch (114.3 mm) and larger';
                break;
            case '2_nps':
                rangeText = 'Qualified for pipe diameter 2 inch (60.3 mm) to unlimited';
                break;
            case '1_nps':
                rangeText = 'Qualified for pipe diameter 1 inch (33.4 mm) to unlimited';
                break;
            case '__manual__':
                if (pipeDiameterManual && pipeDiameterManual.value) {
                    rangeText = `Qualified for pipe diameter ${pipeDiameterManual.value} and larger`;
                } else {
                    rangeText = 'Diameter range not defined';
                }
                break;
            default:
                rangeText = '';
        }
    } else {
        rangeText = ''; // Empty range for plate
        
        // Hide manual input if pipe is not selected
        if (pipeDiameterManual) {
            pipeDiameterManual.style.display = 'none';
        }
    }
    
    // Update the span for display
    if (diameterRangeSpan) {
        diameterRangeSpan.textContent = rangeText;
    }
    
    // Update the hidden input for form submission
    diameterRangeInput.value = rangeText;
    
    console.log('Diameter range updated:', rangeText);
}

/**
 * Function to update all range fields before form submission
 */
function updateAllRangeFields() {
    if (typeof updateDiameterRange === 'function') updateDiameterRange();
    if (typeof updatePositionRange === 'function') updatePositionRange();
    if (typeof updateVerticalProgressionRange === 'function') updateVerticalProgressionRange();
    if (typeof updateFNumberRange === 'function') updateFNumberRange();
    if (typeof updateBackingRange === 'function') updateBackingRange();
}

/**
 * Update oscillation range based on oscillation selection (dummy function since we're using static values)
 */
function updateOscillationRange() {
    // Do nothing, as we're using static values "..." for oscillation values and ranges
    console.log("Oscillation range update called - using static values");
}

/**
 * Update operation mode range based on mode selection (dummy function since we're using static values)
 */
function updateOperationModeRange() {
    // Do nothing, as we're using static values "..." for operation mode values and ranges
    console.log("Operation mode range update called - using static values");
}

// Initialize all helper functions when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    updateTestFields();
    updateVerticalProgressionTerminology();
    handleSpecimenFields();
    updatePositionOptions();
    handleFormSubmission();
    
    // Add event listener to test_position select for range updates
    const testPositionSelect = document.getElementById('test_position');
    if (testPositionSelect) {
        testPositionSelect.addEventListener('change', updatePositionRange);
        // Initial update
        updatePositionRange();
    }
    
    // Add event listener to pipe_diameter_type select for range updates
    const pipeDiameterTypeSelect = document.getElementById('pipe_diameter_type');
    if (pipeDiameterTypeSelect) {
        pipeDiameterTypeSelect.addEventListener('change', updateDiameterRange);
        // Initial update
        updateDiameterRange();
    }
});
