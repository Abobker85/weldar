<!-- RESULTS Section -->
<div class="results-section">
    <table class="results-table">
        <tr>
            <td colspan="4" class="section-header">RESULTS</td>
        </tr>

        <!-- Visual examination row -->
        <tr>
            <td class="var-label" colspan="2">Visual examination of completed weld (QW-302.4)</td>
            <td class="var-value" style="text-align: center; font-weight: bold;" colspan="2">
                <div class="report-number mt-1">
                    <input type="text" class="form-input" name="vt_report_no" id="vt_report_no" 
                        value="{{ $certificate->vt_report_no }}" required>
                </div>
            </td>
        </tr>

        <!-- Bend Tests Section -->
        <tr>
            <td class="var-label" colspan="1" style="width: 33.33%;">
                <div class="checkbox-container">
                    <input type="checkbox" name="transverse_face_root" id="transverse_face_root" {{ $certificate->transverse_face_root ? 'checked' : '' }}>
                    <label for="transverse_face_root">{{ $certificate->transverse_face_root ? '■' : '□' }} Transverse face and root bends
                        [QW-462.3(a)]</label>
                </div>
            </td>
            <td class="var-label" colspan="1" style="width: 33.33%;">
                <div class="checkbox-container">
                    <input type="checkbox" name="longitudinal_bends" id="longitudinal_bends" {{ $certificate->longitudinal_bends ? 'checked' : '' }}>
                    <label for="longitudinal_bends">{{ $certificate->longitudinal_bends ? '■' : '□' }} Longitudinal bends [QW-462.3(b)]</label>
                </div>
            </td>
            <td class="var-label" colspan="2" style="width: 33.34%;">
                <div class="checkbox-container">
                    <input type="checkbox" name="side_bends" id="side_bends" {{ $certificate->side_bends ? 'checked' : '' }}>
                    <label for="side_bends">{{ $certificate->side_bends ? '■' : '□' }} Side bends [QW-462.2]</label>
                </div>
            </td>
        </tr>

        <!-- Pipe and Plate Bend Specimens - Centered -->
        <tr>
            <td class="var-label" colspan="4" style="text-align: center;">
                <div class="checkbox-container" style="justify-content: center;">
                    <input type="checkbox" name="pipe_bend_corrosion" id="pipe_bend_corrosion" {{ $certificate->pipe_bend_corrosion ? 'checked' : '' }}>
                    <label for="pipe_bend_corrosion">{{ $certificate->pipe_bend_corrosion ? '■' : '□' }} Pipe bend specimen, corrosion-resistant weld metal
                        overlay [QW-462.5(c)]</label>
                </div>
            </td>
        </tr>

        <tr>
            <td class="var-label" colspan="4" style="text-align: center;">
                <div class="checkbox-container" style="justify-content: center;">
                    <input type="checkbox" name="plate_bend_corrosion" id="plate_bend_corrosion" {{ $certificate->plate_bend_corrosion ? 'checked' : '' }}>
                    <label for="plate_bend_corrosion">{{ $certificate->plate_bend_corrosion ? '■' : '□' }} Plate bend specimen, corrosion-resistant weld metal
                        overlay [QW-462.5(c)]</label>
                </div>
            </td>
        </tr>

        <!-- Macro Tests -->
        <tr>
            <td class="var-label" colspan="2">
                <div class="checkbox-container">
                    <input type="checkbox" name="pipe_macro_fusion" id="pipe_macro_fusion" {{ $certificate->pipe_macro_fusion ? 'checked' : '' }}>
                    <label for="pipe_macro_fusion">{{ $certificate->pipe_macro_fusion ? '■' : '□' }} Pipe specimen, macro test for fusion
                        [QW-462.5(c)]</label>
                </div>
            </td>
            <td class="var-label" colspan="2">
                <div class="checkbox-container">
                    <input type="checkbox" name="plate_macro_fusion" id="plate_macro_fusion" {{ $certificate->plate_macro_fusion ? 'checked' : '' }}>
                    <label for="plate_macro_fusion">{{ $certificate->plate_macro_fusion ? '■' : '□' }} Plate specimen, macro test for fusion
                        [QW-462.5(c)]</label>
                </div>
            </td>
        </tr>

        <!-- TYPE/RESULT Headers -->
        <tr>
            <td class="test-header" style="width: 25%;">TYPE</td>
            <td class="test-header" style="width: 25%;">RESULT</td>
            <td class="test-header" style="width: 25%;">TYPE</td>
            <td class="test-header" style="width: 25%;">RESULT</td>
        </tr>

        <!-- Test rows -->
        <tr>
            <td class="var-label">Visual examination of completed weld (QW-302.4)</td>
            <td class="var-value" style="text-align: center; font-weight: bold;">
                <select class="form-select" name="visual_examination_result">
                    <option value="ACC" {{ ($certificate->visual_examination_result ?? '') == 'ACC' ? 'selected' : '' }}>ACC</option>
                    <option value="REJ" {{ ($certificate->visual_examination_result ?? '') == 'REJ' ? 'selected' : '' }}>REJ</option>
                </select>
            </td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="additional_type_1"
                    value="{{ $certificate->additional_type_1 ?? '' }}" placeholder="................">
            </td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="additional_result_1"
                    value="{{ $certificate->additional_result_1 ?? '' }}" placeholder="...........">
            </td>
        </tr>

        <!-- Radiography row -->
        <tr>
            <td class="var-label">Radiography of weld</td>
            <td class="var-value" style="text-align: center; font-weight: bold;">
                <select class="form-select" name="radiography_result">
                    <option value="ACC" {{ ($certificate->radiography_result ?? '') == 'ACC' ? 'selected' : '' }}>ACC</option>
                    <option value="REJ" {{ ($certificate->radiography_result ?? '') == 'REJ' ? 'selected' : '' }}>REJ</option>
                    <option value="N/A" {{ ($certificate->radiography_result ?? '') == 'N/A' ? 'selected' : '' }}>N/A</option>
                </select>
            </td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="additional_type_2"
                    value="{{ $certificate->additional_type_2 ?? '' }}" placeholder="................">
            </td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="additional_result_2"
                    value="{{ $certificate->additional_result_2 ?? '' }}" placeholder="...........">
            </td>
        </tr>
        
        <!-- RT Report No. Row -->
        <tr>
            <td class="var-label">RT Report No.</td>
            <td class="var-value" style="text-align: center; font-weight: bold;">
                <input type="text" class="form-input" name="rt_report_no" 
                    value="{{ $certificate->rt_report_no ?? '' }}" placeholder="RT report number">
            </td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="additional_type_3"
                    value="{{ $certificate->additional_type_3 ?? '' }}" placeholder="................">
            </td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="additional_result_3"
                    value="{{ $certificate->additional_result_3 ?? '' }}" placeholder="...........">
            </td>
        </tr>
        
        <!-- Additional rows for more test types and results if needed -->
        <tr>
            <td class="var-label">RT Film ID</td>
            <td class="var-value" style="text-align: center; font-weight: bold;">
                <input type="text" class="form-input" name="rt_film_id" 
                    value="{{ $certificate->rt_film_id ?? '' }}" placeholder="RT film ID">
            </td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="additional_type_4"
                    value="{{ $certificate->additional_type_4 ?? '' }}" placeholder="................">
            </td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="additional_result_4"
                    value="{{ $certificate->additional_result_4 ?? '' }}" placeholder="...........">
            </td>
        </tr>
    </table>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Set up the checkbox display toggle
    const checkboxes = document.querySelectorAll('input[type="checkbox"]');
    
    checkboxes.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const label = this.nextElementSibling;
            const symbol = this.checked ? '■' : '□';
            label.innerHTML = symbol + ' ' + label.innerHTML.substring(2);
        });
    });
});
</script>
