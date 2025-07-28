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
                    onchange="updatePositionRange()" data-saved-value="{{ $certificate->test_position }}">
                    <option value="" disabled {{ empty($certificate->test_position) ? 'selected' : '' }}>-- Select Position --</option>
                    <option value="6G" {{ $certificate->test_position == '6G' ? 'selected' : '' }}>6G</option>
                    <option value="5G" {{ $certificate->test_position == '5G' ? 'selected' : '' }}>5G</option>
                    <option value="2G" {{ $certificate->test_position == '2G' ? 'selected' : '' }}>2G</option>
                    <option value="1G" {{ $certificate->test_position == '1G' ? 'selected' : '' }}>1G</option>
                    <option value="3G" {{ $certificate->test_position == '3G' ? 'selected' : '' }}>3G</option>
                    <option value="4G" {{ $certificate->test_position == '4G' ? 'selected' : '' }}>4G</option>
                </select>
            </div>
        </td>
        <td class="var-range position-range-cell" data-range-row="1" style="font-weight: bold; font-size: 8px;">
            Groove Plate and Pipe Over 24 in. (610 mm) O.D. in all Position
        </td>
    </tr>
    <tr>
        <td class="var-value"></td>
        <td class="var-range position-range-cell" data-range-row="2" style="font-weight: bold; font-size: 8px;">
            Groove Pipe ≤24 in. (610 mm) O.D. in all Position
        </td>
    </tr>
    <tr>
        <td class="var-value"></td>
        <td class="var-range position-range-cell" data-range-row="3" style="font-weight: bold; font-size: 8px;">
            Fillet or Tack Plate and Pipe in all Position
        </td>
    </tr>
    <!-- Hidden position range input - visible span moved to position cells -->
    <input type="hidden" name="position_range" id="position_range" value="{{ $certificate->position_range }}">
   <tr>
        <td class="var-label">Vertical progression (uphill or downhill) :</td>
        <td class="var-value">
            <select class="form-select" name="vertical_progression" id="vertical_progression" onchange="updateVerticalProgressionRange()">
                <option value="None" {{ $certificate->vertical_progression == 'None' ? 'selected' : '' }}>None</option>
                <option value="Uphill" {{ $certificate->vertical_progression == 'Uphill' ? 'selected' : '' }}>Uphill</option>
                <option value="Downhill" {{ $certificate->vertical_progression == 'Downhill' ? 'selected' : '' }}>Downhill</option>
            </select>
        </td>
        <td class="var-range">
            <span id="vertical_progression_range_span">{{ $certificate->vertical_progression_range }}</span>
            <input type="hidden" name="vertical_progression_range" id="vertical_progression_range" value="{{ $certificate->vertical_progression_range }}">
        </td>
    </tr>
    <!-- Remaining position qualification rows -->
    <tr>
        <td class="var-label">Type of fuel gas (OFW):</td>
        <td class="var-value">
            <input type="text" class="form-input" name="fuel_gas_type" value="{{ $certificate->fuel_gas_type ?? '' }}">
        </td>
        <td class="var-range">
            <span> ..... </span>
        </td>
    </tr>
    <tr>
        <td class="var-label">Use of backing gas (GTAW, PAW, GMAW, LBW):</td>
        <td class="var-value">
            <select class="form-select" name="backing_gas" id="backing_gas" onchange="updateBackingGasRange()">
                <option value="" {{ empty($certificate->backing_gas) ? 'selected' : '' }} disabled>Select</option>
                <option value="With backing Gas" {{ ($certificate->backing_gas ?? '') == 'With backing Gas' ? 'selected' : '' }}>With backing Gas</option>
                <option value="Without backing Gas" {{ ($certificate->backing_gas ?? '') == 'Without backing Gas' ? 'selected' : '' }}>Without backing Gas</option>
            </select>
        </td>
        <td class="var-range">
            <span id="backing_gas_range_span">{{ $certificate->backing_gas_range ?? '' }}</span>
            <input type="hidden" name="backing_gas_range" id="backing_gas_range" value="{{ $certificate->backing_gas_range ?? '' }}">
        </td>
    </tr>
    <tr>
        <td class="var-label">Transfer mode (spray, globular, or pulse to short circuit-GMAW):</td>
        <td class="var-value">
            <select class="form-select" name="transfer_mode" id="transfer_mode" onchange="updateTransferModeRange()">
                <option value="spray" {{ ($certificate->transfer_mode ?? '') == 'spray' ? 'selected' : '' }}>Spray</option>
                <option value="globular" {{ ($certificate->transfer_mode ?? '') == 'globular' ? 'selected' : '' }}>Globular</option>
                <option value="pulse" {{ ($certificate->transfer_mode ?? '') == 'pulse' ? 'selected' : '' }}>Pulse</option>
                <option value="short circuit" {{ ($certificate->transfer_mode ?? '') == 'short circuit' ? 'selected' : '' }}>Short Circuit</option>
            </select>
        </td>
        <td class="var-range">
            <span id="transfer_mode_range_span">{{ $certificate->transfer_mode_range ?? 'spray, globular, or pulsed Spray' }}</span>
            <input type="hidden" name="transfer_mode_range" id="transfer_mode_range" value="{{ $certificate->transfer_mode_range ?? 'spray, globular, or pulsed Spray' }}">
        </td>
    </tr>
    <tr>
        <td class="var-label">GTAW current type and polarity (AC, DCEP, DCEN) For LBW or LLBW:</td>
        <td class="var-value">
            <input type="text" class="form-input" name="current_type" value="{{ $certificate->current_type ?? '' }}">
        </td>
        <td class="var-range">
            <span> ..... </span>
        </td>
    </tr>
    <tr>
        <td class="var-label">Type of equipment</td>
        <td class="var-value">
            <input type="text" class="form-input" name="equipment_type" value="{{ $certificate->equipment_type ?? '' }}">
        </td>
        <td class="var-range">
            <span> ..... </span>
        </td>
    </tr>
    <tr>
        <td class="var-label">Technique (keyhole LBW or melt-in)</td>
        <td class="var-value">
            <input type="text" class="form-input" name="technique" value="{{ $certificate->technique ?? '' }}">
        </td>
        <td class="var-range">
            <span> ..... </span>
        </td>
    </tr>
    <tr>
        <td class="var-label">
            Torch/Gun/Beam oscillation
            <div class="checkbox-container" style="display: inline-block; margin-left: 10px;">
                <input type="radio" name="oscillation" id="oscillation_yes" value="yes" {{ ($certificate->oscillation ?? '') == 'yes' ? 'checked' : '' }} onchange="updateOscillationRange()">
                <label for="oscillation_yes">YES</label>
                <input type="radio" name="oscillation" id="oscillation_no" value="no" {{ ($certificate->oscillation ?? 'no') == 'no' ? 'checked' : '' }} onchange="updateOscillationRange()">
                <label for="oscillation_no">NO</label>
            </div>
        </td>
        <td class="var-value">
            <input type="text" class="form-control" name="oscillation_value" id="oscillation_value" value="{{ $certificate->oscillation_value ?? '' }}" placeholder="Oscillation value" oninput="updateOscillationRange()">
        </td>
        <td class="var-range">
            <span id="oscillation_range_span">{{ $certificate->oscillation_range ?? 'NO' }}</span>
            <input type="hidden" name="oscillation_range" id="oscillation_range" value="{{ $certificate->oscillation_range ?? 'NO' }}">
        </td>
    </tr>
    <tr>
        <td class="var-label">Mode of operation (pulsed or continuous):</td>
        <td class="var-value">
            <select class="form-select" name="operation_mode" id="operation_mode" onchange="updateOperationModeRange()">
                <option value="Continuous" {{ ($certificate->operation_mode ?? 'Continuous') == 'Continuous' ? 'selected' : '' }}>Continuous</option>
                <option value="Pulsed" {{ ($certificate->operation_mode ?? '') == 'Pulsed' ? 'selected' : '' }}>Pulsed</option>
            </select>
        </td>
        <td class="var-range">
            <span id="operation_mode_range_span">{{ $certificate->operation_mode_range ?? 'Continuous' }}</span>
            <input type="hidden" name="operation_mode_range" id="operation_mode_range" value="{{ $certificate->operation_mode_range ?? 'Continuous' }}">
        </td>
    </tr>
</table>

<script>
/**
 * Update position range hidden field based on selected test position
 */
function updatePositionRange() {
    const positionSelect = document.getElementById('test_position');
    const positionRangeInput = document.getElementById('position_range');
    const positionRangeSpan = document.getElementById('position_range_span');
    const plateCheckbox = document.getElementById('plate_specimen');
    const pipeCheckbox = document.getElementById('pipe_specimen');
    
    // Determine if this is a pipe or plate specimen
    const isPipe = pipeCheckbox?.checked || false;
    const isPlate = plateCheckbox?.checked || false;
    console.log('Position update - Specimen type:', isPipe ? 'Pipe' : (isPlate ? 'Plate' : 'Unknown'));
    
    if (positionSelect && positionRangeInput) {
        let rangeText = '';
        // Define the text for each cell based on position and specimen type
        const rangeCells = {
            row1: '',
            row2: '',
            row3: ''
        };
        
        if (isPipe) {
            switch(positionSelect.value) {
                case '6G':
                    rangeText = 'All Position Groove Plate and Pipe Over 24 in. (610 mm) O.D. | All Position Groove Pipe ≤24 in. (610 mm) O.D. | All Position Fillet or Tack Plate and Pipe';
                    rangeCells.row1 = 'Groove Plate and Pipe Over 24 in. (610 mm) O.D. in all Position';
                    rangeCells.row2 = 'Groove Pipe ≤24 in. (610 mm) O.D. in all Position';
                    rangeCells.row3 = 'Fillet or Tack Plate and Pipe in all Position';
                    break;
                case '5G':
                    rangeText = '1G, 5G Groove Pipe | All Position Fillet Pipe';
                    rangeCells.row1 = '1G, 5G Groove Pipe';
                    rangeCells.row2 = 'All Position Fillet Pipe except 6G';
                    rangeCells.row3 = '';
                    break;
                case '2G':
                    rangeText = '1G, 2G Groove Pipe | All Position Fillet Pipe';
                    rangeCells.row1 = '1G, 2G Groove Pipe';
                    rangeCells.row2 = 'All Position Fillet Pipe except 6G';
                    rangeCells.row3 = '';
                    break;
                case '1G':
                    rangeText = '1G Groove Pipe | All Position Fillet Pipe';
                    rangeCells.row1 = '1G Groove Pipe';
                    rangeCells.row2 = '1F Fillet Pipe';
                    rangeCells.row3 = '';
                    break;
                default:
                    rangeText = 'Position range not defined';
            }
        } else if (isPlate) {
            switch(positionSelect.value) {
                case '1G':
                    rangeText = '1G Groove Plate | 1F Fillet Plate';
                    rangeCells.row1 = '1G Groove Plate';
                    rangeCells.row2 = '1F Fillet Plate';
                    rangeCells.row3 = '';
                    break;
                case '2G':
                    rangeText = '1G, 2G Groove Plate | 1F, 2F Fillet Plate';
                    rangeCells.row1 = '1G, 2G Groove Plate';
                    rangeCells.row2 = '1F, 2F Fillet Plate';
                    rangeCells.row3 = '';
                    break;
                case '3G':
                    rangeText = '1G, 3G Groove Plate | 1F, 2F, 3F Fillet Plate';
                    rangeCells.row1 = '1G, 3G Groove Plate';
                    rangeCells.row2 = '1F, 2F, 3F Fillet Plate';
                    rangeCells.row3 = '';
                    break;
                case '4G':
                    rangeText = '1G, 4G Groove Plate | 1F, 2F, 4F Fillet Plate';
                    rangeCells.row1 = '1G, 4G Groove Plate';
                    rangeCells.row2 = '1F, 2F, 4F Fillet Plate';
                    rangeCells.row3 = '';
                    break;
                default:
                    rangeText = 'Position range not defined';
            }
        } else {
            rangeText = 'Please select a specimen type (pipe or plate)';
            console.warn('Neither pipe nor plate specimen type selected');
        }
        
        // Update the hidden input value only
        positionRangeInput.value = rangeText;
        
        // Update the position range cells - these now display the range information directly
        document.querySelectorAll('.position-range-cell').forEach(cell => {
            const row = cell.getAttribute('data-range-row');
            if (row === '1') cell.textContent = rangeCells.row1 || '';
            if (row === '2') cell.textContent = rangeCells.row2 || '';
            if (row === '3') cell.textContent = rangeCells.row3 || '';
        });
        
        console.log('Position range updated:', { 
            position: positionSelect.value, 
            rangeText, 
            cells: rangeCells 
        });
    }
}

/**
 * Update vertical progression range hidden field
 */
function updateVerticalProgressionRange() {
    const progressionSelect = document.getElementById('vertical_progression');
    const progressionRangeInput = document.getElementById('vertical_progression_range');
    const progressionRangeSpan = document.getElementById('vertical_progression_range_span');
    
    if (progressionSelect && progressionRangeInput) {
        progressionRangeInput.value = progressionSelect.value;
        if (progressionRangeSpan) {
            progressionRangeSpan.textContent = progressionSelect.value;
        }
    }
}

/**
 * Update backing gas range hidden field
 */
function updateBackingGasRange() {
    const backingGasSelect = document.getElementById('backing_gas');
    const backingGasRangeInput = document.getElementById('backing_gas_range');
    const backingGasRangeSpan = document.getElementById('backing_gas_range_span');
    
    if (backingGasSelect && backingGasRangeInput) {
        backingGasRangeInput.value = backingGasSelect.value;
        if (backingGasRangeSpan) {
            backingGasRangeSpan.textContent = backingGasSelect.value;
        }
    }
}

/**
 * Update transfer mode range hidden field
 */
function updateTransferModeRange() {
    const transferModeSelect = document.getElementById('transfer_mode');
    const transferModeRangeInput = document.getElementById('transfer_mode_range');
    const transferModeRangeSpan = document.getElementById('transfer_mode_range_span');
    
    if (transferModeSelect && transferModeRangeInput) {
        let rangeText = '';
        
        switch(transferModeSelect.value) {
            case 'spray':
                rangeText = 'spray, globular, or pulsed Spray';
                break;
            case 'globular':
                rangeText = 'globular';
                break;
            case 'pulse':
                rangeText = 'pulse';
                break;
            case 'short circuit':
                rangeText = 'short circuit';
                break;
            default:
                rangeText = transferModeSelect.value;
        }
        
        transferModeRangeInput.value = rangeText;
        if (transferModeRangeSpan) {
            transferModeRangeSpan.textContent = rangeText;
        }
    }
}

/**
 * Update oscillation range hidden field
 */
function updateOscillationRange() {
    const oscillationYesRadio = document.getElementById('oscillation_yes');
    const oscillationNoRadio = document.getElementById('oscillation_no');
    const oscillationValueInput = document.getElementById('oscillation_value');
    const oscillationRangeInput = document.getElementById('oscillation_range');
    const oscillationRangeSpan = document.getElementById('oscillation_range_span');
    
    if (oscillationRangeInput && oscillationRangeSpan) {
        let rangeText = 'NO';
        
        if (oscillationYesRadio && oscillationYesRadio.checked) {
            rangeText = 'YES';
            if (oscillationValueInput && oscillationValueInput.value) {
                rangeText += ' - ' + oscillationValueInput.value;
            }
        }
        
        oscillationRangeInput.value = rangeText;
        oscillationRangeSpan.textContent = rangeText;
    }
}

/**
 * Update operation mode range hidden field
 */
function updateOperationModeRange() {
    const operationModeSelect = document.getElementById('operation_mode');
    const operationModeRangeInput = document.getElementById('operation_mode_range');
    const operationModeRangeSpan = document.getElementById('operation_mode_range_span');
    
    if (operationModeSelect && operationModeRangeInput) {
        operationModeRangeInput.value = operationModeSelect.value;
        if (operationModeRangeSpan) {
            operationModeRangeSpan.textContent = operationModeSelect.value;
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize all range values when the page loads
    updatePositionRange();
    updateVerticalProgressionRange();
    updateBackingGasRange();
    updateTransferModeRange();
    updateOscillationRange();
    updateOperationModeRange();
    
    // Add event listeners for specimen type changes
    const plateCheckbox = document.getElementById('plate_specimen');
    const pipeCheckbox = document.getElementById('pipe_specimen');
    
    if (plateCheckbox) {
        plateCheckbox.addEventListener('change', updatePositionRange);
    }
    
    if (pipeCheckbox) {
        pipeCheckbox.addEventListener('change', updatePositionRange);
    }
    
    // Initialize position range cells
    setTimeout(updatePositionRange, 300);
});
</script>
