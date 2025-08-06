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
                        value="{{ isset($certificate) ? $certificate->vt_report_no : ($vtReportNo ?? 'EEA-AIC-VT-0001') }}" readonly>
                </div>
            </td>
        </tr>

        <!-- Bend Tests Section -->
        <tr>
            <td class="var-label" colspan="1" style="width: 33.33%;">
                <div class="checkbox-container">
                    <input type="checkbox" name="transverse_face_root" id="transverse_face_root" {{ isset($certificate) && $certificate->transverse_face_root ? 'checked' : '' }}>
                    <label for="transverse_face_root">□ Transverse face and root bends
                        [QW-462.3(a)]</label>
                </div>
            </td>
            <td class="var-label" colspan="1" style="width: 33.33%;">
                <div class="checkbox-container">
                    <input type="checkbox" name="longitudinal_bends" id="longitudinal_bends" {{ isset($certificate) && $certificate->longitudinal_bends ? 'checked' : '' }}>
                    <label for="longitudinal_bends">□ Longitudinal bends [QW-462.3(b)]</label>
                </div>
            </td>
            <td class="var-label" colspan="2" style="width: 33.34%;">
                <div class="checkbox-container">
                    <input type="checkbox" name="side_bends" id="side_bends" {{ isset($certificate) && $certificate->side_bends ? 'checked' : '' }}>
                    <label for="side_bends">□ Side bends [QW-462.2]</label>
                </div>
            </td>
        </tr>

        <!-- Pipe and Plate Bend Specimens - Centered -->
        <tr>
            <td class="var-label" colspan="4" style="text-align: center;">
                <div class="checkbox-container" style="justify-content: center;">
                    <input type="checkbox" name="pipe_bend_corrosion" id="pipe_bend_corrosion" {{ isset($certificate) && $certificate->pipe_bend_corrosion ? 'checked' : '' }}>
                    <label for="pipe_bend_corrosion">□ Pipe bend specimen, corrosion-resistant weld metal
                        overlay [QW-462.5(c)]</label>
                </div>
            </td>
        </tr>

        <tr>
            <td class="var-label" colspan="4" style="text-align: center;">
                <div class="checkbox-container" style="justify-content: center;">
                    <input type="checkbox" name="plate_bend_corrosion" id="plate_bend_corrosion" {{ isset($certificate) && $certificate->plate_bend_corrosion ? 'checked' : '' }}>
                    <label for="plate_bend_corrosion">□ Plate bend specimen, corrosion-resistant weld metal
                        overlay [QW-462.5(c)]</label>
                </div>
            </td>
        </tr>

        <!-- Macro Tests -->
        <tr>
            <td class="var-label" colspan="2">
                <div class="checkbox-container">
                    <input type="checkbox" name="pipe_macro_fusion" id="pipe_macro_fusion" {{ isset($certificate) && $certificate->pipe_macro_fusion ? 'checked' : '' }}>
                    <label for="pipe_macro_fusion">□ Pipe specimen, macro test for fusion
                        [QW-462.5(c)]</label>
                </div>
            </td>
            <td class="var-label" colspan="2">
                <div class="checkbox-container">
                    <input type="checkbox" name="plate_macro_fusion" id="plate_macro_fusion" {{ isset($certificate) && $certificate->plate_macro_fusion ? 'checked' : '' }}>
                    <label for="plate_macro_fusion">□ Plate specimen, macro test for fusion
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
            <td class="var-value" style="text-align: center; font-weight: bold;">ACC</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="additional_type_1"
                    placeholder="................" value="{{ isset($certificate) ? $certificate->additional_type_1 : '' }}">
            </td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="additional_result_1"
                    placeholder="..........." value="{{ isset($certificate) ? $certificate->additional_result_1 : '' }}">
            </td>
        </tr>

        <!-- Alternative Volumetric Examination Results -->
        <tr>
            <td class="var-label">Alternative Volumetric Examination Results (QW-191):</td>
            <td class="var-value" style="text-align: center; font-weight: bold;">ACC</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="additional_type_2"
                    placeholder="................" value="{{ isset($certificate) ? $certificate->additional_type_2 : '' }}">
            </td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="additional_result_2"
                    placeholder="..........." value="{{ isset($certificate) ? $certificate->additional_result_2 : '' }}">
            </td>
        </tr>

        <!-- Alternative Volumetric Examination Results with report -->
        <tr>
            <td class="var-label">Alternative Volumetric Examination Results (QW-191):</td>
            <td class="var-value" style="text-align: center; font-weight: bold;">
                <div class="report-group">
                    <span>ACC (Doc No.</span>
                    <input type="text" class="form-input rt-report" name="rt_report_no" id="rt_report_no" 
                        value="{{ isset($certificate) ? $certificate->rt_report_no : ($rtReportNo ?? 'EEA-AIC-RT-0001') }}" readonly>
                    <span>)</span>
                </div>
                <div class="doc-group mt-1">
                    <span>Report No.#:</span>
                    <input type="text" class="form-input doc-number" name="rt_doc_no" id="rt_doc_no" 
                        placeholder="SO-xxxxxx" value="{{ isset($certificate) ? $certificate->rt_doc_no : '' }}">
                </div>
            </td>
            <td class="var-value" style="text-align: center;">
                <div class="checkbox-container">
                    <!-- Fixed RT checkbox - use a single hidden field -->
                    <input type="hidden" name="rt" value="0">
                    <input type="checkbox" name="rt" id="rt" value="1" {{ isset($certificate) && $certificate->rt ? 'checked' : '' }}>
                    <label for="rt">■ RT</label>
                </div>
            </td>
            <td class="var-value" style="text-align: center;">
                <div class="checkbox-container">
                    <!-- Fixed UT checkbox - use a single hidden field -->
                    <input type="hidden" name="ut" value="0">
                    <input type="checkbox" name="ut" id="ut" value="1" {{ isset($certificate) && $certificate->ut ? 'checked' : '' }}>
                    <label for="ut">□ UT</label>
                </div>
            </td>
        </tr>

        <!-- Remaining test rows -->
        <tr>
            <td class="var-label">Fillet weld-fracture test (QW-181.2):</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="fillet_fracture_test"
                    placeholder="................" value="{{ isset($certificate) ? $certificate->fillet_fracture_test : '' }}">
            </td>
            <td class="var-label">Length and percent of defects</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="defects_length"
                    placeholder="..........." value="{{ isset($certificate) ? $certificate->defects_length : '' }}">
            </td>
        </tr>

        <tr>
            <td class="var-label">
                <div class="checkbox-container">
                    <input type="checkbox" name="fillet_welds_plate" id="fillet_welds_plate" {{ isset($certificate) && $certificate->fillet_welds_plate ? 'checked' : '' }}>
                    <label for="fillet_welds_plate">□ Fillet welds in plate [QW-462.4(b)]</label>
                </div>
            </td>
            <td class="var-value"></td>
            <td class="var-label">
                <div class="checkbox-container">
                    <input type="checkbox" name="fillet_welds_pipe" id="fillet_welds_pipe" {{ isset($certificate) && $certificate->fillet_welds_pipe ? 'checked' : '' }}>
                    <label for="fillet_welds_pipe">□ Fillet welds in pipe [QW-462.4(c)]</label>
                </div>
            </td>
            <td class="var-value"></td>
        </tr>

        <tr>
            <td class="var-label">Macro examination (QW-184)</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="macro_exam"
                    placeholder="................" value="{{ isset($certificate) ? $certificate->macro_exam : '' }}">
            </td>
            <td class="var-label">Fillet size (in.)</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="fillet_size" placeholder="............" value="{{ isset($certificate) ? $certificate->fillet_size : '' }}">
            </td>
        </tr>

        <tr>
            <td class="var-label">Other tests</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="other_tests"
                    placeholder="................" value="{{ isset($certificate) ? $certificate->other_tests : '' }}">
            </td>
            <td class="var-label">Concavity or convexity (in.)</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="concavity_convexity"
                    placeholder="................" value="{{ isset($certificate) ? $certificate->concavity_convexity : '' }}">
            </td>
        </tr>
    </table>

    <!-- Personnel Information -->
    <table class="results-table">
        <tr>
            <td class="var-label">Film or specimens evaluated by</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="evaluated_by" value="{{ isset($certificate) ? $certificate->evaluated_by : 'Kalith Majeedh' }}" required>
            </td>
            <td class="var-label">Company</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="evaluated_company" value="{{ isset($certificate) ? $certificate->evaluated_company : 'SOGEC' }}" readonly>
            </td>
        </tr>
        <tr>
            <td class="var-label">Mechanical tests conducted by</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="mechanical_tests_by" value="{{ isset($certificate) ? $certificate->mechanical_tests_by : '' }}" >
            </td>
            <td class="var-label">Laboratory test no.</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="lab_test_no" placeholder="Enter lab test number" value="{{ isset($certificate) ? $certificate->lab_test_no : '' }}" >
            </td>
        </tr>
        <tr>
            <td class="var-label">Welding supervised by</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="supervised_by" value="{{ isset($certificate) ? $certificate->supervised_by : 'Ahmed Yousry' }}" >
            </td>
            <td class="var-label">Company</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="supervised_company" value="{{ isset($certificate) ? $certificate->supervised_company : (\App\Models\AppSetting::getValue('system_name', 'ELITE')) }}" readonly>
            </td>
        </tr>
    </table>

    <!-- Certification Statement - Now integrated into the results section -->
    <div style="width: 100%; box-sizing: border-box; padding: 10px; text-align: center; background-color: #f8f9fa; border-top: 1px solid #000;">
        <strong>We certify that the statements in this record are correct and that the test coupons were prepared, welded, and tested in accordance with the requirements of Section IX of the ASME BOILER AND PRESSURE VESSEL CODE.</strong>
        <span class="custom-certification-wrapper">
            <input type="text" name="certification_text" id="certification_text" class="custom-certification-input" 
                value="{{ isset($certificate) ? $certificate->certification_text : '' }}" 
                placeholder="Add custom certification text here " required>
        </span>
    </div>

    <div style="width: 100%; background: #f0f0f0; text-align: center; font-weight: bold; padding: 5px; border-top: 1px solid #000; border-bottom: 1px solid #000;">
        Confirmation of the validity by the employer/Welding coordinator for the following 6 month
    </div>

    <table style="width: 100%; table-layout: fixed; margin: 0; border-collapse: collapse;">
        <tr>
            <td style="width: 33.33%; background: #f8f9fa; font-weight: bold; text-align: center; height: 25px; border: 1px solid #000;">Date</td>
            <td style="width: 33.33%; background: #f8f9fa; font-weight: bold; text-align: center; height: 25px; border: 1px solid #000;">Signature</td>
            <td style="width: 33.33%; background: #f8f9fa; font-weight: bold; text-align: center; height: 25px; border: 1px solid #000;">Position or Title</td>
        </tr>
        <tr class="sig-row">
            <td style="border: 1px solid #000;"><input type="date" class="form-input" name="confirm_date1" style="width: 100%" value="{{ isset($certificate) ? $certificate->confirm_date1 : '' }}"></td>
            <td style="border: 1px solid #000;"></td>
            <td style="border: 1px solid #000;"><input type="text" class="form-input" name="confirm_title1" placeholder="Position" style="width: 100%" value="{{ isset($certificate) ? $certificate->confirm_title1 : '' }}">
            </td>
        </tr>
        <tr class="sig-row">
            <td style="border: 1px solid #000;"><input type="date" class="form-input" name="confirm_date2" style="width: 100%" value="{{ isset($certificate) ? $certificate->confirm_date2 : '' }}"></td>
            <td style="border: 1px solid #000;"></td>
            <td style="border: 1px solid #000;"><input type="text" class="form-input" name="confirm_title2" placeholder="Position" style="width: 100%" value="{{ isset($certificate) ? $certificate->confirm_title2 : '' }}">
            </td>
        </tr>
        <tr class="sig-row">
            <td style="border: 1px solid #000;"><input type="date" class="form-input" name="confirm_date3" style="width: 100%" value="{{ isset($certificate) ? $certificate->confirm_date3 : '' }}"></td>
            <td style="border: 1px solid #000;"></td>
            <td style="border: 1px solid #000;"><input type="text" class="form-input" name="confirm_title3" placeholder="Position" style="width: 100%" value="{{ isset($certificate) ? $certificate->confirm_title3 : '' }}">
            </td>
        </tr>
    </table>

    @if(isset($certificate))
        @include('gtaw_certificates.partials.signature-section', ['certificate' => $certificate])
    @else
        @include('gtaw_certificates.partials.signature-section')
    @endif

<style>
.custom-certification-wrapper {
    display: inline-block;
    margin-left: 5px;
}

.custom-certification-input {
    width: 100%;
    min-width: 200px;
    padding: 2px 5px;
    font-size: 9px;
    font-family: Arial, sans-serif;
    border: 1px solid #ddd;
    border-radius: 3px;
    background-color: #f8f9fa;
    transition: all 0.3s;
}

.custom-certification-input:focus {
    border-color: #007bff;
    background-color: #fff;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
}

.custom-certification-input::placeholder {
    color: #aaa;
    font-style: italic;
}

.official-stamp {
    max-width: 50px;
    max-height: 50px;
    object-fit: contain;
    opacity: 0.8;
}
</style>