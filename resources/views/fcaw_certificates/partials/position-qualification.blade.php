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
                    @foreach($testPositions as $position)
                        <option value="{{ $position }}" {{ old('test_position', isset($certificate) ? $certificate->test_position : '') == $position ? 'selected' : '' }}>{{ $position }}</option>
                    @endforeach
                </select>
                <span id="position_range_span" style="display: none;">{{ old('position_range', isset($certificate) ? $certificate->position_range : '') }}</span>
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
                <option value="None" {{ old('vertical_progression', isset($certificate) ? $certificate->vertical_progression : '') == 'None' ? 'selected' : '' }}>None</option>
                <option value="Upward" {{ old('vertical_progression', isset($certificate) ? $certificate->vertical_progression : '') == 'Upward' ? 'selected' : '' }}>Upward</option>
                <option value="Downward" {{ old('vertical_progression', isset($certificate) ? $certificate->vertical_progression : '') == 'Downward' ? 'selected' : '' }}>Downward</option>
            </select>
        </td>
        <td class="var-range">
            <span id="vertical_progression_range_span">{{ old('vertical_progression_range', isset($certificate) ? $certificate->vertical_progression_range : '') }}</span>
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
                <option value="" disabled>Select</option>
                <option  value="With backing Gas" {{ old('backing_gas', isset($certificate) ? $certificate->backing_gas : '') == 'With backing Gas' ? 'selected' : '' }}>With backing Gas</option>
                <option value="Without backing Gas" {{ old('backing_gas', isset($certificate) ? $certificate->backing_gas : '') == 'Without backing Gas' ? 'selected' : '' }}>Without backing Gas</option>
            </select>
        </td>
        <td class="var-range">
            <span id="backing_gas_range_span">{{ old('backing_gas_range', isset($certificate) ? $certificate->backing_gas_range : '') }}</span>
            <input type="hidden" name="backing_gas_range" id="backing_gas_range" value="{{ old('backing_gas_range', isset($certificate) ? $certificate->backing_gas_range : '') }}">
        </td>
    </tr>
     <tr>
        <td class="var-label">Transfer mode (spray, globular, or pulse to short circuit-GMAW):</td>
        <td class="var-value">
            <select class="form-select" name="transfer_mode" id="transfer_mode"
                onchange="updateTransferModeRange()">
                <option value="spray" {{ old('transfer_mode', isset($certificate) ? $certificate->transfer_mode : '') == 'spray' ? 'selected' : '' }}>Spray</option>
                <option value="globular" {{ old('transfer_mode', isset($certificate) ? $certificate->transfer_mode : '') == 'globular' ? 'selected' : '' }}>Globular</option>
                <option value="pulse" {{ old('transfer_mode', isset($certificate) ? $certificate->transfer_mode : '') == 'pulse' ? 'selected' : '' }}>Pulse</option>
                <option value="short circuit" {{ old('transfer_mode', isset($certificate) ? $certificate->transfer_mode : '') == 'short circuit' ? 'selected' : '' }}>Short Circuit</option>
            </select>
        </td>
        <td class="var-range">
            <span id="transfer_mode_range_span">{{ old('transfer_mode_range', isset($certificate) ? $certificate->transfer_mode_range : '') }}</span>
            <input type="hidden" name="transfer_mode_range" id="transfer_mode_range" value="{{ old('transfer_mode_range', isset($certificate) ? $certificate->transfer_mode_range : '') }}">
        </td>
    </tr>
    <tr>
        <td class="var-label">GTAW current type and polarity (AC, DCEP, DCEN) For LBW or LLBW:</td>
        <td class="var-value">
          <input type="text" class="form-input" name="gtaw_current_type" value="{{ old('gtaw_current_type', isset($certificate) ? $certificate->gtaw_current_type : '') }}">
        </td>
        <td class="var-range">
           <input type="text" class="form-input" name="gtaw_current_type_range" value="{{ old('gtaw_current_type_range', isset($certificate) ? $certificate->gtaw_current_type_range : '') }}">
        </td>
    </tr>
    <tr>
        <td class="var-label">Type of equipment</td>
        <td class="var-value">
            <input type="text" class="form-input" name="equipment_type" value="{{ old('equipment_type', isset($certificate) ? $certificate->equipment_type : '') }}">
        </td>
        <td class="var-range">
            <input type="text" class="form-input" name="equipment_type_range" value="{{ old('equipment_type_range', isset($certificate) ? $certificate->equipment_type_range : '') }}">
        </td>
    </tr>
    <tr>
        <td class="var-label">Technique (keyhole LBW or melt-in)</td>
        <td class="var-value">
            <input type="text" class="form-input" name="technique" value="{{ old('technique', isset($certificate) ? $certificate->technique : '') }}">
        </td>
        <td class="var-range">
          <input type="text" class="form-input" name="technique_range" value="{{ old('technique_range', isset($certificate) ? $certificate->technique_range : '') }}">
        </td>
    </tr>
    <tr>
        <td class="var-label">
            Torch/Gun/Beam oscillation
            <div class="checkbox-container" style="display: inline-block; margin-left: 10px;">
                <input type="radio" name="oscillation" id="oscillation_yes" value="yes" {{ old('oscillation', isset($certificate) ? $certificate->oscillation : '') == 'yes' ? 'checked' : '' }} onchange="updateOscillationRange()">
                <label for="oscillation_yes">YES</label>
                <input type="radio" name="oscillation" id="oscillation_no" value="no" {{ old('oscillation', isset($certificate) ? $certificate->oscillation : '') == 'no' ? 'checked' : '' }} onchange="updateOscillationRange()">
                <label for="oscillation_no">NO</label>
            </div>
        </td>
        <td class="var-value">
            <input type="text" class="form-control" name="oscillation_value" id="oscillation_value" placeholder="Oscillation value" oninput="updateOscillationRange()" value="{{ old('oscillation_value', isset($certificate) ? $certificate->oscillation_value : '') }}">
        </td>
        <td class="var-range">
            <span id="oscillation_range_span">{{ old('oscillation_range', isset($certificate) ? $certificate->oscillation_range : '') }}</span>
            <input type="hidden" name="oscillation_range" id="oscillation_range" value="{{ old('oscillation_range', isset($certificate) ? $certificate->oscillation_range : '') }}">
        </td>
    </tr>
    <tr>
        <td class="var-label">Mode of operation (pulsed or continuous):</td>
        <td class="var-value">
            <select class="form-select" name="operation_mode" id="operation_mode" onchange="updateOperationModeRange()">
                <option value="Continuous" {{ old('operation_mode', isset($certificate) ? $certificate->operation_mode : '') == 'Continuous' ? 'selected' : '' }}>Continuous</option>
                <option value="Pulsed" {{ old('operation_mode', isset($certificate) ? $certificate->operation_mode : '') == 'Pulsed' ? 'selected' : '' }}>Pulsed</option>
            </select>
        </td>
        <td class="var-range">
            <span id="operation_mode_range_span">{{ old('operation_mode_range', isset($certificate) ? $certificate->operation_mode_range : '') }}</span>
            <input type="hidden" name="operation_mode_range" id="operation_mode_range" value="{{ old('operation_mode_range', isset($certificate) ? $certificate->operation_mode_range : '') }}">
        </td>
    </tr>
</table>