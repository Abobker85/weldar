<!-- CSS for ensuring thickness range value visibility -->
<style>
.thickness-range-container {
    position: relative;
    width: 100%;
}
.thickness-range-field {
    width: 100%;
    background-color: #f9f9f9 !important;
    border: 1px solid #ddd !important;
    color: #333 !important; 
}
.thickness-range-display {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    display: flex;
    align-items: center;
    padding-left: 8px;
    pointer-events: none;
    color: #333;
    font-weight: normal;
    z-index: 10;
}
.thickness-visual-indicator {
    margin-top: 5px;
    color: #007bff;
    font-weight: bold;
}
</style>

<!-- Welding Variables Table -->
<table class="variables-table">
    <tr>
        <td class="var-label">Welding Variables (QW-350)</td>
        <td class="var-value" style="width: 150px;"><strong>Actual Values</strong></td>
        <td class="var-range" style="width: 200px;"><strong>Range Qualified</strong></td>
    </tr>
    <tr>
        <td class="var-label">Welding process(es):</td>
        <td class="var-value">
            <select class="form-select" name="welding_process" id="welding_process"
                onchange="updateProcessFields()" disabled>
                <option value="SMAW" selected>SMAW</option>
            </select>
        </td>
        <td class="var-range">
            <span id="process_range_span">SMAW</span>
        </td>
    </tr>
    <tr>
        <td class="var-label">Type (i.e., manual, semi-automatic) used:</td>
        <td class="var-value">
            <select class="form-select" name="welding_type">
                <option value="Manual">Manual</option>
            </select>
        </td>
        <td class="var-range">Manual</td>
    </tr>
    <tr>
        <td class="var-label">Backing (with/without):</td>
        <td class="var-value">
            <select class="form-select" name="backing" id="backing" onchange="updateBackingRange()" 
                    data-saved-value="{{ $certificate->backing ?? '' }}">
                <option value="With Backing" {{ ($certificate->backing ?? '') === 'With Backing' ? 'selected' : '' }}>With Backing</option>
                <option value="Without Backing" {{ ($certificate->backing ?? '') === 'Without Backing' ? 'selected' : '' }}>Without Backing</option>
                <option value="__manual__" {{ !in_array(($certificate->backing ?? ''), ['With Backing', 'Without Backing', '']) && !empty($certificate->backing) ? 'selected' : '' }}>Manual Entry</option>
            </select>
            <input type="text" class="form-input" name="backing_manual" id="backing_manual"
                placeholder="Enter custom backing type" 
                value="{{ !in_array(($certificate->backing ?? ''), ['With Backing', 'Without Backing', '']) ? ($certificate->backing ?? '') : '' }}"
                style="{{ !in_array(($certificate->backing ?? ''), ['With Backing', 'Without Backing', '']) && !empty($certificate->backing) ? 'display: block;' : 'display: none;' }} margin-top: 2px;">
        </td>
        <td class="var-range">
            <span id="backing_range_span">With backing or backing </span>
            <input type="text" class="form-input" name="backing_range_manual"
                id="backing_range_manual" placeholder="Enter qualified range"
                style="display: none; margin-top: 2px;">
        </td>
    </tr>
    <tr>
        <td class="var-label">
            <div class="checkbox-container">
                <input type="hidden" name="plate_specimen" value="0">
                <input type="checkbox" name="plate_specimen" id="plate_specimen" value="1"
                       {{ old('plate_specimen', $certificate->plate_specimen) ? 'checked' : '' }}
                       onchange="toggleDiameterField()">
                <label for="plate_specimen">Plate</label>
                
                <input type="hidden" name="pipe_specimen" value="0">
                <input type="checkbox" name="pipe_specimen" id="pipe_specimen" value="1"
                       {{ old('pipe_specimen', $certificate->pipe_specimen) ? 'checked' : '' }}
                       onchange="toggleDiameterField(); updateDiameterRange(); updatePositionRange();">
                <label for="pipe_specimen">Pipe</label>
            </div>
            (enter diameter if pipe or tube)
        </td>
        <td class="var-value">
            <select class="form-select" name="pipe_diameter_type" id="pipe_diameter_type"
                onchange="updateDiameterRange()" data-saved-value="{{ $certificate->pipe_diameter_type ?? '' }}">
                <option value="8_nps" {{ ($certificate->pipe_diameter_type ?? '') === '8_nps' ? 'selected' : '' }}>8" NPS (Outside diameter 219.1 mm)</option>
                <option value="6_nps" {{ ($certificate->pipe_diameter_type ?? '') === '6_nps' ? 'selected' : '' }}>6" NPS (Outside diameter 168.3 mm)</option>
                <option value="4_nps" {{ ($certificate->pipe_diameter_type ?? '') === '4_nps' ? 'selected' : '' }}>4" NPS (Outside diameter 114.3 mm)</option>
                <option value="2_nps" {{ ($certificate->pipe_diameter_type ?? '') === '2_nps' ? 'selected' : '' }}>2" NPS (Outside diameter 60.3 mm)</option>
                <option value="1_nps" {{ ($certificate->pipe_diameter_type ?? '') === '1_nps' ? 'selected' : '' }}>1" NPS (Outside diameter 33.4 mm)</option>
                <option value="__manual__" {{ !in_array(($certificate->pipe_diameter_type ?? ''), ['8_nps', '6_nps', '4_nps', '2_nps', '1_nps', '']) && !empty($certificate->pipe_diameter_type) ? 'selected' : '' }}>Manual Entry</option>
            </select>
            <input type="text" class="form-input" name="pipe_diameter_manual"
                id="pipe_diameter_manual" placeholder="Enter diameter (e.g., 10 inch NPS)"
                value="{{ $certificate->pipe_diameter_manual ?? '' }}"
                style="{{ !in_array(($certificate->pipe_diameter_type ?? ''), ['8_nps', '6_nps', '4_nps', '2_nps', '1_nps', '']) && !empty($certificate->pipe_diameter_type) ? 'display: block;' : 'display: none;' }} margin-top: 2px;">
        </td>
        <td class="var-range">
            <span id="diameter_range_span">Outside diameter 2 7/8 inch (73 mm) to unlimited</span>
            <input type="hidden" name="diameter_range" id="diameter_range">
            <input type="text" class="form-input" name="diameter_range_manual"
                id="diameter_range_manual" placeholder="Enter qualified range"
                style="display: none; margin-top: 2px;">
        </td>
    </tr>
    <tr>
        <td class="var-label">Base metal P-Number to P-Number:</td>
        <td class="var-value">
            <select class="form-select" name="base_metal_p_no" id="base_metal_p_no"
                onchange="updatePNumberRange()" data-saved-value="{{ trim($certificate->base_metal_p_no ?? '') }}">
                @php
                    $savedPNumber = trim($certificate->base_metal_p_no ?? '');
                    $normalizedPNumber = preg_replace('/\s+/', ' ', strtoupper($savedPNumber));
                @endphp
                <option value="P NO.1 TO P NO.1" {{ $normalizedPNumber === 'P NO.1 TO P NO.1' ? 'selected' : '' }}>P NO.1 TO P NO.1</option>
                <option value="P NO.1 TO P NO.8" {{ $normalizedPNumber === 'P NO.1 TO P NO.8' ? 'selected' : '' }}>P NO.1 TO P NO.8</option>
                <option value="P NO.8 TO P NO.8" {{ $normalizedPNumber === 'P NO.8 TO P NO.8' ? 'selected' : '' }}>P NO.8 TO P NO.8</option>
                <option value="P NO.43 TO P NO.43" {{ $normalizedPNumber === 'P NO.43 TO P NO.43' ? 'selected' : '' }}>P NO.43 TO P NO.43</option>
                <option value="__manual__" {{ !in_array($normalizedPNumber, ['P NO.1 TO P NO.1', 'P NO.1 TO P NO.8', 'P NO.8 TO P NO.8', 'P NO.43 TO P NO.43', '']) && !empty($savedPNumber) ? 'selected' : '' }}>Manual Entry</option>
            </select>
            <input type="text" class="form-input" name="base_metal_p_no_manual"
                id="base_metal_p_no_manual" placeholder="Enter P-Number range"
                value="{{ !in_array(($certificate->base_metal_p_no ?? ''), ['P NO.1 TO P NO.1', 'P NO.1 TO P NO.8', 'P NO.8 TO P NO.8', 'P NO.43 TO P NO.43', '']) ? ($certificate->base_metal_p_no ?? '') : '' }}"
                style="{{ !in_array(($certificate->base_metal_p_no ?? ''), ['P NO.1 TO P NO.1', 'P NO.1 TO P NO.8', 'P NO.8 TO P NO.8', 'P NO.43 TO P NO.43', '']) && !empty($certificate->base_metal_p_no) ? 'display: block;' : 'display: none;' }} margin-top: 2px;">
        </td>
        <td class="var-range">
            <span id="p_number_range_span">{{ $certificate->p_number_range ?? 'P-NO. 1 through P-NO. 15F, P-NO. 34, and P-NO. 41 through P-NO. 49' }}</span>
            <input type="hidden" name="p_number_range_span" id="p_number_range_span_hidden">
             <input type="text" class="form-input" name="p_number_range"
                id="p_number_range" placeholder="Enter qualified range"
                style="display: none; margin-top: 2px;">
            <input type="text" class="form-input" name="p_number_range_manual"
                id="p_number_range_manual" placeholder="Enter qualified range"
                style="display: none; margin-top: 2px;">
        </td>
    </tr>
    <!-- Remaining welding variables rows -->
    <tr>
        <td class="var-label">Filler metal or electrode specification(s) (SFA) (info. only):</td>
        <td class="var-value">
            <select class="form-select" name="filler_spec" id="filler_spec"
                onchange="toggleManualEntry('filler_spec')" data-saved-value="{{ $certificate->filler_spec ?? '' }}">
                <option value="5.1" {{ ($certificate->filler_spec ?? '') === '5.1' ? 'selected' : '' }}>5.1</option>
                <option value="A5.1" {{ ($certificate->filler_spec ?? '') === 'A5.1' ? 'selected' : '' }}>A5.1</option>
                <option value="A5.18" {{ ($certificate->filler_spec ?? '') === 'A5.18' ? 'selected' : '' }}>A5.18</option>
                <option value="A5.20" {{ ($certificate->filler_spec ?? '') === 'A5.20' ? 'selected' : '' }}>A5.20</option>
                <option value="__manual__" {{ !in_array(($certificate->filler_spec ?? ''), ['5.1', 'A5.1', 'A5.18', 'A5.20', '']) && !empty($certificate->filler_spec) ? 'selected' : '' }}>Manual Entry</option>
            </select>
            <input type="text" class="form-input" name="filler_spec_manual" id="filler_spec_manual"
                placeholder="Enter SFA specification" 
                value="{{ !in_array(($certificate->filler_spec ?? ''), ['5.1', 'A5.1', 'A5.18', 'A5.20', '']) ? ($certificate->filler_spec ?? '') : '' }}"
                style="{{ !in_array(($certificate->filler_spec ?? ''), ['5.1', 'A5.1', 'A5.18', 'A5.20', '']) && !empty($certificate->filler_spec) ? 'display: block;' : 'display: none;' }} margin-top: 2px;">
        </td>
        <td class="var-range">
            <input type="text" class="form-input" name="filler_spec_range" id="filler_spec_range"
                placeholder="Enter qualified range" value="------">
        </td>
    </tr>
    <tr>
        <td class="var-label">Filler metal or electrode classification(s) (info. only):</td>
        <td class="var-value">
            <select class="form-select" name="filler_class" id="filler_class"
                onchange="toggleManualEntry('filler_class')" data-saved-value="{{ $certificate->filler_class ?? '' }}">
                <option value="E7018-1" {{ ($certificate->filler_class ?? '') === 'E7018-1' ? 'selected' : '' }}>E7018-1</option>
                <option value="E7018" {{ ($certificate->filler_class ?? '') === 'E7018' ? 'selected' : '' }}>E7018</option>
                <option value="E6010" {{ ($certificate->filler_class ?? '') === 'E6010' ? 'selected' : '' }}>E6010</option>
                <option value="E6013" {{ ($certificate->filler_class ?? '') === 'E6013' ? 'selected' : '' }}>E6013</option>
                <option value="ER70S-2" {{ ($certificate->filler_class ?? '') === 'ER70S-2' ? 'selected' : '' }}>ER70S-2</option>
                <option value="ER70S-6" {{ ($certificate->filler_class ?? '') === 'ER70S-6' ? 'selected' : '' }}>ER70S-6</option>
                <option value="__manual__" {{ !in_array(($certificate->filler_class ?? ''), ['E7018-1', 'E7018', 'E6010', 'E6013', 'ER70S-2', 'ER70S-6', '']) && !empty($certificate->filler_class) ? 'selected' : '' }}>Manual Entry</option>
            </select>
            <input type="text" class="form-input" name="filler_class_manual" id="filler_class_manual"
                placeholder="Enter classification" 
                value="{{ !in_array(($certificate->filler_class ?? ''), ['E7018-1', 'E7018', 'E6010', 'E6013', 'ER70S-2', 'ER70S-6', '']) ? ($certificate->filler_class ?? '') : '' }}"
                style="{{ !in_array(($certificate->filler_class ?? ''), ['E7018-1', 'E7018', 'E6010', 'E6013', 'ER70S-2', 'ER70S-6', '']) && !empty($certificate->filler_class) ? 'display: block;' : 'display: none;' }} margin-top: 2px;">
        </td>
        <td class="var-range">
            <input type="text" class="form-input" name="filler_class_range" id="filler_class_range"
                placeholder="Enter qualified range" value="------">
        </td>
    </tr>
    <tr>
        <td class="var-label">Filler metal F-Number(s):</td>
        <td class="var-value">
            <select class="form-select" name="filler_f_no" id="filler_f_no"
                onchange="updateFNumberRange()" data-saved-value="{{ $certificate->filler_f_no ?? '' }}">
                <option value="F4_with_backing" {{ ($certificate->filler_f_no ?? '') === 'F4_with_backing' ? 'selected' : '' }}>F-No.4 with Backing</option>
                <option value="F5_with_backing" {{ ($certificate->filler_f_no ?? '') === 'F5_with_backing' ? 'selected' : '' }}>F-No.5 with Backing</option>
                <option value="F4_without_backing" {{ ($certificate->filler_f_no ?? '') === 'F4_without_backing' ? 'selected' : '' }}>F-No.4 without Backing</option>
                <option value="F5_without_backing" {{ ($certificate->filler_f_no ?? '') === 'F5_without_backing' ? 'selected' : '' }}>F-No.5 without Backing</option>
                <option value="F43" {{ ($certificate->filler_f_no ?? '') === 'F43' ? 'selected' : '' }}>F-No.43</option>
                <option value="__manual__" {{ !in_array(($certificate->filler_f_no ?? ''), ['F4_with_backing', 'F5_with_backing', 'F4_without_backing', 'F5_without_backing', 'F43', '']) && !empty($certificate->filler_f_no) ? 'selected' : '' }}>Manual Entry</option>
            </select>
            <input type="text" class="form-input" name="filler_f_no_manual" id="filler_f_no_manual"
                placeholder="Enter F-Number" 
                value="{{ !in_array(($certificate->filler_f_no ?? ''), ['F4_with_backing', 'F5_with_backing', 'F4_without_backing', 'F5_without_backing', 'F43', '']) ? ($certificate->filler_f_no ?? '') : '' }}"
                style="{{ !in_array(($certificate->filler_f_no ?? ''), ['F4_with_backing', 'F5_with_backing', 'F4_without_backing', 'F5_without_backing', 'F43', '']) && !empty($certificate->filler_f_no) ? 'display: block;' : 'display: none;' }} margin-top: 2px;">
        </td>
        <td class="var-range">
            <span id="f_number_range_span">F-No.1 with Backing, F-No.2 with backing, F-No.3 with backing & F-No.4 With Backing</span>
            <input type="hidden" name="f_number_range_span" id="f_number_range_span_hidden" value="{{ $certificate->f_number_range ?? '' }}">
            <input type="text" class="form-input" name="f_number_range_manual"
                id="f_number_range_manual" placeholder="Enter qualified range"
                style="display: none; margin-top: 2px;">
        </td>
    </tr>
    <tr>
        <td class="var-label">Consumable insert (GTAW, PAW, LBW):</td>
        <td class="var-value">
            <input type="text" class="form-input" name="consumable_insert" placeholder="------" 
                   value="{{ $certificate->consumable_insert ?? '' }}" data-saved-value="{{ $certificate->consumable_insert ?? '' }}">
        </td>
        <td class="var-range">
            <input type="text" class="form-input" name="consumable_insert_range"
                placeholder="------">
        </td>
    </tr>
    <tr>
        <td class="var-label">Filler Metal Product Form (QW-404.23) (GTAW or PAW):</td>
        <td class="var-value">
            <input type="text" class="form-input" name="filler_product_form" placeholder="------"
                   value="{{ $certificate->filler_product_form ?? '' }}" data-saved-value="{{ $certificate->filler_product_form ?? '' }}">
        </td>
        <td class="var-range">
            <input type="text" class="form-input" name="filler_product_form_range"
                placeholder="------">
        </td>
    </tr>
    <tr>
        <td class="var-label">Deposit thickness for each process:</td>
        <td class="var-value">
            <input type="text" class="form-input" name="deposit_thickness" id="deposit_thickness"
                placeholder="Enter thickness (mm)" value="{{ $certificate->deposit_thickness ?? '' }}" required
                data-saved-value="{{ $certificate->deposit_thickness ?? '' }}"
                onchange="updateDepositThicknessRange(this.value)"
                oninput="this.value = this.value.replace(/[^0-9.]/g, ''); updateDepositThicknessRange(this.value)">
        </td>
        <td class="var-range">
            <!-- Hidden input for form submission -->
            <input type="hidden" name="deposit_thickness_range" id="deposit_thickness_range_hidden" 
                   value="{{ $certificate->deposit_thickness_range ?? '' }}">
            <!-- Visual indicator for thickness range -->
            <div id="deposit_thickness_visual_indicator" class="thickness-visual-indicator" 
                 style="font-weight: bold; padding: 8px; border: 1px solid #ddd; background-color: #f9f9f9; border-radius: 4px;">
                 {{ $certificate->deposit_thickness_range ?? '' }}
            </div>
        </td>
    </tr>
    <tr>
        <td class="var-label">
    Process 1 __ 3 layers minimum
            <div class="checkbox-container" style="display: inline-block; margin-left: 10px;">
                <input type="radio" name="smaw_process" id="smaw_yes" value="yes"
                       {{ ($certificate->smaw_process ?? 'yes') === 'yes' ? 'checked' : '' }}
                       data-saved-value="{{ $certificate->smaw_process ?? 'yes' }}">
                <label for="smaw_yes">YES</label>
                <input type="radio" name="smaw_process" id="smaw_no" value="no"
                       {{ ($certificate->smaw_process ?? 'yes') === 'no' ? 'checked' : '' }}>
                <label for="smaw_no">NO</label>
            </div>
        </td>
        <td class="var-value">
            <input type="text" class="form-input" name="smaw_thickness" id="smaw_thickness" 
                placeholder="Enter thickness (mm)" value="{{ $certificate->smaw_thickness ?? '14.26' }}" required
                data-saved-value="{{ $certificate->smaw_thickness ?? '' }}"
                onchange="calculateThicknessRange(this.value)" 
                oninput="this.value = this.value.replace(/[^0-9.]/g, ''); calculateThicknessRange(this.value)">
        </td>
        <td class="var-range">
            <!-- Hidden input for form submission -->
            <input type="hidden" name="smaw_thickness_range" id="smaw_thickness_range_hidden" 
                   value="{{ $certificate->smaw_thickness_range ?? '' }}">
            <!-- Visual indicator for thickness range -->
            <div id="thickness_visual_indicator" class="thickness-visual-indicator" 
                 style="font-weight: bold; padding: 8px; border: 1px solid #ddd; background-color: #f9f9f9; border-radius: 4px;"></div>
            <script>
                // Immediate execution to calculate the initial thickness range value
                (function() {
                    const thicknessField = document.getElementById('smaw_thickness');
                    const hiddenField = document.getElementById('smaw_thickness_range_hidden');
                    if (field) {
                        field.value = '10mm';
                        field.setAttribute('value', '10mm');
                        // Make the field read-only to prevent user edits
                        field.readOnly = true;
                        console.log('SMAW thickness range value set to 10mm via inline script');
                    }
                })();
            </script>
        </td>
    </tr>
    <!-- Vertical progression field moved to position-qualification.blade.php -->
    
</table>




<script>
// Ultra simplified thickness range function - ALWAYS returns 10mm
function calculateThicknessRange(thickness) {
    // Always use 10mm as fixed value regardless of input
    const fixedValue = '10mm';
    
    console.log('calculateThicknessRange called with:', thickness);
    console.log('Using FIXED value:', fixedValue, '(ignoring input)');
    
    // Simply update the single input field directly
    const rangeField = document.getElementById('smaw_thickness_range');
    
    if (rangeField) {
        // Force value to always be 10mm
        rangeField.value = fixedValue;
        rangeField.setAttribute('value', fixedValue);
        console.log('Thickness range input set to fixed value:', fixedValue);
        
        // Also apply the fixed value to any previous legacy fields (just to be safe)
        const hiddenField = document.getElementById('smaw_thickness_range_hidden');
        if (hiddenField) {
            hiddenField.value = fixedValue;
        }
        
        // Update jQuery if available
        if (typeof jQuery !== 'undefined') {
            jQuery('#smaw_thickness_range').val(fixedValue);
        }
    } else {
        console.error('smaw_thickness_range field not found');
    }
    
    return fixedValue; // Always return our fixed value
}

// SINGLE function to update the thickness range field in the UI
// Now just a wrapper that calls calculateThicknessRange
function updateThicknessRangeUI(value) {
    console.log('updateThicknessRangeUI called with value:', value);
    
    // Get the current thickness
    const thicknessField = document.getElementById('smaw_thickness');
    if (thicknessField) {
        // Recalculate using the current thickness value
        return calculateThicknessRange(thicknessField.value);
    } else {
        console.error('Thickness field not found, using provided value directly');
        return calculateThicknessRange(value);
    }
}

// Alias function to maintain backward compatibility with existing code
function updateThicknessRangeField(value) {
    // Simply delegate to calculateThicknessRange directly
    console.log('updateThicknessRangeField alias called, redirecting to calculateThicknessRange');
    return calculateThicknessRange(value);
}

// Create a simplified persistent updater to ensure the field value stays correct
function createPersistentUpdater(element, value) {
    // Handle undefined/null values
    if (value === undefined || value === null) {
        console.warn('createPersistentUpdater called with null/undefined value');
        value = 'Maximum to be welded';
    }
    
    // Convert to string if not already
    value = String(value);
    
    // Simple updater that ensures all components have the correct value
    function updateThicknessDisplay() {
        console.log('Updating thickness range display to:', value);
        
        // Update all parts of the thickness range UI
        
        // 1. Update the hidden field
        const hiddenField = document.getElementById('smaw_thickness_range_hidden');
        if (hiddenField) {
            hiddenField.value = value;
        }
        
        // 2. Update the display span text
        const displaySpan = document.getElementById('thickness_range_display');
        if (displaySpan) {
            displaySpan.textContent = value;
            displaySpan.style.display = 'flex'; // Make sure it's visible
        }
        
        // 3. Update the input field
        const inputField = document.getElementById('smaw_thickness_range');
        if (inputField) {
            // Temporarily enable
            const wasDisabled = inputField.disabled;
            if (wasDisabled) inputField.disabled = false;
            
            // Set value
            inputField.value = value;
            inputField.setAttribute('value', value);
            
            // Re-disable
            if (wasDisabled) inputField.disabled = true;
        }
        
        // 4. Create or update a visual indicator
        const indicatorId = 'thickness_visual_indicator';
        let visualIndicator = document.getElementById(indicatorId);
        
        if (!visualIndicator) {
            // Create a new indicator
            visualIndicator = document.createElement('div');
            visualIndicator.id = indicatorId;
            visualIndicator.style.marginTop = '5px';
            visualIndicator.style.color = '#007bff';
            visualIndicator.style.fontWeight = 'bold';
            
            // Add it to the range cell
            const rangeCell = document.querySelector('td.var-range');
            if (rangeCell) {
                rangeCell.appendChild(visualIndicator);
            }
        }
        
        // Update the indicator content
        if (visualIndicator) {
            visualIndicator.textContent = 'Range: ' + value;
        }
        
        console.log('Thickness range updated successfully with value:', value);
    }
    
    // Execute the update immediately
    updateThicknessDisplay();
    
    // Schedule another update after a short delay to ensure DOM stability
    setTimeout(updateThicknessDisplay, 300);
}

// Helper function to update the field value - simplified version
function updateFieldValue(element, value) {
    if (!element) {
        console.error('Cannot update field: element is null or undefined');
        return;
    }
    
    // Temporarily enable the field if it's disabled
    const wasDisabled = element.disabled;
    if (wasDisabled) {
        element.disabled = false;
    }
    
    // Set value directly
    element.value = value;
    element.setAttribute('value', value);
    console.log(`Field ${element.id} updated with value:`, value);
    
    // Restore the disabled state if needed
    if (wasDisabled) {
        element.disabled = true;
    }
    
    // Make sure we have a hidden input with the same name to submit the value
    ensureHiddenBackupField(element);
}

// Initialize thickness range on page load
document.addEventListener('DOMContentLoaded', function() {
    // Get the thickness range field
    const thicknessRangeField = document.getElementById('smaw_thickness_range');
    const depositThicknessRangeField = document.getElementById('deposit_thickness_range');
    
    // Initialize the thickness range field
    if (thicknessRangeField) {
        // Make sure the field is disabled
        thicknessRangeField.disabled = true;
        
        // First, ensure there's no pre-filled value that might be incorrect
        // We'll temporarily enable to update the value
        thicknessRangeField.disabled = false;
        thicknessRangeField.value = '';
        thicknessRangeField.disabled = true;
    }
    
    // Initialize the deposit thickness range field
    if (depositThicknessRangeField) {
        // Make sure the field is disabled
        depositThicknessRangeField.disabled = true;
        
        // First, ensure there's no pre-filled value that might be incorrect
        // We'll temporarily enable to update the value
        depositThicknessRangeField.disabled = false;
        depositThicknessRangeField.value = '';
        depositThicknessRangeField.disabled = true;
    }
    
    // Get current thickness value
    const thicknessField = document.getElementById('smaw_thickness');
    if (!thicknessField) {
        console.error('Thickness field not found');
        return;
    }
    
    const thickness = parseFloat(thicknessField.value);
    console.log('Initial thickness on page load:', thickness);
    
    // Use a slight delay to ensure DOM is fully ready
    setTimeout(function() {
        // Calculate the range - this now directly updates the UI
        const range = calculateThicknessRange(thickness);
        console.log('Initial thickness range calculated as:', range);
        
        console.log('Initial thickness range field updated with value:', range);
        
        // Update deposit thickness range
        const depositThicknessField = document.getElementById('deposit_thickness');
        if (depositThicknessField) {
            updateDepositThicknessRange(depositThicknessField.value);
            console.log('Initial deposit thickness range updated');
        }
        
        // Add event listener to ensure thickness range is updated when thickness changes
        if (thicknessField) {
            thicknessField.addEventListener('change', function() {
                const newThickness = this.value;
                // Direct call to calculate and update the range
                const newRange = calculateThicknessRange(newThickness);
                console.log('Thickness change event:', newThickness, 'Range:', newRange);
            });
            
            thicknessField.addEventListener('input', function() {
                const newThickness = this.value;
                // Direct call to calculate and update the range
                const newRange = calculateThicknessRange(newThickness);
                console.log('Thickness input event:', newThickness, 'Range:', newRange);
            });
            
            // Also trigger one calculation immediately
            setTimeout(() => {
                // Call calculateThicknessRange directly
                calculateThicknessRange(document.getElementById('smaw_thickness').value);
                console.log('Forced initial thickness range update');
            }, 500);
        }
    }, 100);
});

// Initialize the thickness range calculation when the page loads
// Function to help debug the thickness range field
function debugThicknessRange() {
    const thicknessField = document.getElementById('smaw_thickness');
    const thicknessRangeField = document.getElementById('smaw_thickness_range');
    const hiddenField = document.getElementById('smaw_thickness_range_hidden');
    
    if (thicknessField && thicknessRangeField) {
        console.log('Current thickness value:', thicknessField.value);
        console.log('Current thickness range value:', thicknessRangeField.value);
        console.log('Hidden field value:', hiddenField ? hiddenField.value : 'Not found');
        console.log('Thickness range attributes:', {
            'value': thicknessRangeField.value,
            'value attribute': thicknessRangeField.getAttribute('value'),
            'data-calculated-value': thicknessRangeField.getAttribute('data-calculated-value'),
            'data-original-value': thicknessRangeField.getAttribute('data-original-value'),
            'disabled': thicknessRangeField.disabled
        });
        
        // Force recalculation
        calculateThicknessRange(thicknessField.value);
        
        // Check if the hidden field exists, create if it doesn't
        if (!hiddenField) {
            const value = thicknessRangeField.value;
            const newHiddenField = document.createElement('input');
            newHiddenField.type = 'hidden';
            newHiddenField.id = 'smaw_thickness_range_hidden';
            newHiddenField.name = 'smaw_thickness_range';
            newHiddenField.value = value;
            
            // Add it near the original field
            if (thicknessRangeField.parentNode) {
                thicknessRangeField.parentNode.appendChild(newHiddenField);
                console.log('Created and added missing hidden thickness range field with value:', value);
            }
        }
    } else {
        console.error('Could not find thickness fields for debugging');
    }
}

// Function to ensure we have a hidden input with the same name to submit the value
function ensureHiddenBackupField(element) {
    if (!element) {
        console.error('No element provided to ensureHiddenBackupField');
        return null;
    }
    
    // If element is specifically the thickness range field, handle specially
    if (element.id === 'smaw_thickness_range') {
        const hiddenId = 'smaw_thickness_range_hidden';
        let hiddenField = document.getElementById(hiddenId);
        const container = document.getElementById('thickness_range_container');
        
        // If hidden field doesn't exist, create it
        if (!hiddenField) {
            hiddenField = document.createElement('input');
            hiddenField.type = 'hidden';
            hiddenField.id = hiddenId;
            hiddenField.name = 'smaw_thickness_range'; // Use same name as visible field
            
            // Add to container if it exists, otherwise add after the original element
            if (container) {
                container.appendChild(hiddenField);
                console.log('Created new hidden field in thickness range container');
            } else if (element.parentNode) {
                element.parentNode.appendChild(hiddenField);
                console.log('Created new hidden field after thickness range field');
            }
        }
        
        // Always update the hidden field value
        hiddenField.value = element.value;
        console.log('Updated hidden backup field:', hiddenId, 'to value:', element.value);
        
        // Also update the display elements
        const displaySpan = document.getElementById('thickness_range_display');
        const visualIndicator = document.getElementById('thickness_visual_indicator');
        
        if (displaySpan) displaySpan.textContent = element.value;
        if (visualIndicator) visualIndicator.textContent = element.value;
        
        return hiddenField;
    } 
    // For other disabled fields
    else if (element.disabled) {
        const hiddenId = element.id + '_hidden';
        let hiddenField = document.getElementById(hiddenId);
        
        // Create hidden field if it doesn't exist
        if (!hiddenField) {
            hiddenField = document.createElement('input');
            hiddenField.type = 'hidden';
            hiddenField.id = hiddenId;
            hiddenField.name = element.name;
            element.parentNode.appendChild(hiddenField);
        }
        
        // Update hidden field value
        hiddenField.value = element.value;
        return hiddenField;
    }
    
    return null;
}

// Calculate deposit thickness range
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
        // Show only the thickness value with mm unit
        rangeValue = thicknessValue + ' mm';
    } else {
        rangeValue = '';
    }
    
    console.log('Calculated deposit thickness range:', rangeValue);
    
    // Get the container element
    const container = document.getElementById('deposit_thickness_range_container');
    if (!container) {
        console.error('deposit_thickness_range_container element not found');
        return;
    }
    
    // Update the visual indicator directly
    const visualIndicator = document.getElementById('deposit_thickness_visual_indicator');
    if (visualIndicator) {
        visualIndicator.textContent = rangeValue;
    } else {
        console.error('deposit_thickness_visual_indicator element not found');
    }
    
    // Update the hidden field for form submission
    const hiddenField = document.getElementById('deposit_thickness_range_hidden');
    
    if (hiddenField) hiddenField.value = rangeValue;
    
    // Create or update the visual indicator
    const indicatorId = 'deposit_thickness_visual_indicator';
    let visualIndicator = document.getElementById(indicatorId);
    
    if (!visualIndicator) {
        visualIndicator = document.createElement('div');
        visualIndicator.id = indicatorId;
        visualIndicator.className = 'thickness-visual-indicator';
        
        // Add it to the range cell
        const rangeCell = document.querySelector('td.var-range');
        if (rangeCell && rangeCell.contains(container)) {
            rangeCell.appendChild(visualIndicator);
        } else {
            container.parentNode?.appendChild(visualIndicator);
        }
    }
    
    if (visualIndicator) {
        visualIndicator.textContent = 'Range: ' + rangeValue;
    }
    
    console.log('Final deposit field values:',
        'rangeField:', rangeField ? rangeField.value : 'N/A',
        'hiddenField:', hiddenField ? hiddenField.value : 'N/A',
        'displaySpan:', displaySpan ? displaySpan.textContent : 'N/A',
        'visualIndicator:', visualIndicator ? visualIndicator.textContent : 'N/A'
    );
    
    return rangeValue;
}

// Ultra simplified force update function - ALWAYS uses 10mm
function forceUpdateThicknessRange(value) {
    // ALWAYS use 10mm as the fixed value, completely ignore input
    const fixedValue = '10mm';
    console.log('forceUpdateThicknessRange called with:', value);
    console.log('FORCE setting to fixed value:', fixedValue, '(ignoring input)');
    
    // Update the single input field
    const rangeField = document.getElementById('smaw_thickness_range');
    
    if (rangeField) {
        // Force the value to always be 10mm
        rangeField.value = fixedValue;
        rangeField.setAttribute('value', fixedValue);
        
        // Log debug info
        console.log('Thickness update completed:');
        console.log('- Fixed value applied:', fixedValue);
        console.log('- Field value confirmed:', rangeField.value);
        
        // Also trigger a change event
        const event = new Event('change', { bubbles: true });
        rangeField.dispatchEvent(event);
    } else {
        console.log('Range field not found, will try to update with jQuery');
    }
    
    // For jQuery compatibility - also update jQuery objects if jQuery is available
    if (typeof jQuery !== 'undefined') {
        try {
            jQuery('#smaw_thickness_range').val(fixedValue);
            // Also trigger jQuery change event
            jQuery('#smaw_thickness_range').trigger('change');
            console.log('jQuery update completed for thickness range with fixed value');
        } catch (e) {
            console.error('Error updating with jQuery:', e);
        }
    }
    
    // Return consistent object with our fixed value
    return {
        rangeValue: fixedValue,
        value: fixedValue,
        fixedValue: fixedValue
    };
}

// Add debug trigger after a delay and expose functions globally
setTimeout(function() {
    // Add debugging convenience functions to window
    window.debugThicknessRange = debugThicknessRange;
    
    // Expose the main thickness functions globally so they can be called from other files
    window.calculateThicknessRange = calculateThicknessRange;
    window.forceUpdateThicknessRange = forceUpdateThicknessRange;
    window.updateThicknessRangeUI = updateThicknessRangeUI;
    window.updateThicknessRangeField = updateThicknessRangeField;
    
    // Also expose a direct function to set the thickness value with jQuery support
    window.setThicknessRange = function(value) {
        // ALWAYS use 10mm as the fixed value, completely ignoring the input value
        const fixedValue = '10mm';
        console.log('JS - Forced thickness range update to:', fixedValue, '(ignoring input value:', value, ')');
        
        // Update the single thickness range element
        const rangeField = document.getElementById('smaw_thickness_range');
        
        if (rangeField) {
            // Force the value to always be 10mm
            rangeField.value = fixedValue;
            rangeField.setAttribute('value', fixedValue);
            console.log('Range field updated directly to:', fixedValue);
        }
        
        // If jQuery is available, also update jQuery objects
        if (typeof jQuery !== 'undefined') {
            try {
                jQuery('#smaw_thickness_range').val(fixedValue);
                console.log('jQuery update completed, value set to:', fixedValue);
                
                // Force a jQuery change event to notify any listeners
                jQuery('#smaw_thickness_range').trigger('change');
            } catch (e) {
                console.error('Error updating with jQuery:', e);
            }
        }
        
        return fixedValue; // Return the fixed value, not just true
    };
    
    // Run debug automatically
    debugThicknessRange();
    
    // Ensure that thickness range is always set to 10mm, regardless of what other scripts might do
    const fixedThicknessValue = '10mm';
    
    // Force our fixed value everywhere, repeatedly
    function enforceFixedThicknessValue() {
        console.log('Enforcing fixed thickness value of', fixedThicknessValue);
        const rangeField = document.getElementById('smaw_thickness_range');
        
        if (rangeField && rangeField.value !== fixedThicknessValue) {
            rangeField.value = fixedThicknessValue;
            rangeField.setAttribute('value', fixedThicknessValue);
            console.log('Fixed thickness value enforced');
        }
        
        if (typeof jQuery !== 'undefined') {
            jQuery('#smaw_thickness_range').val(fixedThicknessValue);
        }
    }
    
    // Call immediately
    enforceFixedThicknessValue();
    
    // And set up intervals to repeatedly ensure our value sticks
    setTimeout(enforceFixedThicknessValue, 500);
    setTimeout(enforceFixedThicknessValue, 1000);
    setTimeout(enforceFixedThicknessValue, 2000);
    
    // Also create a MutationObserver to monitor changes to the field
    if (typeof MutationObserver !== 'undefined') {
        const rangeField = document.getElementById('smaw_thickness_range');
        if (rangeField) {
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (rangeField.value !== fixedThicknessValue) {
                        console.log('Value change detected, enforcing fixed value');
                        enforceFixedThicknessValue();
                    }
                });
            });
            
            observer.observe(rangeField, { 
                attributes: true, 
                attributeFilter: ['value'],
                characterData: true,
                childList: false,
                subtree: false
            });
            console.log('MutationObserver set up to enforce fixed thickness value');
        }
    }
}, 2000);

// Function to initialize welding variables from saved values
function initializeWeldingVariables() {
    // Get all select elements with data-saved-value attribute
    const selectElements = document.querySelectorAll('select[data-saved-value]');
    
    selectElements.forEach(select => {
        const savedValue = select.getAttribute('data-saved-value');
        
        if (savedValue && savedValue !== '') {
            // Check if this is a select with manual entry option
            const hasManualOption = Array.from(select.options).some(option => option.value === '__manual__');
            const manualFieldId = select.id + '_manual';
            
            // Find if there's a matching option
            let foundMatch = false;
            for (let i = 0; i < select.options.length; i++) {
                if (select.options[i].value === savedValue) {
                    select.selectedIndex = i;
                    foundMatch = true;
                    break;
                }
            }
            
            // If no match found and has manual option, select manual and fill the manual field
            if (!foundMatch && hasManualOption) {
                // Find and select the manual option
                for (let i = 0; i < select.options.length; i++) {
                    if (select.options[i].value === '__manual__') {
                        select.selectedIndex = i;
                        break;
                    }
                }
                
                // Show and populate the manual field
                const manualField = document.getElementById(manualFieldId);
                if (manualField) {
                    manualField.value = savedValue;
                    manualField.style.display = 'block';
                }
            }
            
            // Trigger change event to ensure any dependent fields are updated
            const event = new Event('change', { bubbles: true });
            select.dispatchEvent(event);
        }
    });
    
    // Initialize checkbox values
    const checkboxElements = document.querySelectorAll('input[type="checkbox"][data-saved-value]');
    checkboxElements.forEach(checkbox => {
        const savedValue = checkbox.getAttribute('data-saved-value');
        if (savedValue && savedValue !== '') {
            checkbox.checked = savedValue === '1' || savedValue === 'true' || savedValue === 'on';
        }
    });
    
    // Initialize radio button values
    const radioGroups = new Set();
    document.querySelectorAll('input[type="radio"][data-saved-value]').forEach(radio => {
        radioGroups.add(radio.name);
    });
    
    radioGroups.forEach(groupName => {
        const radios = document.querySelectorAll(`input[type="radio"][name="${groupName}"]`);
        if (radios.length > 0) {
            const savedValue = radios[0].getAttribute('data-saved-value');
            if (savedValue && savedValue !== '') {
                radios.forEach(radio => {
                    if (radio.value === savedValue) {
                        radio.checked = true;
                    }
                });
            }
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded - initializing welding variables');
    
    // Function to ensure thickness range is properly initialized
    function initializeThicknessRange() {
        // 1. Get all thickness-related fields
        const thicknessField = document.getElementById('smaw_thickness');
        const thicknessRangeField = document.getElementById('smaw_thickness_range');
        const hiddenRangeField = document.getElementById('smaw_thickness_range_hidden');
        const displaySpan = document.getElementById('thickness_range_display');
        
        if (!thicknessField) {
            console.error('Thickness field (smaw_thickness) not found!');
            return;
        }
        
        // 2. Log the initial state of all fields
        console.log('Thickness field initialization:');
        console.log('- Thickness value:', thicknessField.value);
        console.log('- Range field exists:', !!thicknessRangeField);
        console.log('- Hidden field exists:', !!hiddenRangeField);
        console.log('- Display span exists:', !!displaySpan);
        
        if (thicknessRangeField) {
            // Save original value for reference
            const originalValue = thicknessRangeField.value;
            console.log('- Original range value:', originalValue);
            
            // Set data attribute if needed
            if (!thicknessRangeField.hasAttribute('data-original-value')) {
                thicknessRangeField.setAttribute('data-original-value', originalValue);
            }
            
            // Make sure the field is disabled
            thicknessRangeField.disabled = true;
        }
        
        // 3. Calculate the initial thickness range
        const thickness = thicknessField.value;
        console.log('Calculating initial thickness range for:', thickness);
        calculateThicknessRange(thickness);
        
        // 4. Set up event listeners for thickness changes
        thicknessField.addEventListener('input', function() {
            console.log('Thickness input event with value:', this.value);
            calculateThicknessRange(this.value);
        });
        
        thicknessField.addEventListener('change', function() {
            console.log('Thickness change event with value:', this.value);
            calculateThicknessRange(this.value);
        });
        
        // 5. Force a calculation by triggering an input event
        const inputEvent = new Event('input', { bubbles: true });
        thicknessField.dispatchEvent(inputEvent);
        
        // 6. Return the current thickness for reference
        return thickness;
    }
    
    // Initialize all welding variables with saved values
    initializeWeldingVariables();
    
    // Initialize thickness range with staggered approach
    // First immediate initialization
    initializeThicknessRange();
    
    // Second initialization after a short delay
    setTimeout(function() {
        console.log('Performing secondary thickness range initialization');
        const thickness = initializeThicknessRange();
        
        // Add a specific check for [object HTMLInputElement] and fix it
        const checkAndFixObjectHTML = function() {
            const rangeField = document.getElementById('smaw_thickness_range');
            const hiddenField = document.getElementById('smaw_thickness_range_hidden');
            const displaySpan = document.getElementById('thickness_range_display');
            const visualIndicator = document.getElementById('thickness_visual_indicator');
            
            // Check for the [object HTMLInputElement] issue
            const objectPattern = /\[object HTML.*Element\]/;
            
            let needsFix = false;
            if (rangeField && objectPattern.test(rangeField.value)) needsFix = true;
            if (hiddenField && objectPattern.test(hiddenField.value)) needsFix = true;
            if (displaySpan && objectPattern.test(displaySpan.textContent)) needsFix = true;
            if (visualIndicator && objectPattern.test(visualIndicator.textContent)) needsFix = true;
            
            if (needsFix) {
                console.log('Found [object HTMLInputElement] issue, fixing it...');
                const thicknessInput = document.getElementById('smaw_thickness');
                if (thicknessInput) {
                    const thicknessValue = parseFloat(thicknessInput.value);
                    if (!isNaN(thicknessValue)) {
                        // Recalculate thickness range
                        let rangeValue = '';
                        if (thicknessValue <= 12) {
                            rangeValue = thicknessValue + ' mm to ' + (thicknessValue * 2).toFixed(2) + ' mm';
                        } else {
                            rangeValue = 'Maximum to be welded';
                        }
                        
                        // Update with corrected value
                        updateThicknessRangeField(rangeValue);
                        return true;
                    }
                }
            }
            return needsFix;
        };
        
        // Run the check immediately
        checkAndFixObjectHTML();
        
        // And run it again after a short delay
        setTimeout(checkAndFixObjectHTML, 500);
        
        // Additional initialization steps
        // Manually trigger calculation one more time
        calculateThicknessRange(thickness);
        
        // Final check after longer delay
        setTimeout(function() {
            console.log('Performing final thickness range verification');
            const thicknessField = document.getElementById('smaw_thickness');
            const thicknessRangeField = document.getElementById('smaw_thickness_range');
            const hiddenField = document.getElementById('smaw_thickness_range_hidden');
            
            if (thicknessField && thicknessRangeField) {
                console.log('Final thickness check:');
                console.log('- Current thickness:', thicknessField.value);
                console.log('- Current range field value:', thicknessRangeField.value);
                console.log('- Current hidden field value:', hiddenField ? hiddenField.value : 'No hidden field');
                
                // Force one last calculation
                calculateThicknessRange(thicknessField.value);
            }
        }, 1000);
    }, 300);
    
    // Initialize backing range immediately to ensure it's not empty
    const backing = document.getElementById('backing').value;
    const backingRangeText = (backing === 'With Backing') ? 
        'With backing' : 
        'With or Without backing';
    
    const backingRangeElement = document.getElementById('backing_range_span');
    if (backingRangeElement) {
        backingRangeElement.textContent = backingRangeText;
        
        // Also update the hidden field
        const backingRangeHidden = document.getElementById('backing_range');
        if (backingRangeHidden) {
            backingRangeHidden.value = backingRangeText;
        }
    }
    
    // Check plate/pipe selection to handle diameter range on page load
    const plateCheckbox = document.getElementById('plate_specimen');
    if (plateCheckbox && plateCheckbox.checked) {
        // If plate is selected, clear the diameter range
        const diameterRangeSpan = document.getElementById('diameter_range_span');
        if (diameterRangeSpan) {
            diameterRangeSpan.textContent = '';
        }
        
        // Also clear the hidden field
        const diameterRangeHidden = document.getElementById('diameter_range');
        if (diameterRangeHidden) {
            diameterRangeHidden.value = '';
        }
    }

    // Vertical progression field and initialization has been moved to position-qualification.blade.php
    
    // Ensure range fields are properly initialized
    setTimeout(function() {
        calculateThicknessRange(document.getElementById('smaw_thickness').value);
        const depositField = document.getElementById('deposit_thickness');
        updateDepositThicknessRange(depositField ? depositField.value : '');
    }, 500);
    
    // Run calculations again after a longer delay to handle any race conditions
    setTimeout(function() {
        calculateThicknessRange(document.getElementById('smaw_thickness').value);
        const depositField = document.getElementById('deposit_thickness');
        updateDepositThicknessRange(depositField ? depositField.value : '');
    }, 1000);
    
    // One final update to ensure values persist
    setTimeout(function() {
        calculateThicknessRange(document.getElementById('smaw_thickness').value);
        const depositField = document.getElementById('deposit_thickness');
        updateDepositThicknessRange(depositField ? depositField.value : '');
    }, 2000);

    // Initialize signature pad
    const canvas = document.getElementById('signature-pad');
    const signatureDataInput = document.getElementById('signature_data');
    
    if (canvas) {
        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgba(255, 255, 255, 0.8)',
            penColor: 'black'
        });
        
        // Clear signature button
        document.getElementById('clear-signature').addEventListener('click', function() {
            signaturePad.clear();
            signatureDataInput.value = '';
        });
        
        // Update hidden input when signature changes
        signaturePad.addEventListener("endStroke", () => {
            signatureDataInput.value = signaturePad.toDataURL();
        });
        
        // Handle window resize to maintain signature pad aspect ratio
        window.addEventListener('resize', resizeCanvas);
        
        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            signaturePad.clear(); // Otherwise isEmpty() might return incorrect value
        }
        
        resizeCanvas();
    }
    
    // Add a global function that can be called from console to force update thickness range
    window.forceUpdateThicknessRange = function(value) {
        // If no value provided, recalculate based on current thickness
        if (value === undefined || value === null) {
            const thicknessInput = document.getElementById('smaw_thickness');
            if (thicknessInput) {
                const thickness = parseFloat(thicknessInput.value);
                if (!isNaN(thickness)) {
                    value = calculateThicknessRange(thickness);
                } else {
                    console.warn('Invalid thickness value:', thicknessInput.value);
                    value = 'Maximum to be welded';
                }
            } else {
                console.error('Cannot find thickness input element');
                value = 'Maximum to be welded';
            }
        }
        
        // Ensure we have a valid string value
        if (value === undefined || value === null) {
            value = 'Maximum to be welded';
        }
        
        console.log('Forcing thickness range update to:', value);
        
        // Update the UI using our unified function - this already starts a persistent updater internally
        updateThicknessRangeUI(value);
        
        return {
            calculatedValue: value,
            currentThickness: document.getElementById('smaw_thickness').value,
            rangeInputValue: document.getElementById('smaw_thickness_range').value,
            rangeHiddenValue: document.getElementById('smaw_thickness_range_hidden')?.value,
            rangeDisplayValue: document.getElementById('thickness_range_display')?.textContent
        };
    };
    
    // Add a debug function to check all thickness-related fields
    window.debugThicknessRangeFields = function() {
        const thickness = document.getElementById('smaw_thickness');
        const rangeInput = document.getElementById('smaw_thickness_range');
        const hiddenRange = document.getElementById('smaw_thickness_range_hidden');
        const displaySpan = document.getElementById('thickness_range_display');
        const visualIndicator = document.getElementById('thickness_visual_indicator');
        
        // Calculate what the value should be
        const thicknessValue = thickness ? parseFloat(thickness.value) : null;
        let expectedRange = 'N/A';
        
        if (thicknessValue !== null && !isNaN(thicknessValue)) {
            if (thicknessValue <= 3) {
                expectedRange = thicknessValue + 'mm to ' + (thicknessValue * 2) + 'mm';
            } else if (thicknessValue <= 12) {
                expectedRange = thicknessValue + 'mm to ' + Math.round(thicknessValue * 2) + 'mm';
            } else {
                expectedRange = 'Maximum to be welded';
            }
        }
        
        // Return all relevant information
        return {
            thickness: {
                element: thickness ? true : false,
                value: thickness ? thickness.value : null,
                parsed: thicknessValue
            },
            expectedRange: expectedRange,
            rangeInput: {
                element: rangeInput ? true : false,
                value: rangeInput ? rangeInput.value : null,
                disabled: rangeInput ? rangeInput.disabled : null,
                matches: rangeInput ? (rangeInput.value === expectedRange) : false
            },
            hiddenRange: {
                element: hiddenRange ? true : false,
                value: hiddenRange ? hiddenRange.value : null,
                matches: hiddenRange ? (hiddenRange.value === expectedRange) : false
            },
            displaySpan: {
                element: displaySpan ? true : false,
                textContent: displaySpan ? displaySpan.textContent : null,
                matches: displaySpan ? (displaySpan.textContent.trim() === expectedRange) : false
            },
            visualIndicator: {
                element: visualIndicator ? true : false,
                textContent: visualIndicator ? visualIndicator.textContent : null,
                matches: visualIndicator ? (visualIndicator.textContent.trim() === expectedRange) : false
            }
        };
    };
});
</script>
