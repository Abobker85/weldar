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
                <option value="GTAW" selected>GTAW</option>
            </select>
        </td>
        <td class="var-range">
            <span id="process_range_span">GTAW</span>
        </td>
    </tr>
    <tr>
        <td class="var-label">Type (i.e., manual, semi-automatic) used:</td>
        <td class="var-value">
            <select class="form-select" name="welding_type">
                <option value="Manual" {{ (isset($certificate) && $certificate->welding_type == 'Manual') || !isset($certificate) ? 'selected' : '' }}>Manual</option>
                <option value="Semi-automatic" {{ isset($certificate) && $certificate->welding_type == 'Semi-automatic' ? 'selected' : '' }}>Semi-automatic</option>
                <option value="Automatic" {{ isset($certificate) && $certificate->welding_type == 'Automatic' ? 'selected' : '' }}>Automatic</option>
            </select>
        </td>
        <td class="var-range">Manual</td>
    </tr>
    <tr>
        <td class="var-label">Backing (with/without):</td>
        <td class="var-value">
            <select class="form-select" name="backing" id="backing" onchange="updateBackingRange()">
                <option value="With Backing" {{ isset($certificate) && $certificate->backing == 'With Backing' ? 'selected' : '' }}>With Backing</option>
                <option value="Without Backing" {{ isset($certificate) && $certificate->backing == 'Without Backing' ? 'selected' : '' }}>Without Backing</option>
                <option value="__manual__">Manual Entry</option>
            </select>
            <input type="text" class="form-input" name="backing_manual" id="backing_manual"
                placeholder="Enter custom backing type" style="display: none; margin-top: 2px;">
        </td>
        <td class="var-range">
            <span id="backing_range_span">{{ isset($certificate) ? $certificate->backing_range : 'With backing or backing' }}</span>
            <input type="text" class="form-input" name="backing_range_manual"
                id="backing_range_manual" placeholder="Enter qualified range"
                style="display: none; margin-top: 2px;">
        </td>
    </tr>
    <tr>
        <td class="var-label">
            <div class="checkbox-container">
                <input type="checkbox" name="plate_specimen" id="plate_specimen" onchange="toggleDiameterField()" {{ isset($certificate) && $certificate->plate_specimen ? 'checked' : '' }}>
                <label for="plate_specimen">Plate</label>
                <input type="checkbox" name="pipe_specimen" id="pipe_specimen"
                    onchange="toggleDiameterField(); updateDiameterRange()" {{ isset($certificate) && $certificate->pipe_specimen ? 'checked' : (!isset($certificate) ? 'checked' : '') }}>
                <label for="pipe_specimen">Pipe</label>
            </div>
            (enter diameter if pipe or tube)
        </td>
        <td class="var-value">
            <select class="form-select" name="pipe_diameter_type" id="pipe_diameter_type"
                onchange="updateDiameterRange()">
                <option value="8_nps" {{ isset($certificate) && $certificate->pipe_diameter_type == '8_nps' ? 'selected' : '' }}>8" NPS (Outside diameter 219.1 mm)</option>
                <option value="6_nps" {{ isset($certificate) && $certificate->pipe_diameter_type == '6_nps' ? 'selected' : '' }}>6" NPS (Outside diameter 168.3 mm)</option>
                <option value="4_nps" {{ isset($certificate) && $certificate->pipe_diameter_type == '4_nps' ? 'selected' : '' }}>4" NPS (Outside diameter 114.3 mm)</option>
                <option value="2_nps" {{ isset($certificate) && $certificate->pipe_diameter_type == '2_nps' ? 'selected' : '' }}>2" NPS (Outside diameter 60.3 mm)</option>
                <option value="1_nps" {{ isset($certificate) && $certificate->pipe_diameter_type == '1_nps' ? 'selected' : '' }}>1" NPS (Outside diameter 33.4 mm)</option>
                <option value="__manual__">Manual Entry</option>
            </select>
            <input type="text" class="form-input" name="pipe_diameter_manual"
                id="pipe_diameter_manual" placeholder="Enter diameter (e.g., 10 inch NPS)"
                style="display: none; margin-top: 2px;">
        </td>
        <td class="var-range">
            <span id="diameter_range_span">{{ isset($certificate) ? $certificate->diameter_range : 'Outside diameter 2 7/8 inch (73 mm) to unlimited' }}</span>
            <input type="text" class="form-input" name="diameter_range_manual"
                id="diameter_range_manual" placeholder="Enter qualified range"
                style="display: none; margin-top: 2px;">
        </td>
    </tr>
    <tr>
        <td class="var-label">Base metal P-Number to P-Number:</td>
        <td class="var-value">
            <select class="form-select" name="base_metal_p_no" id="base_metal_p_no"
                onchange="updatePNumberRange()">
                <option value="P NO.1 TO P NO.1" {{ isset($certificate) && $certificate->base_metal_p_no == 'P NO.1 TO P NO.1' ? 'selected' : '' }}>P NO.1 TO P NO.1</option>
                <option value="P NO.1 TO P NO.8" {{ isset($certificate) && $certificate->base_metal_p_no == 'P NO.1 TO P NO.8' ? 'selected' : '' }}>P NO.1 TO P NO.8</option>
                <option value="P NO.8 TO P NO.8" {{ isset($certificate) && $certificate->base_metal_p_no == 'P NO.8 TO P NO.8' ? 'selected' : '' }}>P NO.8 TO P NO.8</option>
                <option value="P NO.43 TO P NO.43" {{ isset($certificate) && $certificate->base_metal_p_no == 'P NO.43 TO P NO.43' ? 'selected' : '' }}>P NO.43 TO P NO.43</option>
                <option value="__manual__">Manual Entry</option>
            </select>
            <input type="text" class="form-input" name="base_metal_p_no_manual"
                id="base_metal_p_no_manual" placeholder="Enter P-Number range"
                style="display: none; margin-top: 2px;">
        </td>
        <td class="var-range">
            <span id="p_number_range_span">{{ isset($certificate) ? $certificate->p_number_range : 'P-NO. 1 through P-NO. 15F, P-NO. 34, and P-NO. 41 through P-NO. 49' }}</span>
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
                onchange="toggleManualEntry('filler_spec')">
                <option value="5.1" {{ isset($certificate) && $certificate->filler_spec == '5.1' ? 'selected' : '' }}>5.1</option>
                <option value="A5.1" {{ isset($certificate) && $certificate->filler_spec == 'A5.1' ? 'selected' : '' }}>A5.1</option>
                <option value="A5.18" {{ isset($certificate) && $certificate->filler_spec == 'A5.18' ? 'selected' : '' }}>A5.18</option>
                <option value="A5.20" {{ isset($certificate) && $certificate->filler_spec == 'A5.20' ? 'selected' : '' }}>A5.20</option>
                <option value="__manual__">Manual Entry</option>
            </select>
            <input type="text" class="form-input" name="filler_spec_manual" id="filler_spec_manual"
                placeholder="Enter SFA specification" style="display: none; margin-top: 2px;">
        </td>
        <td class="var-range">
            <input type="text" class="form-input" name="filler_spec_range" id="filler_spec_range"
                placeholder="Enter qualified range" value="{{ isset($certificate) ? $certificate->filler_spec_range : '------' }}">
        </td>
    </tr>
    <tr>
        <td class="var-label">Filler metal or electrode classification(s) (info. only):</td>
        <td class="var-value">
            <select class="form-select" name="filler_class" id="filler_class"
                onchange="toggleManualEntry('filler_class')">
                <option value="E7018-1" {{ isset($certificate) && $certificate->filler_class == 'E7018-1' ? 'selected' : '' }}>E7018-1</option>
                <option value="E7018" {{ isset($certificate) && $certificate->filler_class == 'E7018' ? 'selected' : '' }}>E7018</option>
                <option value="E6010" {{ isset($certificate) && $certificate->filler_class == 'E6010' ? 'selected' : '' }}>E6010</option>
                <option value="E6013" {{ isset($certificate) && $certificate->filler_class == 'E6013' ? 'selected' : '' }}>E6013</option>
                <option value="ER70S-2" {{ isset($certificate) && $certificate->filler_class == 'ER70S-2' ? 'selected' : '' }}>ER70S-2</option>
                <option value="ER70S-6" {{ isset($certificate) && $certificate->filler_class == 'ER70S-6' ? 'selected' : '' }}>ER70S-6</option>
                <option value="__manual__">Manual Entry</option>
            </select>
            <input type="text" class="form-input" name="filler_class_manual" id="filler_class_manual"
                placeholder="Enter classification" style="display: none; margin-top: 2px;">
        </td>
        <td class="var-range">
            <input type="text" class="form-input" name="filler_class_range" id="filler_class_range"
                placeholder="Enter qualified range" value="{{ isset($certificate) ? $certificate->filler_class_range : '------' }}">
        </td>
    </tr>
    <tr>
        <td class="var-label">Filler metal F-Number(s):</td>
        <td class="var-value">
            <select class="form-select" name="filler_f_no" id="filler_f_no"
                onchange="updateFNumberRange()">
                <option value="F-No.6" {{ isset($certificate) && $certificate->filler_f_no == 'F-No.6' ? 'selected' : '' }}>F-No. 6</option>
                <option value="__manual__">Manual Entry</option>
            </select>
            <input type="text" class="form-input" name="filler_f_no_manual" id="filler_f_no_manual"
                placeholder="Enter F-Number" style="display: none; margin-top: 2px;">
        </td>
        <td class="var-range">
            <span id="f_number_range_span">{{ isset($certificate) ? $certificate->f_number_range : 'All F-No. 6' }}</span>
            <input type="text" class="form-input" name="f_number_range_manual"
                id="f_number_range_manual" placeholder="Enter qualified range"
                style="display: none; margin-top: 2px;">
        </td>
    </tr>
    <tr>
        <td class="var-label">Consumable insert (GTAW):</td>
        <td class="var-value">
            <input type="text" class="form-input" name="consumable_insert" value="Not Applicable" readonly style="background-color: #f0f0f0;">
        </td>
        <td class="var-range">
            <input type="text" class="form-input" name="consumable_insert_range" value="Not Applicable" readonly style="background-color: #f0f0f0;">
        </td>
    </tr>
    <tr>
        <td class="var-label">Filler Metal Product Form (QW-404.23) (GTAW):</td>
        <td class="var-value">
            <input type="text" class="form-input" name="filler_product_form" placeholder="------" value="{{ isset($certificate) ? $certificate->filler_product_form : '' }}">
        </td>
        <td class="var-range">
            <input type="text" class="form-input" name="filler_product_form_range"
                placeholder="------" value="{{ isset($certificate) ? $certificate->filler_product_form_range : '' }}">
        </td>
    </tr>
    <tr>
        <td class="var-label">Deposit thickness for each process:</td>
        <td class="var-value">
            <span>GTAW (</span>
            <input type="text" class="form-input" name="deposit_thickness" id="deposit_thickness"
                placeholder="Enter thickness" value="{{ isset($certificate) ? $certificate->deposit_thickness : '' }}" 
                style="width: 80px; display: inline;" onchange="updateDepositThicknessRange()">
            <span> mm)</span>
        </td>
        <td class="var-range">
            <span>GTAW (</span>
            <div id="deposit_thickness_range_container" class="thickness-range-container" style="width: 120px; display: inline-block;">
                <input type="text" class="form-input thickness-range-field" name="deposit_thickness_range" id="deposit_thickness_range"
                    value="{{ isset($certificate) ? $certificate->deposit_thickness_range : 'Max. to be welded' }}" 
                    disabled>
                <input type="hidden" name="deposit_thickness_range_hidden" id="deposit_thickness_range_hidden" 
                    value="{{ isset($certificate) ? $certificate->deposit_thickness_range : 'Max. to be welded' }}">
                <span id="deposit_thickness_range_display" class="thickness-range-display">
                    {{ isset($certificate) ? $certificate->deposit_thickness_range : 'Max. to be welded' }}
                </span>
            </div>
            <span> mm)</span>
        </td>
    </tr>
    <tr>
        <td class="var-label">
            Process 1 __ 3 layers minimum
            <div class="checkbox-container" style="display: inline-block; margin-left: 10px;">
                <input type="radio" name="gtaw_process" id="gtaw_yes" value="yes" {{ !isset($certificate) || $certificate->gtaw_process ? 'checked' : '' }}>
                <label for="gtaw_yes">YES</label>
                <input type="radio" name="gtaw_process" id="gtaw_no" value="no" {{ isset($certificate) && !$certificate->gtaw_process ? 'checked' : '' }}>
                <label for="gtaw_no">NO</label>
            </div>
        </td>
        <td class="var-value">
            <span>GTAW (</span>
            <input type="text" class="form-input" name="gtaw_thickness" id="gtaw_thickness" 
                placeholder="Enter thickness" value="{{ isset($certificate) ? $certificate->gtaw_thickness : '5' }}" required
                onchange="calculateThicknessRange(this.value)" 
                oninput="this.value = this.value.replace(/[^0-9.]/g, ''); calculateThicknessRange(this.value)"
                style="width: 80px; display: inline;">
            <span> mm)</span>
        </td>
        <td class="var-range">
            <span>GTAW (</span>
            <div id="gtaw_thickness_range_container" class="thickness-range-container" style="width: 120px; display: inline-block;">
                <input type="text" class="form-input thickness-range-field" name="gtaw_thickness_range" id="gtaw_thickness_range"
                    placeholder="Max. to be welded" disabled value="{{ isset($certificate) ? $certificate->gtaw_thickness_range : '' }}">
                <input type="hidden" name="gtaw_thickness_range_hidden" id="gtaw_thickness_range_hidden" 
                    value="{{ isset($certificate) ? $certificate->gtaw_thickness_range : '' }}">
                <span id="gtaw_thickness_range_display" class="thickness-range-display">
                    {{ isset($certificate) ? $certificate->gtaw_thickness_range : '' }}
                </span>
            </div>
            <span>)</span>
        </td>
    </tr>
    <tr>
        <td class="var-label">
            Process 2 __ 3 layers minimum
            <div class="checkbox-container" style="display: inline-block; margin-left: 10px;">
                <input type="radio" name="process2" id="process2_yes" value="yes" disabled>
                <label for="process2_yes">YES</label>
                <input type="radio" name="process2" id="process2_no" value="no" checked disabled>
                <label for="process2_no">NO</label>
            </div>
        </td>
        <td class="var-value">
            <span>N/A</span>
        </td>
        <td class="var-range">
            <span>N/A</span>
        </td>
    </tr>
</table>

<script>
// Calculate thickness range based on actual thickness
function calculateThicknessRange(thickness) {
    // Debug input value
    console.log('calculateThicknessRange input:', thickness);
    
    // Handle case when an HTML element is passed instead of a value
    let thicknessValue;
    
    // Check if thickness is an HTML element
    if (thickness && typeof thickness === 'object' && thickness.value !== undefined) {
        thicknessValue = parseFloat(thickness.value);
        console.log('Input is an object with value:', thickness.value);
    } else if (thickness && String(thickness).includes('[object HTMLInputElement]')) {
        // Handle case where object was converted to string
        const thicknessField = document.getElementById('gtaw_thickness');
        thicknessValue = thicknessField ? parseFloat(thicknessField.value) : NaN;
        console.log('Input is an HTML element as string, found value:', thicknessField ? thicknessField.value : 'not found');
    } else {
        thicknessValue = parseFloat(thickness);
        console.log('Input is a primitive value, parsed as:', thicknessValue);
    }
    
    let rangeValue = '';
    
    if (!isNaN(thicknessValue)) {
        console.log('Valid thickness value:', thicknessValue);
        if (thicknessValue <= 12) {
            // If thickness is 0-12mm, show range from thickness to 2x thickness
            rangeValue = thicknessValue + ' mm to ' + (thicknessValue * 2).toFixed(2) + ' mm';
            console.log('Thickness <= 12mm, range calculated as:', rangeValue);
        } else {
            // If thickness is 13mm or greater, use "Maximum to be welded"
            rangeValue = 'Max. to be welded';
            console.log('Thickness > 12mm, using default:', rangeValue);
        }
    } else {
        console.warn('Invalid thickness value (NaN)');
        rangeValue = 'Max. to be welded';
    }

    // Get the container element
    const container = document.getElementById('gtaw_thickness_range_container');
    if (!container) {
        console.error('gtaw_thickness_range_container element not found');
        return;
    }
    
    // Completely replace the HTML in the container with our new elements
    container.innerHTML = `
        <input type="text" class="form-input thickness-range-field" name="gtaw_thickness_range" id="gtaw_thickness_range"
            placeholder="Max. to be welded" disabled value="${rangeValue}">
        <input type="hidden" name="gtaw_thickness_range_hidden" id="gtaw_thickness_range_hidden" 
            value="${rangeValue}">
        <span id="gtaw_thickness_range_display" class="thickness-range-display">
            ${rangeValue}
        </span>
    `;
    
    console.log('GTAW thickness range container completely replaced with new HTML');
    
    // As an extra precaution, also update each individual element
    const thicknessRangeField = document.getElementById('gtaw_thickness_range');
    const hiddenField = document.getElementById('gtaw_thickness_range_hidden');
    const displaySpan = document.getElementById('gtaw_thickness_range_display');
    
    if (thicknessRangeField) {
        thicknessRangeField.value = rangeValue;
        thicknessRangeField.setAttribute('value', rangeValue);
        thicknessRangeField.disabled = true;
    }
    
    // Create or update a visual indicator for the range
    const indicatorId = 'gtaw_thickness_visual_indicator';
    let visualIndicator = document.getElementById(indicatorId);
    
    if (!visualIndicator) {
        visualIndicator = document.createElement('div');
        visualIndicator.id = indicatorId;
        visualIndicator.className = 'thickness-visual-indicator';
        visualIndicator.style.marginTop = '5px';
        visualIndicator.style.color = '#007bff';
        visualIndicator.style.fontWeight = 'bold';
        
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
    if (hiddenField) hiddenField.value = rangeValue;
    if (displaySpan) displaySpan.textContent = rangeValue;
    
    // Create hidden field for form submission
    ensureHiddenField('gtaw_thickness_range', rangeValue);
    
    console.log('GTAW thickness range updated to:', rangeValue);
}
}

// Function to create or update hidden field for disabled inputs
function ensureHiddenField(fieldId, value) {
    const originalField = document.getElementById(fieldId);
    if (!originalField || !originalField.disabled) return;
    
    const hiddenId = fieldId + '_hidden';
    let hiddenField = document.getElementById(hiddenId);
    
    if (!hiddenField) {
        hiddenField = document.createElement('input');
        hiddenField.type = 'hidden';
        hiddenField.id = hiddenId;
        hiddenField.name = originalField.name;
        originalField.parentNode.appendChild(hiddenField);
    }
    
    hiddenField.value = value;
}

// Calculate deposit thickness range
function updateDepositThicknessRange(inputElement) {
    // Get the deposit thickness value
    let depositThicknessElement = inputElement;
    if (!depositThicknessElement) {
        depositThicknessElement = document.getElementById('deposit_thickness');
    }
    
    // Handle if the parameter is already a value
    let depositThickness;
    if (typeof depositThicknessElement === 'string') {
        depositThickness = depositThicknessElement;
    } else if (depositThicknessElement && typeof depositThicknessElement === 'object') {
        // Check if it's an input element
        if (depositThicknessElement.value !== undefined) {
            depositThickness = depositThicknessElement.value;
        } else {
            // Fall back to getting the element by ID
            const element = document.getElementById('deposit_thickness');
            depositThickness = element ? element.value : '';
        }
    } else {
        const element = document.getElementById('deposit_thickness');
        depositThickness = element ? element.value : '';
    }
    
    // Check for "[object HTMLInputElement]" string
    if (String(depositThickness).includes('[object HTMLInputElement]')) {
        const element = document.getElementById('deposit_thickness');
        depositThickness = element ? element.value : '';
    }
    
    const thicknessValue = parseFloat(depositThickness);
    let rangeValue = '';
    
    if (!isNaN(thicknessValue)) {
        if (thicknessValue <= 12) {
            rangeValue = thicknessValue + ' mm to ' + (thicknessValue * 2).toFixed(2) + ' mm';
        } else {
            rangeValue = 'Max. to be welded';
        }
    } else {
        rangeValue = 'Max. to be welded';
    }
    
    // Get the container element
    const container = document.getElementById('deposit_thickness_range_container');
    if (!container) {
        console.error('deposit_thickness_range_container element not found');
        return;
    }
    
    // Completely replace the HTML in the container with our new elements
    container.innerHTML = `
        <input type="text" class="form-input thickness-range-field" name="deposit_thickness_range" id="deposit_thickness_range"
            placeholder="Max. to be welded" disabled value="${rangeValue}">
        <input type="hidden" name="deposit_thickness_range_hidden" id="deposit_thickness_range_hidden" 
            value="${rangeValue}">
        <span id="deposit_thickness_range_display" class="thickness-range-display">
            ${rangeValue}
        </span>
    `;
    
    console.log('Deposit thickness range container completely replaced with new HTML');
    
    // As an extra precaution, also update each individual element
    const depositRangeField = document.getElementById('deposit_thickness_range');
    const hiddenField = document.getElementById('deposit_thickness_range_hidden');
    const displaySpan = document.getElementById('deposit_thickness_range_display');
    
    if (depositRangeField) {
        depositRangeField.value = rangeValue;
        depositRangeField.setAttribute('value', rangeValue);
        depositRangeField.disabled = true;
    }
    if (hiddenField) hiddenField.value = rangeValue;
    if (displaySpan) displaySpan.textContent = rangeValue;
    
    // Create hidden field for form submission
    ensureHiddenField('deposit_thickness_range', rangeValue);
    
    console.log('Deposit thickness range updated to:', rangeValue);
}
}

// Initialize the thickness range calculation when the page loads
document.addEventListener('DOMContentLoaded', function() {
    // Ensure thickness range fields are properly initialized with the correct values
    const thicknessFields = [
        { input: 'gtaw_thickness', range: 'gtaw_thickness_range' },
        { input: 'deposit_thickness', range: 'deposit_thickness_range' }
    ];
    
    thicknessFields.forEach(field => {
        const inputField = document.getElementById(field.input);
        const rangeField = document.getElementById(field.range);
        
        if (inputField && rangeField) {
            // Clear any existing value that might be incorrect
            rangeField.disabled = false;
            rangeField.value = '';
            rangeField.disabled = true;
        }
    });
    
    // Calculate all thickness ranges
    const gtawThickness = document.getElementById('gtaw_thickness').value;
    calculateThicknessRange(gtawThickness);
    updateDepositThicknessRange();

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

    // Initialize F-Number range for GTAW
    const fNo = document.getElementById('filler_f_no').value;
    if (fNo === 'F-No.6') {
        document.getElementById('f_number_range_span').textContent = 'All F-No. 6';
    }
    
    // Add event listeners for thickness fields
    const gtawThicknessField = document.getElementById('gtaw_thickness');
    if (gtawThicknessField) {
        gtawThicknessField.addEventListener('input', function() {
            calculateThicknessRange(this.value);
        });
        gtawThicknessField.addEventListener('change', function() {
            calculateThicknessRange(this.value);
        });
    }
    
    const depositThicknessField = document.getElementById('deposit_thickness');
    if (depositThicknessField) {
        depositThicknessField.addEventListener('input', function() {
            updateDepositThicknessRange(this);
        });
        depositThicknessField.addEventListener('change', function() {
            updateDepositThicknessRange(this);
        });
        
        // Call it once to initialize
        updateDepositThicknessRange(depositThicknessField);
    }
    
    // Initialize thickness range on page load
    const thicknessField = document.getElementById('gtaw_thickness');
    if (thicknessField) {
        // Calculate thickness range based on initial value
        calculateThicknessRange(thicknessField.value);
        console.log('Initial thickness range calculation performed for value:', thicknessField.value);
    }
    
    // Run calculations again after a delay to ensure they take effect
    setTimeout(function() {
        calculateThicknessRange(gtawThickness);
        updateDepositThicknessRange();
    }, 300);
    
    // Run calculations once more after a longer delay to handle any race conditions
    setTimeout(function() {
        calculateThicknessRange(gtawThickness);
        updateDepositThicknessRange();
    }, 1000);
});
</script>