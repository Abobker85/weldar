/**
 * SMAW Certificate Form Debug
 * Provides debugging tools for the SMAW Certificate form
 */

// Immediately-invoked function expression (IIFE) to avoid polluting global scope
(function() {
    'use strict';
    
    /**
     * Log the current state of specimen and position related fields
     */
    function logSpecimenState(source) {
        console.group(`Specimen State (source: ${source})`);
        
        // Specimen type checkboxes
        const plateCheckbox = document.getElementById('plate_specimen');
        const pipeCheckbox = document.getElementById('pipe_specimen');
        
        console.log('Specimen type:', {
            'plate_checked': plateCheckbox?.checked,
            'pipe_checked': pipeCheckbox?.checked
        });
        
        // Position fields
        const positionSelect = document.getElementById('test_position');
        const positionRange = document.getElementById('position_range');
        const positionRangeSpan = document.getElementById('position_range_span');
        
        console.log('Position fields:', {
            'test_position': positionSelect?.value,
            'position_range': positionRange?.value,
            'position_range_span': positionRangeSpan?.textContent
        });
        
        // Diameter fields
        const diameterField = document.getElementById('diameter');
        const pipeDiameterField = document.getElementById('pipe_diameter_type');
        const diameterRange = document.getElementById('diameter_range');
        
        console.log('Diameter fields:', {
            'diameter': diameterField?.value,
            'diameter_disabled': diameterField?.disabled,
            'pipe_diameter_type': pipeDiameterField?.value,
            'pipe_diameter_disabled': pipeDiameterField?.disabled,
            'diameter_range': diameterRange?.value
        });
        
        // Range cells in table
        const rangeCells = document.querySelectorAll('.var-range');
        if (rangeCells && rangeCells.length > 0) {
            console.log(`Found ${rangeCells.length} range cells in the table`);
            Array.from(rangeCells).forEach((cell, index) => {
                console.log(`Range cell ${index}:`, {
                    'data-range': cell.getAttribute('data-range'),
                    'text': cell.textContent.trim()
                });
            });
        } else {
            console.log('No range cells found in the table');
        }
        
        console.groupEnd();
    }
    
    /**
     * Override critical functions to add logging
     */
    function overrideFunctions() {
        // Store original functions
        const originalToggleDiameterField = window.toggleDiameterField;
        const originalUpdatePositionRange = window.updatePositionRange;
        const originalUpdatePositionOptions = window.updatePositionOptions;
        
        // Override toggleDiameterField
        if (typeof originalToggleDiameterField === 'function') {
            window.toggleDiameterField = function() {
                console.log('🔄 toggleDiameterField called');
                const result = originalToggleDiameterField.apply(this, arguments);
                logSpecimenState('toggleDiameterField');
                return result;
            };
        }
        
        // Override updatePositionRange
        if (typeof originalUpdatePositionRange === 'function') {
            window.updatePositionRange = function() {
                console.log('🔄 updatePositionRange called');
                const result = originalUpdatePositionRange.apply(this, arguments);
                logSpecimenState('updatePositionRange');
                return result;
            };
        }
        
        // Override updatePositionOptions
        if (typeof originalUpdatePositionOptions === 'function') {
            window.updatePositionOptions = function() {
                console.log('🔄 updatePositionOptions called');
                const result = originalUpdatePositionOptions.apply(this, arguments);
                logSpecimenState('updatePositionOptions');
                return result;
            };
        }
    }
    
    /**
     * Initialize debugging
     */
    function initializeDebugging() {
        console.log('📊 Form debugging initialized');
        overrideFunctions();
        
        // Log initial state
        setTimeout(() => {
            logSpecimenState('initial');
        }, 500);
    }
    
    // Expose debugging functions globally
    window.logSpecimenState = logSpecimenState;
    
    // Initialize debugging when DOM is loaded
    document.addEventListener('DOMContentLoaded', initializeDebugging);
})();
