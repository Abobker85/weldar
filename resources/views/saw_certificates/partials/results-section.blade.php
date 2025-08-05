<!-- RESULTS Section -->
<div class="results-section">
    <table class="results-table">
        <tr>
            <td colspan="4" class="section-header">RESULTS</td>
        </tr>

        <!-- Visual examination row -->
        <tr>
            <td class="var-label" colspan="4">
                <strong>Visual examination of completed weld (QW-302.4)</strong>
                <div style="margin-top: 5px;">
                    <select class="form-input" name="visual_examination_result" style="display: inline-block; width: 100px;">
                        <option value="Accepted" {{ old('visual_examination_result', $certificate->visual_examination_result ?? 'Accepted') == 'Accepted' ? 'selected' : '' }}>Accepted</option>
                        <option value="ACC" {{ old('visual_examination_result', $certificate->visual_examination_result ?? '') == 'ACC' ? 'selected' : '' }}>ACC</option>
                        <option value="Rejected" {{ old('visual_examination_result', $certificate->visual_examination_result ?? '') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                    </select>
                    <span style="margin-left: 10px;">see Report No.</span>
                    <input type="text" class="form-input" name="vt_report_no" 
                        value="{{ old('vt_report_no', $certificate->vt_report_no ?? $vtReportNo ?? '') }}" 
                        style="display: inline-block; width: 150px;" placeholder="EEA-AIC-VT-0488">
                </div>
            </td>
        </tr>

        <!-- Bend Tests Section Headers -->
        <tr>
            <td class="var-label" style="width: 33.33%;">
                <div class="checkbox-container">
                    <input type="checkbox" name="transverse_face_root_bends" id="transverse_face_root_bends" 
                        {{ old('transverse_face_root_bends', $certificate->transverse_face_root_bends ?? false) ? 'checked' : '' }}>
                    <label for="transverse_face_root_bends">□ Transverse face and root bends [QW-462.3(a)]</label>
                </div>
            </td>
            <td class="var-label" style="width: 33.33%;">
                <div class="checkbox-container">
                    <input type="checkbox" name="longitudinal_bends" id="longitudinal_bends" 
                        {{ old('longitudinal_bends', $certificate->longitudinal_bends ?? false) ? 'checked' : '' }}>
                    <label for="longitudinal_bends">□ Longitudinal bends [QW-462.3(b)]</label>
                </div>
            </td>
            <td class="var-label" style="width: 33.34%;">
                <div class="checkbox-container">
                    <input type="checkbox" name="side_bends" id="side_bends" 
                        {{ old('side_bends', $certificate->side_bends ?? false) ? 'checked' : '' }}>
                    <label for="side_bends">□ Side bends (QW-462.2)</label>
                </div>
            </td>
        </tr>

        <!-- Corrosion Resistant Tests -->
        <tr>
            <td class="var-label" colspan="4">
                <div class="checkbox-container">
                    <input type="checkbox" name="pipe_bend_corrosion" id="pipe_bend_corrosion" 
                        {{ old('pipe_bend_corrosion', $certificate->pipe_bend_corrosion ?? false) ? 'checked' : '' }}>
                    <label for="pipe_bend_corrosion">□ Pipe bend specimen, corrosion-resistant weld metal overlay [QW-462.5(c)]</label>
                </div>
            </td>
        </tr>

        <tr>
            <td class="var-label" colspan="4">
                <div class="checkbox-container">
                    <input type="checkbox" name="plate_bend_corrosion" id="plate_bend_corrosion" 
                        {{ old('plate_bend_corrosion', $certificate->plate_bend_corrosion ?? false) ? 'checked' : '' }}>
                    <label for="plate_bend_corrosion">□ Plate bend specimen, corrosion-resistant weld metal overlay [QW-462.5(d)]</label>
                </div>
            </td>
        </tr>

        <!-- Macro Tests -->
        <tr>
            <td class="var-label" colspan="2">
                <div class="checkbox-container">
                    <input type="checkbox" name="pipe_macro_fusion" id="pipe_macro_fusion" 
                        {{ old('pipe_macro_fusion', $certificate->pipe_macro_fusion ?? false) ? 'checked' : '' }}>
                    <label for="pipe_macro_fusion">□ Pipe specimen, macro test for fusion [QW-462.5(b)]</label>
                </div>
            </td>
            <td class="var-label" colspan="2">
                <div class="checkbox-container">
                    <input type="checkbox" name="plate_macro_fusion" id="plate_macro_fusion" 
                        {{ old('plate_macro_fusion', $certificate->plate_macro_fusion ?? false) ? 'checked' : '' }}>
                    <label for="plate_macro_fusion">□ Plate specimen, macro test for fusion [QW-462.5(e)]</label>
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

        <!-- Test Results Rows -->
        <tr>
            <td class="var-label">Visual examination of completed weld (QW-302.4)</td>
            <td class="var-value" style="text-align: center; font-weight: bold;">ACC</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="additional_type_1"
                    placeholder="................" value="{{ old('additional_type_1', $certificate->additional_type_1 ?? '') }}">
            </td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="additional_result_1"
                    placeholder="..........." value="{{ old('additional_result_1', $certificate->additional_result_1 ?? '') }}">
            </td>
        </tr>

        <tr>
            <td class="var-label">
                <input type="text" class="form-input" name="test_type_2"
                    placeholder="…..........." value="{{ old('test_type_2', $certificate->test_type_2 ?? '') }}">
            </td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="test_result_2"
                    placeholder="….........." value="{{ old('test_result_2', $certificate->test_result_2 ?? '') }}">
            </td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="additional_type_2"
                    placeholder="................" value="{{ old('additional_type_2', $certificate->additional_type_2 ?? '') }}">
            </td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="additional_result_2"
                    placeholder="..........." value="{{ old('additional_result_2', $certificate->additional_result_2 ?? '') }}">
            </td>
        </tr>

        <!-- Alternative Volumetric Examination Results -->
        <tr>
            <td class="var-label">Alternative Volumetric Examination Results (QW-191):</td>
            <td class="var-value" style="text-align: center; font-weight: bold;">
                <div style="display: flex; flex-direction: column; gap: 5px;">
                    <div>
                        <select class="form-input" name="alternative_volumetric_result" style="width: 80px;">
                            <option value="ACC" {{ old('alternative_volumetric_result', $certificate->alternative_volumetric_result ?? 'ACC') == 'ACC' ? 'selected' : '' }}>ACC</option>
                            <option value="REJ" {{ old('alternative_volumetric_result', $certificate->alternative_volumetric_result ?? '') == 'REJ' ? 'selected' : '' }}>REJ</option>
                        </select>
                    </div>
                    <div style="font-size: 8px;">
                        (Report No.
                        <input type="text" class="form-input" name="rt_report_no" 
                            value="{{ old('rt_report_no', $certificate->rt_report_no ?? $rtReportNo ?? '') }}" 
                            style="width: 120px; display: inline;" placeholder="EEA-AIC-RT-0488">
                    </div>
                    <div style="font-size: 8px;">
                        Doc No.#: 
                        <input type="text" class="form-input" name="rt_doc_no" 
                            value="{{ old('rt_doc_no', $certificate->rt_doc_no ?? '') }}" 
                            style="width: 80px; display: inline;" placeholder="SO-629764">)
                    </div>
                </div>
            </td>
            <td class="var-value" style="text-align: center;">
                <div class="checkbox-container">
                    <input type="checkbox" name="rt_selected" id="rt_selected" 
                        {{ old('rt_selected', $certificate->rt_selected ?? false) ? 'checked' : '' }}>
                    <label for="rt_selected">■ RT</label>
                </div>
            </td>
            <td class="var-value" style="text-align: center;">
                <div class="checkbox-container">
                    <input type="checkbox" name="ut_selected" id="ut_selected" 
                        {{ old('ut_selected', $certificate->ut_selected ?? false) ? 'checked' : '' }}>
                    <label for="ut_selected">□ UT</label>
                </div>
            </td>
        </tr>

        <!-- Additional test rows -->
        <tr>
            <td class="var-label">Fillet weld-fracture test (QW-181.2):</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="fillet_fracture_test"
                    placeholder="…..........." value="{{ old('fillet_fracture_test', $certificate->fillet_fracture_test ?? '') }}">
            </td>
            <td class="var-label">Length and percent of defects</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="defects_length_percent"
                    placeholder="…........." value="{{ old('defects_length_percent', $certificate->defects_length_percent ?? '') }}">
            </td>
        </tr>

        <tr>
            <td class="var-label">
                <div class="checkbox-container">
                    <input type="checkbox" name="fillet_welds_plate" id="fillet_welds_plate" 
                        {{ old('fillet_welds_plate', $certificate->fillet_welds_plate ?? false) ? 'checked' : '' }}>
                    <label for="fillet_welds_plate">□ Fillet welds in plate [QW-462.4(b)]</label>
                </div>
            </td>
            <td class="var-value"></td>
            <td class="var-label">
                <div class="checkbox-container">
                    <input type="checkbox" name="fillet_welds_pipe" id="fillet_welds_pipe" 
                        {{ old('fillet_welds_pipe', $certificate->fillet_welds_pipe ?? false) ? 'checked' : '' }}>
                    <label for="fillet_welds_pipe">□ Fillet welds in pipe [QW-462.4(c)]</label>
                </div>
            </td>
            <td class="var-value"></td>
        </tr>

        <tr>
            <td class="var-label">Macro examination (QW-184)</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="macro_examination"
                    placeholder="…..............." value="{{ old('macro_examination', $certificate->macro_examination ?? '') }}">
            </td>
            <td class="var-label">Fillet size (in.)</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="fillet_size" 
                    placeholder="….........." value="{{ old('fillet_size', $certificate->fillet_size ?? '') }}">
            </td>
        </tr>

        <tr>
            <td class="var-label">Other tests</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="other_tests"
                    placeholder="…............" value="{{ old('other_tests', $certificate->other_tests ?? '') }}">
            </td>
            <td class="var-label">Concavity or convexity (in.)</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="concavity_convexity"
                    placeholder="…............" value="{{ old('concavity_convexity', $certificate->concavity_convexity ?? '') }}">
            </td>
        </tr>
    </table>

    <!-- Personnel Information -->
    <table class="results-table">
        <tr>
            <td class="var-label">Film or specimens evaluated by</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="film_evaluated_by" 
                    value="{{ old('film_evaluated_by', $certificate->film_evaluated_by ?? 'Kalith Majeeth') }}" 
                    placeholder="Kalith Majeeth">
            </td>
            <td class="var-label">Company</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="evaluated_company" 
                    value="{{ old('evaluated_company', $certificate->evaluated_company ?? '') }}" 
                    placeholder="Company Name">
            </td>
        </tr>
        <tr>
            <td class="var-label">Mechanical tests conducted by</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="mechanical_tests_by" 
                    value="{{ old('mechanical_tests_by', $certificate->mechanical_tests_by ?? '') }}" 
                    placeholder="…..........">
            </td>
            <td class="var-label">Laboratory test no.</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="lab_test_no" 
                    value="{{ old('lab_test_no', $certificate->lab_test_no ?? '') }}" 
                    placeholder="…....">
            </td>
        </tr>
        <tr>
            <td class="var-label">Welding supervised by</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="welding_supervised_by" 
                    value="{{ old('welding_supervised_by', $certificate->welding_supervised_by ?? 'Ahmed Yousry') }}" 
                    placeholder="Ahmed Yousry" required>
            </td>
            <td class="var-label">Company</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="supervised_company" 
                    value="{{ old('supervised_company', $certificate->supervised_company ?? '') }}" 
                    placeholder="Company Name">
            </td>
        </tr>
    </table>
</div>