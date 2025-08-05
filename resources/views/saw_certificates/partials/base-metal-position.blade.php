<!-- Base Metal and Position Section -->
<div class="cert-details-row">
    <div style="flex: 1; padding: 0 10px;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <strong>Base metal</strong>
            <select class="form-input" name="base_metal_p_no_from" style="width: 100px;">
                <option value="P-Number 1" {{ old('base_metal_p_no_from', $certificate->base_metal_p_no_from ?? 'P-Number 1') == 'P-Number 1' ? 'selected' : '' }}>P-Number 1</option>
                <option value="P-Number 2" {{ old('base_metal_p_no_from', $certificate->base_metal_p_no_from ?? '') == 'P-Number 2' ? 'selected' : '' }}>P-Number 2</option>
                <option value="P-Number 3" {{ old('base_metal_p_no_from', $certificate->base_metal_p_no_from ?? '') == 'P-Number 3' ? 'selected' : '' }}>P-Number 3</option>
                <option value="P-Number 4" {{ old('base_metal_p_no_from', $certificate->base_metal_p_no_from ?? '') == 'P-Number 4' ? 'selected' : '' }}>P-Number 4</option>
                <option value="P-Number 5" {{ old('base_metal_p_no_from', $certificate->base_metal_p_no_from ?? '') == 'P-Number 5' ? 'selected' : '' }}>P-Number 5</option>
            </select>
            <strong>to</strong>
            <select class="form-input" name="base_metal_p_no_to" style="width: 100px;">
                <option value="P-Number 1" {{ old('base_metal_p_no_to', $certificate->base_metal_p_no_to ?? 'P-Number 1') == 'P-Number 1' ? 'selected' : '' }}>P-Number 1</option>
                <option value="P-Number 2" {{ old('base_metal_p_no_to', $certificate->base_metal_p_no_to ?? '') == 'P-Number 2' ? 'selected' : '' }}>P-Number 2</option>
                <option value="P-Number 3" {{ old('base_metal_p_no_to', $certificate->base_metal_p_no_to ?? '') == 'P-Number 3' ? 'selected' : '' }}>P-Number 3</option>
                <option value="P-Number 4" {{ old('base_metal_p_no_to', $certificate->base_metal_p_no_to ?? '') == 'P-Number 4' ? 'selected' : '' }}>P-Number 4</option>
                <option value="P-Number 5" {{ old('base_metal_p_no_to', $certificate->base_metal_p_no_to ?? '') == 'P-Number 5' ? 'selected' : '' }}>P-Number 5</option>
            </select>
        </div>
    </div>
    <div style="width: 100px; border-left: 1px solid #000; padding: 0 10px; text-align: center;">
        <strong>Position</strong>
    </div>
    <div style="width: 80px; border-left: 1px solid #000; padding: 0 10px; text-align: center;">
        <select class="form-input" name="test_position" id="test_position" onchange="updatePositionRange()">
            <option value="1G" {{ old('test_position', $certificate->test_position ?? '1G') == '1G' ? 'selected' : '' }}>1G</option>
            <option value="2G" {{ old('test_position', $certificate->test_position ?? '') == '2G' ? 'selected' : '' }}>2G</option>
            <option value="3G" {{ old('test_position', $certificate->test_position ?? '') == '3G' ? 'selected' : '' }}>3G</option>
            <option value="4G" {{ old('test_position', $certificate->test_position ?? '') == '4G' ? 'selected' : '' }}>4G</option>
            <option value="5G" {{ old('test_position', $certificate->test_position ?? '') == '5G' ? 'selected' : '' }}>5G</option>
            <option value="6G" {{ old('test_position', $certificate->test_position ?? '') == '6G' ? 'selected' : '' }}>6G</option>
        </select>
    </div>
</div>

<div class="cert-details-row">
    <div style="flex: 1; padding: 0 10px;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <div class="checkbox-container">
                <input type="checkbox" name="plate_specimen" id="plate_specimen" {{ old('plate_specimen', $certificate->plate_specimen ?? false) ? 'checked' : '' }} onchange="toggleSpecimenFields()">
                <label for="plate_specimen"><strong>Plate</strong></label>
            </div>
            <div class="checkbox-container">
                <input type="checkbox" name="pipe_specimen" id="pipe_specimen" {{ old('pipe_specimen', $certificate->pipe_specimen ?? false) ? 'checked' : '' }} onchange="toggleSpecimenFields()">
                <label for="pipe_specimen"><strong>Pipe</strong></label>
            </div>
            <span>(enter diameter, if pipe or tube)</span>
            <div id="pipe_diameter_field" style="display: none;">
                <input type="text" class="form-input" name="pipe_diameter" id="pipe_diameter" 
                    value="{{ old('pipe_diameter', $certificate->pipe_diameter ?? '') }}" 
                    placeholder="e.g., NPS 8" style="width: 80px;">
            </div>
        </div>
    </div>
    <div style="width: 180px; border-left: 1px solid #000; padding: 5px; text-align: left; font-size: 8px;">
        <div id="position_range_display">
            @if(isset($certificate))
                {!! nl2br(str_replace(' | ', '<br>', $certificate->position_range ?? '')) !!}
            @else
                F for Groove Plate and Pipe Over 24 in. (610 mm) O.D.<br>
                F for Fillet or Tack Plate and Pipe
            @endif
        </div>
        <input type="hidden" name="position_range" id="position_range" value="{{ old('position_range', $certificate->position_range ?? '') }}">
    </div>
</div>

<div class="cert-details-row">
    <div style="flex: 1; padding: 0 10px;">
        <div style="display: flex; align-items: center; gap: 10px;">
            <strong>Filler metal (SFA) specification</strong>
            <input type="text" class="form-input" name="filler_metal_sfa_spec" 
                value="{{ old('filler_metal_sfa_spec', $certificate->filler_metal_sfa_spec ?? '5.17') }}" 
                style="width: 60px;" placeholder="5.17">
            
            <strong>Filler metal or electrode classification</strong>
            <input type="text" class="form-input" name="filler_metal_classification" 
                value="{{ old('filler_metal_classification', $certificate->filler_metal_classification ?? 'F7A2 EM12K') }}" 
                style="width: 100px;" placeholder="F7A2 EM12K">
        </div>
    </div>
</div>

<script>
function toggleSpecimenFields() {
    const plateCheckbox = document.getElementById('plate_specimen');
    const pipeCheckbox = document.getElementById('pipe_specimen');
    const pipeField = document.getElementById('pipe_diameter_field');
    
    if (pipeCheckbox && pipeCheckbox.checked && pipeField) {
        pipeField.style.display = 'inline-block';
    } else if (pipeField) {
        pipeField.style.display = 'none';
    }
    
    updatePositionRange();
}

function updatePositionRange() {
    const position = document.getElementById('test_position')?.value;
    const isPipe = document.getElementById('pipe_specimen')?.checked;
    const rangeDisplay = document.getElementById('position_range_display');
    const hiddenField = document.getElementById('position_range');
    
    if (!rangeDisplay) return;
    
    let ranges = [];
    
    switch (position) {
        case '1G':
            ranges.push('F for Groove Plate and Pipe Over 24 in. (610 mm) O.D.');
            if (isPipe) {
                ranges.push('F for Groove Pipe ≥ 2 7∕8 in. (73 mm) O.D.');
            }
            ranges.push('F for Fillet or Tack Plate and Pipe');
            break;
        case '2G':
            ranges.push('F & H for Groove Plate and Pipe Over 24 in. (610 mm) O.D.');
            if (isPipe) {
                ranges.push('F & H for Groove Pipe ≥ 2 7∕8 in. (73 mm) O.D.');
            }
            ranges.push('F & H for Fillet or Tack Plate and Pipe');
            break;
        default:
            ranges.push('F for Groove Plate and Pipe Over 24 in. (610 mm) O.D.');
            ranges.push('F for Fillet or Tack Plate and Pipe');
    }
    
    rangeDisplay.innerHTML = ranges.join('<br>');
    if (hiddenField) {
        hiddenField.value = ranges.join(' | ');
    }
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', function() {
    toggleSpecimenFields();
    updatePositionRange();
});
</script>