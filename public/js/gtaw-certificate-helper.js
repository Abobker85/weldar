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
 * Makes evaluated_company field nullable when RT or UT is checked
 */
function updateTestFields() {
    const rtCheckbox = document.getElementById('rt');
    const utCheckbox = document.getElementById('ut');
    const evaluatedCompanyField = document.querySelector('input[name="evaluated_company"]');
    
    if (!rtCheckbox || !utCheckbox || !evaluatedCompanyField) return;
    
    const updateField = () => {
        if (rtCheckbox.checked || utCheckbox.checked) {
            evaluatedCompanyField.removeAttribute('required');
            // Let the field be editable always
            evaluatedCompanyField.readOnly = false;
            
            // Don't automatically clear value if user entered something
            if (evaluatedCompanyField.getAttribute('data-auto-filled') === 'true') {
                evaluatedCompanyField.value = '';
            }
        } else {
            evaluatedCompanyField.setAttribute('required', 'required');
            // Keep field editable but leave existing value if present
            evaluatedCompanyField.readOnly = false;
        }
    };
    
    // Add event listeners
    rtCheckbox.addEventListener('change', updateField);
    utCheckbox.addEventListener('change', updateField);
    
    // Mark field as auto-filled if it's empty
    if (!evaluatedCompanyField.value) {
        evaluatedCompanyField.setAttribute('data-auto-filled', 'true');
    }
    
    // Initialize on page load
    updateField();
}

/**
 * Updates vertical progression terminology from Upward/Downward to Uphill/Downhill
 */
function updateVerticalProgressionTerminology() {
    const verticalProgression = document.getElementById('vertical_progression');
    if (!verticalProgression) {
        console.log('Vertical progression element not found in updateVerticalProgressionTerminology');
        return;
    }
    
    // Check if this is a select element (it should be)
    if (!verticalProgression.options) {
        console.log('Vertical progression element does not have options property - may be wrong element type');
        return;
    }
    
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
    
    // Also update the hidden field if it exists
    const hiddenField = document.getElementById('vertical_progression_hidden');
    if (hiddenField) {
        if (hiddenField.value === 'Upward') {
            hiddenField.value = 'Uphill';
        } else if (hiddenField.value === 'Downward') {
            hiddenField.value = 'Downhill';
        }
    }
    
    // Update the vertical progression range span if applicable
    const updateVerticalProgressionRange = () => {
        const verticalProgressionRangeSpan = document.getElementById('vertical_progression_range_span');
        if (!verticalProgressionRangeSpan) return;
        
        if (verticalProgression.value === 'Uphill') {
            verticalProgressionRangeSpan.textContent = 'Uphill';
        } else if (verticalProgression.value === 'Downhill') {
            verticalProgressionRangeSpan.textContent = 'Downhill';
        } else {
            verticalProgressionRangeSpan.textContent = verticalProgression.value;
        }
    };
    
    // Add event listener to update the range span when selection changes
    verticalProgression.addEventListener('change', updateVerticalProgressionRange);
    
    // Initial update
    updateVerticalProgressionRange();
}

/**
 * Handle plate/pipe specimen fields
 */
function handleSpecimenFields() {
    const plateSpecimenCheckbox = document.getElementById('plate_specimen');
    const pipeSpecimenCheckbox = document.getElementById('pipe_specimen');
    
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
    
    // Update diameter range span when plate/pipe selections change
    const updateDiameterRangeSpan = () => {
        const diameterRangeSpan = document.getElementById('diameter_range_span');
        if (!diameterRangeSpan) return;
        
        if (plateSpecimenCheckbox.checked) {
            diameterRangeSpan.textContent = '';
        }
    };
    
    plateSpecimenCheckbox.addEventListener('change', updateDiameterRangeSpan);
    pipeSpecimenCheckbox.addEventListener('change', updateDiameterRangeSpan);
    
    // Initial update
    updateDiameterRangeSpan();
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

// Initialize all helper functions when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    updateTestFields();
    updateVerticalProgressionTerminology();
    handleSpecimenFields();
    handleFormSubmission();
});
