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
            <select class="form-input" name="welder_id" id="welder_id" required>
                <option value="">Select Welder</option>
                @foreach($welders as $welder)
                    <option value="{{ $welder->id }}" {{ old('welder_id', $certificate->welder_id ?? '') == $welder->id ? 'selected' : '' }}>{{ $welder->name }}</option>
                @endforeach
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
                @else
                    <div class="photo-placeholder">No Photo</div>
                @endif
            </div>
            <input type="file" name="photo" id="photo" accept="image/*" class="photo-input" onchange="previewPhoto(this)">
        </div>
    </div>
</div>

<script>
// Update welder details when welder is selected
document.addEventListener('DOMContentLoaded', function() {
    const welderSelect = document.getElementById('welder_id');
    if (welderSelect) {
        welderSelect.addEventListener('change', function() {
            const welderId = this.value;
            if (welderId) {
                fetch(`/welders/${welderId}/details`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.welder) {
                            document.getElementById('welder_id_no').value = data.welder.welder_id_no || '';
                            document.getElementById('iqama_no').value = data.welder.iqama_no || '';
                            document.getElementById('passport_no').value = data.welder.passport_no || '';
                            
                            // Update photo if available
                            const photoPreview = document.getElementById('photo-preview');
                            if (data.welder.photo_path && photoPreview) {
                                photoPreview.innerHTML = `<img src="${data.welder.photo_path}" alt="Welder Photo" class="preview-image">`;
                            }
                        }
                        
                        // Update company code display
                        if (data.company) {
                            const companyCodeDisplay = document.getElementById('company-code-display');
                            if (companyCodeDisplay) {
                                companyCodeDisplay.innerHTML = `
                                    <span style="color: #dc3545; font-size: 16px;">${data.company.code || 'AIC'}</span>
                                    <span style="color: #999; font-size: 12px;">${data.company.name || 'steel'}</span>
                                `;
                            }
                        }
                    })
                    .catch(error => console.error('Error fetching welder details:', error));
            }
        });
    }
});

function previewPhoto(input) {
    const preview = document.getElementById('photo-preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Photo Preview" class="preview-image">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<style>
.photo-container {
    position: absolute;
    right: 10px;
    top: 5px;
    width: 80px;
    height: 60px;
    border: 1px solid #000;
    background: #f8f8f8;
}

.photo-upload-section {
    width: 100%;
    height: 100%;
    position: relative;
}

.photo-label {
    position: absolute;
    top: 2px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 8px;
    font-weight: bold;
    z-index: 2;
}

.photo-preview {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
}

.preview-image {
    max-width: 100%;
    max-height: 100%;
    object-fit: cover;
}

.photo-placeholder {
    font-size: 8px;
    color: #666;
    text-align: center;
}

.photo-input {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}
</style>