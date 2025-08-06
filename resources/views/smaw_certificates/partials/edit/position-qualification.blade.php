<!-- Position Qualification Section -->
<table class="variables-table">
    <tr>
        <td class="section-header" colspan="3">Position Qualification</td>
    </tr>
    <tr>
        <td rowspan="3" class="var-label" style="vertical-align: middle;">Position(s):</td>
        <td class="var-value">
            <div class="form-group">
                <label for="test_position">Test Position:</label>
                <!-- Debug: {{ $certificate->test_position }} -->
                <select class="form-select" name="test_position" id="test_position" required
                    onchange="updatePositionRange()" data-saved-value="{{ $certificate->test_position ?? '' }}">
                    <option value="" disabled>-- Select Position --</option>
                    @php
                    $test_position = $certificate->test_position ?? '';
                    @endphp
                    <option value="1G" @if($test_position == '1G') selected @endif>1G</option>
                    <option value="2G" @if($test_position == '2G') selected @endif>2G</option>
                    <option value="3G" @if($test_position == '3G') selected @endif>3G</option>
                    <option value="4G" @if($test_position == '4G') selected @endif>4G</option>
                    <option value="5G" @if($test_position == '5G') selected @endif>5G</option>
                    <option value="6G" @if($test_position == '6G') selected @endif>6G</option>
                    <option value="1F" @if($test_position == '1F') selected @endif>1F</option>
                    <option value="2F" @if($test_position == '2F') selected @endif>2F</option>
                    <option value="3F" @if($test_position == '3F') selected @endif>3F</option>
                    <option value="4F" @if($test_position == '4F') selected @endif>4F</option>
                </select>
                <input type="hidden" name="position_range" id="position_range" value="{{ $certificate->position_range ?? '' }}">
            </div>
        </td>
        <td class="var-range" style="font-weight: bold; font-size: 8px;">
            Groove Plate and Pipe Over 24 in. (610 mm) O.D. in all Position
        </td>
    </tr>
    <tr>
        <td class="var-value"></td>
        <td class="var-range" style="font-weight: bold; font-size: 8px;">
            Groove Pipe ≤24 in. (610 mm) O.D. in all Position
        </td>
    </tr>
    <tr>
        <td class="var-value"></td>
        <td class="var-range" style="font-weight: bold; font-size: 8px;">
            Fillet or Tack Plate and Pipe in all Position
        </td>
    </tr>
   <tr>
        <td class="var-label">Vertical progression (uphill or downhill):</td>
        <td class="var-value">
            <select class="form-select" name="vertical_progression" id="vertical_progression" 
                onchange="updateVerticalProgressionRange()" data-saved-value="{{ $certificate->vertical_progression ?? 'Uphill' }}">
                <option value="Uphill" {{ ($certificate->vertical_progression ?? 'Uphill') === 'Uphill' ? 'selected' : '' }}>Uphill</option>
                <option value="Downhill" {{ ($certificate->vertical_progression ?? 'Uphill') === 'Downhill' ? 'selected' : '' }}>Downhill</option>
                <option value="__manual__" {{ !in_array(($certificate->vertical_progression ?? 'Uphill'), ['Uphill', 'Downhill']) && !empty($certificate->vertical_progression) ? 'selected' : '' }}>Manual Entry</option>
            </select>
            <input type="text" class="form-input" name="vertical_progression_manual" id="vertical_progression_manual"
                placeholder="Enter vertical progression" 
                value="{{ !in_array(($certificate->vertical_progression ?? 'Uphill'), ['Uphill', 'Downhill']) ? ($certificate->vertical_progression ?? '') : '' }}"
                style="{{ !in_array(($certificate->vertical_progression ?? 'Uphill'), ['Uphill', 'Downhill']) && !empty($certificate->vertical_progression) ? 'display: block;' : 'display: none;' }} margin-top: 2px;">
        </td>
        <td class="var-range">
            <span id="vertical_progression_range_span">Uphill</span>
            <input type="hidden" name="vertical_progression_range" id="vertical_progression_range" value="Uphill">
            <input type="text" class="form-input" name="vertical_progression_range_manual"
                id="vertical_progression_range_manual" placeholder="Enter qualified range"
                style="display: none; margin-top: 2px;">
        </td>
    </tr>
    <!-- Remaining position qualification rows -->
    <tr>
        <td class="var-label">Type of fuel gas (OFW):</td>
        <td class="var-value">
            <span> ..... </span>
        </td>
        <td class="var-range">
            <span> ..... </span>
        </td>
    </tr>
    <tr>
        <td class="var-label">Use of backing gas (GTAW, PAW, GMAW, LBW):</td>
        <td class="var-value">
            <span> ..... </span>
        </td>
        <td class="var-range">
            <span> ..... </span>
        </td>
    </tr>
    <tr>
        <td class="var-label">Transfer mode (spray, globular, or pulse to short circuit-GMAW):</td>
        <td class="var-value">
            <span> ..... </span>
        </td>
        <td class="var-range">
            <span> ..... </span>
        </td>
    </tr>
    <tr>
        <td class="var-label">GTAW current type and polarity (AC, DCEP, DCEN) For LBW or LLBW:</td>
        <td class="var-value">
            <span> ..... </span>
        </td>
        <td class="var-range">
            <span> ..... </span>
        </td>
    </tr>
    <tr>
        <td class="var-label">Type of equipment</td>
        <td class="var-value">
            <span> ..... </span>
        </td>
        <td class="var-range">
            <span> ..... </span>
        </td>
    </tr>
    <tr>
        <td class="var-label">Technique (keyhole LBW or melt-in)</td>
        <td class="var-value">
            <span> ..... </span>
        </td>
        <td class="var-range">
            <span> ..... </span>
        </td>
    </tr>
    <tr>
        <td class="var-label">
            Torch/Gun/Beam oscillation
            <div class="checkbox-container" style="display: inline-block; margin-left: 10px;">
                <input type="radio" name="oscillation" id="oscillation_yes" value="yes">
                <label for="oscillation_yes">YES</label>
                <input type="radio" name="oscillation" id="oscillation_no" value="no" checked>
                <label for="oscillation_no">NO</label>
            </div>
        </td>
        <td class="var-value">
            <span> ..... </span>
        </td>
        <td class="var-range">
            <span> ..... </span>
        </td>
    </tr>
    <tr>
        <td class="var-label">Mode of operation (pulsed or continuous):</td>
        <td class="var-value">
            <span> ..... </span>
        </td>
        <td class="var-range">
            <span> ..... </span>
        </td>
    </tr>
</table>

<script>
// Update vertical progression range based on selected value
function updateVerticalProgressionRange() {
    const verticalProgression = document.getElementById('vertical_progression');
    const verticalProgressionSpan = document.getElementById('vertical_progression_range_span');
    const verticalProgressionRange = document.getElementById('vertical_progression_range');
    const verticalProgressionManual = document.getElementById('vertical_progression_manual');
    const verticalProgressionRangeManual = document.getElementById('vertical_progression_range_manual');
    
    if (!verticalProgression) return;
    
    if (verticalProgression.value === '__manual__') {
        if (verticalProgressionManual) verticalProgressionManual.style.display = 'block';
        if (verticalProgressionRangeManual) verticalProgressionRangeManual.style.display = 'block';
        if (verticalProgressionSpan) verticalProgressionSpan.style.display = 'none';
        
        // When manual is selected, use the manual value
        if (verticalProgressionRange) {
            const manualValue = verticalProgressionManual ? verticalProgressionManual.value || 'Uphill' : 'Uphill';
            verticalProgressionRange.value = manualValue;
            // Also set the span content (though it's hidden)
            if (verticalProgressionSpan) verticalProgressionSpan.textContent = manualValue;
            
            console.log('Vertical progression set to manual value:', manualValue);
        }
    } else {
        if (verticalProgressionManual) verticalProgressionManual.style.display = 'none';
        if (verticalProgressionRangeManual) verticalProgressionRangeManual.style.display = 'none';
        if (verticalProgressionSpan) verticalProgressionSpan.style.display = 'inline';
        
        // Set the text based on selection
        const rangeText = verticalProgression.value === 'Downhill' ? 'Downhill' : 'Uphill';
        
        if (verticalProgressionSpan) verticalProgressionSpan.textContent = rangeText;
        if (verticalProgressionRange) verticalProgressionRange.value = rangeText;
        
        console.log('Vertical progression updated to:', rangeText);
    }
}

// Initialize vertical progression range on page load
document.addEventListener('DOMContentLoaded', function() {
    const verticalProgression = document.getElementById('vertical_progression');
    if (verticalProgression) {
        // Initialize the field based on saved value
        updateVerticalProgressionRange();
        
        // Add listener for changes
        verticalProgression.addEventListener('change', updateVerticalProgressionRange);
    }
});
</script>
