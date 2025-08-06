/**
 * SMAW Certificate Form Range Functions
 * Handles all range-related functionality for the SMAW Certificate form
 */

// Immediately-invoked function expression (IIFE) to avoid polluting global scope
(function() {
    'use strict';
    
    /**
     * Add event listeners for range fields
     */
    /**
     * Initialize specimen checkboxes to ensure they are mutually exclusive
     */
    function initializeSpecimenCheckboxes() {
        const plateSpecimen = document.getElementById('plate_specimen');
        const pipeSpecimen = document.getElementById('pipe_specimen');
        
        if (plateSpecimen && pipeSpecimen) {
            // If both are checked (which shouldn't happen), make plate the priority
            if (plateSpecimen.checked && pipeSpecimen.checked) {
                pipeSpecimen.checked = false;
            }
            
            // Ensure at least one is checked
            if (!plateSpecimen.checked && !pipeSpecimen.checked) {
                plateSpecimen.checked = true;
            }
            
            console.log('Initialized specimen checkboxes:', {
                'plate_specimen': plateSpecimen.checked,
                'pipe_specimen': pipeSpecimen.checked
            });
        }
    }
    
    function addRangeFieldListeners() {
        // Initialize specimen checkboxes first
        initializeSpecimenCheckboxes();
        
        // Add listeners to update ranges when form fields change
        const rangeUpdaters = [
            { id: 'backing', handler: updateBackingRange },
            { id: 'pipe_diameter_type', handler: updateDiameterRange },
            { id: 'base_metal_p_no', handler: updatePNumberRange },
            { id: 'test_position', handler: function() {
                console.log('Test position changed, updating position range');
                updatePositionRange();
            }},
            { id: 'filler_f_no', handler: updateFNumberRange },
            { id: 'vertical_progression', handler: updateVerticalProgressionRange },
            { id: 'smaw_thickness', handler: function() {
                console.log('SMAW thickness changed, updating thickness range');
                const thickness = document.getElementById('smaw_thickness')?.value || '';
                calculateThicknessRange(thickness);
                updateDiaThickness();
            }}
        ];
        
        rangeUpdaters.forEach(updater => {
            const element = document.getElementById(updater.id);
            if (element) {
                element.addEventListener('change', updater.handler);
                console.log(`Added range listener for ${updater.id}`);
            }
        });
        
        // Add listeners for specimen type checkboxes
        const plateSpecimen = document.getElementById('plate_specimen');
        const pipeSpecimen = document.getElementById('pipe_specimen');
        
        if (plateSpecimen) {
            plateSpecimen.addEventListener('change', function() {
                updateSpecimenType(this);
            });
        }
        
        if (pipeSpecimen) {
            pipeSpecimen.addEventListener('change', function() {
                updateSpecimenType(this);
            });
        }
    }
    
    /**
     * Update diameter range based on pipe diameter selection
     */
    function updateDiameterRange() {
        const diameterType = document.getElementById('pipe_diameter_type')?.value || '';
        const rangeDisplay = document.getElementById('diameter_range');
        const rangeDisplaySpan = document.getElementById('diameter_range_span');
        
        let rangeText = '';
        switch (diameterType) {
            case '8_nps':
                rangeText = 'Pipe of diameter ≥ 219.1 mm (8" NPS)';
                break;
            case '6_nps':
                rangeText = 'Pipe of diameter ≥ 168.3 mm (6" NPS)';
                break;
            case '4_nps':
                rangeText = 'Pipe of diameter ≥ 114.3 mm (4" NPS)';
                break;
            case '2_nps':
                rangeText = 'Pipe of diameter ≥ 60.3 mm (2" NPS)';
                break;
            case '__manual__':
                const manualElement = document.getElementById('diameter_range_manual');
                if (manualElement) manualElement.style.display = 'block';
                rangeText = 'Manual entry - specify range';
                break;
            default:
                rangeText = 'Not specified';
        }
        
        // Update the hidden input field to ensure it's saved when the form is submitted
        if (rangeDisplay) {
            rangeDisplay.value = rangeText;
            console.log('Diameter range updated to:', rangeText);
        }
        
        // Also update any display span if it exists
        if (rangeDisplaySpan) {
            rangeDisplaySpan.textContent = rangeText;
            rangeDisplaySpan.style.display = 'block';
        }
        
        // Update any visible display in the table
        const diameterRangeCells = document.querySelectorAll('.var-range[data-range="diameter"]');
        if (diameterRangeCells && diameterRangeCells.length > 0) {
            diameterRangeCells[0].innerHTML = `<span style="font-weight: bold; font-size: 8px;">${rangeText}</span>`;
        }
        
        updateDiaThickness();
    }
    
    /**
     * Update P-Number range based on base metal selection
     */
    function updatePNumberRange() {
        const pNumberType = document.getElementById('base_metal_p_no')?.value || '';
        const rangeDisplay = document.getElementById('p_number_range');
        const rangeDisplaySpan = document.getElementById('p_number_range_span');
        
        let rangeText = '';
        switch (pNumberType) {
            case 'P NO.1 TO P NO.1':
                rangeText = 'P-No.1 Group 1 or 2';
                break;
            case 'P NO.3 TO P NO.3':
                rangeText = 'P-No.3 Group 1 or 2';
                break;
            case 'P NO.4 TO P NO.4':
                rangeText = 'P-No.4 Group 1 or 2';
                break;
            case 'P NO.5A TO P NO.5A':
                rangeText = 'P-No.5A Group 1 or 2';
                break;
            case 'P NO.8 TO P NO.8':
                rangeText = 'P-No.8 Group 1 or 2';
                break;
            case 'P NO.1 TO P NO.8':
                rangeText = 'P-No.1 to P-No.8';
                break;
            case 'P NO.43 TO P NO.43':
                rangeText = 'P-No.43';
                break;
            case '__manual__':
                const manualElement = document.getElementById('p_number_range_manual');
                if (manualElement) manualElement.style.display = 'block';
                rangeText = 'Manual entry - specify range';
                break;
            default:
                rangeText = 'P-NO. 1 through P-NO. 15F, P-NO. 34, and P-NO. 41 through P-NO. 49';
        }
        
        // Update the hidden input field
        if (rangeDisplay) {
            rangeDisplay.value = rangeText;
            console.log('P-Number range updated to:', rangeText);
        }
        
        // Also update any display span if it exists
        if (rangeDisplaySpan) {
            rangeDisplaySpan.textContent = rangeText;
            rangeDisplaySpan.style.display = 'block';
        }
        
        // Update any visible display in the table
        const pNumberRangeCells = document.querySelectorAll('.var-range[data-range="p-number"]');
        if (pNumberRangeCells && pNumberRangeCells.length > 0) {
            pNumberRangeCells[0].innerHTML = `<span style="font-weight: bold; font-size: 8px;">${rangeText}</span>`;
        }
    }
    
    /**
     * Update position range based on test position selection
     */
    function updatePositionRange() {
        try {
            console.log('updatePositionRange function called');
            
            const testPositionElement = document.getElementById('test_position');
            let testPosition = testPositionElement ? testPositionElement.value : '';
            
            // Log the current state of the select element
            if (testPositionElement) {
                console.log('Current test_position dropdown state:', {
                    'value': testPositionElement.value,
                    'selectedIndex': testPositionElement.selectedIndex,
                    'selected option text': testPositionElement.selectedIndex >= 0 ? 
                        testPositionElement.options[testPositionElement.selectedIndex].text : 'none'
                });
            }
            
            // Check if we should try to get the value from data-saved-value attribute
            if (testPositionElement && (!testPosition || testPosition === '')) {
                const savedValue = testPositionElement.getAttribute('data-saved-value');
                if (savedValue && savedValue.trim() !== '') {
                    console.log('No value selected, using data-saved-value:', savedValue);
                    testPosition = savedValue;
                    
                    // Try to set the dropdown to match this value
                    testPositionElement.value = savedValue;
                    
                    // Also force select the right option by iterating through options
                    for (let i = 0; i < testPositionElement.options.length; i++) {
                        if (testPositionElement.options[i].value === savedValue) {
                            testPositionElement.selectedIndex = i;
                            testPositionElement.options[i].selected = true;
                            break;
                        }
                    }
                }
            }
            
            const pipeSpecimenElement = document.getElementById('pipe_specimen');
            const plateSpecimenElement = document.getElementById('plate_specimen');
            const isPipeSpecimen = pipeSpecimenElement ? pipeSpecimenElement.checked : false;
            const isPlateSpecimen = plateSpecimenElement ? plateSpecimenElement.checked : false;
            
            const rangeDisplay = document.getElementById('position_range');
            const rangeDisplaySpan = document.getElementById('position_range_span');
            
            console.log('Elements found:', {
                'test_position element': testPositionElement,
                'test_position value': testPosition,
                'pipe_specimen checked': isPipeSpecimen,
                'plate_specimen checked': isPlateSpecimen,
                'position_range element': rangeDisplay,
                'position_range_span element': rangeDisplaySpan
            });
            
            if (!testPosition) {
                console.log('No test position selected, skipping range update');
                return;
            }
            
            // Ensure the test position value matches what's in the dropdown
            if (testPositionElement) {
                // This ensures we're using the actual selected value from the dropdown
                const selectedOption = testPositionElement.options[testPositionElement.selectedIndex];
                if (selectedOption) {
                    console.log('Selected option text:', selectedOption.text);
                }
            }
            
            let rangeText = '';
            
            // Position range rules based on specimen type
            const pipePositionRules = {
                '1G': '1G Groove Pipe | 1F Fillet Pipe',
                '2G': '1G, 2G Groove Pipe | 1F, 2F Fillet Pipe',
                '5G': '1G, 5G Groove Pipe | All Position Fillet Pipe',
                '6G': 'All Position Groove Pipe | All Position Fillet Pipe',
                '3G': '1G, 3G Groove Pipe | 1F, 2F, 3F Fillet Pipe',
                '4G': '1G, 4G Groove Pipe | 1F, 2F, 4F Fillet Pipe'
            };
            
            const platePositionRules = {
                '1G': '1G Groove Plate | 1F Fillet Plate',
                '2G': '1G, 2G Groove Plate | 1F, 2F Fillet Plate',
                '3G': '1G, 3G Groove Plate | 1F, 2F, 3F Fillet Plate',
                '4G': '1G, 4G Groove Plate | 1F, 2F, 4F Fillet Plate'
            };
            
            // Determine range text based on specimen type
            if (isPipeSpecimen) {
                rangeText = pipePositionRules[testPosition] || 'Not specified';
            } else if (isPlateSpecimen) {
                rangeText = platePositionRules[testPosition] || 'Not specified';
            } else {
                // Default to pipe if neither is explicitly checked
                rangeText = pipePositionRules[testPosition] || 'Not specified';
            }
            
            console.log(`Selected position ${testPosition} with ${isPipeSpecimen ? 'pipe' : 'plate'} specimen, range: ${rangeText}`);
            
            // Update the hidden input field
            if (rangeDisplay) {
                rangeDisplay.value = rangeText;
                console.log('Position range hidden field updated to:', rangeText);
            }
            
            // Also update any display span if it exists (but we've removed it from edit form)
            if (rangeDisplaySpan) {
                rangeDisplaySpan.textContent = rangeText;
                console.log('Position range span updated to:', rangeText);
            }
            
            // Update all range cells with the same data-range attribute
            const positionRangeCells = document.querySelectorAll('.var-range[data-range="position"]');
            if (positionRangeCells && positionRangeCells.length > 0) {
                positionRangeCells.forEach(cell => {
                    cell.innerHTML = `<span style="font-weight: bold; font-size: 8px;">${rangeText}</span>`;
                });
                console.log(`Updated ${positionRangeCells.length} position range cells`);
            } else {
                // If no specific position range cells, try to update the first range cell
                const rangeCells = document.querySelectorAll('.var-range');
                if (rangeCells && rangeCells.length > 0) {
                    rangeCells[0].innerHTML = `<span style="font-weight: bold; font-size: 8px;">${rangeText}</span>`;
                    console.log('Updated first range cell as fallback');
                }
            }
        } catch (error) {
            console.error('Error in updatePositionRange:', error);
        }
    }
    
    /**
     * Update backing range based on backing selection
     */
    function updateBackingRange() {
        const backing = document.getElementById('backing')?.value || '';
        const rangeDisplay = document.getElementById('backing_range');
        const rangeDisplaySpan = document.getElementById('backing_range_span');
        
        let rangeText = '';
        switch (backing) {
            case 'With Backing':
                rangeText = 'With backing or backing and gouging';
                break;
            case 'Without Backing':
                rangeText = 'With backing or backing and gouging | Without backing | Without backing and gouging';
                break;
            case '__manual__':
                const manualElement = document.getElementById('backing_range_manual');
                if (manualElement) manualElement.style.display = 'block';
                rangeText = 'Manual entry - specify range';
                break;
            default:
                rangeText = 'Not specified';
        }
        
        // Update the hidden input field
        if (rangeDisplay) {
            rangeDisplay.value = rangeText;
            console.log('Backing range updated to:', rangeText);
        }
        
        // Also update any display span if it exists
        if (rangeDisplaySpan) {
            rangeDisplaySpan.textContent = rangeText;
            rangeDisplaySpan.style.display = 'block';
        }
        
        // Update any visible display in the table
        const backingRangeCells = document.querySelectorAll('.var-range[data-range="backing"]');
        if (backingRangeCells && backingRangeCells.length > 0) {
            backingRangeCells[0].innerHTML = `<span style="font-weight: bold; font-size: 8px;">${rangeText}</span>`;
        }
    }
    
    /**
     * Update F-Number range based on filler F-Number selection
     */
    function updateFNumberRange() {
        const fillerFNo = document.getElementById('filler_f_no')?.value || '';
        const rangeDisplay = document.getElementById('f_number_range');
        const rangeDisplaySpan = document.getElementById('f_number_range_span');
        
        let rangeText = '';
        switch (fillerFNo) {
            case 'F4_with_backing':
                rangeText = 'F-No.4 Only';
                break;
            case 'F5_with_backing':
                rangeText = 'F-No.5 Only';
                break;
            case 'F4_without_backing':
                rangeText = 'F-No.4 Only';
                break;
            case 'F5_without_backing':
                rangeText = 'F-No.5 Only';
                break;
            case 'F43':
                rangeText = 'F-No.43 Only';
                break;
            case '__manual__':
                const manualElement = document.getElementById('f_number_range_manual');
                if (manualElement) manualElement.style.display = 'block';
                rangeText = 'Manual entry - specify range';
                break;
            default:
                rangeText = 'Not specified';
        }
        
        // Update the hidden input field
        if (rangeDisplay) {
            rangeDisplay.value = rangeText;
            console.log('F-Number range updated to:', rangeText);
        }
        
        // Also update any display span if it exists
        if (rangeDisplaySpan) {
            rangeDisplaySpan.textContent = rangeText;
            rangeDisplaySpan.style.display = 'block';
        }
        
        // Update any visible display in the table
        const fNumberRangeCells = document.querySelectorAll('.var-range[data-range="f-number"]');
        if (fNumberRangeCells && fNumberRangeCells.length > 0) {
            fNumberRangeCells[0].innerHTML = `<span style="font-weight: bold; font-size: 8px;">${rangeText}</span>`;
        }
    }
    
    /**
     * Update vertical progression range
     */
    function updateVerticalProgressionRange() {
        const verticalProgression = document.getElementById('vertical_progression')?.value || '';
        const rangeDisplay = document.getElementById('vertical_progression_range');
        const rangeDisplaySpan = document.getElementById('vertical_progression_range_span');
        
        let rangeText = '';
        switch (verticalProgression) {
            case 'Uphill':
                rangeText = 'Uphill only';
                break;
            case 'Downhill':
                rangeText = 'Downhill only';
                break;
            case 'Both':
                rangeText = 'Uphill and Downhill';
                break;
            default:
                rangeText = 'Not specified';
        }
        
        // Update the hidden input field
        if (rangeDisplay) {
            rangeDisplay.value = rangeText;
            console.log('Vertical progression range updated to:', rangeText);
        }
        
        // Also update any display span if it exists
        if (rangeDisplaySpan) {
            rangeDisplaySpan.textContent = rangeText;
            rangeDisplaySpan.style.display = 'block';
        }
        
        // Update any visible display in the table
        const verticalProgressionRangeCells = document.querySelectorAll('.var-range[data-range="vertical-progression"]');
        if (verticalProgressionRangeCells && verticalProgressionRangeCells.length > 0) {
            verticalProgressionRangeCells[0].innerHTML = `<span style="font-weight: bold; font-size: 8px;">${rangeText}</span>`;
        }
    }
    
    /**
     * Update specimen type selection
     */
    function updateSpecimenType(checkbox) {
        const plateCheckbox = document.getElementById('plate_specimen');
        const pipeCheckbox = document.getElementById('pipe_specimen');
        
        // Make checkboxes mutually exclusive
        if (checkbox.id === 'plate_specimen' && checkbox.checked) {
            pipeCheckbox.checked = false;
        } else if (checkbox.id === 'pipe_specimen' && checkbox.checked) {
            plateCheckbox.checked = false;
        }
        
        // Ensure at least one specimen type is selected
        if (!plateCheckbox.checked && !pipeCheckbox.checked) {
            checkbox.checked = true;
            alert('At least one specimen type must be selected.');
        }
        
        // Update position range based on specimen type
        updatePositionRange();
    }
    
    /**
     * Update diameter/thickness display
     */
    function updateDiaThickness() {
        const diameterType = document.getElementById('pipe_diameter_type')?.value || '';
        const diameterManual = document.getElementById('pipe_diameter_manual')?.value || '';
        const thickness = document.getElementById('smaw_thickness')?.value || '';
        const display = document.getElementById('dia_thickness_display');
        
        if (!display) return; // Exit if display element doesn't exist
        
        let diameter = '';
        
        switch (diameterType) {
            case '8_nps':
                diameter = '219.1';
                break;
            case '6_nps':
                diameter = '168.3';
                break;
            case '4_nps':
                diameter = '114.3';
                break;
            case '2_nps':
                diameter = '60.3';
                break;
            case '__manual__':
                diameter = diameterManual;
                break;
        }
        
        display.value = diameter + '/' + thickness + ' mm';
        
        // Update thickness range when thickness changes
        calculateThicknessRange(thickness);
    }
    
    /**
     * Calculate thickness range based on input thickness
     */
    function calculateThicknessRange(thickness) {
        console.log('Calculating thickness range for thickness:', thickness);
        
        // If thickness is empty or not a number, use default value
        if (!thickness || isNaN(parseFloat(thickness))) {
            console.log('Invalid thickness value, using default');
            updateThicknessRangeDisplay('Maximum to be welded');
            return 'Maximum to be welded';
        }
        
        // Parse the thickness value and calculate the range
        const thicknessValue = parseFloat(thickness);
        if (thicknessValue <= 12) {
            // For thickness <= 12mm: double the thickness value
            const doubledValue = (thicknessValue * 2).toFixed(2);
            const rangeValue = doubledValue + ' mm';
            console.log('Calculated thickness range:', rangeValue);
            updateThicknessRangeDisplay(rangeValue);
            return rangeValue;
        } else {
            console.log('Thickness exceeds 12mm, using "Maximum to be welded"');
            updateThicknessRangeDisplay('Maximum to be welded');
            return 'Maximum to be welded';
        }
    }
    
    /**
     * Update all thickness range display elements and hidden fields
     */
    function updateThicknessRangeDisplay(rangeValue) {
        // Update visual indicator
        const visualIndicator = document.getElementById('thickness_visual_indicator');
        if (visualIndicator) {
            visualIndicator.textContent = rangeValue;
            visualIndicator.style.display = 'block';
        }
        
        // Update hidden input field for form submission
        const hiddenField = document.getElementById('smaw_thickness_range_hidden');
        if (hiddenField) {
            hiddenField.value = rangeValue;
        }
        
        // Try using jQuery to update any other related elements if available
        if (typeof $ !== 'undefined' || typeof jQuery !== 'undefined') {
            const jq = $ || jQuery;
            try {
                // Update hidden field
                if (jq('#smaw_thickness_range_hidden').length) {
                    jq('#smaw_thickness_range_hidden').val(rangeValue);
                }
                
                // Update any display spans
                if (jq('#thickness_range_display').length) {
                    jq('#thickness_range_display').text(rangeValue);
                }
                
                console.log('Updated all thickness range elements to:', rangeValue);
            } catch (e) {
                console.error('Error updating thickness range elements:', e);
            }
        }
        
        // Update display span
        const displaySpan = document.getElementById('thickness_range_display');
        if (displaySpan) {
            displaySpan.textContent = rangeValue;
        }
        
        // No need to update visual indicator again, it's already done above
        
        // Try using jQuery if available
        if (typeof $ !== 'undefined') {
            try {
                $('#smaw_thickness_range').val(rangeValue);
                
                // Update related elements if they exist
                if ($('#smaw_thickness_range_hidden').length) {
                    $('#smaw_thickness_range_hidden').val(rangeValue);
                }
                if ($('#thickness_range_display').length) {
                    $('#thickness_range_display').text(rangeValue);
                }
                if ($('#thickness_visual_indicator').length) {
                    $('#thickness_visual_indicator').text(rangeValue);
                }
                
                // Trigger change event to notify any listeners
                $('#smaw_thickness_range').trigger('change');
                
                console.log('Used jQuery to update thickness range to value:', rangeValue);
            } catch (e) {
                console.log('jQuery update attempt failed:', e);
            }
        }
        
        // Update any visible display in the table
        const thicknessRangeCells = document.querySelectorAll('.var-range[data-range="thickness"]');
        if (thicknessRangeCells && thicknessRangeCells.length > 0) {
            thicknessRangeCells[0].innerHTML = `<span style="font-weight: bold;">${rangeValue}</span>`;
            console.log('Updated thickness range cells in table to value:', rangeValue);
        }
        
        return rangeValue;
    }
    
    /**
     * Update all range fields at once
     */
    function updateAllRangeFields() {
        try {
            console.log('Updating all range fields...');
            
            // Make sure specimen checkboxes are correctly set
            initializeSpecimenCheckboxes();
            
            updateDiameterRange();
            updatePNumberRange();
            updatePositionRange();
            updateBackingRange();
            updateFNumberRange();
            updateVerticalProgressionRange();
            
            // Update position vertical progression if it exists
            if (document.getElementById('vertical_progression')) {
                updatePositionVerticalProgressionRange();
            }
            
            // Update thickness range
            const thickness = document.getElementById('smaw_thickness')?.value || '';
            calculateThicknessRange(thickness);
            
            // Log all range values after update
            const rangeFields = [
                'diameter_range', 
                'p_number_range', 
                'position_range',
                'backing_range', 
                'f_number_range', 
                'vertical_progression_range',
                'smaw_thickness_range'
            ];
            
            console.log('Range values after update:');
            rangeFields.forEach(field => {
                const element = document.getElementById(field);
                console.log(`${field}: ${element ? element.value : 'not found'}`);
            });
        } catch (e) {
            console.error('Error updating range fields:', e);
        }
    }
    
    /**
     * Set explicit range values for all range fields
     */
    function setExplicitRangeValues() {
        try {
            console.log('Setting explicit range values...');
            
            // P-Number range - use optional chaining to prevent errors
            const pNoElement = document.getElementById('base_metal_p_no');
            const pNo = pNoElement?.value || '';
            const pNumberRules = {
                'P NO.1 TO P NO.1': 'P-No.1 Group 1 or 2',
                'P NO.3 TO P NO.3': 'P-No.3 Group 1 or 2',
                'P NO.4 TO P NO.4': 'P-No.4 Group 1 or 2',
                'P NO.5A TO P NO.5A': 'P-No.5A Group 1 or 2',
                'P NO.8 TO P NO.8': 'P-No.8 Group 1 or 2',
                'P NO.1 TO P NO.8': 'P-No.1 to P-No.8',
                'P NO.43 TO P NO.43': 'P-No.43'
            };
            
            const pNumberRangeText = pNumberRules[pNo] || 'P-NO. 1 through P-NO. 15F, P-NO. 34, and P-NO. 41 through P-NO. 49';
            const pNumberRangeElement = document.getElementById('p_number_range');
            
            if (pNumberRangeElement) {
                pNumberRangeElement.value = pNumberRangeText;
                console.log('Set P-Number range to:', pNumberRangeText);
            }
        
            // Call the update functions to set other range values
            console.log('Updating all range values...');
            
            // Update all ranges
            try { updateBackingRange(); } catch(e) { console.error('Error updating backing range:', e); }
            try { updateDiameterRange(); } catch(e) { console.error('Error updating diameter range:', e); }
            try { updateFNumberRange(); } catch(e) { console.error('Error updating F-Number range:', e); }
            try { updateVerticalProgressionRange(); } catch(e) { console.error('Error updating vertical progression range:', e); }
            try { updatePositionRange(); } catch(e) { console.error('Error updating position range:', e); }
            
            // Update position vertical progression if it exists
            try { 
                if (document.getElementById('vertical_progression')) {
                    updatePositionVerticalProgressionRange();
                }
            } catch(e) { 
                console.error('Error updating position vertical progression range:', e); 
            }
            
            // Update thickness range
            try { 
                const thickness = document.getElementById('smaw_thickness')?.value || '';
                calculateThicknessRange(thickness); 
            } catch(e) { 
                console.error('Error updating thickness range:', e); 
            }
            
            // Debug - output all range values to console
            console.log('Range values after explicit initialization:');
            const rangeFields = [
                'p_number_range',
                'diameter_range', 
                'f_number_range',
                'vertical_progression_range',
                'position_range',
                'backing_range',
                'smaw_thickness_range'
            ];
            
            rangeFields.forEach(field => {
                const element = document.getElementById(field);
                console.log(`${field}: ${element ? element.value : 'element not found'}`);
                
                // If element exists but has no value, mark it for attention
                if (element && (!element.value || element.value.trim() === '')) {
                    console.warn(`WARNING: ${field} has no value after initialization!`);
                }
            });
        } catch(e) {
            console.error('Error in setExplicitRangeValues:', e);
        }
    }
    
    /**
     * Update position vertical progression range
     */
    function updatePositionVerticalProgressionRange() {
        const verticalProgression = document.getElementById('vertical_progression')?.value || '';
        const rangeSpan = document.getElementById('vertical_progression_range_span');
        
        let rangeText = '';
        switch (verticalProgression) {
            case 'Uphill':
                rangeText = 'Uphill';
                break;
            case 'Downhill':
                rangeText = 'Downhill';
                break;
            case 'None':
                rangeText = 'Not applicable';
                break;
            default:
                rangeText = 'Not specified';
        }
        
        // Update the span if it exists
        if (rangeSpan) {
            rangeSpan.textContent = rangeText;
            rangeSpan.style.display = 'block';
            console.log('Position vertical progression range updated to:', rangeText);
        }
    }
    
    // Add event listener to initialize the vertical progression field on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize vertical progression on page load
        updatePositionVerticalProgressionRange();
    });
    
    // Function to update thickness range based on thickness value
    function enforceFixedThicknessValue() {
        // Get the thickness field to calculate value dynamically
        const thicknessField = document.getElementById('smaw_thickness');
        const thicknessValue = thicknessField ? thicknessField.value : '';
        
        // Calculate the range based on the current thickness value
        let valueToUse = calculateThicknessRange(thicknessValue);
        console.log('Updating thickness range to calculated value:', valueToUse);
        
        // No need to update visible field as it's been removed
        // Instead, we use the updateThicknessRangeDisplay function
        // which is called by calculateThicknessRange
        
        // Ensure the visual indicator is properly updated
        const visualIndicator = document.getElementById('thickness_visual_indicator');
        if (visualIndicator && visualIndicator.textContent !== valueToUse) {
            visualIndicator.textContent = valueToUse;
            console.log('Updated visual indicator to:', valueToUse);
        }
        
        // Update the hidden field for form submission
        const hiddenField = document.getElementById('smaw_thickness_range_hidden');
        if (hiddenField && hiddenField.value !== valueToUse) {
            hiddenField.value = valueToUse;
            console.log('Updated hidden field to:', valueToUse);
        }
        
        // Try jQuery to update ALL matching elements
        if (typeof $ !== 'undefined' || typeof jQuery !== 'undefined') {
            const jq = $ || jQuery;
            try {
                // Update hidden fields by ID
                if (jq('#smaw_thickness_range_hidden').length > 0) {
                    jq('#smaw_thickness_range_hidden').val(valueToUse);
                }
                
                // Update visual indicator
                if (jq('#thickness_visual_indicator').length > 0) {
                    jq('#thickness_visual_indicator').text(valueToUse);
                }
                
                // Also update by name (will update all fields with this name)
                jq('input[name="smaw_thickness_range"]').val(valueToUse);
                
                // Trigger change events for hidden fields
                jq('input[name="smaw_thickness_range"]').trigger('change');
            } catch (e) {
                console.error('Error updating with jQuery:', e);
            }
        }
        
        return valueToUse;
    }
    
    // Add event listener to initialize thickness range values
    document.addEventListener('DOMContentLoaded', function() {
        // Wait a bit for other scripts to finish initializing
        setTimeout(function() {
            console.log('Initializing thickness range with actual thickness value');
            
            // Get the actual thickness value
            const thicknessField = document.getElementById('smaw_thickness');
            const thicknessValue = thicknessField ? thicknessField.value : '';
            
            // Make sure the visual indicator is properly styled
            const visualIndicator = document.getElementById('thickness_visual_indicator');
            if (visualIndicator) {
                visualIndicator.style.padding = '8px';
                visualIndicator.style.fontWeight = 'bold';
                visualIndicator.style.border = '1px solid #ddd';
                visualIndicator.style.backgroundColor = '#f9f9f9';
                visualIndicator.style.borderRadius = '4px';
                visualIndicator.style.display = 'block';
            }
            
            // Calculate range and update all displays
            calculateThicknessRange(thicknessValue);
            
            // Call our enforcer function once
            enforceFixedThicknessValue();
            
            // Add change/input event listeners if not already added
            if (thicknessField) {
                thicknessField.addEventListener('change', function() {
                    const thickness = this.value;
                    console.log('Thickness change event in range-functions.js:', thickness);
                    // Calculate and update all displays
                    calculateThicknessRange(thickness);
                });
                
                thicknessField.addEventListener('input', function() {
                    const thickness = this.value;
                    console.log('Thickness input event in range-functions.js:', thickness);
                    // Calculate and update immediately when typing
                    calculateThicknessRange(thickness);
                });
            }
        }, 1000); // Delay by 1 second to ensure page is fully loaded
    });
    
    // Add final setup when window is fully loaded
    window.addEventListener('load', function() {
        console.log('Window fully loaded - initializing thickness range based on actual input');
        
        // Call our function to calculate values based on actual thickness
        enforceFixedThicknessValue();
        
        // Add a final check after a delay to ensure everything is properly initialized
        setTimeout(function() {
            // Check for hidden fields
            const hiddenFields = document.querySelectorAll('[id="smaw_thickness_range_hidden"]');
            const elementsByName = document.querySelectorAll('[name="smaw_thickness_range"]');
            
            console.log(`Found ${hiddenFields.length} elements with id="smaw_thickness_range_hidden"`);
            console.log(`Found ${elementsByName.length} elements with name="smaw_thickness_range"`);
            
            // Check for visual indicator
            const visualIndicator = document.getElementById('thickness_visual_indicator');
            if (!visualIndicator) {
                console.warn('Visual indicator element not found!');
            } else {
                console.log('Visual indicator found and is ready');
                // Add some basic styling if not already styled
                if (!visualIndicator.style.padding) {
                    visualIndicator.style.padding = '8px';
                    visualIndicator.style.fontWeight = 'bold';
                    visualIndicator.style.border = '1px solid #ddd';
                    visualIndicator.style.backgroundColor = '#f9f9f9';
                    visualIndicator.style.borderRadius = '4px';
                }
            }
            
            // Make sure thickness range is properly updated based on thickness value
            const thicknessField = document.getElementById('smaw_thickness');
            if (thicknessField) {
                const thicknessValue = thicknessField.value;
                console.log('Current thickness value:', thicknessValue);
                enforceFixedThicknessValue();
            }
        }, 1000);
    });

    // Expose functions to global scope
    window.addRangeFieldListeners = addRangeFieldListeners;
    window.initializeSpecimenCheckboxes = initializeSpecimenCheckboxes;
    /**
     * Update deposit thickness range based on deposit thickness value
     */
    function updateDepositThicknessRange(thickness) {
        // Handle various input formats
        let thicknessStr = String(thickness).trim();
        
        // Remove any non-numeric characters except period
        thicknessStr = thicknessStr.replace(/[^\d.]/g, '');
        
        // Handle case when an HTML element is passed instead of a value
        if (thickness && typeof thickness === 'object' && thickness.value !== undefined) {
            thicknessStr = String(thickness.value).trim().replace(/[^\d.]/g, '');
        } else if (thicknessStr.includes('[object HTMLInputElement]')) {
            // Handle case where object was converted to string
            const thicknessField = document.getElementById('deposit_thickness');
            thicknessStr = thicknessField ? String(thicknessField.value).trim().replace(/[^\d.]/g, '') : '';
        }
        
        // If no value or invalid, try to get from the input field
        if (!thicknessStr || isNaN(parseFloat(thicknessStr))) {
            const element = document.getElementById('deposit_thickness');
            if (element) {
                thicknessStr = String(element.value).trim().replace(/[^\d.]/g, '');
            }
        }
        
        // Log the parsed value for debugging
        console.log('Parsed deposit thickness value:', thicknessStr);
        
        const thicknessValue = parseFloat(thicknessStr);
        let rangeValue = '';
        
        if (!isNaN(thicknessValue)) {
            if (thicknessValue <= 12) {
                // For thickness <= 12mm: double the thickness value
                const doubledValue = (thicknessValue * 2).toFixed(2);
                rangeValue = doubledValue + ' mm';
            } else {
                // For thickness > 12mm: show "Maximum to be welded"
                rangeValue = 'Maximum to be welded';
            }
        } else {
            rangeValue = '';
        }
        
        console.log('Calculated deposit thickness range:', rangeValue);
        
        // Update the visual indicator directly
        const visualIndicator = document.getElementById('deposit_thickness_visual_indicator');
        if (visualIndicator) {
            visualIndicator.textContent = rangeValue;
        } else {
            console.error('deposit_thickness_visual_indicator element not found');
        }
        
        // Update the hidden field for form submission
        const hiddenField = document.getElementById('deposit_thickness_range_hidden');
        if (hiddenField) {
            hiddenField.value = rangeValue;
        }
    }

    window.updateDiameterRange = updateDiameterRange;
    window.updatePNumberRange = updatePNumberRange;
    window.updatePositionRange = updatePositionRange;
    window.updateBackingRange = updateBackingRange;
    window.updateFNumberRange = updateFNumberRange;
    window.updateVerticalProgressionRange = updateVerticalProgressionRange;
    window.updatePositionVerticalProgressionRange = updatePositionVerticalProgressionRange;
    window.updateSpecimenType = updateSpecimenType;
    window.enforceFixedThicknessValue = enforceFixedThicknessValue;
    window.updateDiaThickness = updateDiaThickness;
    window.calculateThicknessRange = calculateThicknessRange;
    window.updateAllRangeFields = updateAllRangeFields;
    window.setExplicitRangeValues = setExplicitRangeValues;
    window.updateDepositThicknessRange = updateDepositThicknessRange;
})();
