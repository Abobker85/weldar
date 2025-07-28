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
    function addRangeFieldListeners() {
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
            { id: 'vertical_progression', handler: updateVerticalProgressionRange }
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
            const testPosition = testPositionElement ? testPositionElement.value : '';
            
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
            
            // Also update any display span if it exists
            if (rangeDisplaySpan) {
                rangeDisplaySpan.textContent = rangeText;
                rangeDisplaySpan.style.display = 'block';
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
    }
    
    /**
     * Update all range fields at once
     */
    function updateAllRangeFields() {
        try {
            console.log('Updating all range fields...');
            updateDiameterRange();
            updatePNumberRange();
            updatePositionRange();
            updateBackingRange();
            updateFNumberRange();
            updateVerticalProgressionRange();
            
            // Log all range values after update
            const rangeFields = [
                'diameter_range', 
                'p_number_range', 
                'position_range',
                'backing_range', 
                'f_number_range', 
                'vertical_progression_range'
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
            
            // Debug - output all range values to console
            console.log('Range values after explicit initialization:');
            const rangeFields = [
                'p_number_range',
                'diameter_range', 
                'f_number_range',
                'vertical_progression_range',
                'position_range',
                'backing_range'
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
    
    // Expose functions to global scope
    window.addRangeFieldListeners = addRangeFieldListeners;
    window.updateDiameterRange = updateDiameterRange;
    window.updatePNumberRange = updatePNumberRange;
    window.updatePositionRange = updatePositionRange;
    window.updateBackingRange = updateBackingRange;
    window.updateFNumberRange = updateFNumberRange;
    window.updateVerticalProgressionRange = updateVerticalProgressionRange;
    window.updateSpecimenType = updateSpecimenType;
    window.updateDiaThickness = updateDiaThickness;
    window.updateAllRangeFields = updateAllRangeFields;
    window.setExplicitRangeValues = setExplicitRangeValues;
})();
