/**
 * Initialize form fields for editing SMAW certificates
 * This script handles proper initialization of form fields when editing an existing certificate
 */

document.addEventListener('DOMContentLoaded', function() {
    console.log('🔄 SMAW Certificate Edit: Initializing form fields from saved values');
    
    // Initialize test position from saved value
    initializeTestPosition();
    
    // Initialize vertical progression from saved value
    initializeVerticalProgression();
    
    // Initialize pipe/plate specimen selection
    initializeSpecimenSelection();
});

/**
 * Initialize test position dropdown with the saved value
 */
function initializeTestPosition() {
    const testPositionSelect = document.getElementById('test_position');
    if (testPositionSelect) {
        // Get the saved value from the data attribute
        const savedValue = testPositionSelect.getAttribute('data-saved-value');
        
        console.log('INIT: Found test_position element with data-saved-value:', savedValue);
        console.log('INIT: Current selected value:', testPositionSelect.value);
        
        // Force selection if we have a saved value, regardless of current selection
        if (savedValue && savedValue.trim() !== '') {
            console.log('INIT: Setting test_position to saved value:', savedValue);
            
            // First try: Directly set the value property
            testPositionSelect.value = savedValue;
            
            // Second try: Find and select the option by index
            let optionFound = false;
            for (let i = 0; i < testPositionSelect.options.length; i++) {
                // Log each option for debugging
                console.log(`Option ${i}: value='${testPositionSelect.options[i].value}'`);
                
                if (testPositionSelect.options[i].value === savedValue) {
                    console.log('INIT: Found matching option at index', i);
                    // Deselect all options first
                    for (let j = 0; j < testPositionSelect.options.length; j++) {
                        testPositionSelect.options[j].selected = false;
                    }
                    
                    // Select the target option
                    testPositionSelect.selectedIndex = i;
                    testPositionSelect.options[i].selected = true;
                    optionFound = true;
                    
                    // Force a value assignment again after setting selected
                    testPositionSelect.value = savedValue;
                    break;
                }
            }
            
            // Third try: Check if option exists, if not, create it
            if (!optionFound) {
                console.warn(`Option with value '${savedValue}' not found in dropdown, checking if it's missing`);
                
                // Check if the saved value is valid but missing from options
                if (['1G', '2G', '3G', '4G', '5G', '6G', '1F', '2F', '3F', '4F'].includes(savedValue)) {
                    console.log('Adding missing option for valid value:', savedValue);
                    const newOption = document.createElement('option');
                    newOption.value = savedValue;
                    newOption.text = savedValue;
                    newOption.selected = true;
                    testPositionSelect.appendChild(newOption);
                    testPositionSelect.value = savedValue;
                }
            }
            
            // Log final selection state
            console.log('INIT: After all attempts, test_position value is now:', testPositionSelect.value);
        }
        
        // Update position range after setting the value
        if (typeof updatePositionRange === 'function') {
            console.log('INIT: Calling updatePositionRange() to refresh position range');
            updatePositionRange();
        }
    } else {
        console.warn('INIT: test_position element not found!');
    }
}

/**
 * Initialize vertical progression dropdown with the saved value
 */
function initializeVerticalProgression() {
    const verticalProgressionSelect = document.getElementById('vertical_progression');
    if (verticalProgressionSelect) {
        // Get selected option
        const selectedOption = verticalProgressionSelect.querySelector('option[selected]');
        if (selectedOption) {
            // Value is already set by the blade template's selected attribute
            console.log('Vertical progression already set to:', selectedOption.value);
            
            // Update the range display
            if (typeof updatePositionVerticalProgressionRange === 'function') {
                updatePositionVerticalProgressionRange();
            }
        }
    }
}

/**
 * Initialize pipe/plate specimen checkboxes
 */
function initializeSpecimenSelection() {
    const plateSpecimen = document.getElementById('plate_specimen');
    const pipeSpecimen = document.getElementById('pipe_specimen');
    
    if (plateSpecimen && pipeSpecimen) {
        // If both checkboxes exist, ensure proper initialization
        if (plateSpecimen.checked && pipeSpecimen.checked) {
            // If both are checked, prioritize plate
            console.log('Both plate and pipe are checked, prioritizing plate');
            pipeSpecimen.checked = false;
        } else if (!plateSpecimen.checked && !pipeSpecimen.checked) {
            // If neither is checked, default to plate
            console.log('Neither plate nor pipe is checked, defaulting to plate');
            plateSpecimen.checked = true;
        }
        
        // Initialize pipe diameter field state
        if (typeof toggleDiameterField === 'function') {
            toggleDiameterField();
        }
    }
}
