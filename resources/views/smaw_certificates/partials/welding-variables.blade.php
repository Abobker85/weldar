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
            <select class="form-select" id="welding_process_display" disabled>
                <option value="SMAW" selected>SMAW</option>
            </select>
            <input type="hidden" name="welding_process" value="SMAW">
        </td>
        <td class="var-range">
            <span id="process_range_span">SMAW</span>
            <input type="hidden" name="welding_process_range" value="SMAW">
        </td>
    </tr>
    <tr>
        <td class="var-label">Type (i.e., manual, semi-automatic) used:</td>
        <td class="var-value">
            <select class="form-select" disabled>
                <option value="Manual">Manual</option>
            </select>
            <input type="hidden" name="welding_type" value="Manual">
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
        <td class="var-range" data-range="backing">
            <span id="backing_range_span">{{ $certificate->backing_range ?? '' }}</span>
            <input type="text" class="form-input" name="backing_range_manual"
                id="backing_range_manual" placeholder="Enter qualified range"
                style="display: none; margin-top: 2px;">
        </td>
    </tr>
    <tr>
        <td class="var-label">
            <div class="checkbox-container">
                <input type="checkbox" name="plate_specimen" id="plate_specimen" 
                       {{ isset($certificate->plate_specimen) && $certificate->plate_specimen ? 'checked' : '' }}
                       data-saved-value="{{ $certificate->plate_specimen ?? '' }}"
                       onchange="toggleDiameterField()">
                <label for="plate_specimen">Plate</label>
                <input type="checkbox" name="pipe_specimen" id="pipe_specimen"
                       {{ isset($certificate->pipe_specimen) && $certificate->pipe_specimen ? 'checked' : 'checked' }}
                       data-saved-value="{{ $certificate->pipe_specimen ?? '' }}"
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
            <span id="p_number_range_span">P-NO. 1 through P-NO. 15F, P-NO. 34, and P-NO. 41 through P-NO. 49</span>
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
            <span id="f_number_range_span">F-No.1 with Backing, F-No.2 with backing, F-No.3 with backing &
                F-No.4 With Backing</span>
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
            <input type="text" class="form-input" name="deposit_thickness"
                placeholder="4mm &14.26 mm" value="{{ $certificate->deposit_thickness ?? '' }}" 
                data-saved-value="{{ $certificate->deposit_thickness ?? '' }}">
        </td>
        <td class="var-range">
            <input type="text" class="form-input" name="deposit_thickness_range"
                placeholder="------">
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
                onchange="calculateThicknessRange(this.value)">
        </td>
        <td class="var-range">
            <input type="text" class="form-input" name="smaw_thickness_range" id="smaw_thickness_range"
                placeholder="Max. to be welded" readonly>
        </td>
    </tr>
    <!-- Need to add the vertical progression field with proper span element -->
    
</table>




<script>
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

// Initialize the thickness range calculation when the page loads
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
    }
    
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
    // Initialize all welding variables with saved values
    initializeWeldingVariables();
    
    const smawThickness = document.getElementById('smaw_thickness').value;
    calculateThicknessRange(smawThickness);
    
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

    // Initialize vertical progression
    const verticalProgression = document.getElementById('vertical_progression').value;
    const verticalProgressionRangeText = verticalProgression === 'Uphill' ? 'Uphill' : 'Downhill';
    
    const verticalProgressionSpan = document.getElementById('vertical_progression_range_span');
    if (verticalProgressionSpan) {
        verticalProgressionSpan.textContent = verticalProgressionRangeText;
        
        // Also update the hidden field
        const verticalProgressionHidden = document.getElementById('vertical_progression_range');
        if (verticalProgressionHidden) {
            verticalProgressionHidden.value = verticalProgressionRangeText;
        }
    }
    
    // Ensure range fields are properly initialized
    setTimeout(function() {
        // ...existing code...
    }, 500);

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
