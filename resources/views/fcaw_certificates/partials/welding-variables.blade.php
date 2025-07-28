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
                <option value="FCAW" {{ old('welding_process', $certificate->welding_process) == 'FCAW' ? 'selected' : '' }}>FCAW</option>
            </select>
        </td>
        <td class="var-range">
            <span id="process_range_span">FCAW or GMAW</span>
        </td>
    </tr>
    <tr>
        <td class="var-label">Type (i.e., manual, semi-automatic) used:</td>
        <td class="var-value">
            <select class="form-select" name="welding_type">
                <option value="semi-automatic" {{ old('welding_type', $certificate->welding_type) == 'semi-automatic' ? 'selected' : '' }}>semi-automatic</option>
                <option value="Manual" {{ old('welding_type', $certificate->welding_type) == 'Manual' ? 'selected' : '' }}>Manual</option>
            </select>
        </td>
        <td class="var-range">semi-automatic</td>
    </tr>
    <tr>
        <td class="var-label">Backing (with/without):</td>
        <td class="var-value">
            <select class="form-select" name="backing" id="backing" onchange="updateBackingRange(); toggleManualBackingEntry()">
                <option value="With Backing" {{ old('backing', $certificate->backing) == 'With Backing' ? 'selected' : '' }}>With Backing</option>
                <option value="Without Backing" {{ old('backing', $certificate->backing) == 'Without Backing' ? 'selected' : '' }}>Without Backing</option>
                <option value="__manual__" {{ old('backing', $certificate->backing) == '__manual__' ? 'selected' : '' }}>Manual Entry</option>
            </select>
            <input type="text" class="form-input" name="backing_manual" id="backing_manual"
                placeholder="Enter custom backing type" style="display: none; margin-top: 2px;" 
                oninput="updateBackingRange()" value="{{ old('backing_manual', $certificate->backing_manual) }}">
        </td>
        <td class="var-range">
            <span id="backing_range_span">{{ old('backing_range', $certificate->backing_range) }}</span>
            <input type="hidden" name="backing_range" id="backing_range" value="{{ old('backing_range', $certificate->backing_range) }}">
            <input type="text" class="form-input" name="backing_range_manual"
                id="backing_range_manual" placeholder="Enter qualified range"
                style="display: none; margin-top: 2px;" value="{{ old('backing_range_manual', $certificate->backing_range_manual) }}">
        </td>
    </tr>
    <tr>
        <td class="var-label">
            <div class="checkbox-container">
                <input type="checkbox" name="plate_specimen" id="plate_specimen" {{ old('plate_specimen', $certificate->plate_specimen) ? 'checked' : '' }} onchange="toggleDiameterField()">
                <label for="plate_specimen">Plate</label>
                <input type="checkbox" name="pipe_specimen" id="pipe_specimen" {{ old('pipe_specimen', $certificate->pipe_specimen) ? 'checked' : '' }}
                    onchange="toggleDiameterField(); updateDiameterRange()">
                <label for="pipe_specimen">Pipe</label>
            </div>
            (enter diameter if pipe or tube)
        </td>
        <td class="var-value">
            <select class="form-select" name="pipe_diameter_type" id="pipe_diameter_type"
                onchange="updateDiameterRange()">
                <option value="8_nps" {{ old('pipe_diameter_type', $certificate->pipe_diameter_type) == '8_nps' ? 'selected' : '' }}>8" NPS (Outside diameter 219.1 mm)</option>
                <option value="6_nps" {{ old('pipe_diameter_type', $certificate->pipe_diameter_type) == '6_nps' ? 'selected' : '' }}>6" NPS (Outside diameter 168.3 mm)</option>
                <option value="4_nps" {{ old('pipe_diameter_type', $certificate->pipe_diameter_type) == '4_nps' ? 'selected' : '' }}>4" NPS (Outside diameter 114.3 mm)</option>
                <option value="2_nps" {{ old('pipe_diameter_type', $certificate->pipe_diameter_type) == '2_nps' ? 'selected' : '' }}>2" NPS (Outside diameter 60.3 mm)</option>
                <option value="1_nps" {{ old('pipe_diameter_type', $certificate->pipe_diameter_type) == '1_nps' ? 'selected' : '' }}>1" NPS (Outside diameter 33.4 mm)</option>
                <option value="__manual__" {{ old('pipe_diameter_type', $certificate->pipe_diameter_type) == '__manual__' ? 'selected' : '' }}>Manual Entry</option>
            </select>
            <input type="text" class="form-input" name="pipe_diameter_manual"
                id="pipe_diameter_manual" placeholder="Enter diameter (e.g., 10 inch NPS)"
                style="display: none; margin-top: 2px;" value="{{ old('pipe_diameter_manual', $certificate->pipe_diameter_manual) }}">
        </td>
        <td class="var-range">
            <span id="diameter_range_span">{{ old('diameter_range', $certificate->diameter_range) }}</span>
            <input type="hidden" name="diameter_range" id="diameter_range" value="{{ old('diameter_range', $certificate->diameter_range) }}">
            <input type="text" class="form-input" name="diameter_range_manual"
                id="diameter_range_manual" placeholder="Enter qualified range"
                style="display: none; margin-top: 2px;" value="{{ old('diameter_range_manual', $certificate->diameter_range_manual) }}">
        </td>
    </tr>
    
    <tr>
        <td class="var-label">Base metal P-Number to P-Number:</td>
        <td class="var-value">
            <select class="form-select" name="base_metal_p_no" id="base_metal_p_no"
                onchange="updatePNumberRange()">
                <option value="P NO.1 TO P NO.1" {{ old('base_metal_p_no', $certificate->base_metal_p_no) == 'P NO.1 TO P NO.1' ? 'selected' : '' }}>P NO.1 TO P NO.1</option>
                <option value="P NO.1 TO P NO.8" {{ old('base_metal_p_no', $certificate->base_metal_p_no) == 'P NO.1 TO P NO.8' ? 'selected' : '' }}>P NO.1 TO P NO.8</option>
                <option value="P NO.8 TO P NO.8" {{ old('base_metal_p_no', $certificate->base_metal_p_no) == 'P NO.8 TO P NO.8' ? 'selected' : '' }}>P NO.8 TO P NO.8</option>
                <option value="P NO.43 TO P NO.43" {{ old('base_metal_p_no', $certificate->base_metal_p_no) == 'P NO.43 TO P NO.43' ? 'selected' : '' }}>P NO.43 TO P NO.43</option>
                <option value="__manual__" {{ old('base_metal_p_no', $certificate->base_metal_p_no) == '__manual__' ? 'selected' : '' }}>Manual Entry</option>
            </select>
            <input type="text" class="form-input" name="base_metal_p_no_manual"
                id="base_metal_p_no_manual" placeholder="Enter P-Number range"
                style="display: none; margin-top: 2px;" value="{{ old('base_metal_p_no_manual', $certificate->base_metal_p_no_manual) }}">
        </td>
        <td class="var-range">
            <span id="p_number_range_span">{{ old('p_number_range', $certificate->p_number_range) }}</span>
             <input type="text" class="form-input" name="p_number_range"
                id="p_number_range" placeholder="Enter qualified range"
                style="display: none; margin-top: 2px;" value="{{ old('p_number_range', $certificate->p_number_range) }}">
            <input type="text" class="form-input" name="p_number_range_manual"
                id="p_number_range_manual" placeholder="Enter qualified range"
                style="display: none; margin-top: 2px;" value="{{ old('p_number_range_manual', $certificate->p_number_range_manual) }}">
        </td>
    </tr>
    <!-- Remaining welding variables rows -->
    <tr>
        <td class="var-label">Filler metal or electrode specification(s) (SFA) (info. only):</td>
        <td class="var-value">
            <select class="form-select" name="filler_spec" id="filler_spec"
                onchange="toggleManualEntry('filler_spec')">
                <option value="5.1" {{ old('filler_spec', $certificate->filler_spec) == '5.1' ? 'selected' : '' }}>5.1</option>
                <option value="A5.1" {{ old('filler_spec', $certificate->filler_spec) == 'A5.1' ? 'selected' : '' }}>A5.1</option>
                <option value="A5.18" {{ old('filler_spec', $certificate->filler_spec) == 'A5.18' ? 'selected' : '' }}>A5.18</option>
                <option value="A5.20" {{ old('filler_spec', $certificate->filler_spec) == 'A5.20' ? 'selected' : '' }}>A5.20</option>
                <option value="__manual__" {{ old('filler_spec', $certificate->filler_spec) == '__manual__' ? 'selected' : '' }}>Manual Entry</option>
            </select>
            <input type="text" class="form-input" name="filler_spec_manual" id="filler_spec_manual"
                placeholder="Enter SFA specification" style="display: none; margin-top: 2px;" value="{{ old('filler_spec_manual', $certificate->filler_spec_manual) }}">
        </td>
        <td class="var-range">
            <input type="text" class="form-input" name="filler_spec_range" id="filler_spec_range"
                placeholder="Enter qualified range" value="{{ old('filler_spec_range', $certificate->filler_spec_range) }}">
        </td>
    </tr>
    <tr>
        <td class="var-label">Filler metal or electrode classification(s) (info. only):</td>
        <td class="var-value">
            <select class="form-select" name="filler_class" id="filler_class"
                onchange="toggleManualEntry('filler_class')">
                <option value="E7018-1" {{ old('filler_class', $certificate->filler_class) == 'E7018-1' ? 'selected' : '' }}>E7018-1</option>
                <option value="E7018" {{ old('filler_class', $certificate->filler_class) == 'E7018' ? 'selected' : '' }}>E7018</option>
                <option value="E6010" {{ old('filler_class', $certificate->filler_class) == 'E6010' ? 'selected' : '' }}>E6010</option>
                <option value="E6013" {{ old('filler_class', $certificate->filler_class) == 'E6013' ? 'selected' : '' }}>E6013</option>
                <option value="ER70S-2" {{ old('filler_class', $certificate->filler_class) == 'ER70S-2' ? 'selected' : '' }}>ER70S-2</option>
                <option value="ER70S-6" {{ old('filler_class', $certificate->filler_class) == 'ER70S-6' ? 'selected' : '' }}>ER70S-6</option>
                <option value="__manual__" {{ old('filler_class', $certificate->filler_class) == '__manual__' ? 'selected' : '' }}>Manual Entry</option>
            </select>
            <input type="text" class="form-input" name="filler_class_manual" id="filler_class_manual"
                placeholder="Enter classification" style="display: none; margin-top: 2px;" value="{{ old('filler_class_manual', $certificate->filler_class_manual) }}">
        </td>
        <td class="var-range">
            <input type="text" class="form-input" name="filler_class_range" id="filler_class_range"
                placeholder="Enter qualified range" value="{{ old('filler_class_range', $certificate->filler_class_range) }}">
        </td>
    </tr>
    <tr>
        <td class="var-label">Filler metal F-Number(s):</td>
        <td class="var-value">
            <select class="form-select" name="filler_f_no" id="filler_f_no"
                onchange="updateFNumberRange()">
                <option value="F-No.6" {{ old('filler_f_no', $certificate->filler_f_no) == 'F-No.6' ? 'selected' : '' }}>F-No.6</option>
              
                <option value="__manual__" {{ old('filler_f_no', $certificate->filler_f_no) == '__manual__' ? 'selected' : '' }}>Manual Entry</option>
            </select>
            <input type="text" class="form-input" name="filler_f_no_manual" id="filler_f_no_manual"
                placeholder="Enter F-Number" style="display: none; margin-top: 2px;" value="{{ old('filler_f_no_manual', $certificate->filler_f_no_manual) }}">
        </td>
        <td class="var-range">
            <span id="f_number_range_span">{{ old('f_number_range', $certificate->f_number_range) }}</span>
            <input type="text" class="form-input" name="f_number_range_manual"
                id="f_number_range_manual" placeholder="Enter qualified range"
                style="display: none; margin-top: 2px;" value="{{ old('f_number_range_manual', $certificate->f_number_range_manual) }}">
        </td>
    </tr>
    <tr>
        <td class="var-label">Consumable insert (GTAW, PAW, LBW):</td>
        <td class="var-value">
            <input type="text" class="form-input" name="consumable_insert" placeholder="------" value="{{ old('consumable_insert', $certificate->consumable_insert) }}">
        </td>
        <td class="var-range">
            <input type="text" class="form-input" name="consumable_insert_range"
                placeholder="------" value="{{ old('consumable_insert_range', $certificate->consumable_insert_range) }}">
        </td>
    </tr>
    <tr>
        <td class="var-label">Filler Metal Product Form (QW-404.23) (GTAW or PAW):</td>
        <td class="var-value">
            <input type="text" class="form-input" name="filler_product_form" placeholder="------" value="{{ old('filler_product_form', $certificate->filler_product_form) }}">
        </td>
        <td class="var-range">
            <input type="text" class="form-input" name="filler_product_form_range"
                placeholder="------" value="{{ old('filler_product_form_range', $certificate->filler_product_form_range) }}">
        </td>
    </tr>
    <tr>
        <td class="var-label">Deposit thickness for each process:</td>
        <td class="var-value">
            <input type="text" class="form-input" name="deposit_thickness"
                placeholder="4mm &14.26 mm" value="{{ old('deposit_thickness', $certificate->deposit_thickness) }}">
        </td>
        <td class="var-range">
            <input type="text" class="form-input" name="deposit_thickness_range"
                placeholder="------" value="{{ old('deposit_thickness_range', $certificate->deposit_thickness_range) }}">
        </td>
    </tr>
    <tr>
        <td class="var-label">
    Process 1 __ 3 layers minimum
            <div class="checkbox-container" style="display: inline-block; margin-left: 10px;">
                <input type="radio" name="smaw_process" id="smaw_yes" value="yes" {{ old('smaw_process', $certificate->smaw_process) == 'yes' ? 'checked' : '' }}>
                <label for="smaw_yes">YES</label>
                <input type="radio" name="smaw_process" id="smaw_no" value="no" {{ old('smaw_process', $certificate->smaw_process) == 'no' ? 'checked' : '' }}>
                <label for="smaw_no">NO</label>
            </div>
        </td>
        <td class="var-value">
            <input type="text" class="form-input" name="smaw_thickness" id="smaw_thickness" 
                placeholder="Enter thickness (mm)" value="{{ old('smaw_thickness', $certificate->smaw_thickness) }}" required
                onchange="calculateThicknessRange(this.value)">
        </td>
        <td class="var-range">
            <input type="text" class="form-input" name="smaw_thickness_range" id="smaw_thickness_range"
                placeholder="Max. to be welded" value="{{ old('smaw_thickness_range', $certificate->smaw_thickness_range) }}" readonly>
        </td>
    </tr>
    
    <!-- Process 2 layers minimum -->
    <tr>
        <td class="var-label">
    Process 2 __ 3 layers minimum
            <div class="checkbox-container" style="display: inline-block; margin-left: 10px;">
                <input type="radio" name="process2" id="process2_yes" value="yes" {{ old('process2', $certificate->process2) == 'yes' ? 'checked' : '' }}>
                <label for="process2_yes">YES</label>
                <input type="radio" name="process2" id="process2_no" value="no" {{ old('process2', $certificate->process2) == 'no' ? 'checked' : '' }}>
                <label for="process2_no">NO</label>
            </div>
        </td>
        <td class="var-value">
            <input type="text" class="form-input" name="process2_thickness" id="process2_thickness" 
                placeholder="Enter thickness (mm)" value="{{ old('process2_thickness', $certificate->process2_thickness) }}">
        </td>
        <td class="var-range">
            <input type="text" class="form-input" name="process2_thickness_range" id="process2_thickness_range"
                placeholder="------" value="{{ old('process2_thickness_range', $certificate->process2_thickness_range) }}" readonly>
        </td>
    </tr>
    
   
    
  
</table>




<script>
// Function to update backing range value based on selected backing option
function updateBackingRange() {
    const backing = document.getElementById('backing').value;
    let backingRangeText = '';
    
    if (backing === 'With Backing') {
        backingRangeText = 'With backing';
    } else if (backing === 'Without Backing') {
        backingRangeText = 'With or Without backing';
    } else if (backing === '__manual__') {
        const manualValue = document.getElementById('backing_manual').value;
        backingRangeText = manualValue || 'With backing';
    }
    
    // Update the span text and hidden input value
    const backingRangeSpan = document.getElementById('backing_range_span');
    const backingRangeInput = document.getElementById('backing_range');
    
    if (backingRangeSpan) {
        backingRangeSpan.textContent = backingRangeText;
    }
    
    if (backingRangeInput) {
        backingRangeInput.value = backingRangeText;
    }
}

// Calculate thickness range based on actual thickness
function calculateThicknessRange(thickness) {
    const thicknessValue = parseFloat(thickness);
    let rangeValue = '';
    
    if (!isNaN(thicknessValue)) {
        if (thicknessValue <= 12) {
            // If thickness is 0-12, multiply by 2
            rangeValue = (thicknessValue * 2).toFixed(2) + ' mm';
        } else {
            // If thickness is 13 or greater, use "Maximum to be welded"
            rangeValue = 'Maximum to be welded';
        }
    }
    
    document.getElementById('smaw_thickness_range').value = rangeValue;
}

// Toggle manual entry field for backing
function toggleManualBackingEntry() {
    const backingSelect = document.getElementById('backing');
    const backingManualInput = document.getElementById('backing_manual');
    
    if (backingSelect && backingManualInput) {
        if (backingSelect.value === '__manual__') {
            backingManualInput.style.display = 'block';
        } else {
            backingManualInput.style.display = 'none';
        }
    }
}

// Initialize the thickness range calculation when the page loads
document.addEventListener('DOMContentLoaded', function() {
    const smawThickness = document.getElementById('smaw_thickness').value;
    calculateThicknessRange(smawThickness);
    
    // Initialize form values and ranges
    
    // Initialize plate/pipe specimen fields
    const plateCheckbox = document.getElementById('plate_specimen');
    const pipeCheckbox = document.getElementById('pipe_specimen');
    
    if (plateCheckbox && plateCheckbox.checked) {
        const diameterRangeSpan = document.getElementById('diameter_range_span');
        if (diameterRangeSpan) {
            diameterRangeSpan.textContent = 'Plate & Pipe';
        }
        
        const diameterRangeHidden = document.getElementById('diameter_range');
        if (diameterRangeHidden) {
            diameterRangeHidden.value = 'Plate & Pipe';
        }
    }
    
    // Initialize positions field
    const positions = document.getElementById('positions');
    if (positions) {
        positions.addEventListener('change', function() {
            if (this.value === '__manual__') {
                document.getElementById('positions_manual').style.display = 'block';
            } else {
                document.getElementById('positions_manual').style.display = 'none';
            }
        });
    }
    
    // Initialize backing fields - call the updateBackingRange function
    updateBackingRange();
    toggleManualBackingEntry();
    
    // Initialize backing gas fields
    const backingGasRangeElement = document.getElementById('backing_gas_range_span');
    if (backingGasRangeElement) {
        backingGasRangeElement.textContent = 'With or Without backing Gas';
        
        // Also update the hidden field
        const backingGasRangeHidden = document.getElementById('backing_gas_range');
        if (backingGasRangeHidden) {
            backingGasRangeHidden.value = 'With or Without backing Gas';
        }
    }
    
    // Initialize vertical progression
    const verticalProgression = document.getElementById('vertical_progression').value;
    const verticalProgressionSpan = document.getElementById('vertical_progression_range_span');
    if (verticalProgressionSpan) {
        verticalProgressionSpan.textContent = 'Upward';
        
        // Also update the hidden field
        const verticalProgressionHidden = document.getElementById('vertical_progression_range');
        if (verticalProgressionHidden) {
            verticalProgressionHidden.value = 'Upward';
        }
    }
    
    // Initialize transfer mode
    const transferMode = document.getElementById('transfer_mode').value;
    const transferModeSpan = document.getElementById('transfer_mode_range_span');
    if (transferModeSpan) {
        transferModeSpan.textContent = 'spray, globular, or pulsed Spray';
        
        // Also update the hidden field
        const transferModeHidden = document.getElementById('transfer_mode_range');
        if (transferModeHidden) {
            transferModeHidden.value = 'spray, globular, or pulsed Spray';
        }
    }
    
    // Ensure range fields are properly initialized
    setTimeout(function() {
        // Initialize all range fields
        updatePNumberRange();
        updateFNumberRange();
        updatePositionRange();
        updateBackingRange();
        updateDiameterRange();
        toggleDiameterField();
        
        // Initialize new fields
        if (typeof updateBackingGasRange === 'function') updateBackingGasRange();
        if (typeof updateVerticalProgressionRange === 'function') updateVerticalProgressionRange();
        if (typeof updateTransferModeRange === 'function') updateTransferModeRange();
        if (typeof updateEquipmentTypeRange === 'function') updateEquipmentTypeRange();
        if (typeof updateTechniqueRange === 'function') updateTechniqueRange();
        if (typeof updateOscillationRange === 'function') updateOscillationRange();
        if (typeof updateOperationModeRange === 'function') updateOperationModeRange();
    }, 500);

    // Function to update oscillation range
    function updateOscillationRange() {
        const oscillationYes = document.getElementById('oscillation_yes');
        const oscillationNo = document.getElementById('oscillation_no');
        const oscillationRangeSpan = document.getElementById('oscillation_range_span');
        const oscillationRange = document.getElementById('oscillation_range');
        
        if (oscillationYes && oscillationNo && oscillationRangeSpan && oscillationRange) {
            if (oscillationYes.checked) {
                oscillationRangeSpan.textContent = 'YES';
                oscillationRange.value = 'YES';
            } else {
                oscillationRangeSpan.textContent = '.....';
                oscillationRange.value = '.....';
            }
        }
    }
    
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
});
</script>
