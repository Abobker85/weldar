<!-- Certificate details rows with improved layout -->
<div class="cert-details-row with-photo two-row-container">
    <!-- First row -->
    <div class="detail-row">
        <div class="cert-left">
            Certificate No:
            <input type="text" class="form-input" name="certificate_no" id="certificate_no"
                value="{{ $newCertNo ?? ($certificate->certificate_no ?? '') }}" style="width: 120px; display: inline; font-weight: bold;" readonly>
        </div>
        <div class="cert-center">
            <strong>Welder's name:</strong>
            <select class="form-input select2" name="welder_id" id="welder_id" style="width: 100%;">
                <option value="">-- Select Welder --</option>
                @foreach($welders as $welder)
                    <option value="{{ $welder->id }}" {{ old('welder_id', isset($certificate) ? $certificate->welder_id : '') == $welder->id ? 'selected' : '' }}>{{ $welder->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="cert-right">
            <strong>Welder ID No:</strong>
            <input type="text" class="form-input" name="welder_id_no" id="welder_id_no"
                style="width: 60px; display: inline; font-weight: bold;" value="{{ old('welder_id_no', isset($certificate) ? $certificate->welder->welder_no : '') }}" readonly>
        </div>
    </div>
    
    <!-- Second row -->
    <div class="detail-row">
        <div class="cert-left">
            <strong>Gov ID Iqama number:</strong>
            <input type="text" class="form-input" name="iqama_no" id="iqama_no"
                style="width: 100px; display: inline; font-weight: bold;" value="{{ old('iqama_no', isset($certificate) ? $certificate->welder->iqama_no : '') }}" readonly>
        </div>
        <div class="cert-center">
            <strong>Company:</strong>
            <select class="form-input select2" name="company_id" id="company_id" style="width: 100%;">
                <option value="">-- Select Company --</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ old('company_id', isset($certificate) ? $certificate->company_id : '') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="cert-right">
            <strong>Passport No:</strong>
            <input type="text" class="form-input" name="passport_no" id="passport_no"
                style="width: 80px; display: inline; font-weight: bold;" value="{{ old('passport_no', isset($certificate) ? $certificate->welder->passport_no : '') }}" readonly>
        </div>
    </div>
    
    <!-- Photo placement that spans both rows -->
    <div class="photo-container">
        @include('components.photo-upload', ['photo_path' => isset($certificate) ? $certificate->photo_path : null])
    </div>
</div>

<!-- Add Select2 CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
$(document).ready(function() {
    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap4',
        width: '100%'
    });
});
</script>