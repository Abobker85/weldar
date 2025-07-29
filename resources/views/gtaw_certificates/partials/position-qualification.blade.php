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
                    onchange="updatePositionRange()">
                    <option value="" disabled>-- Select Position --</option>
                    <option value="6G" {{ ($certificate->test_position ?? '6G') == '6G' ? 'selected' : '' }}>6G</option>
                    <option value="5G" {{ ($certificate->test_position ?? '') == '5G' ? 'selected' : '' }}>5G</option>
                    <option value="1G" {{ ($certificate->test_position ?? '') == '1G' ? 'selected' : '' }}>1G</option>
                </select>
                <span id="position_range_span" style="display: none;">{{ $certificate->position_range ?? '' }}</span>
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
        <td class="var-label">Vertical progression (uphill or downhill) :</td>
        <td class="var-value">
            <select class="form-select" name="vertical_progression" id="vertical_progression" onchange="updateVerticalProgressionRange()">
                <option value="None" {{ ($certificate->vertical_progression ?? 'None') == 'None' ? 'selected' : '' }}>None</option>
                <option value="Uphill" {{ in_array($certificate->vertical_progression ?? '', ['Uphill', 'Upward']) ? 'selected' : '' }}>Uphill</option>
                <option value="Downhill" {{ in_array($certificate->vertical_progression ?? '', ['Downhill', 'Downward']) ? 'selected' : '' }}>Downhill</option>
            </select>
        </td>
        <td class="var-range">
            <span id="vertical_progression_range_span">{{ $certificate->vertical_progression_range ?? 'Uphill' }}</span>
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
            <select class="form-select" name="backing_gas" id="backing_gas" onchange="updateBackingGasRange()">
                <option value="" selected disabled>Select</option>
                <option value="With backing Gas" {{ ($certificate->backing_gas ?? '') == 'With backing Gas' ? 'selected' : '' }}>With backing Gas</option>
                <option value="Without backing Gas" {{ ($certificate->backing_gas ?? '') == 'Without backing Gas' ? 'selected' : '' }}>Without backing Gas</option>
            </select>
        </td>
        <td class="var-range">
            <span id="backing_gas_range_span">{{ $certificate->backing_gas_range ?? '...' }}</span>
            <input type="hidden" name="backing_gas_range" id="backing_gas_range" value="{{ $certificate->backing_gas_range ?? '' }}">
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
            <select class="form-select" name="gtaw_polarity" id="gtaw_polarity" onchange="updateGtawPolarityRange()">
                <option value="" selected disabled>Select</option>
                <option value="AC" {{ ($certificate->gtaw_polarity ?? 'AC') == 'AC' ? 'selected' : '' }}>AC</option>
                <option value="DCEN" {{ ($certificate->gtaw_polarity ?? '') == 'DCEN' ? 'selected' : '' }}>DCEN</option>
            </select>
        </td>
        <td class="var-range">
            <span id="gtaw_polarity_range_span">{{ $certificate->gtaw_polarity_range ?? '...' }}</span>
            <input type="hidden" name="gtaw_polarity_range" id="gtaw_polarity_range" value="{{ $certificate->gtaw_polarity_range ?? '' }}">
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
                <input type="radio" name="oscillation" id="oscillation_yes" value="yes" {{ ($certificate->oscillation ?? 'no') == 'yes' ? 'checked' : '' }}>
                <label for="oscillation_yes">YES</label>
                <input type="radio" name="oscillation" id="oscillation_no" value="no" {{ ($certificate->oscillation ?? 'no') == 'no' ? 'checked' : '' }}>
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
