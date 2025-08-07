<!-- Test Description Header -->
<div class="cert-details-row" style="height: 35px;">
    <div style="width: 100%; text-align: center; padding: 5px; border-right: 1px solid #000; background: #f0f0f0;">
        <strong>Test Description</strong>
    </div>
</div>

<div class="cert-details-row">
    <div class="cert-left" style="width: 50%;">
        <strong>Identification of WPS followed:</strong>
        <input type="text" class="form-input" name="wps_followed" value="{{ old('wps_followed', isset($certificate) ? $certificate->wps_followed : '') }}" required
            style="width: 170px; font-weight: bold;">
    </div>

    <div style="width: 120px; border-right: 1px solid #000; padding: 0 10px; text-align: center;">
        <strong>Revision No:</strong>
        <input type="text" class="form-input" name="revision_no" value="{{ old('revision_no', isset($certificate) ? $certificate->revision_no : '') }}"
            style="width: 60px; font-weight: bold;">
    </div>

    <div style="width: 120px; border-right: 1px solid #000; padding: 0 10px; text-align: center;">
        <div class="checkbox-container">
            <input type="checkbox" name="test_coupon" id="test_coupon" {{ old('test_coupon', isset($certificate) ? $certificate->test_coupon : false) ? 'checked' : '' }}>
            <label for="test_coupon"><strong>■ Test coupon</strong></label>
        </div>
    </div>
    <div style="flex: 1; padding: 0 10px; text-align: center;">
        <div class="checkbox-container">
            <input type="checkbox" name="production_weld" id="production_weld" {{ old('production_weld', isset($certificate) ? $certificate->production_weld : false) ? 'checked' : '' }}>
            <label for="production_weld"><strong>□ Production weld</strong></label>
        </div>
    </div>
</div>

<div class="cert-details-row">
    <div class="cert-left" style="width: 50%;">
        <strong>Base Metal Specification:</strong>
        <input type="text" class="form-input" name="base_metal_spec" value="{{ old('base_metal_spec', isset($certificate) ? $certificate->base_metal_spec : '') }}" required
            style="width: 170px; font-weight: bold;">
    </div>
    <div style="width: 120px; border-right: 1px solid #000; padding: 0 10px; text-align: center;">
        <strong>Date of Test:</strong>
    </div>
    <div style="flex: 1; padding: 0 10px; text-align: center;">
        <input type="date" class="form-input date-input" name="test_date" value="{{ old('test_date', isset($certificate) ? $certificate->test_date->format('Y-m-d') : '') }}" required
            style="font-weight: bold;" onchange="formatDateDisplay(this)">
        <div id="formatted_date" class="text-muted mt-1" style="font-size: 8px;">
            {{ old('test_date', isset($certificate) ? $certificate->test_date->format('jS \o\f F Y') : '') }}
        </div>
    </div>
</div>

<div class="cert-details-row">
    <div class="cert-left">
        <strong>Diameter:</strong>
        <input type="text" class="form-input" name="diameter" id="diameter" value="{{ old('diameter', isset($certificate) ? $certificate->diameter : '') }}"
            style="width: 80px; display: inline; font-weight: bold;" {{ old('plate_specimen', isset($certificate) ? $certificate->plate_specimen : false) && !old('pipe_specimen', isset($certificate) ? $certificate->pipe_specimen : false) ? '' : 'required' }}>
    </div>
    <div style="width: 160px; border-right: 1px solid #000; padding: 0 10px; text-align: center; display: flex; align-items: center; gap: 5px; justify-content: center;">
        <strong>Thickness:</strong>
        <input type="text" class="form-input" name="thickness" id="thickness" value="{{ old('thickness', isset($certificate) ? $certificate->thickness : '') }}"
            style="width: 100px; display: inline; font-weight: bold;" {{ old('plate_specimen', isset($certificate) ? $certificate->plate_specimen : false) && !old('pipe_specimen', isset($certificate) ? $certificate->pipe_specimen : false) ? '' : 'required' }}>
    </div>
    <div style="flex: 1; padding: 0 10px; text-align: center;">
        <!-- Empty cell -->
        <input type="hidden" name="dia_thickness" id="dia_thickness">
    </div>
</div>