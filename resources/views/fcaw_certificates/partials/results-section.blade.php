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
                        value="{{ old('vt_report_no', isset($certificate) ? $certificate->vt_report_no : '') }}" readonly>
                </div>
            </td>
        </tr>

        <!-- Bend Tests Section -->
        <tr>
            <td class="var-label" colspan="1" style="width: 33.33%;">
                <div class="checkbox-container">
                    <input type="checkbox" name="transverse_face_root" id="transverse_face_root" {{ old('transverse_face_root', isset($certificate) ? $certificate->transverse_face_root : '') ? 'checked' : '' }}>
                    <label for="transverse_face_root">□ Transverse face and root bends
                        [QW-462.3(a)]</label>
                </div>
            </td>
            <td class="var-label" colspan="1" style="width: 33.33%;">
                <div class="checkbox-container">
                    <input type="checkbox" name="longitudinal_bends" id="longitudinal_bends" {{ old('longitudinal_bends', isset($certificate) ? $certificate->longitudinal_bends : '') ? 'checked' : '' }}>
                    <label for="longitudinal_bends">□ Longitudinal bends [QW-462.3(b)]</label>
                </div>
            </td>
            <td class="var-label" colspan="2" style="width: 33.34%;">
                <div class="checkbox-container">
                    <input type="checkbox" name="side_bends" id="side_bends" {{ old('side_bends', isset($certificate) ? $certificate->side_bends : '') ? 'checked' : '' }}>
                    <label for="side_bends">□ Side bends [QW-462.2]</label>
                </div>
            </td>
        </tr>

        <!-- Pipe and Plate Bend Specimens - Centered -->
        <tr>
            <td class="var-label" colspan="4" style="text-align: center;">
                <div class="checkbox-container" style="justify-content: center;">
                    <input type="checkbox" name="pipe_bend_corrosion" id="pipe_bend_corrosion" {{ old('pipe_bend_corrosion', isset($certificate) ? $certificate->pipe_bend_corrosion : '') ? 'checked' : '' }}>
                    <label for="pipe_bend_corrosion">□ Pipe bend specimen, corrosion-resistant weld metal
                        overlay [QW-462.5(c)]</label>
                </div>
            </td>
        </tr>

        <tr>
            <td class="var-label" colspan="4" style="text-align: center;">
                <div class="checkbox-container" style="justify-content: center;">
                    <input type="checkbox" name="plate_bend_corrosion" id="plate_bend_corrosion" {{ old('plate_bend_corrosion', isset($certificate) ? $certificate->plate_bend_corrosion : '') ? 'checked' : '' }}>
                    <label for="plate_bend_corrosion">□ Plate bend specimen, corrosion-resistant weld metal
                        overlay [QW-462.5(c)]</label>
                </div>
            </td>
        </tr>

        <!-- Macro Tests -->
        <tr>
            <td class="var-label" colspan="2">
                <div class="checkbox-container">
                    <input type="checkbox" name="pipe_macro_fusion" id="pipe_macro_fusion" {{ old('pipe_macro_fusion', isset($certificate) ? $certificate->pipe_macro_fusion : '') ? 'checked' : '' }}>
                    <label for="pipe_macro_fusion">□ Pipe specimen, macro test for fusion
                        [QW-462.5(c)]</label>
                </div>
            </td>
            <td class="var-label" colspan="2">
                <div class="checkbox-container">
                    <input type="checkbox" name="plate_macro_fusion" id="plate_macro_fusion" {{ old('plate_macro_fusion', isset($certificate) ? $certificate->plate_macro_fusion : '') ? 'checked' : '' }}>
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
                    placeholder="................" value="{{ old('additional_type_1', isset($certificate) ? $certificate->additional_type_1 : '') }}">
            </td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="additional_result_1"
                    placeholder="..........." value="{{ old('additional_result_1', isset($certificate) ? $certificate->additional_result_1 : '') }}">
            </td>
        </tr>

        <!-- Alternative Volumetric Examination Results -->
        <tr>
            <td class="var-label">Alternative Volumetric Examination Results (QW-191):</td>
            <td class="var-value" style="text-align: center; font-weight: bold;">ACC</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="additional_type_2"
                    placeholder="................" value="{{ old('additional_type_2', isset($certificate) ? $certificate->additional_type_2 : '') }}">
            </td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="additional_result_2"
                    placeholder="..........." value="{{ old('additional_result_2', isset($certificate) ? $certificate->additional_result_2 : '') }}">
            </td>
        </tr>

        <!-- Alternative Volumetric Examination Results with report -->
        <tr>
            <td class="var-label">Alternative Volumetric Examination Results (QW-191):</td>
            <td class="var-value" style="text-align: center; font-weight: bold;">
                <div class="report-group">
                    <span>ACC (Doc No.</span>
                    <input type="text" class="form-input rt-report" name="rt_report_no" id="rt_report_no" 
                        value="{{ old('rt_report_no', isset($certificate) ? $certificate->rt_report_no : '') }}" readonly>
                    <span>)</span>
                </div>
                <div class="doc-group mt-1">
                    <span>Report No.#:</span>
                    <input type="text" class="form-input doc-number" name="rt_doc_no" id="rt_doc_no" 
                        placeholder="SO-xxxxxx" value="{{ old('rt_doc_no', isset($certificate) ? $certificate->rt_doc_no : '') }}">
                </div>
            </td>
            <td class="var-value" style="text-align: center;">
                <div class="checkbox-container">
                    <!-- Fixed RT checkbox - use a single hidden field -->
                    <input type="hidden" name="rt" value="0">
                    <input type="checkbox" name="rt" id="rt" value="1" {{ old('rt', isset($certificate) ? $certificate->rt : '') ? 'checked' : '' }} onchange="updateTestFields()">
                    <label for="rt">■ RT</label>
                </div>
            </td>
            <td class="var-value" style="text-align: center;">
                <div class="checkbox-container">
                    <!-- Fixed UT checkbox - use a single hidden field -->
                    <input type="hidden" name="ut" value="0">
                    <input type="checkbox" name="ut" id="ut" value="1" {{ old('ut', isset($certificate) ? $certificate->ut : '') ? 'checked' : '' }} onchange="updateTestFields()">
                    <label for="ut">□ UT</label>
                </div>
            </td>
        </tr>

        <!-- Remaining test rows -->
        <tr>
            <td class="var-label">Fillet weld-fracture test (QW-181.2):</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="fillet_fracture_test"
                    placeholder="................" value="{{ old('fillet_fracture_test', isset($certificate) ? $certificate->fillet_fracture_test : '') }}">
            </td>
            <td class="var-label">Length and percent of defects</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="defects_length"
                    placeholder="..........." value="{{ old('defects_length', isset($certificate) ? $certificate->defects_length : '') }}">
            </td>
        </tr>

        <tr>
            <td class="var-label">
                <div class="checkbox-container">
                    <input type="checkbox" name="fillet_welds_plate" id="fillet_welds_plate" {{ old('fillet_welds_plate', isset($certificate) ? $certificate->fillet_welds_plate : '') ? 'checked' : '' }}>
                    <label for="fillet_welds_plate">□ Fillet welds in plate [QW-462.4(b)]</label>
                </div>
            </td>
            <td class="var-value"></td>
            <td class="var-label">
                <div class="checkbox-container">
                    <input type="checkbox" name="fillet_welds_pipe" id="fillet_welds_pipe" {{ old('fillet_welds_pipe', isset($certificate) ? $certificate->fillet_welds_pipe : '') ? 'checked' : '' }}>
                    <label for="fillet_welds_pipe">□ Fillet welds in pipe [QW-462.4(c)]</label>
                </div>
            </td>
            <td class="var-value"></td>
        </tr>

        <tr>
            <td class="var-label">Macro examination (QW-184)</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="macro_exam"
                    placeholder="................" value="{{ old('macro_exam', isset($certificate) ? $certificate->macro_exam : '') }}">
            </td>
            <td class="var-label">Fillet size (in.)</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="fillet_size" placeholder="............" value="{{ old('fillet_size', isset($certificate) ? $certificate->fillet_size : '') }}">
            </td>
        </tr>

        <tr>
            <td class="var-label">Other tests</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="other_tests"
                    placeholder="................" value="{{ old('other_tests', isset($certificate) ? $certificate->other_tests : '') }}">
            </td>
            <td class="var-label">Concavity or convexity (in.)</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="concavity_convexity"
                    placeholder="................" value="{{ old('concavity_convexity', isset($certificate) ? $certificate->concavity_convexity : '') }}">
            </td>
        </tr>
    </table>

    <!-- Personnel Information -->
    <table class="results-table">
        <tr>
            <td class="var-label">Film or specimens evaluated by</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="evaluated_by" value="{{ old('evaluated_by', isset($certificate) ? $certificate->evaluated_by : '') }}" required>
            </td>
            <td class="var-label">Company</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="evaluated_company" id="evaluated_company" value="{{ old('evaluated_company', isset($certificate) ? $certificate->evaluated_company : '') }}">
            </td>
        </tr>
        <tr>
            <td class="var-label">Mechanical tests conducted by</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="mechanical_tests_by" id="mechanical_tests_by" value="{{ old('mechanical_tests_by', isset($certificate) ? $certificate->mechanical_tests_by : '') }}">
            </td>
            <td class="var-label">Laboratory test no.</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="lab_test_no" id="lab_test_no" placeholder="Enter lab test number" value="{{ old('lab_test_no', isset($certificate) ? $certificate->lab_test_no : '') }}">
            </td>
        </tr>
        <tr>
            <td class="var-label">Welding supervised by</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="supervised_by" value="{{ old('supervised_by', isset($certificate) ? $certificate->supervised_by : '') }}" required>
            </td>
            <td class="var-label">Company</td>
            <td class="var-value" style="text-align: center;">
                <input type="text" class="form-input" name="supervised_company" value="{{ old('supervised_company', isset($certificate) ? $certificate->supervised_company : 'Elite Engineering Arabia') }}" readonly>
            </td>
        </tr>
    </table>

    <!-- Certification Statement - Now integrated into the results section -->
    <div style="width: 100%; box-sizing: border-box; padding: 10px; text-align: center; background-color: #f8f9fa; border-top: 1px solid #000;">
        <strong>We certify that the statements in this record are correct and that the test coupons were prepared, welded, and tested in accordance with the requirements of Section IX of the ASME BOILER AND PRESSURE VESSEL CODE.</strong>
        <span class="custom-certification-wrapper">
            <input type="text" name="certification_text" id="certification_text" class="custom-certification-input" 
                value="{{ old('certification_text', isset($certificate) ? $certificate->certification_text : '') }}" placeholder="Add custom certification text here" required>
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
            <td style="border: 1px solid #000;"><input type="date" class="form-input" name="confirm_date1" style="width: 100%" value="{{ old('confirm_date1', isset($certificate) ? $certificate->confirm_date1 : '') }}"></td>
            <td style="border: 1px solid #000;"></td>
            <td style="border: 1px solid #000;"><input type="text" class="form-input" name="confirm_title1" placeholder="Position" style="width: 100%" value="{{ old('confirm_title1', isset($certificate) ? $certificate->confirm_title1 : '') }}">
            </td>
        </tr>
        <tr class="sig-row">
            <td style="border: 1px solid #000;"><input type="date" class="form-input" name="confirm_date2" style="width: 100%" value="{{ old('confirm_date2', isset($certificate) ? $certificate->confirm_date2 : '') }}"></td>
            <td style="border: 1px solid #000;"></td>
            <td style="border: 1px solid #000;"><input type="text" class="form-input" name="confirm_title2" placeholder="Position" style="width: 100%" value="{{ old('confirm_title2', isset($certificate) ? $certificate->confirm_title2 : '') }}">
            </td>
        </tr>
        <tr class="sig-row">
            <td style="border: 1px solid #000;"><input type="date" class="form-input" name="confirm_date3" style="width: 100%" value="{{ old('confirm_date3', isset($certificate) ? $certificate->confirm_date3 : '') }}"></td>
            <td style="border: 1px solid #000;"></td>
            <td style="border: 1px solid #000;"><input type="text" class="form-input" name="confirm_title3" placeholder="Position" style="width: 100%" value="{{ old('confirm_title3', isset($certificate) ? $certificate->confirm_title3 : '') }}">
            </td>
        </tr>
    </table>

    @include('fcaw_certificates.partials.signature-section')

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

<script>
// Function to update the mechanical test and lab test fields based on RT/UT selection
function updateTestFields() {
    const rtChecked = document.getElementById('rt').checked;
    const utChecked = document.getElementById('ut').checked;
    
    const mechanicalTestsBy = document.getElementById('mechanical_tests_by');
    const labTestNo = document.getElementById('lab_test_no');
    const evaluatedCompany = document.getElementById('evaluated_company');
    const rtDocNo = document.getElementById('rt_doc_no');
    
    if (rtChecked || utChecked) {
        // If either RT or UT is checked, disable mechanical tests fields but keep them submittable
        mechanicalTestsBy.disabled = true;
        labTestNo.disabled = true;
        evaluatedCompany.disabled = true;
        
        // Make RT doc number field required
        if (rtDocNo) {
            rtDocNo.setAttribute('required', 'required');
        }
        
        // Empty the values for these fields
        mechanicalTestsBy.value = '';
        labTestNo.value = '';
        evaluatedCompany.value = '';
        
        // Remove required attribute
        mechanicalTestsBy.removeAttribute('required');
        labTestNo.removeAttribute('required');
        evaluatedCompany.removeAttribute('required');
        
        // Set placeholder to indicate why it's disabled
        mechanicalTestsBy.placeholder = "Not required with RT/UT";
        labTestNo.placeholder = "Not required with RT/UT";
        evaluatedCompany.placeholder = "Not required with RT/UT";
    } else {
        // If neither RT nor UT is checked, enable the fields
        mechanicalTestsBy.disabled = false;
        labTestNo.disabled = false;
        evaluatedCompany.disabled = false;
        
        // Make RT doc number field optional
        if (rtDocNo) {
            rtDocNo.removeAttribute('required');
        }
        
        // Reset placeholders
        mechanicalTestsBy.placeholder = "";
        labTestNo.placeholder = "Enter lab test number";
        evaluatedCompany.placeholder = "SOGEC";
    }
}

// Initialize fields on page load
document.addEventListener('DOMContentLoaded', function() {
    // Run once on page load to set initial state
    updateTestFields();
    
    // Add event listener for form submission
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Enable disabled fields just before submission so they get included in the form data
            const disabledFields = document.querySelectorAll('input:disabled, select:disabled, textarea:disabled');
            disabledFields.forEach(field => {
                // Store the original disabled state
                field.dataset.wasDisabled = "true";
                // Temporarily enable the field
                field.disabled = false;
            });
            
            // Submit will continue normally
            // Fields will be re-disabled in case of validation errors
            setTimeout(function() {
                document.querySelectorAll('[data-was-disabled="true"]').forEach(field => {
                    field.disabled = true;
                    delete field.dataset.wasDisabled;
                });
            }, 500);
        });
    }
});
</script>