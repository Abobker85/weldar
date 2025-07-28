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
                    <option value="6G" selected>6G</option>
                    <option value="5G">5G</option>
                    <option value="1G">1G</option>
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
        <td class="var-label">Vertical progression (uphill or downhill) :</td>
        <td class="var-value">
            <select class="form-select" name="vertical_progression" id="vertical_progression" onchange="updateVerticalProgressionRange()">
                <option value="None"  selected>None</option>
                <option value="Upward">Upward</option>
                <option value="Downward">Downward</option>
            </select>
        </td>
        <td class="var-range">
            <span id="vertical_progression_range_span">Upward</span>
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
                <option  value="With backing Gas">With backing Gas</option>
                <option value="Without backing Gas">Without backing Gas</option>
            </select>
        </td>
        <td class="var-range">
            <span id="backing_gas_range_span">...</span>
            <input type="hidden" name="backing_gas_range" id="backing_gas_range" value="">
        </td>
    </tr>
     <tr>
        <td class="var-label">Transfer mode (spray, globular, or pulse to short circuit-GMAW):</td>
        <td class="var-value">
            <select class="form-select" name="transfer_mode" id="transfer_mode"
                onchange="updateTransferModeRange()">
                <option value="spray" selected>Spray</option>
                <option value="globular">Globular</option>
                <option value="pulse">Pulse</option>
                <option value="short circuit">Short Circuit</option>
            </select>
        </td>
        <td class="var-range">
            <span id="transfer_mode_range_span">spray, globular, or pulsed Spray</span>
            <input type="hidden" name="transfer_mode_range" id="transfer_mode_range" value="spray, globular, or pulsed Spray">
        </td>
    </tr>
    <tr>
        <td class="var-label">GTAW current type and polarity (AC, DCEP, DCEN) For LBW or LLBW:</td>
        <td class="var-value">
          .....
        </td>
        <td class="var-range">
           ...
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
            ....
        </td>
        <td class="var-range">
          ...   </td>
    </tr>
    <tr>
        <td class="var-label">
            Torch/Gun/Beam oscillation
            <div class="checkbox-container" style="display: inline-block; margin-left: 10px;">
                <input type="radio" name="oscillation" id="oscillation_yes" value="yes" onchange="updateOscillationRange()">
                <label for="oscillation_yes">YES</label>
                <input type="radio" name="oscillation" id="oscillation_no" value="no" checked onchange="updateOscillationRange()">
                <label for="oscillation_no">NO</label>
            </div>
        </td>
        <td class="var-value">
            <input type="text" class="form-control" name="oscillation_value" id="oscillation_value" placeholder="Oscillation value" oninput="updateOscillationRange()">
        </td>
        <td class="var-range">
            <span id="oscillation_range_span">NO</span>
            <input type="hidden" name="oscillation_range" id="oscillation_range" value="NO">
        </td>
    </tr>
    <tr>
        <td class="var-label">Mode of operation (pulsed or continuous):</td>
        <td class="var-value">
            <select class="form-select" name="operation_mode" id="operation_mode" onchange="updateOperationModeRange()">
                <option value="Continuous" selected>Continuous</option>
                <option value="Pulsed">Pulsed</option>
            </select>
        </td>
        <td class="var-range">
            <span id="operation_mode_range_span">Continuous</span>
            <input type="hidden" name="operation_mode_range" id="operation_mode_range" value="Continuous">
        </td>
    </tr>
</table>
