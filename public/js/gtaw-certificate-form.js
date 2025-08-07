// Remove any require statements from the top of this file
// For example, if there was something like:
// const someModule = require('some-module');
// That needs to be removed or replaced with browser-compatible code

// Global debug helper for welder selection
window.debugWelderSelection = function(welderId, source) {
    console.log(`${source}: Welder selection detected for ID:`, welderId);
    console.trace(`${source}: Call stack trace`);
    
    // Log information about the current page
    console.log('Current URL:', window.location.href);
    console.log('Certificate handlers registered:',  {
        gtaw: typeof window.gtawLoadWelderData === 'function',
        smaw: typeof window.smawLoadWelderData === 'function',
        'gtaw-smaw': typeof window.gtawSmawLoadWelderData === 'function',
        fcaw: typeof window.fcawLoadWelderData === 'function'
    });
    
    // Original functions for reference
    if (window.gtawLoadWelderData) {
        console.log('Calling gtawLoadWelderData from debugWelderSelection');
        window.gtawLoadWelderData(welderId);
    } else {
        console.error('gtawLoadWelderData function not found in window scope!');
    }
};

// Function to handle welder data from backend
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
        
        const welderNameField = document.getElementById('welder_name');
        if (welderNameField) welderNameField.value = welder.name || '';
        
        const welderIqamaField = document.getElementById('welder_iqama_no');
        if (welderIqamaField) welderIqamaField.value = welder.iqama_no || '';
        
        const welderPassportField = document.getElementById('welder_passport_no');
        if (welderPassportField) welderPassportField.value = welder.passport_no || '';
    
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
    if (data.gtaw_certificate) {
        const certNumberField = document.getElementById('certificate_no');
        if (certNumberField) {
            certNumberField.value = data.gtaw_certificate;
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

// Create shadow function to detect if the original loadWelderData is called
function loadWelderData(welderId) {
    console.warn('⚠️ ORIGINAL loadWelderData called instead of gtawLoadWelderData! This might be causing your issue.');
    console.trace('Call stack for the original loadWelderData function:');
    
    // Fetch welder data from backend
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
    const apiUrl = `${baseUrl}/api/welders/${welderId}?certificate_type=smaw`;
    const url = apiUrl;
    
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('Successfully fetched welder data:', data);
            
            // Use our handler to process the data
            window.handleBackendWelderData(data);
            
            // Also call gtawLoadWelderData if it exists for backward compatibility
            if (typeof gtawLoadWelderData === 'function') {
                console.log('Additionally calling gtawLoadWelderData...');
                gtawLoadWelderData(welderId);
            }
        })
        .catch(error => {
            console.error('Error fetching welder data:', error);
            
            // Fall back to gtawLoadWelderData if fetch fails
            if (typeof gtawLoadWelderData === 'function') {
                console.log('Fetch failed, redirecting to gtawLoadWelderData...');
                gtawLoadWelderData(welderId);
            }
        });
}

// Store loadWelderData implementations to diagnose issues
window.certificateLoadFunctions = {
    gtaw: null,
    smaw: null,
    gtawSmaw: null
};

// Define the GTAW specific welder data loader function
window.gtawLoadWelderData = function(welderId, directData = null) {
    console.log('gtawLoadWelderData called with ID:', welderId);
    
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
        console.error('No welder ID provided to gtawLoadWelderData');
        return;
    }
    
    // Otherwise fetch data from the backend
    const url = `/api/welders/${welderId}?certificate_type=gtaw`;
    
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('gtawLoadWelderData received data:', data);
            window.handleBackendWelderData(data);
        })
        .catch(error => {
            console.error('Error in gtawLoadWelderData:', error);
            Swal.fire({
                title: 'Error',
                text: 'Failed to load welder data. Please try again.',
                icon: 'error'
            });
        });
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

// Set default values for SMAW process fields
function setDefaultSMAWValues() {
    // Set default values specific to SMAW process
    document.getElementById('welding_process').value = 'GTAW';
    document.getElementById('backing').value = 'With Backing';
    document.getElementById('pipe_diameter_type').value = '8_nps';
    document.getElementById('base_metal_p_no').value = 'P NO.1 TO P NO.1';
    document.getElementById('filler_spec').value = 'A5.1';
    document.getElementById('filler_class').value = 'E7018-1';
    document.getElementById('filler_f_no').value = 'F4_with_backing';
    document.getElementById('test_position').value = '6G';
    
    // Set appropriate vertical progression value based on options in the dropdown
    const verticalProgressionSelect = document.getElementById('vertical_progression');
    if (verticalProgressionSelect) {
        // Check if 'Uphill' or 'Upward' exists as an option
        let hasUphill = false;
        let hasUpward = false;
        for (let i = 0; i < verticalProgressionSelect.options.length; i++) {
            if (verticalProgressionSelect.options[i].value === 'Uphill') {
                hasUphill = true;
            } else if (verticalProgressionSelect.options[i].value === 'Upward') {
                hasUpward = true;
            }
        }
        
        // Set value based on available options
        if (hasUphill) {
            verticalProgressionSelect.value = 'Uphill';
        } else if (hasUpward) {
            verticalProgressionSelect.value = 'Upward';
        } else {
            // Default to first option if neither exists
            if (verticalProgressionSelect.options.length > 0) {
                verticalProgressionSelect.selectedIndex = 1; // Skip the first option if it's empty
            }
        }
    }

    // Update all dependent fields
    updateProcessFields();
    updateBackingRange();
    updateDiameterRange();
    updatePNumberRange();
    updateFNumberRange();
    updateVerticalProgressionRange();
}

// Update process fields based on selected welding process
function updateProcessFields() {
    const process = document.getElementById('welding_process');
    const processRange = document.getElementById('process_range');

    if (!process || !processRange) {
        // Elements not found, skip update
        return;
    }

    if (process.value === 'GTAW') {
        processRange.textContent = 'GTAW';
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
        
        // Update to use the same range for all P-Number options
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

    // Check if elements exist before proceeding
    if (!fNumberRange) {
        console.error('Element f_number_range_span not found in the DOM');
        return;
    }

    if (fNo === '__manual__') {
        if (manualInput) manualInput.style.display = 'block';
        if (manualRangeInput) manualRangeInput.style.display = 'block';
        fNumberRange.style.display = 'none';
        if (manualInput) manualInput.focus();
        return;
    }

    if (manualInput) manualInput.style.display = 'none';
    if (manualRangeInput) manualRangeInput.style.display = 'none';
    fNumberRange.style.display = 'block';

    const fNumberRanges = {
        'F-No.6': 'All F-No. 6',
        'F5_with_backing': 'F-No.1 with Backing & F-No.5 With Backing',
        'F4_without_backing': 'F-No.1 with Backing, F-No.2 with backing, F-No.3 with backing & F-No.4 With and Without Backing',
        'F5_without_backing': 'F-No.1 with Backing & F-No.5 With and without Backing',
        'F43': 'F-No. 34 and all F-No. 41 through F-No. 46',
        // Add any other F-Number options here
    };

    fNumberRange.textContent = fNumberRanges[fNo] || 'F-No.1 with Backing, F-No.2 with backing, F-No.3 with backing & F-No.4 With Backing';
    
    // Update the hidden field for form submission
    const fNumberRangeHidden = document.getElementById('f_number_range');
    if (fNumberRangeHidden) {
        fNumberRangeHidden.value = fNumberRange.textContent;
        console.log('Updated f_number_range to: ' + fNumberRange.textContent);
    }
    
    // Ensure the select has a valid option selected
    const fillerFNoSelect = document.getElementById('filler_f_no');
    if (fillerFNoSelect && (!fillerFNoSelect.value || fillerFNoSelect.value === '')) {
        // If no option is selected, select the first non-empty option
        for (let i = 0; i < fillerFNoSelect.options.length; i++) {
            if (fillerFNoSelect.options[i].value) {
                fillerFNoSelect.selectedIndex = i;
                break;
            }
        }
    }
}

// Toggle manual entry fields for various inputs
function toggleManualEntry(fieldType) {
    const selectElement = document.getElementById(fieldType);
    const manualInput = document.getElementById(`${fieldType}_manual`);

    if (selectElement.value === '__manual__') {
        manualInput.style.display = 'block';
        manualInput.focus();
    } else {
        manualInput.style.display = 'none';
    }
}

// Update vertical progression range
function updateVerticalProgressionRange() {
    console.log('==== updateVerticalProgressionRange called ====');
    
    // Get select element
    const verticalProgressionSelect = document.getElementById('vertical_progression');
    
    // Check if element exists and is a select element
    if (!verticalProgressionSelect) {
        console.error('Element vertical_progression not found in the DOM');
        return;
    }
    
    // Check if it's a select element with options
    if (!verticalProgressionSelect.options) {
        console.error('Element vertical_progression is not a select element or has no options property');
        return;
    }
    
    // Add debugging to show what options are available
    console.log('Available options in dropdown:');
    Array.from(verticalProgressionSelect.options).forEach((opt, idx) => {
        console.log(`Option ${idx}: value=${opt.value}, text=${opt.text}, selected=${opt.selected}`);
    });
    
    const progression = verticalProgressionSelect.value;
    console.log('Current dropdown selected value:', progression);
    
    const rangeElement = document.getElementById('vertical_progression_range_span');

    // Check if range element exists before setting properties
    if (!rangeElement) {
        console.error('Element vertical_progression_range_span not found in the DOM');
        return;
    }

    // Set range text based on selected value, handling both terminologies
    let rangeText;
    if (progression === 'None') {
        rangeText = 'None';
    } else if (progression === 'Uphill' || progression === 'Upward') {
        rangeText = 'Uphill';
    } else if (progression === 'Downhill' || progression === 'Downward') {
        rangeText = 'Downhill';
    } else {
        rangeText = progression; // Use the actual value if none of the above
    }

    console.log('Range text determined as:', rangeText);

    // Update the displayed text
    rangeElement.textContent = rangeText;
    
    // Update the hidden field for form submission
    const rangeHiddenField = document.getElementById('vertical_progression_range');
    if (rangeHiddenField) {
        rangeHiddenField.value = rangeText;
        console.log('Updated vertical_progression_range to: ' + rangeText);
    } else {
        console.error('Range hidden field not found in DOM!');
    }
    
    // Update the hidden input field that tracks vertical_progression
    const hiddenField = document.getElementById('vertical_progression_hidden');
    if (hiddenField) {
        hiddenField.value = progression;
        console.log('Updated vertical_progression_hidden to: ' + progression);
        
        // Add extra debugging to check what's going on
        console.log('After updating hidden fields in updateVerticalProgressionRange:');
        console.log('- select value:', progression);
        console.log('- hidden field value:', hiddenField.value);
        console.log('- range field value:', rangeHiddenField ? rangeHiddenField.value : 'not found');
    } else {
        console.error('Vertical progression hidden field not found in DOM!');
    }
    
    // Set the selected value in the dropdown if it's empty
    if (!progression) {
        console.log('No progression value selected, trying to set from range text:', rangeText);
        
        // Find and select appropriate option based on the range text
        for (let i = 0; i < verticalProgressionSelect.options.length; i++) {
            const optValue = verticalProgressionSelect.options[i].value;
            if ((rangeText === 'Uphill' && (optValue === 'Uphill' || optValue === 'Upward')) ||
                (rangeText === 'Downhill' && (optValue === 'Downhill' || optValue === 'Downward')) ||
                (rangeText === 'None' && optValue === 'None')) {
                verticalProgressionSelect.selectedIndex = i;
                console.log('Selected option index', i, 'with value', optValue);
                break;
            }
        }
    }
    
    console.log('==== updateVerticalProgressionRange completed ====');
}

// Reset form to default values
function resetForm() {
    Swal.fire({
        title: 'Reset Form?',
        text: "This will clear all entered data!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, reset it!'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('certificate-form').reset();
            document.getElementById('photo-preview').innerHTML = 'Click to upload<br>welder photo';
            setCurrentDate();
            setDefaultSMAWValues();
            updateProcessFields();
            toggleDiameterField();
            updatePositionOptions();
            Swal.fire('Reset', 'Form has been reset to default values.', 'success');
        }
    });
}

// Photo preview functionality
function previewPhoto(input) {
    const preview = document.getElementById('photo-preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML =
                `<img src="${e.target.result}" style="width: 80px; height: 110px; object-fit: cover;">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Preview stamp when uploaded
function previewStamp(input) {
    const preview = document.getElementById('stamp-preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Stamp">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

// Update backing range
function updateBackingRange() {
    const backing = document.getElementById('backing').value;
    const backingRange = document.getElementById('backing_range_span');
    const manualInput = document.getElementById('backing_manual');
    const manualRangeInput = document.getElementById('backing_range_manual');

    if (backing === '__manual__') {
        manualInput.style.display = 'block';
        manualRangeInput.style.display = 'block';
        backingRange.style.display = 'none';
        manualInput.focus();
    } else {
        manualInput.style.display = 'none';
        manualRangeInput.style.display = 'none';
        backingRange.style.display = 'block';
        
        // Set the appropriate backing range text
        const backingRangeText = (backing === 'With Backing') ? 
            'With backing  ' : 
            'With or Without backing';
        
        // Update the visible span text - make sure it's properly displayed
        backingRange.textContent = backingRangeText;
        backingRange.innerHTML = backingRangeText; // Also set innerHTML to ensure display
        
        console.log('Updated backing range to: ' + backingRangeText);
        
        // Update the hidden field for form submission
        const backingRangeHidden = document.getElementById('backing_range');
        if (backingRangeHidden) {
            backingRangeHidden.value = backingRangeText;
            console.log('Updated backing_range hidden field to: ' + backingRangeText);
        }
    }
}

// Update diameter range based on pipe size
function updateDiameterRange() {
    const diameterType = document.getElementById('pipe_diameter_type').value;
    const diameterRange = document.getElementById('diameter_range_span');
    const manualInput = document.getElementById('pipe_diameter_manual');
    const manualRangeInput = document.getElementById('diameter_range_manual');

    if (diameterType === '__manual__') {
        manualInput.style.display = 'block';
        manualRangeInput.style.display = 'block';
        diameterRange.style.display = 'none';
        manualInput.focus();
        return;
    }

    manualInput.style.display = 'none';
    manualRangeInput.style.display = 'none';
    diameterRange.style.display = 'block';

    const diameterRules = {
        '8_nps': {
            range: 'Outside diameter 2 7/8 inch (73 mm) to unlimited'
        },
        '6_nps': {
            range: 'Outside diameter 2 7/8 inch (73 mm) to unlimited'
        },
        '4_nps': {
            range: 'Outside diameter 2 7/8 inch (73 mm) to unlimited'
        },
        '2_nps': {
            range: 'Outside diameter 1 inch (25mm) to unlimited'
        },
        '1_nps': {
            range: 'Outside diameter 1 inch (25mm) to unlimited'
        }
    };

    if (diameterRules[diameterType]) {
        diameterRange.textContent = diameterRules[diameterType].range;
        
        // Update the hidden field for form submission
        const diameterRangeHidden = document.getElementById('diameter_range');
        if (diameterRangeHidden) {
            diameterRangeHidden.value = diameterRules[diameterType].range;
        }
    }
}

// Toggle diameter field based on plate/pipe selection
function toggleDiameterField() {
    const plateCheckbox = document.getElementById('plate_specimen');
    const pipeCheckbox = document.getElementById('pipe_specimen');
    const diameterField = document.getElementById('diameter');
    const pipeDiameterField = document.getElementById('pipe_diameter_type');
    const diameterRangeSpan = document.getElementById('diameter_range_span');

    if (plateCheckbox.checked) {
        pipeCheckbox.checked = false;
        diameterField.disabled = true;
        diameterField.value = "N/A";
        pipeDiameterField.disabled = true;

        // New: Clear pipe diameter dropdown when plate is selected
        pipeDiameterField.value = "";
        
        // Clear diameter range span when plate is selected
        if (diameterRangeSpan) {
            diameterRangeSpan.textContent = '';
        }
        
        // Update hidden diameter range field
        const diameterRangeHidden = document.getElementById('diameter_range');
        if (diameterRangeHidden) {
            diameterRangeHidden.value = '';
        }

        // Update position options based on plate selection
        updatePositionOptions();
    } else {
        pipeCheckbox.checked = true;
        diameterField.disabled = false;
        diameterField.value = "8 inch";
        pipeDiameterField.disabled = false;

        // New: Restore pipe diameter dropdown when pipe is selected
        pipeDiameterField.value = "8_nps";

        // Update position options based on pipe selection
        updatePositionOptions();
    }
    updateDiaThickness();
}

// Update position dropdown based on pipe/plate selection
function updatePositionOptions() {
    const plateCheckbox = document.getElementById('plate_specimen');
    const pipeCheckbox = document.getElementById('pipe_specimen');
    const positionSelect = document.getElementById('test_position');
    
    // Check if required elements exist
    if (!plateCheckbox || !pipeCheckbox || !positionSelect) {
        console.error('Required elements for updatePositionOptions not found');
        return;
    }

    // Store current value to preserve it
    const currentValue = positionSelect.value;
    console.log('Current position value before update:', currentValue);

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
        // For pipe, show 1G, 2G, 5G, 6G
        const pipePositions = ['1G', '2G', '5G', '6G'];
        pipePositions.forEach(pos => {
            positionSelect.add(new Option(pos, pos));
        });
    }

    // Try to restore the previous value if it's still valid
    let valueRestored = false;
    if (currentValue) {
        for (let i = 0; i < positionSelect.options.length; i++) {
            if (positionSelect.options[i].value === currentValue) {
                positionSelect.selectedIndex = i;
                valueRestored = true;
                console.log('Successfully restored position value:', currentValue);
                break;
            }
        }
    }

    // If we couldn't restore the value, set appropriate defaults
    if (!valueRestored) {
        if (pipeCheckbox.checked) {
            // Default to 6G for pipe
            for (let i = 0; i < positionSelect.options.length; i++) {
                if (positionSelect.options[i].value === '6G') {
                    positionSelect.selectedIndex = i;
                    console.log('Set default pipe position to 6G');
                    break;
                }
            }
        } else if (plateCheckbox.checked && positionSelect.options.length > 1) {
            // Default to 1G for plate
            for (let i = 0; i < positionSelect.options.length; i++) {
                if (positionSelect.options[i].value === '1G') {
                    positionSelect.selectedIndex = i;
                    console.log('Set default plate position to 1G');
                    break;
                }
            }
        }
    }

    // Update position range based on new selection
    updatePositionRange();
}

// Update position qualification range
function updatePositionRange() {
    const positionSelect = document.getElementById('test_position');
    if (!positionSelect) return;

    const position = positionSelect.value;
    console.log('Updating position range for position:', position);

    // Get the range cells from the position qualification table
    const rangeCells = document.querySelectorAll('.var-range[style*="font-weight: bold"]');
    
    // If we don't find the styled cells, look for position range spans or cells differently
    let positionRangeCells = rangeCells;
    if (positionRangeCells.length === 0) {
        // Alternative selector - look in the position qualification section
        const positionSection = document.querySelector('table.variables-table');
        if (positionSection) {
            // Find the position row and get the range cell
            const rows = positionSection.querySelectorAll('tr');
            for (let row of rows) {
                const labelCell = row.querySelector('.var-label');
                if (labelCell && labelCell.textContent.includes('Position(s):')) {
                    // This is the position row, find the range cells
                    positionRangeCells = row.querySelectorAll('.var-range');
                    break;
                }
            }
        }
    }

    // Define position rules
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
    
    // Update the range cells if we found them
    if (positionRangeCells && positionRangeCells.length >= 3) {
        positionRangeCells[0].textContent = rules.groove_over_24;
        if (positionRangeCells.length >= 2) {
            positionRangeCells[1].textContent = rules.groove_under_24;
        }
        if (positionRangeCells.length >= 3) {
            positionRangeCells[2].textContent = rules.fillet;
        }
        console.log('Updated position range cells with rules for position:', position);
    } else {
        console.log('Position range cells not found, trying alternative approach');
        
        // Alternative approach - look for specific position range span
        const positionRangeSpan = document.getElementById('position_range_span');
        if (positionRangeSpan) {
            const combinedRange = rules.groove_over_24 + ' | ' + rules.groove_under_24 + ' | ' + rules.fillet;
            positionRangeSpan.textContent = combinedRange;
            console.log('Updated position range span with combined rules');
        }
    }
    
    // Update the hidden field for form submission
    const positionRangeHidden = document.getElementById('position_range');
    if (positionRangeHidden) {
        const isPipe = document.getElementById('pipe_specimen').checked;
        if (isPipe) {
            positionRangeHidden.value = rules.groove_over_24 + ' | ' + rules.groove_under_24 + ' | ' + rules.fillet;
        } else {
            positionRangeHidden.value = rules.groove_over_24 + ' | ' + rules.fillet;
        }
        console.log('Updated position_range hidden field to:', positionRangeHidden.value);
    }
}


function initializeEditFormPositions() {
    console.log('Initializing edit form positions...');
    
    // Get the current values from the form (these come from the server/database)
    const plateCheckbox = document.getElementById('plate_specimen');
    const pipeCheckbox = document.getElementById('pipe_specimen');
    const positionSelect = document.getElementById('test_position');
    
    if (!plateCheckbox || !pipeCheckbox || !positionSelect) {
        console.error('Required position elements not found during initialization');
        return;
    }
    
    // Store the original position value before updating options
    const originalPosition = positionSelect.value;
    console.log('Original position from database:', originalPosition);
    
    // Ensure proper checkbox states based on the database values
    // In edit mode, the checkboxes should already be set from the server
    console.log('Plate checked:', plateCheckbox.checked, 'Pipe checked:', pipeCheckbox.checked);
    
    // If neither is checked, default to pipe (this shouldn't happen in normal cases)
    if (!plateCheckbox.checked && !pipeCheckbox.checked) {
        console.log('Neither plate nor pipe was checked, defaulting to pipe');
        pipeCheckbox.checked = true;
    }
    
    // Update position options based on current plate/pipe selection
    updatePositionOptions();
    
    // After updating options, try to restore the original position
    if (originalPosition) {
        let positionRestored = false;
        for (let i = 0; i < positionSelect.options.length; i++) {
            if (positionSelect.options[i].value === originalPosition) {
                positionSelect.selectedIndex = i;
                positionRestored = true;
                console.log('Successfully restored original position:', originalPosition);
                break;
            }
        }
        
        if (!positionRestored) {
            console.warn('Could not restore original position:', originalPosition, 'Available options:', 
                Array.from(positionSelect.options).map(opt => opt.value));
        }
    }
    
    // Update the position range to match the current selection
    updatePositionRange();
    
    // Also update diameter range and other dependent fields
    updateDiameterRange();
}

// Enhanced setup event listeners function
function setupEventListeners() {
    console.log('Setting up event listeners...');
    
    // Add field change event listeners with null checks to prevent errors
    const testPosition = document.getElementById('test_position');
    if (testPosition) {
        testPosition.addEventListener('change', function() {
            console.log('Position changed to:', this.value);
            updatePositionRange();
        });
    }
    
    const plateSpecimen = document.getElementById('plate_specimen');
    if (plateSpecimen) {
        plateSpecimen.addEventListener('change', function() {
            console.log('Plate specimen changed, checked:', this.checked);
            toggleDiameterField();
            updatePositionOptions();
        });
    }
    
    const pipeSpecimen = document.getElementById('pipe_specimen');
    if (pipeSpecimen) {
        pipeSpecimen.addEventListener('change', function() {
            console.log('Pipe specimen changed, checked:', this.checked);
            toggleDiameterField();
            updatePositionOptions();
        });
    }
    
    // ... rest of the existing event listeners remain the same ...
    const diameter = document.getElementById('diameter');
    if (diameter) {
        diameter.addEventListener('change', updateDiaThickness);
    }
    
    const thickness = document.getElementById('thickness');
    if (thickness) {
        thickness.addEventListener('change', updateDiaThickness);
    }
    
    const backing = document.getElementById('backing');
    if (backing) {
        backing.addEventListener('change', updateBackingRange);
    }
    
    const baseMetalPNo = document.getElementById('base_metal_p_no');
    if (baseMetalPNo) {
        baseMetalPNo.addEventListener('change', updatePNumberRange);
    }
    
    const fillerFNo = document.getElementById('filler_f_no');
    if (fillerFNo) {
        fillerFNo.addEventListener('change', updateFNumberRange);
    }
    
    const verticalProgression = document.getElementById('vertical_progression');
    if (verticalProgression) {
        console.log('Setting up vertical_progression change event with current value:', verticalProgression.value);
        
        verticalProgression.addEventListener('change', function(e) {
            console.log('Vertical progression changed to:', this.value);
            updateVerticalProgressionRange();
        });
    }
    
    const pipeDiameterType = document.getElementById('pipe_diameter_type');
    if (pipeDiameterType) {
        pipeDiameterType.addEventListener('change', updateDiameterRange);
    }
    
    const backingGasEl = document.getElementById('backing_gas');
    if (backingGasEl) {
        backingGasEl.addEventListener('change', updateBackingGasRange);
    }
    
    const gtawPolarityEl = document.getElementById('gtaw_polarity');
    if (gtawPolarityEl) {
        gtawPolarityEl.addEventListener('change', updateGtawPolarityRange);
    }
    
    // Register our GTAW handler function globally
    window.gtawLoadWelderData = gtawLoadWelderData;
    console.log('✅ GTAW Certificate: Registered gtawLoadWelderData function globally');
}

// Updated DOMContentLoaded event handler
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔄 GTAW Certificate: DOM Content Loaded - Initializing form');
    
    // Register certificate-specific functions for use by welder-search.js
    window.certificateType = 'gtaw';
    window.gtawLoadWelderData = gtawLoadWelderData;
    
    // Initialize basic form settings
    setCurrentDate();
    setDefaultSMAWValues();
    updateProcessFields();
    setupEventListeners();
    
    // Special handling for edit mode - delay position initialization slightly
    // to ensure all form elements are fully loaded
    setTimeout(function() {
        initializeEditFormPositions();
        
        // Initialize all other range fields
        updateBackingRange();
        updateDiameterRange();
        updatePNumberRange();
        updateFNumberRange();
        updateVerticalProgressionRange();
        
        console.log('✅ Edit form position initialization completed');
    }, 100);
    
    // Add event listeners for all dropdown fields that have ranges
    addRangeFieldListeners();

    // Initialize signature pads
    initializeSignaturePads();
    
    console.log('🔥 GTAW Welder Qualification Form initialized!');
});

// Rest of the functions remain the same as in your original file...
// (I'm keeping the existing functions you already have)

// Toggle diameter field based on plate/pipe selection
function toggleDiameterField() {
    const plateCheckbox = document.getElementById('plate_specimen');
    const pipeCheckbox = document.getElementById('pipe_specimen');
    const diameterField = document.getElementById('diameter');
    const pipeDiameterField = document.getElementById('pipe_diameter_type');
    const diameterRangeSpan = document.getElementById('diameter_range_span');

    if (plateCheckbox.checked) {
        pipeCheckbox.checked = false;
        diameterField.disabled = true;
        diameterField.value = "N/A";
        pipeDiameterField.disabled = true;
        
        // Clear pipe diameter dropdown when plate is selected
        pipeDiameterField.value = "";
        
        // Clear diameter range span when plate is selected
        if (diameterRangeSpan) {
            diameterRangeSpan.textContent = '';
        }
        
        // Update hidden diameter range field
        const diameterRangeHidden = document.getElementById('diameter_range');
        if (diameterRangeHidden) {
            diameterRangeHidden.value = '';
        }
    } else {
        pipeCheckbox.checked = true;
        diameterField.disabled = false;
        diameterField.value = "8 inch";
        pipeDiameterField.disabled = false;
        
        // Restore pipe diameter dropdown when pipe is selected
        pipeDiameterField.value = "8_nps";
        
        // Update diameter range
        updateDiameterRange();
    }
    
    updateDiaThickness();
}


// Validate form before submission
function validateForm() {
    // Basic validation could be added here
    Swal.fire({
        title: 'Form Validated',
        text: 'Your certificate data looks good!',
        icon: 'success',
        confirmButtonText: 'Continue'
    });
    return true;
}

/**
 * Validate all required fields before form submission
 */
function validateRequiredFields() {
    const requiredFields = document.querySelectorAll('input[required], select[required], textarea[required]');
    let isValid = true;
    let firstInvalidField = null;
    
    requiredFields.forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            if (!firstInvalidField) {
                firstInvalidField = field;
            }
            isValid = false;
        } else {
            field.classList.remove('is-invalid');
        }
    });
    
    if (!isValid && firstInvalidField) {
        firstInvalidField.focus();
        firstInvalidField.scrollIntoView({ behavior: 'smooth', block: 'center' });
        
        Swal.fire({
            icon: 'error',
            title: 'Required Fields Missing',
            text: 'Please fill in all required fields before submitting the form.'
        });
    }
    
    return isValid;
}

/**
 * Update all range fields before form submission to ensure 
 * backend receives correct values
 */
function updateAllRangeFields() {
    try {
        // First call individual update functions to ensure DOM is updated
        if (document.getElementById('backing')) {
            updateBackingRange();
        }
        
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
        
        if (document.getElementById('vertical_progression')) {
            updateVerticalProgressionRange();
        }
        
        // Update backing gas and GTAW polarity ranges
        if (document.getElementById('backing_gas')) {
            updateBackingGasRange();
        }
        
        if (document.getElementById('gtaw_polarity')) {
            updateGtawPolarityRange();
        }
        
        // Then transfer values from display spans to hidden fields
        const rangeFields = [
            {source: 'pipe_diameter_type', target: 'diameter_range', span: 'diameter_range_span'},
            {source: 'base_metal_p_no', target: 'p_number_range', span: 'p_number_range_span'},
            {source: 'test_position', target: 'position_range', span: 'position_range_span'},
            {source: 'backing', target: 'backing_range', span: 'backing_range_span'},
            {source: 'filler_f_no', target: 'f_number_range', span: 'f_number_range_span'},
            {source: 'vertical_progression', target: 'vertical_progression_range', span: 'vertical_progression_range_span'},
            {source: 'backing_gas', target: 'backing_gas_range', span: 'backing_gas_range_span'},
            {source: 'gtaw_polarity', target: 'gtaw_polarity_range', span: 'gtaw_polarity_range_span'}
        ];
        
        rangeFields.forEach(field => {
            const spanElement = document.getElementById(field.span);
            const targetField = document.getElementById(field.target);
            
            if (spanElement && targetField) {
                // Make sure the target hidden field gets the text content from the span
                targetField.value = spanElement.textContent.trim();
                console.log(`Set ${field.target} = "${targetField.value}"`);
            }
        });
    } catch (error) {
        console.error('Error in updateAllRangeFields:', error);
    }
}

/**
 * Update hidden field value for a specific range field
 */
function updateRangeFieldValues(sourceFieldId, targetFieldId) {
    const sourceField = document.getElementById(sourceFieldId);
    const targetField = document.getElementById(targetFieldId);
    
    if (!sourceField || !targetField) return;
    
    // Get the display text from the corresponding range span
    const rangeSpan = document.getElementById(`${sourceFieldId.replace('_type', '')}_range_span`);
    
    if (rangeSpan) {
        targetField.value = rangeSpan.textContent.trim();
        console.log(`Updated range for ${sourceFieldId} to value: ${targetField.value}`);
    }
}

// Function to handle form submission with validation and AJAX
function submitCertificateForm() {
    // Clear previous validation errors
    clearValidationErrors();
    
    // Update all range fields before submission
    updateAllRangeFields();
    
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
        title: 'Saving...',
        text: 'Please wait while the certificate is being saved',
        allowOutsideClick: false,
        allowEscapeKey: false,
        showConfirmButton: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
    
    // Get CSRF token from meta tag
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Send AJAX request
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        Swal.close();
        
        if (data.success) {
            // Show success message
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: data.message || 'Certificate has been saved successfully.',
                timer: 2000,
                showConfirmButton: false
            }).then(() => {
                // Redirect to the certificate view page
                if (data.redirect) {
                    window.location.href = data.redirect;
                }
            });
        } else {
            // Show error message
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message || 'An error occurred while saving the certificate.',
                confirmButtonText: 'OK'
            });
            
            // Display validation errors
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
            text: 'An unexpected error occurred. Please try again.',
            confirmButtonText: 'OK'
        });
    });
    
    return false; // Prevent default form submission
}

// Function to clear validation errors
function clearValidationErrors() {
    // Remove error messages
    const errorMessages = document.querySelectorAll('.invalid-feedback');
    errorMessages.forEach(el => {
        if (el.parentNode) {
            el.parentNode.removeChild(el);
        }
    });
    
    // Remove error classes
    const invalidInputs = document.querySelectorAll('.is-invalid');
    invalidInputs.forEach(el => {
        el.classList.remove('is-invalid');
    });
}

// Function to display validation errors
function displayValidationErrors(errors) {
    for (const field in errors) {
        const input = document.querySelector(`[name="${field}"]`);
        if (input) {
            // Add error class to input
            input.classList.add('is-invalid');
            
            // Create error message
            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback';
            errorDiv.textContent = errors[field][0]; // First error message
            
            // Add error message after input
            input.parentNode.insertBefore(errorDiv, input.nextSibling);
            
            // Focus first field with error
            if (field === Object.keys(errors)[0]) {
                input.focus();
                
                // Scroll to the input with error
                input.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    }
}

// Load welder data when selected (GTAW specific implementation)
function gtawLoadWelderData(welderId) {
    console.log('GTAW CERTIFICATE: Loading welder data for ID:', welderId);
    console.trace('GTAW CERTIFICATE: Call stack trace');
    
    // Register this function for debugging
    window.certificateLoadFunctions.gtaw = gtawLoadWelderData;
    
    if (!welderId) return;
    
    // Get CSRF token
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // Show loading indicator
    const welderIdField = document.getElementById('welder_id_no');
    const welderNameField = document.getElementById('welder_search');
    
    if (welderIdField) welderIdField.value = 'Loading...';
    if (welderNameField) welderNameField.value = 'Loading welder data...';
    
    // Get base URL
    const baseUrl = window.location.origin;
    // Use unified API route for welder data - use all certificate type to get all numbers
    const apiUrl = `${baseUrl}/api/welders/${welderId}/all`;
    
    console.log('Fetching welder data from:', apiUrl);
    
    fetch(apiUrl, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token || '',
            'Accept': 'application/json'
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Handle different API response formats
            let welder;
            
            if (data && data.welder) {
                // Standard format with welder object inside data
                welder = data.welder;
            } else if (data && data.id) {
                // Alternative format where welder data is directly in the response
                welder = data;
            } else {
                console.error('Unexpected API response format:', data);
                throw new Error('Unexpected API response format');
            }
            
            // Update fields
            document.getElementById('welder_id').value = welder.id;
            document.getElementById('welder_id_no').value = welder.welder_id || '';
            document.getElementById('iqama_no').value = welder.iqama_no || '';
            document.getElementById('passport_no').value = welder.passport_no || '';
            document.getElementById('company_name').value = welder.company_name || '';
            document.getElementById('company_id').value = welder.company_id || '';

            // Update photo if available
            const photoPreview = document.getElementById('photo-preview');
            if (photoPreview) {
                if (welder.photo_path) {
                    photoPreview.innerHTML = `<img src="${welder.photo_path}" class="welder-photo-preview">`;
                } else {
                    photoPreview.innerHTML = `<div>No photo<br>available for<br>${welder.name}</div>`;
                }
            }
            
            // Update certificate and report numbers from the API response
            // For GTAW certificates, use gtaw_certificate field directly
            if (data.gtaw_certificate) {
                const certField = document.getElementById('certificate_no');
                if (certField) {
                    certField.value = data.gtaw_certificate;
                    console.log('Updated certificate number from API response:', data.gtaw_certificate);
                }
            } else if (data.certificate_no) {
                const certField = document.getElementById('certificate_no');
                if (certField) {
                    certField.value = data.certificate_no;
                    console.log('Updated certificate number from API response:', data.certificate_no);
                }
            }
            
            // Use report numbers directly from API response
            if (data.vt_report_no) {
                const vtField = document.getElementById('vt_report_no');
                if (vtField) {
                    vtField.value = data.vt_report_no;
                    console.log('Updated VT report number from API:', data.vt_report_no);
                }
            }
            
            if (data.rt_report_no) {
                const rtField = document.getElementById('rt_report_no');
                if (rtField) {
                    rtField.value = data.rt_report_no;
                    console.log('Updated RT report number from API:', data.rt_report_no);
                }
            }
            
            if (data.ut_report_no) {
                const utField = document.getElementById('ut_report_no');
                if (utField) {
                    utField.value = data.ut_report_no;
                    console.log('Updated UT report number from API:', data.ut_report_no);
                }
            }
            
            // Only generate certificate numbers if they were not provided by the API
            if (typeof generateCertificateNumber === 'function' && !data.gtaw_certificate && !data.certificate_no) {
                console.log('No certificate numbers in API response, generating new ones...');
                generateCertificateNumber(welder.company_id || null);
            }
            
            console.log('Welder data loaded successfully');
        })
        .catch(error => {
            console.error('Error fetching welder data:', error);
            
            // Clear loading indicators
            if (welderIdField) welderIdField.value = '';
            if (welderNameField) welderNameField.value = 'Error loading welder data';
            
            // Display user-friendly error message
            if (typeof Swal !== 'undefined') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error Loading Welder Data',
                    html: `
                        <p>There was a problem loading the welder information.</p>
                        <p>Please try again or select a different welder.</p>
                        <details>
                            <summary>Technical details (for support)</summary>
                            <pre>${error.message}</pre>
                            <p>URL: ${apiUrl}</p>
                        </details>
                    `,
                    confirmButtonText: 'OK'
                });
            } else {
                alert('Error loading welder data: ' + error.message);
            }
        });
}

// Function to generate certificate number based on company
function generateCertificateNumber(companyId, companyCode) {
    if (!companyId) return;

    fetch(`/generate-certificate-number?company_id=${companyId}&type=GTAW`)
        .then(response => response.json())
        .then(data => {
            // Set certificate number
            if (data.certificate_no) {
                document.getElementById('certificate_no').value = data.certificate_no;
            } else if (data.gtaw_certificate) {
                // Handle new API format that uses gtaw_certificate
                document.getElementById('certificate_no').value = data.gtaw_certificate;
            }
            
            // Set report numbers
            if (data.vt_report_no) {
                document.getElementById('vt_report_no').value = data.vt_report_no;
            }
            
            if (data.rt_report_no) {
                document.getElementById('rt_report_no').value = data.rt_report_no;
            }
            
            if (data.ut_report_no) {
                const utField = document.getElementById('ut_report_no');
                if (utField) {
                    utField.value = data.ut_report_no;
                }
            }
        })
        .catch(error => {
            console.error('Error generating certificate number:', error);
        });
}

// Setup event listeners - UNIFIED FUNCTION
function setupEventListeners() {
    // Add field change event listeners with null checks to prevent errors
    const testPosition = document.getElementById('test_position');
    if (testPosition) {
        testPosition.addEventListener('change', updatePositionRange);
    }
    
    const plateSpecimen = document.getElementById('plate_specimen');
    if (plateSpecimen) {
        plateSpecimen.addEventListener('change', toggleDiameterField);
    }
    
    const pipeSpecimen = document.getElementById('pipe_specimen');
    if (pipeSpecimen) {
        pipeSpecimen.addEventListener('change', toggleDiameterField);
    }
    
    const diameter = document.getElementById('diameter');
    if (diameter) {
        diameter.addEventListener('change', updateDiaThickness);
    }
    
    const thickness = document.getElementById('thickness');
    if (thickness) {
        thickness.addEventListener('change', updateDiaThickness);
    }
    
    const backing = document.getElementById('backing');
    if (backing) {
        backing.addEventListener('change', updateBackingRange);
    }
    
    const baseMetalPNo = document.getElementById('base_metal_p_no');
    if (baseMetalPNo) {
        baseMetalPNo.addEventListener('change', updatePNumberRange);
    }
    
    const fillerFNo = document.getElementById('filler_f_no');
    if (fillerFNo) {
        fillerFNo.addEventListener('change', updateFNumberRange);
    }
    
    const verticalProgression = document.getElementById('vertical_progression');
    if (verticalProgression) {
        console.log('Setting up vertical_progression change event with current value:', verticalProgression.value);
        
        // Add a wrapper to log more info
        verticalProgression.addEventListener('change', function(e) {
            console.log('Vertical progression changed to:', this.value);
            console.log('Event triggered from:', e.target);
            
            // Call the actual handler
            updateVerticalProgressionRange();
            
            // Debug state after update
            const hiddenField = document.getElementById('vertical_progression_hidden');
            const rangeField = document.getElementById('vertical_progression_range');
            
            console.log('After change event processing:');
            console.log('- dropdown value:', this.value);
            console.log('- hidden field value:', hiddenField ? hiddenField.value : 'not found');
            console.log('- range field value:', rangeField ? rangeField.value : 'not found');
        });
    } else {
        console.error('Vertical progression dropdown not found in DOM during initialization!');
    }
    
    const pipeDiameterType = document.getElementById('pipe_diameter_type');
    if (pipeDiameterType) {
        pipeDiameterType.addEventListener('change', updateDiameterRange);
    }
    
    // Set up backing gas and GTAW polarity change listeners if they exist
    const backingGasEl = document.getElementById('backing_gas');
    if (backingGasEl) {
        backingGasEl.addEventListener('change', updateBackingGasRange);
    }
    
    const gtawPolarityEl = document.getElementById('gtaw_polarity');
    if (gtawPolarityEl) {
        gtawPolarityEl.addEventListener('change', updateGtawPolarityRange);
    }
    
    // Add form submission event listener to ensure plate/pipe specimen values are correctly set
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Set proper values for plate_specimen and pipe_specimen based on checkboxes
            const plateCheckbox = document.getElementById('plate_specimen');
            const pipeCheckbox = document.getElementById('pipe_specimen');
            
            // Ensure hidden inputs are created or updated with correct boolean values
            ensureHiddenInput(form, 'plate_specimen', plateCheckbox.checked ? '1' : '0');
            ensureHiddenInput(form, 'pipe_specimen', pipeCheckbox.checked ? '1' : '0');
        });
    }

    // REMOVED: Individual welder search setup since this is now handled centrally in welder-search.js
    // Just register our handler function globally so it can be called from welder-search.js
    window.gtawLoadWelderData = gtawLoadWelderData;
    
    // Log that we've registered the handler
    console.log('✅ GTAW Certificate: Registered gtawLoadWelderData function globally');
    
    // Still handle closing the results dropdown when clicking elsewhere
    const searchInput = document.getElementById('welder_search');
    const resultsContainer = document.getElementById('welder_results');
    
    if (searchInput && resultsContainer) {
        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !resultsContainer.contains(e.target)) {
                resultsContainer.style.display = 'none';
            }
        });
    }
}

/**
 * Helper function to ensure hidden inputs are created/updated correctly
 */
function ensureHiddenInput(form, name, value) {
    let input = form.querySelector(`input[name="${name}"][type="hidden"]`);
    if (!input) {
        input = document.createElement('input');
        input.type = 'hidden';
        input.name = name;
        form.appendChild(input);
    }
    input.value = value;
}

/**
 * Add event listeners to all dropdown fields that have ranges
 */
function addRangeFieldListeners() {
    const rangeFields = [
        {field: 'backing', updateFn: updateBackingRange},
        {field: 'pipe_diameter_type', updateFn: updateDiameterRange},
        {field: 'base_metal_p_no', updateFn: updatePNumberRange},
        {field: 'filler_f_no', updateFn: updateFNumberRange},
        {field: 'test_position', updateFn: updatePositionRange},
        {field: 'vertical_progression', updateFn: updateVerticalProgressionRange},
        {field: 'backing_gas', updateFn: updateBackingGasRange},
        {field: 'gtaw_polarity', updateFn: updateGtawPolarityRange}
    ];
    
    rangeFields.forEach(item => {
        const element = document.getElementById(item.field);
        if (element) {
            element.addEventListener('change', item.updateFn);
        }
    });
}

/**
 * Update backing gas range based on selection
 */
function updateBackingGasRange() {
    const backingGasSelect = document.getElementById('backing_gas');
    const backingGasRangeSpan = document.getElementById('backing_gas_range_span');
    const backingGasRangeInput = document.getElementById('backing_gas_range');
    
    if (!backingGasSelect || !backingGasRangeSpan || !backingGasRangeInput) {
        console.error('Backing gas elements not found');
        return;
    }
    
    const selectedValue = backingGasSelect.value;
    let rangeText = '';
    
    if (selectedValue === 'With backing Gas') {
        rangeText = 'With backing Gas';
    } else if (selectedValue === 'Without backing Gas') {
        rangeText = 'With or Without backing Gas';
    } else {
        rangeText = '...';
    }
    
    // Update the display span and hidden input
    backingGasRangeSpan.textContent = rangeText;
    backingGasRangeInput.value = rangeText;
    console.log('Updated backing gas range to:', rangeText);
}

/**
 * Update GTAW polarity range based on selection
 */
function updateGtawPolarityRange() {
    const gtawPolaritySelect = document.getElementById('gtaw_polarity');
    const gtawPolarityRangeSpan = document.getElementById('gtaw_polarity_range_span');
    const gtawPolarityRangeInput = document.getElementById('gtaw_polarity_range');
    
    if (!gtawPolaritySelect || !gtawPolarityRangeSpan || !gtawPolarityRangeInput) {
        console.error('GTAW polarity elements not found');
        return;
    }
    
    const selectedValue = gtawPolaritySelect.value;
    
    // For GTAW polarity, the range is the same as the selected value
    gtawPolarityRangeSpan.textContent = selectedValue || '...';
    gtawPolarityRangeInput.value = selectedValue;
    console.log('Updated GTAW polarity range to:', selectedValue);
}

// Initialize form on document ready
// Register the certificate-specific handler globally before DOM loads
// This ensures it's available for the welder-search.js to find
window.gtawLoadWelderData = function(welderId, directData = null) {
    console.log('gtawLoadWelderData called with ID:', welderId);
    
    if (directData) {
        // If data is directly provided (like from handleBackendWelderData)
        window.handleBackendWelderData(directData);
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
    // Use web-based API route
    const url = `${baseUrl}/api/welders/${welderId}?certificate_type=gtaw`;
    
    fetch(url)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            console.log('gtawLoadWelderData received data:', data);
            window.handleBackendWelderData(data);
        })
        .catch(error => {
            console.error('Error in gtawLoadWelderData:', error);
            Swal.fire({
                title: 'Error',
                text: 'Failed to load welder data. Please try again.',
                icon: 'error'
            });
        });
};

document.addEventListener('DOMContentLoaded', function() {
    console.log('🔄 GTAW Certificate: DOM Content Loaded - Initializing form');
    
    // Register certificate-specific functions for use by welder-search.js
    window.certificateType = 'gtaw';
    window.gtawLoadWelderData = gtawLoadWelderData;
    
    setCurrentDate();
    setDefaultSMAWValues();
    updateProcessFields();
    setupEventListeners();
    toggleDiameterField();
    updatePositionOptions();
    
    // Initialize all range fields explicitly
    updateBackingRange();
    updateDiameterRange();
    updatePNumberRange();
    updateFNumberRange();
    updateVerticalProgressionRange();
    updatePositionRange();
    
    // Add event listeners for all dropdown fields that have ranges
    addRangeFieldListeners();

    // Initialize signature pads
    initializeSignaturePads();
    
    // For testing purposes, you can uncomment this code to directly test with sample data
    /*
    const testData = {
        "welder": {
            "id": 2,
            "name": "Raja Faisal Jahangir Jahangir Ahmed",
            "welder_id": "2",
            "iqama_no": "1524522299",
            "passport_no": "V51065625",
            "company_id": 2,
            "company_name": "test",
            "photo": "welders/na7GFCUb8hBuxX6RW6T9uAdKIUqFfjIX6SLmd8vf.png",
            "photo_path": "http://127.0.0.1:8000/storage/welders/na7GFCUb8hBuxX6RW6T9uAdKIUqFfjIX6SLmd8vf.png"
        },
        "company_code": "EEA-test",
        "smaw_certificate": "EEA-test-SMAW-0020",
        "gtaw_certificate": "EEA-test-GTAW-0007",
        "fcaw_certificate": "EEA-test-FCAW-0001",
        "gtaw_smaw_certificate": "EEA-test-GTAW-SMAW-0001",
        "vt_report_no": "EEA-test-VT-0020",
        "rt_report_no": "EEA-test-RT-0020",
        "ut_report_no": "EEA-test-UT-0001"
    };
    
    // Apply test data to form
    window.handleBackendWelderData(testData);
    */
    
    // Add a global error handler for fetching operations
    window.addEventListener('error', function(event) {
        console.error('Global error caught:', event.error);
        if (event.error && event.error.toString().includes('fetch')) {
            Swal.fire({
                title: 'Network Error',
                text: 'There was a problem connecting to the server. Please check your internet connection.',
                icon: 'error'
            });
        }
    });

    console.log('🔥 GTAW Welder Qualification Form initialized!');
});

/**
 * Initialize signature pads for welder and inspector signatures
 */
function initializeSignaturePads() {
    // Initialize welder signature pad
    initializeWelderSignature();
    
    // Initialize inspector signature functionality
    initializeInspectorSignature();
}

/**
 * Initialize signature pads for welder and inspector signatures
 */
function initializeSignaturePads() {
    console.log('Initializing signature pads...');
    
    // Initialize welder signature pad
    initializeWelderSignature();
    
    // Initialize inspector signature functionality
    initializeInspectorSignature();
}

/**
 * Initialize welder signature pad
 */
function initializeWelderSignature() {
    const canvas = document.getElementById('signature-pad');
    const signatureDataInput = document.getElementById('signature_data');
    const clearButton = document.getElementById('clear-signature');
    
    if (!canvas) {
        console.log('Welder signature canvas not found');
        return;
    }
    
    console.log('Initializing welder signature pad...');
    
    // Get existing signature data from hidden input
    const existingSignature = signatureDataInput ? signatureDataInput.value : '';
    console.log('Existing welder signature data:', existingSignature ? 'Present' : 'None');
    
    // Initialize signature pad
    const signaturePad = new SignaturePad(canvas, {
        backgroundColor: 'rgba(255, 255, 255, 0.8)',
        penColor: 'black',
        minWidth: 1,
        maxWidth: 3,
        throttle: 16,
        minDistance: 5
    });
    
    // Store reference globally for access from other functions
    window.welderSignaturePad = signaturePad;
    
    // Function to resize canvas properly without clearing
    function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        
        // Save current signature data before resize
        const currentSignatureData = signaturePad.isEmpty() ? existingSignature : signaturePad.toDataURL();
        
        // Resize canvas
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        canvas.getContext("2d").scale(ratio, ratio);
        
        // Restore signature data after resize if it exists
        if (currentSignatureData && currentSignatureData !== '') {
            setTimeout(() => {
                try {
                    signaturePad.fromDataURL(currentSignatureData);
                    console.log('Signature restored after resize');
                } catch (error) {
                    console.error('Error restoring signature after resize:', error);
                }
            }, 100);
        }
    }
    
    // Initial canvas setup
    resizeCanvas();
    
    // Load existing signature if available
    if (existingSignature && existingSignature !== '') {
        setTimeout(() => {
            try {
                signaturePad.fromDataURL(existingSignature);
                console.log('✅ Existing welder signature loaded successfully');
            } catch (error) {
                console.error('Error loading existing welder signature:', error);
            }
        }, 200);
    }
    
    // Clear signature button event
    if (clearButton) {
        clearButton.addEventListener('click', function(e) {
            e.preventDefault();
            signaturePad.clear();
            if (signatureDataInput) {
                signatureDataInput.value = '';
            }
            console.log('Welder signature cleared');
        });
    }
    
    // Update hidden input when signature changes
    signaturePad.addEventListener("endStroke", () => {
        if (signatureDataInput) {
            signatureDataInput.value = signaturePad.toDataURL();
            console.log('Welder signature updated');
        }
    });
    
    // Handle window resize
    window.addEventListener('resize', resizeCanvas);
    
    console.log('✅ Welder signature pad initialized successfully');
}

/**
 * Initialize inspector signature pad
 */
function initializeInspectorSignature() {
    const inspectorCanvas = document.getElementById('inspector-signature-pad');
    const inspectorSignatureDataInput = document.getElementById('inspector_signature_data');
    const inspectorClearButton = document.getElementById('clear-inspector-signature');
    
    if (!inspectorCanvas) {
        console.log('Inspector signature canvas not found');
        return;
    }
    
    console.log('Initializing inspector signature pad...');
    
    // Get existing inspector signature data
    const existingInspectorSignature = inspectorSignatureDataInput ? inspectorSignatureDataInput.value : '';
    console.log('Existing inspector signature data:', existingInspectorSignature ? 'Present' : 'None');
    
    // Initialize inspector signature pad
    const inspectorSignaturePad = new SignaturePad(inspectorCanvas, {
        backgroundColor: 'rgba(255, 255, 255, 0.8)',
        penColor: 'black',
        minWidth: 1,
        maxWidth: 3,
        throttle: 16,
        minDistance: 5
    });
    
    // Store reference globally
    window.inspectorSignaturePad = inspectorSignaturePad;
    
    // Function to resize inspector canvas
    function resizeInspectorCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        
        // Save current signature data before resize
        const currentSignatureData = inspectorSignaturePad.isEmpty() ? existingInspectorSignature : inspectorSignaturePad.toDataURL();
        
        // Resize canvas
        inspectorCanvas.width = inspectorCanvas.offsetWidth * ratio;
        inspectorCanvas.height = inspectorCanvas.offsetHeight * ratio;
        inspectorCanvas.getContext("2d").scale(ratio, ratio);
        
        // Restore signature data after resize
        if (currentSignatureData && currentSignatureData !== '') {
            setTimeout(() => {
                try {
                    inspectorSignaturePad.fromDataURL(currentSignatureData);
                    console.log('Inspector signature restored after resize');
                } catch (error) {
                    console.error('Error restoring inspector signature after resize:', error);
                }
            }, 100);
        }
    }
    
    // Initial canvas setup
    resizeInspectorCanvas();
    
    // Load existing inspector signature if available
    if (existingInspectorSignature && existingInspectorSignature !== '') {
        setTimeout(() => {
            try {
                inspectorSignaturePad.fromDataURL(existingInspectorSignature);
                console.log('✅ Existing inspector signature loaded successfully');
            } catch (error) {
                console.error('Error loading existing inspector signature:', error);
            }
        }, 200);
    }
    
    // Clear inspector signature button
    if (inspectorClearButton) {
        inspectorClearButton.addEventListener('click', function(e) {
            e.preventDefault();
            inspectorSignaturePad.clear();
            if (inspectorSignatureDataInput) {
                inspectorSignatureDataInput.value = '';
            }
            console.log('Inspector signature cleared');
        });
    }
    
    // Update hidden input when inspector signature changes
    inspectorSignaturePad.addEventListener("endStroke", () => {
        if (inspectorSignatureDataInput) {
            inspectorSignatureDataInput.value = inspectorSignaturePad.toDataURL();
            console.log('Inspector signature updated');
        }
    });
    
    // Handle window resize for inspector canvas
    window.addEventListener('resize', resizeInspectorCanvas);
    
    console.log('✅ Inspector signature pad initialized successfully');
}

/**
 * Function to manually load signature data (useful for edit forms)
 */
function loadExistingSignatures() {
    console.log('Loading existing signatures...');
    
    // Load welder signature
    const welderSignatureData = document.getElementById('signature_data');
    if (welderSignatureData && welderSignatureData.value && window.welderSignaturePad) {
        try {
            window.welderSignaturePad.fromDataURL(welderSignatureData.value);
            console.log('✅ Welder signature loaded from form data');
        } catch (error) {
            console.error('Error loading welder signature from form data:', error);
        }
    }
    
    // Load inspector signature
    const inspectorSignatureData = document.getElementById('inspector_signature_data');
    if (inspectorSignatureData && inspectorSignatureData.value && window.inspectorSignaturePad) {
        try {
            window.inspectorSignaturePad.fromDataURL(inspectorSignatureData.value);
            console.log('✅ Inspector signature loaded from form data');
        } catch (error) {
            console.error('Error loading inspector signature from form data:', error);
        }
    }
}

/**
 * Function to validate signatures before form submission
 */
function validateSignatures() {
    let isValid = true;
    const errors = [];
    
    // Check welder signature (usually required)
    const welderSignatureData = document.getElementById('signature_data');
    if (!welderSignatureData || !welderSignatureData.value) {
        errors.push('Welder signature is required');
        isValid = false;
    }
    
    // Inspector signature may or may not be required based on your business logic
    // Uncomment the following if inspector signature is required:
    /*
    const inspectorSignatureData = document.getElementById('inspector_signature_data');
    if (!inspectorSignatureData || !inspectorSignatureData.value) {
        errors.push('Inspector signature is required');
        isValid = false;
    }
    */
    
    if (!isValid) {
        console.error('Signature validation failed:', errors);
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Missing Signatures',
                text: errors.join('\n'),
                confirmButtonText: 'OK'
            });
        } else {
            alert('Missing signatures:\n' + errors.join('\n'));
        }
    }
    
    return isValid;
}

// Enhanced form submission that includes signature validation
function submitCertificateFormWithSignatures() {
    console.log('Submitting certificate form with signature validation...');
    
    // Update all range fields before submission
    updateAllRangeFields();
    
    // Validate signatures
    if (!validateSignatures()) {
        return false;
    }
    
    // Validate required fields
    if (!validateRequiredFields()) {
        return false;
    }
    
    // Continue with existing form submission logic
    return submitCertificateForm();
}

// Modified DOMContentLoaded event to include better signature initialization
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔄 DOM Content Loaded - Enhanced signature initialization');
    
    // Wait for all elements to be fully rendered before initializing signatures
    setTimeout(() => {
        initializeSignaturePads();
        
        // For edit forms, load existing signatures after a short delay
        setTimeout(() => {
            loadExistingSignatures();
        }, 500);
        
    }, 300);
});

// Export functions for global access
window.initializeSignaturePads = initializeSignaturePads;
window.loadExistingSignatures = loadExistingSignatures;
window.validateSignatures = validateSignatures;
window.submitCertificateFormWithSignatures = submitCertificateFormWithSignatures;
