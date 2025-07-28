/**
 * SMAW Certificate Form Initialization
 * Handles the initial setup and validation of the SMAW Certificate form
 */

// Immediately-invoked function expression (IIFE) to avoid polluting global scope
(function() {
    'use strict';
    
    // Form initialization function
    function initializeForm() {
        console.log('Initializing SMAW certificate form');
        
        // Set up specimen type controls first
        if (typeof window.forceInitSpecimenState === 'function') {
            console.log('Initializing specimen state');
            window.forceInitSpecimenState();
        } else {
            console.warn('forceInitSpecimenState function not found');
        }
        
        // Fix problematic fields immediately on page load
        if (typeof window.fixProblematicFields === 'function') {
            window.fixProblematicFields();
        }
        
        // Initialize welding variables with saved values
        if (typeof window.initializeWeldingVariables === 'function') {
            window.initializeWeldingVariables();
        }
        
        // Set up range field listeners
        if (typeof window.addRangeFieldListeners === 'function') {
            window.addRangeFieldListeners();
        }
        
        // Initialize signature pads
        if (typeof window.initializeSignaturePads === 'function') {
            window.initializeSignaturePads();
        }
        
        // Initialize position range values
        if (typeof window.initializePositionRange === 'function') {
            window.initializePositionRange();
        }
        
        // Initialize form fields and state
        if (typeof window.updateDiameterRange === 'function') updateDiameterRange();
        if (typeof window.updatePNumberRange === 'function') updatePNumberRange();
        if (typeof window.updatePositionRange === 'function') updatePositionRange();
        if (typeof window.updateBackingRange === 'function') updateBackingRange();
        if (typeof window.updateFNumberRange === 'function') updateFNumberRange();
        if (typeof window.updateVerticalProgressionRange === 'function') updateVerticalProgressionRange();
        if (typeof window.updateDiaThickness === 'function') updateDiaThickness();
        
        // Initialize welder and company information
        if (typeof window.displayWelderAndCompanyInfo === 'function') {
            try {
                window.displayWelderAndCompanyInfo();
            } catch (err) {
                console.warn('Error calling displayWelderAndCompanyInfo:', err);
            }
        }
        
        // Initialize range values on page load
        if (typeof window.setExplicitRangeValues === 'function') {
            window.setExplicitRangeValues();
        }
        
        // Force update all range fields to ensure they have values
        if (typeof window.updateAllRangeFields === 'function') {
            window.updateAllRangeFields();
        }
        
        // Add keyboard shortcuts
        document.addEventListener('keydown', function(event) {
            // Ctrl+S to save
            if (event.ctrlKey && event.key === 's') {
                event.preventDefault();
                document.getElementById('saveBtn').click();
            }
            
            // Ctrl+P to preview
            if (event.ctrlKey && event.key === 'p') {
                event.preventDefault();
                if (typeof window.previewForm === 'function') {
                    window.previewForm();
                }
            }
        });
        
        // Immediate verification of specimen state and position
        setTimeout(function() {
            const plateCheckbox = document.getElementById('plate_specimen');
            const pipeCheckbox = document.getElementById('pipe_specimen');
            const testPosition = document.getElementById('test_position')?.value;
            const positionRange = document.getElementById('position_range')?.value;
            
            console.log('Form state verification:', {
                'Plate checked': plateCheckbox?.checked,
                'Pipe checked': pipeCheckbox?.checked,
                'Position': testPosition,
                'Position Range': positionRange
            });
            
            // Fix pipe/plate state if needed
            if (plateCheckbox?.checked === pipeCheckbox?.checked) {
                console.warn('Both or neither specimen type checked - fixing...');
                if (typeof window.toggleDiameterField === 'function') {
                    window.toggleDiameterField();
                }
            }
            
            // Ensure position range is properly set
            if (!positionRange || positionRange === 'Not specified') {
                console.warn('Position range missing or invalid - updating...');
                if (typeof window.updatePositionRange === 'function') {
                    window.updatePositionRange();
                }
            }
        }, 500);
        
        console.log('Form initialization complete');
    }
    
    // Register the initialization function to run when DOM is fully loaded
    document.addEventListener('DOMContentLoaded', function() {
        try {
            initializeForm();
        } catch (error) {
            console.error('Error during form initialization:', error);
        }
    });
})();
