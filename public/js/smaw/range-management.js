/**
 * SMAW Certificate Form Range Management
 * Handles additional range-related functionality not covered in range-functions.js
 */

// Immediately-invoked function expression (IIFE) to avoid polluting global scope
(function() {
    'use strict';
    
    /**
     * Update all range fields at once
     */
    function updateAllRangeFields() {
        try {
            console.log('Updating all range fields before submission...');
            
            // These functions should be defined in range-functions.js
            if (typeof updateDiameterRange === 'function') updateDiameterRange();
            if (typeof updatePNumberRange === 'function') updatePNumberRange();
            if (typeof updatePositionRange === 'function') updatePositionRange();
            if (typeof updateBackingRange === 'function') updateBackingRange();
            if (typeof updateFNumberRange === 'function') updateFNumberRange();
            if (typeof updateVerticalProgressionRange === 'function') updateVerticalProgressionRange();

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

            const pNumberRangeText = pNumberRules[pNo] || 
                'P-NO. 1 through P-NO. 15F, P-NO. 34, and P-NO. 41 through P-NO. 49';
            const pNumberRangeElement = document.getElementById('p_number_range');

            if (pNumberRangeElement) {
                pNumberRangeElement.value = pNumberRangeText;
                console.log('Set P-Number range to:', pNumberRangeText);
            }

            // Call the update functions to set other range values
            console.log('Updating all range values...');

            // Backing range
            const backingElement = document.getElementById('backing');
            const backingRange = document.getElementById('backing_range');
            if (backingElement && backingRange) {
                try {
                    if (typeof updateBackingRange === 'function') updateBackingRange();
                } catch (e) {
                    console.error('Error updating backing range:', e);
                    // Set default value if update failed
                    backingRange.value = backingElement.value === 'With Backing' ? 
                        'With backing or backing and gouging' : 
                        'With backing or backing and gouging | Without backing | Without backing and gouging';
                }
            }

            // Update all other ranges
            try {
                if (typeof updateDiameterRange === 'function') updateDiameterRange();
            } catch (e) {
                console.error('Error updating diameter range:', e);
            }
            try {
                if (typeof updateFNumberRange === 'function') updateFNumberRange();
            } catch (e) {
                console.error('Error updating F-Number range:', e);
            }
            try {
                if (typeof updateVerticalProgressionRange === 'function') updateVerticalProgressionRange();
            } catch (e) {
                console.error('Error updating vertical progression range:', e);
            }
            try {
                if (typeof updatePositionRange === 'function') updatePositionRange();
            } catch (e) {
                console.error('Error updating position range:', e);
            }

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
        } catch (e) {
            console.error('Error in setExplicitRangeValues:', e);
        }
    }

    /**
     * Initialize welding variables with saved values
     */
    function initializeWeldingVariables() {
        try {
            console.log('Initializing welding variables...');
            
            // Call setExplicitRangeValues to ensure range values are set
            setExplicitRangeValues();
            
            // Check for empty range fields and fix them
            const rangeFields = [
                'diameter_range',
                'p_number_range',
                'position_range',
                'backing_range',
                'f_number_range',
                'vertical_progression_range'
            ];

            rangeFields.forEach(field => {
                const element = document.getElementById(field);
                if (element && (!element.value || element.value.trim() === '')) {
                    console.warn(`Range field ${field} is empty, will attempt to fix...`);

                    // Force update the specific range field based on current selections
                    switch (field) {
                        case 'diameter_range':
                            if (typeof updateDiameterRange === 'function') updateDiameterRange();
                            break;
                        case 'p_number_range':
                            if (typeof updatePNumberRange === 'function') updatePNumberRange();
                            break;
                        case 'position_range':
                            if (typeof updatePositionRange === 'function') updatePositionRange();
                            break;
                        case 'backing_range':
                            if (typeof updateBackingRange === 'function') updateBackingRange();
                            break;
                        case 'f_number_range':
                            if (typeof updateFNumberRange === 'function') updateFNumberRange();
                            break;
                        case 'vertical_progression_range':
                            if (typeof updateVerticalProgressionRange === 'function') updateVerticalProgressionRange();
                            break;
                    }
                }
            });
        } catch (e) {
            console.error('Error initializing welding variables:', e);
        }
    }

    // Export functions to global scope
    window.updateAllRangeFields = updateAllRangeFields;
    window.setExplicitRangeValues = setExplicitRangeValues;
    window.initializeWeldingVariables = initializeWeldingVariables;
    
})();
