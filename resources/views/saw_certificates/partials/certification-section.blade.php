<!-- Certification Statement Section -->
<div class="certification-section">
    <div class="cert-statement" style="text-align: center; padding: 15px; font-size: 10px; border: 2px solid #000; border-top: none; background: white;">
        <strong>We certify that the statements in this record are correct and that the test coupons were prepared, welded, and tested in accordance with the requirements of Section IX of the ASME BOILER AND PRESSURE VESSEL CODE.</strong>
        <div style="margin-top: 10px;">
            <textarea name="certification_text" id="certification_text" 
                class="form-input" style="width: 100%; height: 60px; resize: vertical;" 
                placeholder="Add any additional certification text here">{{ old('certification_text', $certificate->certification_text ?? '') }}</textarea>
        </div>
    </div>

    <!-- Confirmation Section (6 month validity) -->
    <div class="confirmation-section" style="border: 2px solid #000; border-top: none;">
        <div class="confirmation-header" style="background: #f0f0f0; text-align: center; font-weight: bold; padding: 8px; border-bottom: 1px solid #000;">
            Confirmation of the validity by the employer/Welding coordinator for the following 6 month
        </div>

        <table class="confirmation-table" style="width: 100%; border-collapse: collapse; font-size: 9px;">
            <tr>
                <td style="width: 25%; background: #f8f9fa; font-weight: bold; text-align: center; height: 25px; border: 1px solid #000;">Date</td>
                <td style="width: 40%; background: #f8f9fa; font-weight: bold; text-align: center; height: 25px; border: 1px solid #000;">Signature</td>
                <td style="width: 35%; background: #f8f9fa; font-weight: bold; text-align: center; height: 25px; border: 1px solid #000;">Position or Title</td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; padding: 5px;">
                    <input type="date" class="form-input" name="confirm_date_1" 
                        value="{{ old('confirm_date_1', $certificate->confirm_date_1 ?? '') }}" 
                        style="width: 100%; border: none;">
                </td>
                <td style="border: 1px solid #000; height: 40px; text-align: center;">
                    <!-- Signature area - could be enhanced with signature pad -->
                    <div style="height: 35px; display: flex; align-items: center; justify-content: center; color: #666; font-style: italic;">
                        Signature
                    </div>
                </td>
                <td style="border: 1px solid #000; padding: 5px;">
                    <input type="text" class="form-input" name="confirm_position_1" 
                        value="{{ old('confirm_position_1', $certificate->confirm_position_1 ?? '') }}" 
                        style="width: 100%; border: none;" placeholder="Position/Title">
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; padding: 5px;">
                    <input type="date" class="form-input" name="confirm_date_2" 
                        value="{{ old('confirm_date_2', $certificate->confirm_date_2 ?? '') }}" 
                        style="width: 100%; border: none;">
                </td>
                <td style="border: 1px solid #000; height: 40px; text-align: center;">
                    <div style="height: 35px; display: flex; align-items: center; justify-content: center; color: #666; font-style: italic;">
                        Signature
                    </div>
                </td>
                <td style="border: 1px solid #000; padding: 5px;">
                    <input type="text" class="form-input" name="confirm_position_2" 
                        value="{{ old('confirm_position_2', $certificate->confirm_position_2 ?? '') }}" 
                        style="width: 100%; border: none;" placeholder="Position/Title">
                </td>
            </tr>
            <tr>
                <td style="border: 1px solid #000; padding: 5px;">
                    <input type="date" class="form-input" name="confirm_date_3" 
                        value="{{ old('confirm_date_3', $certificate->confirm_date_3 ?? '') }}" 
                        style="width: 100%; border: none;">
                </td>
                <td style="border: 1px solid #000; height: 40px; text-align: center;">
                    <div style="height: 35px; display: flex; align-items: center; justify-content: center; color: #666; font-style: italic;">
                        Signature
                    </div>
                </td>
                <td style="border: 1px solid #000; padding: 5px;">
                    <input type="text" class="form-input" name="confirm_position_3" 
                        value="{{ old('confirm_position_3', $certificate->confirm_position_3 ?? '') }}" 
                        style="width: 100%; border: none;" placeholder="Position/Title">
                </td>
            </tr>
        </table>
    </div>
</div>

<style>
.certification-section {
    margin-top: 0;
}

.cert-statement textarea.form-input {
    border: 1px solid #ddd;
    border-radius: 4px;
    padding: 8px;
    font-family: Arial, sans-serif;
    font-size: 9px;
    line-height: 1.4;
}

.cert-statement textarea.form-input:focus {
    border-color: #007bff;
    outline: none;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
}

.confirmation-table input.form-input {
    font-size: 9px;
    padding: 2px 4px;
}

.confirmation-table input.form-input:focus {
    outline: 1px solid #007bff;
}
</style>