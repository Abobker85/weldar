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
                <select class="form-select" name="test_position" id="test_position" required
                    onchange="updatePositionRange()" data-saved-value="{{ $certificate->test_position ?? '' }}">
                    <option value="" disabled {{ empty($certificate->test_position) ? 'selected' : '' }}>-- Select Position --</option>
                    <option value="6G" {{ ($certificate->test_position ?? '') == '6G' ? 'selected' : '' }}>6G</option>
                    <option value="5G" {{ ($certificate->test_position ?? '') == '5G' ? 'selected' : '' }}>5G</option>
                    <option value="1G" {{ ($certificate->test_position ?? '') == '1G' ? 'selected' : '' }}>1G</option>
                </select>
                <span id="position_range_span" style="display: none;"></span>
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
        <td class="var-label">Vertical progression:</td>
        <td class="var-value">
            <select class="form-select" name="vertical_progression" id="vertical_progression" onchange="updatePositionVerticalProgressionRange()">
                <option value="None"  selected>None</option>
                <option value="Uphill">Uphill</option>
                <option value="Downhill">Downhill</option>
            </select>
        </td>
        <td class="var-range">
            <span id="vertical_progression_range_span">Uphill</span>
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
