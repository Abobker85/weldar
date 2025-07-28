/**
 * SMAW Certificate Form Position Initialization
 * Handles initialization of position range values
 */

// Immediately-invoked function expression (IIFE) to avoid polluting global scope
(function() {
    'use strict';
    
    /**
     * Initialize position range values on page load
     */
    function initializePositionRange() {
        console.log('Initializing position range fields');
        
        const positionRangeField = document.getElementById('position_range');
        const positionSelect = document.getElementById('test_position');
        const positionRangeSpan = document.getElementById('position_range_span');
        
        if (!positionRangeField || !positionSelect) {
            console.error('Position range elements not found');
            return;
        }
        
        // If we have a saved position range value, show it in the span
        if (positionRangeField.value) {
            console.log('Using saved position range:', positionRangeField.value);
            if (positionRangeSpan) {
                positionRangeSpan.textContent = positionRangeField.value;
                positionRangeSpan.style.display = 'block';
            }
            return;
        }
        
        // Otherwise, update position range based on selected position
        if (typeof window.updatePositionRange === 'function') {
            window.updatePositionRange();
        }
    }
    
    // Expose to global scope
    window.initializePositionRange = initializePositionRange;
    
    // Initialize on DOM ready
    document.addEventListener('DOMContentLoaded', function() {
        initializePositionRange();
    });
})();
