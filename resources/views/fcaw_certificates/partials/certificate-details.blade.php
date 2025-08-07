<!-- Certificate details rows with improved layout -->
<div class="cert-details-row with-photo two-row-container">
    <!-- First row -->
    <div class="detail-row">
        <div class="cert-left">
            Certificate No:
            <input type="text" class="form-input" name="certificate_no" id="certificate_no"
                value="{{ $certificate->certificate_no ?? ($newCertNo ?? '') }}" style="width: 120px; display: inline; font-weight: bold;" readonly>
        </div>
        <div class="cert-center">
            <strong>Welder's name:</strong>
            @include('components.welder-search', ['welders' => $welders, 'selectedWelder' => $selectedWelder ?? null])
        </div>
        <div class="cert-right">
            <strong>Welder ID No:</strong>
            <input type="text" class="form-input" name="welder_id_no" id="welder_id_no"
                value="{{ $certificate->welder->welder_no ?? '' }}" style="width: 60px; display: inline; font-weight: bold;" readonly>
        </div>
    </div>
    
    <!-- Second row -->
    <div class="detail-row">
        <div class="cert-left">
            <strong>Gov ID Iqama number:</strong>
            <input type="text" class="form-input" name="iqama_no" id="iqama_no"
                value="{{ $certificate->welder->iqama_no ?? '' }}" style="width: 100px; display: inline; font-weight: bold;" readonly>
        </div>
        <div class="cert-center">
            <strong>Company:</strong>
            <input type="text" class="form-input" name="company_name" id="company_name"
                value="{{ $certificate->company->name ?? '' }}" style="font-weight: bold;" readonly>
            <input type="hidden" name="company_id" id="company_id" value="{{ $certificate->company_id ?? '' }}">
        </div>
        <div class="cert-right">
            <strong>Passport No:</strong>
            <input type="text" class="form-input" name="passport_no" id="passport_no"
                value="{{ $certificate->welder->passport_id_no ?? '' }}" style="width: 80px; display: inline; font-weight: bold;" readonly>
        </div>
    </div>
    
    <!-- Photo placement that spans both rows -->
    <div class="photo-container">
        @include('components.photo-upload', ['photoPath' => $certificate->photo_path ?? null])
    </div>
</div>
