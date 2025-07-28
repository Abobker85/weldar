/**
 * SMAW Certificate Form Specimen Type Control
 * Handles the pipe/plate toggle functionality and related fields
 */

// Immediately-invoked function expression (IIFE) to avoid polluting global scope
(function() {
    'use strict';
    
    /**
     * Toggle diameter field visibility and state based on plate/pipe selection
     * This is specific to the SMAW certificate form
     */
    function toggleDiameterField() {
        console.log('SMAW: toggleDiameterField called');
        
        const plateCheckbox = document.getElementById('plate_specimen');
        const pipeCheckbox = document.getElementById('pipe_specimen');
        
        if (!plateCheckbox || !pipeCheckbox) {
            console.error('Required checkboxes not found');
            return;
        }
        
        const diameterField = document.getElementById('diameter');
        const pipeDiameterField = document.getElementById('pipe_diameter_type');
        const diameterRangeManual = document.getElementById('diameter_range_manual');
        const pipeDiameterManual = document.getElementById('pipe_diameter_manual');
        const diameterRangeSpan = document.getElementById('diameter_range_span');
        const diameterRangeHidden = document.getElementById('diameter_range');
        
        // Ensure at least one checkbox is checked
        if (!plateCheckbox.checked && !pipeCheckbox.checked) {
            pipeCheckbox.checked = true;
        }
        
        // Always make checkboxes mutually exclusive
        if (plateCheckbox.checked) {
            pipeCheckbox.checked = false;
        } else {
            pipeCheckbox.checked = true;
        }
        
        console.log('SMAW Specimen type:', {
            plate: plateCheckbox.checked,
            pipe: pipeCheckbox.checked
        });
        
        // Find pipe-specific container (if any) and adjust visibility
        const pipeContainer = document.querySelector('.pipe-specific-fields');
        if (pipeContainer) {
            pipeContainer.style.display = pipeCheckbox.checked ? 'block' : 'none';
        }
        
        // Handle field visibility and state
        if (plateCheckbox.checked) {
            // Plate selected - disable and clear pipe-related fields
            if (diameterField) {
                diameterField.disabled = true;
                diameterField.value = "N/A";
            }
            
            if (pipeDiameterField) {
                pipeDiameterField.disabled = true;
                pipeDiameterField.value = "";
            }
            
            // Clear manual inputs
            if (diameterRangeManual) diameterRangeManual.value = "";
            if (pipeDiameterManual) pipeDiameterManual.value = "";
            
            // Set diameter range for plate specimen
            if (diameterRangeSpan) diameterRangeSpan.textContent = "Plate specimen";
            if (diameterRangeHidden) diameterRangeHidden.value = "Plate specimen";
            
            // Update any UI elements for plate specimen
            document.querySelectorAll('.plate-only').forEach(el => {
                el.style.display = 'block';
            });
            
            document.querySelectorAll('.pipe-only').forEach(el => {
                el.style.display = 'none';
            });
            
        } else {
            // Pipe selected - enable pipe-related fields and set defaults
            if (diameterField) {
                diameterField.disabled = false;
                diameterField.value = diameterField.value === "N/A" ? "8 inch" : (diameterField.value || "8 inch");
            }
            
            if (pipeDiameterField) {
                pipeDiameterField.disabled = false;
                pipeDiameterField.value = pipeDiameterField.value || "8_nps";
            }
            
            // Update any UI elements for pipe specimen
            document.querySelectorAll('.plate-only').forEach(el => {
                el.style.display = 'none';
            });
            
            document.querySelectorAll('.pipe-only').forEach(el => {
                el.style.display = 'block';
            });
            
            // Update diameter range based on pipe size
            if (typeof window.updateDiameterRange === 'function') {
                window.updateDiameterRange();
            }
        }
        
        // Update related fields
        if (typeof window.updateDiaThickness === 'function') {
            window.updateDiaThickness();
        }
        
        // Update position options based on specimen type
        updatePositionOptions();
        
        // Ensure position range is also updated
        if (typeof window.updatePositionRange === 'function') {
            window.updatePositionRange();
        }
    }
    
    /**
     * Update position dropdown options based on pipe/plate selection
     */
    function updatePositionOptions() {
        console.log('updatePositionOptions called');
        
        const plateCheckbox = document.getElementById('plate_specimen');
        const pipeCheckbox = document.getElementById('pipe_specimen');
        const positionSelect = document.getElementById('test_position');
        
        if (!plateCheckbox || !pipeCheckbox || !positionSelect) {
            console.error('Required elements not found for updatePositionOptions');
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
        } else {
            // For pipe, show 1G, 2G, 5G, 6G
            const pipePositions = ['1G', '2G', '5G', '6G', '3G', '4G'];
            pipePositions.forEach(pos => {
                positionSelect.add(new Option(pos, pos));
            });
        }
        
        // Try to restore the previous value if it's still valid
        if (Array.from(positionSelect.options).some(option => option.value === currentValue)) {
            positionSelect.value = currentValue;
        } else {
            // Default to appropriate position based on specimen type
            if (pipeCheckbox.checked) {
                positionSelect.value = '6G';
            } else if (plateCheckbox.checked && positionSelect.options.length > 1) {
                positionSelect.value = '1G';
            }
        }
        
        console.log('Position updated to:', positionSelect.value);
        
        // Update position range based on new selection
        if (typeof window.updatePositionRange === 'function') {
            window.updatePositionRange();
        } else {
            console.warn('updatePositionRange function not found');
        }
    }
    
    /**
     * Set up all event listeners for specimen type controls
     */
    function setupSpecimenTypeListeners() {
        console.log('Setting up specimen type listeners');
        
        const plateCheckbox = document.getElementById('plate_specimen');
        const pipeCheckbox = document.getElementById('pipe_specimen');
        
        if (plateCheckbox) {
            plateCheckbox.addEventListener('change', function() {
                console.log('Plate checkbox changed:', this.checked);
                toggleDiameterField();
            });
        }
        
        if (pipeCheckbox) {
            pipeCheckbox.addEventListener('change', function() {
                console.log('Pipe checkbox changed:', this.checked);
                toggleDiameterField();
            });
        }
    }
    
    // Expose functions to global scope
    window.toggleDiameterField = toggleDiameterField;
    window.updatePositionOptions = updatePositionOptions;
    window.setupSpecimenTypeListeners = setupSpecimenTypeListeners;
    
    // Set up listeners when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        setupSpecimenTypeListeners();
        
        // Initialize specimen type controls
        toggleDiameterField();
    });
})();
