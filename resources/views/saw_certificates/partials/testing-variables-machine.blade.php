<!-- Testing Variables When Using Automatic Welding Equipment Section -->
<table class="variables-table">
    <tr>
        <td colspan="4" class="section-header">Testing Variables and Qualification Limits When Using Automatic Welding Equipment</td>
    </tr>
    <tr>
        <td class="var-label" style="width: 40%;">Welding Variables (QW-361.1)</td>
        <td class="var-value" style="width: 20%;"><strong>Actual Values</strong></td>
        <td class="var-range" style="width: 20%;"><strong>Range Qualified</strong></td>
        <td rowspan="7" class="var-range" style="width: 20%; vertical-align: top; text-align: left; padding: 5px;">
            <div style="font-weight: bold; margin-bottom: 5px;">Actual Values | Range Qualified</div>
            <div style="margin-bottom: 3px;">Machine | Machine</div>
            <div style="margin-bottom: 3px;">SAW | SAW</div>
            <div style="margin-bottom: 3px;">Direct Visual Control | Direct Visual Control</div>
            <div style="margin-bottom: 3px;">Remote Visual Control | Remote Visual Control</div>
            <div style="margin-bottom: 3px;">With Automatic joint tracking | With Automatic joint tracking</div>
            <div style="margin-bottom: 3px;">Without Automatic joint tracking | With & Without Automatic joint tracking</div>
            <div style="margin-bottom: 3px;">1G | F for Groove Plate and Pipe Over 24 in. (610 mm) O.D.</div>
            <div style="margin-bottom: 3px;"> | F for Groove Pipe ≥ 2 7∕8 in. (73 mm) O.D.</div>
            <div style="margin-bottom: 3px;"> | F for Fillet or Tack Plate and Pipe</div>
            <div style="margin-bottom: 3px;">With Backing | With Backing</div>
            <div style="margin-bottom: 3px;">Without Backing | With or Without backing</div>
            <div style="margin-bottom: 3px;">Single passes per side | Single passes per side</div>
            <div style="margin-bottom: 3px;">multiple passes per side | Single & multiple passes per side</div>
        </td>
    </tr>
    <tr>
        <td class="var-label">Type of welding (automatic):</td>
        <td class="var-value">
            <input type="text" class="form-input" name="automatic_welding_type" 
                value="{{ old('automatic_welding_type', $certificate->automatic_welding_type ?? '') }}" 
                placeholder="…....." style="width: 100%;">
        </td>
        <td class="var-range">
            <input type="text" class="form-input" name="automatic_welding_type_range" 
                value="{{ old('automatic_welding_type_range', $certificate->automatic_welding_type_range ?? '') }}" 
                placeholder="…..........." style="width: 100%;">
        </td>
    </tr>
    <tr>
        <td class="var-label">Welding process(es):</td>
        <td class="var-value">
            <input type="text" class="form-input" name="automatic_welding_process" 
                value="{{ old('automatic_welding_process', $certificate->automatic_welding_process ?? '') }}" 
                placeholder="…....." style="width: 100%;">
        </td>
        <td class="var-range">
            <input type="text" class="form-input" name="automatic_welding_process_range" 
                value="{{ old('automatic_welding_process_range', $certificate->automatic_welding_process_range ?? '') }}" 
                placeholder="…..........." style="width: 100%;">
        </td>
    </tr>
    <tr>
        <td class="var-label">Filler metal used (Yes or No) (EBW or LBW):</td>
        <td class="var-value">
            <select class="form-input" name="filler_metal_used_auto">
                <option value="">Select</option>
                <option value="Yes" {{ old('filler_metal_used_auto', $certificate->filler_metal_used_auto ?? '') == 'Yes' ? 'selected' : '' }}>Yes</option>
                <option value="No" {{ old('filler_metal_used_auto', $certificate->filler_metal_used_auto ?? '') == 'No' ? 'selected' : '' }}>No</option>
            </select>
        </td>
        <td class="var-range">
            <input type="text" class="form-input" name="filler_metal_used_auto_range" 
                value="{{ old('filler_metal_used_auto_range', $certificate->filler_metal_used_auto_range ?? '') }}" 
                placeholder="…..........." style="width: 100%;">
        </td>
    </tr>
    <tr>
        <td class="var-label">Type of laser for LBW (CO2 to YAG, etc.):</td>
        <td class="var-value">
            <input type="text" class="form-input" name="laser_type" 
                value="{{ old('laser_type', $certificate->laser_type ?? '') }}" 
                placeholder="…....." style="width: 100%;">
        </td>
        <td class="var-range">
            <input type="text" class="form-input" name="laser_type_range" 
                value="{{ old('laser_type_range', $certificate->laser_type_range ?? '') }}" 
                placeholder="…..........." style="width: 100%;">
        </td>
    </tr>
    <tr>
        <td class="var-label">Continuous drive or inertia welding (FW):</td>
        <td class="var-value">
            <input type="text" class="form-input" name="drive_type" 
                value="{{ old('drive_type', $certificate->drive_type ?? '') }}" 
                placeholder="…....." style="width: 100%;">
        </td>
        <td class="var-range">
            <input type="text" class="form-input" name="drive_type_range" 
                value="{{ old('drive_type_range', $certificate->drive_type_range ?? '') }}" 
                placeholder="…..........." style="width: 100%;">
        </td>
    </tr>
    <tr>
        <td class="var-label">Vacuum or out of vacuum (EBW):</td>
        <td class="var-value">
            <input type="text" class="form-input" name="vacuum_type" 
                value="{{ old('vacuum_type', $certificate->vacuum_type ?? '') }}" 
                placeholder="…....." style="width: 100%;">
        </td>
        <td class="var-range">
            <input type="text" class="form-input" name="vacuum_type_range" 
                value="{{ old('vacuum_type_range', $certificate->vacuum_type_range ?? '') }}" 
                placeholder="…..........." style="width: 100%;">
        </td>
    </tr>
</table>


<!-- Testing Variables When Using Machine Welding Equipment Section -->
<table class="variables-table">
    <tr>
        <td colspan="3" class="section-header">Testing Variables and Qualification Limits When Using Machine Welding Equipment</td>
    </tr>
    <tr>
        <td class="var-label" style="width: 40%;">Welding Variables (QW-361.2)</td>
        <td class="var-value" style="width: 30%;"><strong>Actual Values</strong></td>
        <td class="var-range" style="width: 30%;"><strong>Range Qualified</strong></td>
    </tr>
    <tr>
        <td class="var-label">Type of welding (Machine):</td>
        <td class="var-value">
            <select class="form-input" name="welding_type">
                <option value="Machine" {{ old('welding_type', $certificate->welding_type ?? 'Machine') == 'Machine' ? 'selected' : '' }}>Machine</option>
                <option value="Automatic" {{ old('welding_type', $certificate->welding_type ?? '') == 'Automatic' ? 'selected' : '' }}>Automatic</option>
            </select>
        </td>
        <td class="var-range">
            <span id="welding_type_range">Machine</span>
            <input type="hidden" name="welding_type_range" value="{{ old('welding_type_range', $certificate->welding_type_range ?? 'Machine') }}">
        </td>
    </tr>
    <tr>
        <td class="var-label">Welding process:</td>
        <td class="var-value">
            <select class="form-input" name="welding_process">
                <option value="SAW" {{ old('welding_process', $certificate->welding_process ?? 'SAW') == 'SAW' ? 'selected' : '' }}>SAW</option>
                <option value="GTAW" {{ old('welding_process', $certificate->welding_process ?? '') == 'GTAW' ? 'selected' : '' }}>GTAW</option>
                <option value="GMAW" {{ old('welding_process', $certificate->welding_process ?? '') == 'GMAW' ? 'selected' : '' }}>GMAW</option>
                <option value="FCAW" {{ old('welding_process', $certificate->welding_process ?? '') == 'FCAW' ? 'selected' : '' }}>FCAW</option>
            </select>
        </td>
        <td class="var-range">
            <span id="welding_process_range">SAW</span>
            <input type="hidden" name="welding_process_range" value="{{ old('welding_process_range', $certificate->welding_process_range ?? 'SAW') }}">
        </td>
    </tr>
    <tr>
        <td class="var-label">Direct or remote visual control:</td>
        <td class="var-value">
            <select class="form-input" name="visual_control_type" onchange="updateVisualControlRange()">
                <option value="Direct Visual Control" {{ old('visual_control_type', $certificate->visual_control_type ?? 'Direct Visual Control') == 'Direct Visual Control' ? 'selected' : '' }}>Direct Visual Control</option>
                <option value="Remote Visual Control" {{ old('visual_control_type', $certificate->visual_control_type ?? '') == 'Remote Visual Control' ? 'selected' : '' }}>Remote Visual Control</option>
            </select>
        </td>
        <td class="var-range">
            <span id="visual_control_range">Direct Visual Control</span>
            <input type="hidden" name="visual_control_range" value="{{ old('visual_control_range', $certificate->visual_control_range ?? 'Direct Visual Control') }}">
        </td>
    </tr>
    <tr>
        <td class="var-label">Automatic arc voltage control (GTAW):</td>
        <td class="var-value">
            <input type="text" class="form-input" name="arc_voltage_control" 
                value="{{ old('arc_voltage_control', $certificate->arc_voltage_control ?? '') }}" 
                placeholder="….............." style="width: 100%;">
        </td>
        <td class="var-range">
            <input type="text" class="form-input" name="arc_voltage_control_range" 
                value="{{ old('arc_voltage_control_range', $certificate->arc_voltage_control_range ?? '') }}" 
                placeholder="….............." style="width: 100%;">
        </td>
    </tr>
    <tr>
        <td class="var-label">Automatic joint tracking:</td>
        <td class="var-value">
            <select class="form-input" name="joint_tracking" onchange="updateJointTrackingRange()">
                <option value="With Automatic joint tracking" {{ old('joint_tracking', $certificate->joint_tracking ?? 'With Automatic joint tracking') == 'With Automatic joint tracking' ? 'selected' : '' }}>With Automatic joint tracking</option>
                <option value="Without Automatic joint tracking" {{ old('joint_tracking', $certificate->joint_tracking ?? '') == 'Without Automatic joint tracking' ? 'selected' : '' }}>Without Automatic joint tracking</option>
            </select>
        </td>
        <td class="var-range">
            <span id="joint_tracking_range">With Automatic joint tracking</span>
            <input type="hidden" name="joint_tracking_range" value="{{ old('joint_tracking_range', $certificate->joint_tracking_range ?? 'With Automatic joint tracking') }}">
        </td>
    </tr>
    <tr>
        <td class="var-label">Position(s):</td>
        <td class="var-value">
            <span id="position_display">1G</span>
            <input type="hidden" name="position_actual" value="{{ old('test_position', $certificate->test_position ?? '1G') }}">
        </td>
        <td class="var-range" style="font-size: 8px; line-height: 1.2;">
            <div>F for Groove Plate and Pipe Over 24 in. (610 mm) O.D.</div>
            <div>F for Groove Pipe ≥ 2 7∕8 in. (73 mm) O.D.</div>
            <div>F for Fillet or Tack Plate and Pipe</div>
        </td>
    </tr>
    <tr>
        <td class="var-label">Consumable inserts (GTAW or PAW):</td>
        <td class="var-value">
            <input type="text" class="form-input" name="consumable_inserts" 
                value="{{ old('consumable_inserts', $certificate->consumable_inserts ?? '') }}" 
                placeholder="….........." style="width: 100%;">
        </td>
        <td class="var-range">
            <input type="text" class="form-input" name="consumable_inserts_range" 
                value="{{ old('consumable_inserts_range', $certificate->consumable_inserts_range ?? '') }}" 
                placeholder="….........." style="width: 100%;">
        </td>
    </tr>
    <tr>
        <td class="var-label">Backing (with or without):</td>
        <td class="var-value">
            <select class="form-input" name="backing" id="backing" onchange="updateBackingRange()">
                <option value="With backing" {{ old('backing', $certificate->backing ?? 'With backing') == 'With backing' ? 'selected' : '' }}>With backing</option>
                <option value="Without backing" {{ old('backing', $certificate->backing ?? '') == 'Without backing' ? 'selected' : '' }}>Without backing</option>
            </select>
        </td>
        <td class="var-range">
            <span id="backing_range_display">With backing</span>
            <input type="hidden" name="backing_range" id="backing_range" value="{{ old('backing_range', $certificate->backing_range ?? 'With backing') }}">
        </td>
    </tr>
    <tr>
        <td class="var-label">Single or multiple passes per side:</td>
        <td class="var-value">
            <select class="form-input" name="passes_per_side" onchange="updatePassesRange()">
                <option value="Single passes per side" {{ old('passes_per_side', $certificate->passes_per_side ?? '') == 'Single passes per side' ? 'selected' : '' }}>Single passes per side</option>
                <option value="multiple passes per side" {{ old('passes_per_side', $certificate->passes_per_side ?? 'multiple passes per side') == 'multiple passes per side' ? 'selected' : '' }}>multiple passes per side</option>
            </select>
        </td>
        <td class="var-range">
            <span id="passes_range_display">Single & multiple passes per side</span>
            <input type="hidden" name="passes_range" value="{{ old('passes_range', $certificate->passes_range ?? 'Single & multiple passes per side') }}">
        </td>
    </tr>
</table>

<script>
function updateVisualControlRange() {
    const visualControl = document.querySelector('[name="visual_control_type"]').value;
    const rangeSpan = document.getElementById('visual_control_range');
    const hiddenField = document.querySelector('[name="visual_control_range"]');
    
    // For SAW, the range is typically the same as the actual value
    rangeSpan.textContent = visualControl;
    hiddenField.value = visualControl;
}

function updateJointTrackingRange() {
    const jointTracking = document.querySelector('[name="joint_tracking"]').value;
    const rangeSpan = document.getElementById('joint_tracking_range');
    const hiddenField = document.querySelector('[name="joint_tracking_range"]');
    
    let range = '';
    if (jointTracking === 'With Automatic joint tracking') {
        range = 'With Automatic joint tracking';
    } else {
        range = 'With & Without Automatic joint tracking';
    }
    
    rangeSpan.textContent = range;
    hiddenField.value = range;
}

function updateBackingRange() {
    const backing = document.getElementById('backing').value;
    const rangeDisplay = document.getElementById('backing_range_display');
    const hiddenField = document.getElementById('backing_range');
    
    let range = '';
    switch (backing) {
        case 'With backing':
            range = 'With backing';
            break;
        case 'Without backing':
            range = 'With or Without backing';
            break;
        default:
            range = 'With backing';
    }
    
    rangeDisplay.textContent = range;
    hiddenField.value = range;
}

function updatePassesRange() {
    const passes = document.querySelector('[name="passes_per_side"]').value;
    const rangeDisplay = document.getElementById('passes_range_display');
    const hiddenField = document.querySelector('[name="passes_range"]');
    
    let range = '';
    if (passes === 'Single passes per side') {
        range = 'Single passes per side';
    } else {
        range = 'Single & multiple passes per side';
    }
    
    rangeDisplay.textContent = range;
    hiddenField.value = range;
}

// Initialize ranges on page load
document.addEventListener('DOMContentLoaded', function() {
    updateVisualControlRange();
    updateJointTrackingRange();
    updateBackingRange();
    updatePassesRange();
    
    // Update position display from main form
    const testPosition = document.getElementById('test_position');
    if (testPosition) {
        document.getElementById('position_display').textContent = testPosition.value;
        
        testPosition.addEventListener('change', function() {
            document.getElementById('position_display').textContent = this.value;
            document.querySelector('[name="position_actual"]').value = this.value;
        });
    }
});
</script>