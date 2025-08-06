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
                <option value="FCAW" {{ old('welding_process', isset($certificate) ? $certificate->welding_process : '') == 'FCAW' ? 'selected' : '' }}>FCAW</option>
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
                <option value="semi-automatic" {{ old('welding_type', isset($certificate) ? $certificate->welding_type : '') == 'semi-automatic' ? 'selected' : '' }}>semi-automatic</option>
                <option value="Manual" {{ old('welding_type', isset($certificate) ? $certificate->welding_type : '') == 'Manual' ? 'selected' : '' }}>Manual</option>
            </select>
        </td>
        <td class="var-range">semi-automatic</td>
    </tr>
    <tr>
        <td class="var-label">Backing (with/without):</td>
        <td class="var-value">
            <select class="form-select" name="backing" id="backing" onchange="updateBackingRange(); toggleManualBackingEntry()">
                <option value="With Backing" {{ old('backing', isset($certificate) ? $certificate->backing : '') == 'With Backing' ? 'selected' : '' }}>With Backing</option>
                <option value="Without Backing" {{ old('backing', isset($certificate) ? $certificate->backing : '') == 'Without Backing' ? 'selected' : '' }}>Without Backing</option>
                <option value="__manual__" {{ old('backing', isset($certificate) ? $certificate->backing : '') == '__manual__' ? 'selected' : '' }}>Manual Entry</option>
            </select>
            <input type="text" class="form-input" name="backing_manual" id="backing_manual"
                placeholder="Enter custom backing type" style="display: none; margin-top: 2px;" 
                oninput="updateBackingRange()" value="{{ old('backing_manual', isset($certificate) ? $certificate->backing_manual : '') }}">
        </td>
        <td class="var-range">
            <span id="backing_range_span">{{ old('backing_range', isset($certificate) ? $certificate->backing_range : '') }}</span>
            <input type="hidden" name="backing_range" id="backing_range" value="{{ old('backing_range', isset($certificate) ? $certificate->backing_range : '') }}">
            <input type="text" class="form-input" name="backing_range_manual"
                id="backing_range_manual" placeholder="Enter qualified range"
                style="display: none; margin-top: 2px;" value="{{ old('backing_range_manual', isset($certificate) ? $certificate->backing_range_manual : '') }}">
        </td>
    </tr>
    <tr>
        <td class="var-label">
            <div class="checkbox-container">
                <input type="checkbox" name="plate_specimen" id="plate_specimen" {{ old('plate_specimen', isset($certificate) ? $certificate->plate_specimen : '') ? 'checked' : '' }} onchange="handleSpecimenToggle()">
                <label for="plate_specimen">Plate</label>
                <input type="checkbox" name="pipe_specimen" id="pipe_specimen" {{ old('pipe_specimen', isset($certificate) ? $certificate->pipe_specimen : '') ? 'checked' : '' }}
                    onchange="handleSpecimenToggle()">
                <label for="pipe_specimen">Pipe</label>
            </div>
            (enter diameter if pipe or tube)
        </td>
        <td class="var-value">
            <select class="form-select" name="pipe_diameter_type" id="pipe_diameter_type"
                onchange="updateDiameterRange()" disabled>
                <option value="8_nps" {{ old('pipe_diameter_type', isset($certificate) ? $certificate->pipe_diameter_type : '') == '8_nps' ? 'selected' : '' }}>8" NPS (Outside diameter 219.1 mm)</option>
                <option value="6_nps" {{ old('pipe_diameter_type', isset($certificate) ? $certificate->pipe_diameter_type : '') == '6_nps' ? 'selected' : '' }}>6" NPS (Outside diameter 168.3 mm)</option>
                <option value="4_nps" {{ old('pipe_diameter_type', isset($certificate) ? $certificate->pipe_diameter_type : '') == '4_nps' ? 'selected' : '' }}>4" NPS (Outside diameter 114.3 mm)</option>
                <option value="2_nps" {{ old('pipe_diameter_type', isset($certificate) ? $certificate->pipe_diameter_type : '') == '2_nps' ? 'selected' : '' }}>2" NPS (Outside diameter 60.3 mm)</option>
                <option value="1_nps" {{ old('pipe_diameter_type', isset($certificate) ? $certificate->pipe_diameter_type : '') == '1_nps' ? 'selected' : '' }}>1" NPS (Outside diameter 33.4 mm)</option>
                <option value="__manual__" {{ old('pipe_diameter_type', isset($certificate) ? $certificate->pipe_diameter_type : '') == '__manual__' ? 'selected' : '' }}>Manual Entry</option>
            </select>
            <input type="text" class="form-input" name="pipe_diameter_manual"
                id="pipe_diameter_manual" placeholder="Enter diameter (e.g., 10 inch NPS)"
                style="display: none; margin-top: 2px;" value="{{ old('pipe_diameter_manual', isset($certificate) ? $certificate->pipe_diameter_manual : '') }}">
        </td>
        <td class="var-range">
            <span id="diameter_range_span">{{ old('diameter_range', isset($certificate) ? $certificate->diameter_range : '') }}</span>
            <input type="hidden" name="diameter_range" id="diameter_range" value="{{ old('diameter_range', isset($certificate) ? $certificate->diameter_range : '') }}">
        </td>
    </tr>
    
    <tr>
        <td class="var-label">Base metal P-Number to P-Number:</td>
        <td class="var-value">
            <select class="form-select" name="base_metal_p_no" id="base_metal_p_no"
                onchange="updatePNumberRange()">
                <option value="P NO.1 TO P NO.1" {{ old('base_metal_p_no', isset($certificate) ? $certificate->base_metal_p_no : '') == 'P NO.1 TO P NO.1' ? 'selected' : '' }}>P NO.1 TO P NO.1</option>
                <option value="P NO.1 TO P NO.8" {{ old('base_metal_p_no', isset($certificate) ? $certificate->base_metal_p_no : '') == 'P NO.1 TO P NO.8' ? 'selected' : '' }}>P NO.1 TO P NO.8</option>
                <option value="P NO.8 TO P NO.8" {{ old('base_metal_p_no', isset($certificate) ? $certificate->base_metal_p_no : '') == 'P NO.8 TO P NO.8' ? 'selected' : '' }}>P NO.8 TO P NO.8</option>
                <option value="P NO.43 TO P NO.43" {{ old('base_metal_p_no', isset($certificate) ? $certificate->base_metal_p_no : '') == 'P NO.43 TO P NO.43' ? 'selected' : '' }}>P NO.43 TO P NO.43</option>
                <option value="__manual__" {{ old('base_metal_p_no', isset($certificate) ? $certificate->base_metal_p_no : '') == '__manual__' ? 'selected' : '' }}>Manual Entry</option>
            </select>
            <input type="text" class="form-input" name="base_metal_p_no_manual"
                id="base_metal_p_no_manual" placeholder="Enter P-Number range"
                style="display: none; margin-top: 2px;" value="{{ old('base_metal_p_no_manual', isset($certificate) ? $certificate->base_metal_p_no_manual : '') }}">
        </td>
        <td class="var-range">
            <span id="p_number_range_span">{{ old('p_number_range', isset($certificate) ? $certificate->p_number_range : '') }}</span>
             <input type="hidden" name="p_number_range" id="p_number_range" value="{{ old('p_number_range', isset($certificate) ? $certificate->p_number_range : '') }}">
            <input type="text" class="form-input" name="p_number_range_manual"
                id="p_number_range_manual" placeholder="Enter qualified range"
                style="display: none; margin-top: 2px;" value="{{ old('p_number_range_manual', isset($certificate) ? $certificate->p_number_range_manual : '') }}">
        </td>
    </tr>
    <!-- Remaining welding variables rows -->
    <tr>
        <td class="var-label">Filler metal or electrode specification(s) (SFA) (info. only):</td>
        <td class="var-value">
            <select class="form-select" name="filler_spec" id="filler_spec"
                onchange="toggleManualEntry('filler_spec'); updateFillerSpecRange()">
                <option value="5.1" {{ old('filler_spec', isset($certificate) ? $certificate->filler_spec : '') == '5.1' ? 'selected' : '' }}>5.1</option>
                <option value="A5.1" {{ old('filler_spec', isset($certificate) ? $certificate->filler_spec : '') == 'A5.1' ? 'selected' : '' }}>A5.1</option>
                <option value="A5.18" {{ old('filler_spec', isset($certificate) ? $certificate->filler_spec : '') == 'A5.18' ? 'selected' : '' }}>A5.18</option>
                <option value="A5.20" {{ old('filler_spec', isset($certificate) ? $certificate->filler_spec : '') == 'A5.20' ? 'selected' : '' }}>A5.20</option>
                <option value="__manual__" {{ old('filler_spec', isset($certificate) ? $certificate->filler_spec : '') == '__manual__' ? 'selected' : '' }}>Manual Entry</option>
            </select>
            <input type="text" class="form-input" name="filler_spec_manual" id="filler_spec_manual"
                placeholder="Enter SFA specification" style="display: none; margin-top: 2px;" value="{{ old('filler_spec_manual', isset($certificate) ? $certificate->filler_spec_manual : '') }}" oninput="updateFillerSpecRange()">
        </td>
        <td class="var-range">
            <input type="text" class="form-input" name="filler_spec_range" id="filler_spec_range"
                placeholder="Enter qualified range" value="{{ old('filler_spec_range', isset($certificate) ? $certificate->filler_spec_range : '') }}">
        </td>
    </tr>
    <tr>
        <td class="var-label">Filler metal or electrode classification(s) (info. only):</td>
        <td class="var-value">
            <select class="form-select" name="filler_class" id="filler_class"
                onchange="toggleManualEntry('filler_class'); updateFillerClassRange()">
                <option value="E7018-1" {{ old('filler_class', isset($certificate) ? $certificate->filler_class : '') == 'E7018-1' ? 'selected' : '' }}>E7018-1</option>
                <option value="E7018" {{ old('filler_class', isset($certificate) ? $certificate->filler_class : '') == 'E7018' ? 'selected' : '' }}>E7018</option>
                <option value="E6010" {{ old('filler_class', isset($certificate) ? $certificate->filler_class : '') == 'E6010' ? 'selected' : '' }}>E6010</option>
                <option value="E6013" {{ old('filler_class', isset($certificate) ? $certificate->filler_class : '') == 'E6013' ? 'selected' : '' }}>E6013</option>
                <option value="ER70S-2" {{ old('filler_class', isset($certificate) ? $certificate->filler_class : '') == 'ER70S-2' ? 'selected' : '' }}>ER70S-2</option>
                <option value="ER70S-6" {{ old('filler_class', isset($certificate) ? $certificate->filler_class : '') == 'ER70S-6' ? 'selected' : '' }}>ER70S-6</option>
                <option value="__manual__" {{ old('filler_class', isset($certificate) ? $certificate->filler_class : '') == '__manual__' ? 'selected' : '' }}>Manual Entry</option>
            </select>
            <input type="text" class="form-input" name="filler_class_manual" id="filler_class_manual"
                placeholder="Enter classification" style="display: none; margin-top: 2px;" value="{{ old('filler_class_manual', isset($certificate) ? $certificate->filler_class_manual : '') }}" oninput="updateFillerClassRange()">
        </td>
        <td class="var-range">
            <input type="text" class="form-input" name="filler_class_range" id="filler_class_range"
                placeholder="Enter qualified range" value="{{ old('filler_class_range', isset($certificate) ? $certificate->filler_class_range : '') }}">
        </td>
    </tr>
    <tr>
        <td class="var-label">Filler metal F-Number(s):</td>
        <td class="var-value">
            <select class="form-select" name="filler_f_no" id="filler_f_no"
                onchange="updateFNumberRange()">
                <option value="F-No.6" {{ old('filler_f_no', isset($certificate) ? $certificate->filler_f_no : '') == 'F-No.6' ? 'selected' : '' }}>F-No.6</option>
                <option value="__manual__" {{ old('filler_f_no', isset($certificate) ? $certificate->filler_f_no : '') == '__manual__' ? 'selected' : '' }}>Manual Entry</option>
            </select>
            <input type="text" class="form-input" name="filler_f_no_manual" id="filler_f_no_manual"
                placeholder="Enter F-Number" style="display: none; margin-top: 2px;" value="{{ old('filler_f_no_manual', isset($certificate) ? $certificate->filler_f_no_manual : '') }}">
        </td>
        <td class="var-range">
            <span id="f_number_range_span">{{ old('f_number_range', isset($certificate) ? $certificate->f_number_range : '') }}</span>
            <input type="hidden" name="f_number_range" id="f_number_range" value="{{ old('f_number_range', isset($certificate) ? $certificate->f_number_range : '') }}">
            <input type="text" class="form-input" name="f_number_range_manual"
                id="f_number_range_manual" placeholder="Enter qualified range"
                style="display: none; margin-top: 2px;" value="{{ old('f_number_range_manual', isset($certificate) ? $certificate->f_number_range_manual : '') }}">
        </td>
    </tr>
    <tr>
        <td class="var-label">Deposit thickness for each process:</td>
        <td class="var-value">
            <input type="text" class="form-input" name="deposit_thickness"
                placeholder="4mm &14.26 mm" value="{{ old('deposit_thickness', isset($certificate) ? $certificate->deposit_thickness : '') }}">
        </td>
        <td class="var-range">
            <input type="text" class="form-input" name="deposit_thickness_range"
                placeholder="------" value="{{ old('deposit_thickness_range', isset($certificate) ? $certificate->deposit_thickness_range : '') }}">
        </td>
    </tr>
    <tr>
        <td class="var-label">
            Process 1 __ 3 layers minimum
            <div class="checkbox-container" style="display: inline-block; margin-left: 10px;">
                <input type="radio" name="fcaw_process" id="fcaw_yes" value="yes" {{ old('fcaw_process', isset($certificate) ? $certificate->fcaw_process : '') == 'yes' ? 'checked' : '' }}>
                <label for="fcaw_yes">YES</label>
                <input type="radio" name="fcaw_process" id="fcaw_no" value="no" {{ old('fcaw_process', isset($certificate) ? $certificate->fcaw_process : '') == 'no' ? 'checked' : '' }}>
                <label for="fcaw_no">NO</label>
            </div>
        </td>
        <td class="var-value">
            <input type="text" class="form-input" name="fcaw_thickness" id="fcaw_thickness" 
                placeholder="Enter thickness (mm)" value="{{ old('fcaw_thickness', isset($certificate) ? $certificate->fcaw_thickness : '') }}" required
                onchange="calculateThicknessRange(this.value, 'fcaw')">
        </td>
        <td class="var-range">
            <input type="text" class="form-input" name="fcaw_thickness_range" id="fcaw_thickness_range"
                placeholder="Max. to be welded" value="{{ old('fcaw_thickness_range', isset($certificate) ? $certificate->fcaw_thickness_range : '') }}" readonly>
        </td>
    </tr>
</table>

<script>
// Function to handle specimen toggle logic
function handleSpecimenToggle() {
    const plateCheckbox = document.getElementById('plate_specimen');
    const pipeCheckbox = document.getElementById('pipe_specimen');
    const pipeDiameterSelect = document.getElementById('pipe_diameter_type');
    const diameterRangeSpan = document.getElementById('diameter_range_span');
    const diameterRangeHidden = document.getElementById('diameter_range');
    
    if (plateCheckbox.checked && pipeCheckbox.checked) {
        // Both are checked - disable pipe diameter but allow range
        pipeDiameterSelect.disabled = true;
        if (diameterRangeSpan) {
            diameterRangeSpan.textContent = 'Plate & Pipe';
        }
        if (diameterRangeHidden) {
            diameterRangeHidden.value = 'Plate & Pipe';
        }
    } else if (plateCheckbox.checked && !pipeCheckbox.checked) {
        // Only plate is checked - disable pipe diameter
        pipeDiameterSelect.disabled = true;
        if (diameterRangeSpan) {
            diameterRangeSpan.textContent = 'Plate';
        }
        if (diameterRangeHidden) {
            diameterRangeHidden.value = 'Plate';
        }
    } else if (!plateCheckbox.checked && pipeCheckbox.checked) {
        // Only pipe is checked - enable pipe diameter
        pipeDiameterSelect.disabled = false;
        updateDiameterRange();
    } else {
        // Neither is checked - disable pipe diameter and clear range
        pipeDiameterSelect.disabled = true;
        if (diameterRangeSpan) {
            diameterRangeSpan.textContent = '';
        }
        if (diameterRangeHidden) {
            diameterRangeHidden.value = '';
        }
    }
}

// Function to toggle manual entry for filler fields
function toggleManualEntry(fieldName) {
    const selectField = document.getElementById(fieldName);
    const manualField = document.getElementById(fieldName + '_manual');
    
    if (selectField && manualField) {
        if (selectField.value === '__manual__') {
            manualField.style.display = 'block';
            manualField.required = true;
        } else {
            manualField.style.display = 'none';
            manualField.required = false;
        }
    }
}

// Function to update filler spec range
function updateFillerSpecRange() {
    const fillerSpec = document.getElementById('filler_spec');
    const fillerSpecManual = document.getElementById('filler_spec_manual');
    const fillerSpecRange = document.getElementById('filler_spec_range');
    
    if (fillerSpec && fillerSpecRange) {
        if (fillerSpec.value === '__manual__' && fillerSpecManual) {
            fillerSpecRange.value = fillerSpecManual.value;
        } else {
            fillerSpecRange.value = fillerSpec.value;
        }
    }
}

// Function to update filler class range
function updateFillerClassRange() {
    const fillerClass = document.getElementById('filler_class');
    const fillerClassManual = document.getElementById('filler_class_manual');
    const fillerClassRange = document.getElementById('filler_class_range');
    
    if (fillerClass && fillerClassRange) {
        if (fillerClass.value === '__manual__' && fillerClassManual) {
            fillerClassRange.value = fillerClassManual.value;
        } else {
            fillerClassRange.value = fillerClass.value;
        }
    }
}

// Calculate thickness range based on actual thickness
function calculateThicknessRange(thickness, processType) {
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
    
    document.getElementById(processType + '_thickness_range').value = rangeValue;
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    // Initialize specimen toggle
    handleSpecimenToggle();
    
    // Initialize manual entry fields
    toggleManualEntry('filler_spec');
    toggleManualEntry('filler_class');
    toggleManualEntry('filler_f_no');
    
    // Initialize thickness ranges
    const fcawThickness = document.getElementById('fcaw_thickness');
    if (fcawThickness && fcawThickness.value) {
        calculateThicknessRange(fcawThickness.value, 'fcaw');
    }
    
    // Initialize filler ranges
    updateFillerSpecRange();
    updateFillerClassRange();
});
</script>