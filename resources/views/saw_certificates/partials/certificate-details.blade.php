<!-- Certificate details rows exactly matching Excel layout -->
<!-- Certificate details rows exactly matching Excel layout -->
<div class="cert-details-row with-photo two-row-container">
    <!-- First row -->
    <div class="detail-row">
        <div class="cert-left">
            Certificate No:
            <input type="text" class="form-input" name="certificate_no" id="certificate_no"
                value="{{ $newCertNo ?? ($certificate->certificate_no ?? '') }}" style="width: 120px; display: inline; font-weight: bold;" readonly>
        </div>
        <div class="cert-center">
            <strong>Welding Operator's name:</strong>
            <select class="form-input welder-search" name="welder_id" id="welder_id" required>
                @if(isset($certificate) && $certificate->welder)
                    <option value="{{ $certificate->welder->id }}" selected>{{ $certificate->welder->name }}</option>
                @endif
            </select>
        </div>
        <div class="cert-right">
            <strong>Welder ID No:</strong>
            <input type="text" class="form-input" name="welder_id_no" id="welder_id_no"
                style="width: 60px; display: inline; font-weight: bold;" value="{{ old('welder_id_no', $certificate->welder->welder_no ?? '') }}" readonly>
        </div>
    </div>

    <!-- Second row -->
    <div class="detail-row">
        <div class="cert-left">
            <strong>Gov ID/Iqama number:</strong>
            <input type="text" class="form-input" name="iqama_no" id="iqama_no"
                style="width: 100px; display: inline; font-weight: bold;" value="{{ old('iqama_no', $certificate->welder->iqama_no ?? '') }}" readonly>
        </div>
        <div class="cert-center">
            <strong>Company:</strong>
            <select class="form-input" name="company_id" id="company_id" required>
                <option value="">Select Company</option>
                @foreach($companies as $company)
                    <option value="{{ $company->id }}" {{ old('company_id', $certificate->company_id ?? '') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="cert-right">
            <strong>Passport No:</strong>
            <input type="text" class="form-input" name="passport_no" id="passport_no"
                style="width: 80px; display: inline; font-weight: bold;" value="{{ old('passport_no', $certificate->welder->passport_no ?? '') }}" readonly>
        </div>
    </div>

    <!-- Photo placement that spans both rows -->
    <div class="photo-container">
        <div class="photo-upload-section">
            <label for="photo" class="photo-label">PHOTO</label>
            <div class="photo-preview" id="photo-preview">
                @if(isset($certificate) && $certificate->photo_path)
                    <img src="{{ asset('storage/' . $certificate->photo_path) }}" alt="Welder Photo" class="preview-image">
                @elseif(isset($certificate) && $certificate->welder && $certificate->welder->photo)
                    <img src="{{ asset('storage/' . $certificate->welder->photo) }}" alt="Welder Photo" class="preview-image">
                @else
                    <div class="photo-placeholder">No Photo</div>
                @endif
            </div>
            <input type="file" name="photo" id="photo" accept="image/*" class="photo-input" onchange="previewPhoto(this)">
        </div>
    </div>
</div>