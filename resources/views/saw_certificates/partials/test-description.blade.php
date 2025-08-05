<!-- Test Description Header -->
<div class="cert-details-row" style="height: 35px;">
    <div style="width: 100%; text-align: center; padding: 5px; border-right: 1px solid #000; background: #f0f0f0;">
        <strong>Test Description</strong>
    </div>
</div>

<div class="cert-details-row">
    <div class="cert-left" style="width: 50%;">
        <strong>Identification of WPS followed:</strong>
        <input type="text" class="form-input" name="wps_followed" value="{{ old('wps_followed', $certificate->wps_followed ?? '') }}" required
            style="width: 170px; font-weight: bold;" placeholder="e.g., 2020-S-003">
    </div>

    <div style="width: 120px; border-right: 1px solid #000; padding: 0 10px; text-align: center;">
        <div class="checkbox-container">
            <input type="checkbox" name="test_coupon" id="test_coupon" {{ old('test_coupon', $certificate->test_coupon ?? false) ? 'checked' : '' }}>
            <label for="test_coupon"><strong>■ Test coupon</strong></label>
        </div>
    </div>

    <div style="flex: 1; padding: 0 10px; text-align: center;">
        <div class="checkbox-container">
            <input type="checkbox" name="production_weld" id="production_weld" {{ old('production_weld', $certificate->production_weld ?? false) ? 'checked' : '' }}>
            <label for="production_weld"><strong>□ Production weld</strong></label>
        </div>
    </div>
</div>

<div class="cert-details-row">
    <div class="cert-left" style="width: 50%;">
        <strong>Base Metal Specification:</strong>
        <input type="text" class="form-input" name="base_metal_spec" value="{{ old('base_metal_spec', $certificate->base_metal_spec ?? '') }}" required
            style="width: 170px; font-weight: bold;" placeholder="e.g., ASTM A516 Gr.70">
    </div>
    <div style="width: 120px; border-right: 1px solid #000; padding: 0 10px; text-align: center;">
        <strong>Date of Test:</strong>
    </div>
    <div style="flex: 1; padding: 0 10px; text-align: center;">
        <input type="date" class="form-input date-input" name="test_date" value="{{ old('test_date', isset($certificate) ? $certificate->test_date->format('Y-m-d') : date('Y-m-d')) }}" required
            style="font-weight: bold;" onchange="formatDateDisplay(this)">
        <div id="formatted_date" class="text-muted mt-1" style="font-size: 8px;">{{ old('test_date', isset($certificate) ? $certificate->test_date->format('F j, Y') : date('F j, Y')) }}</div>
    </div>
</div>

<div class="cert-details-row">
    <div class="cert-left">
        <strong>Dia / Thickness:</strong>
        <input type="text" class="form-input" name="dia_thickness" id="dia_thickness" 
            value="{{ old('dia_thickness', $certificate->diameter ?? '') }}" 
            style="width: 150px; display: inline; font-weight: bold;" 
            placeholder="e.g., …...../20mm" required>
    </div>
    <div style="flex: 1; padding: 0 10px; text-align: center;">
        <!-- Empty space for layout consistency -->
    </div>
</div>

<script>
function formatDateDisplay(input) {
    const date = new Date(input.value);
    const formattedDiv = document.getElementById('formatted_date');
    if (formattedDiv) {
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        formattedDiv.textContent = date.toLocaleDateString('en-US', options);
    }
}
</script>