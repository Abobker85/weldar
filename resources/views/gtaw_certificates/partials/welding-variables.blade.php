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
                <option value="Manual">Manual</option>
                <option value="Semi-automatic">Semi-automatic</option>
                <option value="Automatic">Automatic</option>
            </select>
        </td>
        <td class="var-range">Manual</td>
    </tr>
       <tr>
        <td class="var-label">Backing (with/without):</td>
        <td class="var-value">
            <select class="form-select" name="backing" id="backing" onchange="updateBackingRange()">
                <option value="With Backing">With Backing</option>
                <option value="Without Backing">Without Backing</option>
                <option value="__manual__">Manual Entry</option>
            </select>
            <input type="text" class="form-input" name="backing_manual" id="backing_manual"
                placeholder="Enter custom backing type" style="display: none; margin-top: 2px;">
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
                <input type="checkbox" name="plate_specimen" id="plate_specimen" onchange="toggleDiameterField()">
                <label for="plate_specimen">Plate</label>
                <input type="checkbox" name="pipe_specimen" id="pipe_specimen" checked
                    onchange="toggleDiameterField(); updateDiameterRange()">
                <label for="pipe_specimen">Pipe</label>
            </div>
            (enter diameter if pipe or tube)
        </td>
        <td class="var-value">
            <select class="form-select" name="pipe_diameter_type" id="pipe_diameter_type"
                onchange="updateDiameterRange()">
                <option value="8_nps">8" NPS (Outside diameter 219.1 mm)</option>
                <option value="6_nps">6" NPS (Outside diameter 168.3 mm)</option>
                <option value="4_nps">4" NPS (Outside diameter 114.3 mm)</option>
                <option value="2_nps">2" NPS (Outside diameter 60.3 mm)</option>
                <option value="1_nps">1" NPS (Outside diameter 33.4 mm)</option>
                <option value="__manual__">Manual Entry</option>
            </select>
            <input type="text" class="form-input" name="pipe_diameter_manual"
                id="pipe_diameter_manual" placeholder="Enter diameter (e.g., 10 inch NPS)"
                style="display: none; margin-top: 2px;">
        </td>
        <td class="var-range">
            <span id="diameter_range_span">Outside diameter 2 7/8 inch (73 mm) to unlimited</span>
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
                <option value="P NO.1 TO P NO.1">P NO.1 TO P NO.1</option>
                <option value="P NO.1 TO P NO.8">P NO.1 TO P NO.8</option>
                <option value="P NO.8 TO P NO.8">P NO.8 TO P NO.8</option>
                <option value="P NO.43 TO P NO.43">P NO.43 TO P NO.43</option>
                <option value="__manual__">Manual Entry</option>
            </select>
            <input type="text" class="form-input" name="base_metal_p_no_manual"
                id="base_metal_p_no_manual" placeholder="Enter P-Number range"
                style="display: none; margin-top: 2px;">
        </td>
        <td class="var-range">
            <span id="p_number_range_span">P-NO. 1 through P-NO. 15F, P-NO. 34, and P-NO. 41 through P-NO. 49</span>
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
                <option value="5.1">5.1</option>
                <option value="A5.1">A5.1</option>
                <option value="A5.18">A5.18</option>
                <option value="A5.20">A5.20</option>
                <option value="__manual__">Manual Entry</option>
            </select>
            <input type="text" class="form-input" name="filler_spec_manual" id="filler_spec_manual"
                placeholder="Enter SFA specification" style="display: none; margin-top: 2px;">
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
                onchange="toggleManualEntry('filler_class')">
                <option value="E7018-1">E7018-1</option>
                <option value="E7018">E7018</option>
                <option value="E6010">E6010</option>
                <option value="E6013">E6013</option>
                <option value="ER70S-2">ER70S-2</option>
                <option value="ER70S-6">ER70S-6</option>
                <option value="__manual__">Manual Entry</option>
            </select>
            <input type="text" class="form-input" name="filler_class_manual" id="filler_class_manual"
                placeholder="Enter classification" style="display: none; margin-top: 2px;">
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
                onchange="updateFNumberRange()">
                <option selected value="F-No.6">F-No. 6</option>
              
                <option value="__manual__">Manual Entry</option>
            </select>
            <input type="text" class="form-input" name="filler_f_no_manual" id="filler_f_no_manual"
                placeholder="Enter F-Number" style="display: none; margin-top: 2px;">
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
            <input type="text" class="form-input" name="consumable_insert" placeholder="------">
        </td>
        <td class="var-range">
            <input type="text" class="form-input" name="consumable_insert_range"
                placeholder="------">
        </td>
    </tr>
    <tr>
        <td class="var-label">Filler Metal Product Form (QW-404.23) (GTAW or PAW):</td>
        <td class="var-value">
            <input type="text" class="form-input" name="filler_product_form" placeholder="------">
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
                placeholder="4mm &14.26 mm">
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
                <input type="radio" name="gtaw_process" id="gtaw_yes" value="yes" checked>
                <label for="gtaw_yes">YES</label>
                <input type="radio" name="gtaw_process" id="gtaw_no" value="no">
                <label for="gtaw_no">NO</label>
            </div>
        </td>
        <td class="var-value">
            <input type="text" class="form-input" name="gtaw_thickness" id="gtaw_thickness" 
                placeholder="Enter thickness (mm)" value="14.26" required
                onchange="calculateThicknessRange(this.value)">
        </td>
        <td class="var-range">
            <input type="text" class="form-input" name="gtaw_thickness_range" id="gtaw_thickness_range"
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

    document.getElementById('gtaw_thickness_range').value = rangeValue;
}

// Initialize the thickness range calculation when the page loads
document.addEventListener('DOMContentLoaded', function() {
    const gtawThickness = document.getElementById('gtaw_thickness').value;
    calculateThicknessRange(gtawThickness);

    // Initialize backing range immediately to ensure it's not empty
    const backing = document.getElementById('backing').value;
    const backingRangeText = (backing === 'With Backing') ? 
        'With backing ' : 
        ' With or Without backing ';
    
    const backingRangeElement = document.getElementById('backing_range_span');
    if (backingRangeElement) {
        backingRangeElement.textContent = backingRangeText;
        
        // Also update the hidden field
        const backingRangeHidden = document.getElementById('backing_range');
        if (backingRangeHidden) {
            backingRangeHidden.value = backingRangeText;
        }
    }

    // Initialize vertical progression
    const verticalProgression = document.getElementById('vertical_progression').value;
    const verticalProgressionRangeText = verticalProgression === 'Upward' ? 'Upward' : 'Downward';
    
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
