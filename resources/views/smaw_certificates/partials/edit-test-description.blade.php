<!-- Test Description Header -->
<div class="cert-details-row" style="height: 35px;">
    <div style="width: 100%; text-align: center; padding: 5px; border-right: 1px solid #000; background: #f0f0f0;">
        <strong>Test Description</strong>
    </div>
</div>

<div class="cert-details-row">
    <div class="cert-left" style="width: 50%;">
        <strong>Identification of WPS followed:</strong>
        <input type="text" class="form-input" name="wps_followed" value="{{ $certificate->wps_followed }}" required
            style="width: 170px; font-weight: bold;">
    </div>

    <div style="width: 120px; border-right: 1px solid #000; padding: 0 10px; text-align: center;">
        <strong>Revision No:</strong>
        <input type="text" class="form-input" name="revision_no" value="{{ $certificate->revision_no }}" required
            style="width: 60px; font-weight: bold;">
    </div>

    <div style="width: 120px; border-right: 1px solid #000; padding: 0 10px; text-align: center;">
        <div class="checkbox-container">
            <input type="checkbox" name="test_coupon" id="test_coupon" {{ $certificate->test_coupon ? 'checked' : '' }}>
            <label for="test_coupon"><strong>{{ $certificate->test_coupon ? '■' : '□' }} Test coupon</strong></label>
        </div>
    </div>
    <div style="flex: 1; padding: 0 10px; text-align: center;">
        <div class="checkbox-container">
            <input type="checkbox" name="production_weld" id="production_weld" {{ $certificate->production_weld ? 'checked' : '' }}>
            <label for="production_weld"><strong>{{ $certificate->production_weld ? '■' : '□' }} Production weld</strong></label>
        </div>
    </div>
</div>

<div class="cert-details-row">
    <div class="cert-left" style="width: 50%;">
        <strong>Base Metal Specification:</strong>
        <input type="text" class="form-input" name="base_metal_spec" value="{{ $certificate->base_metal_spec }}" required
            style="width: 170px; font-weight: bold;">
    </div>
    <div style="width: 120px; border-right: 1px solid #000; padding: 0 10px; text-align: center;">
        <strong>Date of Test:</strong>
    </div>
    <div style="flex: 1; padding: 0 10px; text-align: center;">
        <input type="date" class="form-input date-input" name="test_date" value="{{ $certificate->test_date }}" required
            style="font-weight: bold;" onchange="formatDateDisplay(this)">
        <div id="formatted_date" class="text-muted mt-1" style="font-size: 8px;">{{ \Carbon\Carbon::parse($certificate->test_date)->format('j \o\f F Y') }}</div>
    </div>
</div>

<div class="cert-details-row">
    <div class="cert-left">
        <strong>Diameter:</strong>
        <input type="text" class="form-input" name="diameter" id="diameter" value="{{ $certificate->diameter }}"
            style="width: 80px; display: inline; font-weight: bold;" required>
    </div>
    <div style="width: 160px; border-right: 1px solid #000; padding: 0 10px; text-align: center; display: flex; align-items: center; gap: 5px; justify-content: center;">
        <strong>Thickness:</strong>
        <input type="text" class="form-input" name="thickness" id="thickness" value="{{ $certificate->thickness }}"
            style="width: 100px; display: inline; font-weight: bold;" required>
    </div>
    <div style="flex: 1; padding: 0 10px; text-align: center;">
        <!-- Empty cell -->
        <input type="hidden" name="dia_thickness" id="dia_thickness" value="{{ $certificate->dia_thickness }}">
    </div>
</div>

<script>
function formatDateDisplay(input) {
    const date = new Date(input.value);
    if (!isNaN(date)) {
        const day = date.getDate();
        const month = date.toLocaleString('default', { month: 'long' });
        const year = date.getFullYear();
        
        const formattedDate = `${day} of ${month} ${year}`;
        document.getElementById('formatted_date').textContent = formattedDate;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Initialize date format display
    const dateInput = document.querySelector('.date-input');
    if (dateInput) {
        formatDateDisplay(dateInput);
    }
    
    // Toggle checkbox styles when checked/unchecked
    document.getElementById('test_coupon').addEventListener('change', function() {
        this.nextElementSibling.innerHTML = this.checked ? '<strong>■ Test coupon</strong>' : '<strong>□ Test coupon</strong>';
    });
    
    document.getElementById('production_weld').addEventListener('change', function() {
        this.nextElementSibling.innerHTML = this.checked ? '<strong>■ Production weld</strong>' : '<strong>□ Production weld</strong>';
    });
});
</script>
