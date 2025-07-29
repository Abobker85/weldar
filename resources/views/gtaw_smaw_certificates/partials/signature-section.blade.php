<!-- Final organization section - Now part of the same container -->
<table style="width: 100%; table-layout: fixed; margin: 0; border-collapse: collapse; border-top: 1px solid #000;">
    <tr>
        <td colspan="3" style="background: #f8f9fa; font-weight: bold; text-align: center; height: 25px; border: 1px solid #000;">Organization</td>
        <td style="width: 80px; background: #f8f9fa; font-weight: bold; text-align: center; height: 25px; border: 1px solid #000;">QR CODE</td>
    </tr>
    <tr>
        <td class="var-label" style="width: 20%; background: #f8f9fa; font-weight: bold; border: 1px solid #000; padding: 5px;">Test Witnessed by:</td>
        <td class="var-value" style="width: 40%; border: 1px solid #000; padding: 5px;">{{ \App\Models\AppSetting::getValue('company_name', 'ELITE ENGINEERING ARABIA') }}</td>
        <td class="var-value" style="width: 20%; border: 1px solid #000; padding: 5px;">Reviewed / Approved by:</td>
        <td rowspan="6" style="text-align: center; width: 80px; border: 1px solid #000;">
            <div style="width: 80px; height: 80px; border: 1px solid #000; background: #f0f0f0; display: flex; align-items: center; justify-content: center; font-size: 8px;">
                QR Code<br>Will be<br>Generated
            </div>
        </td>
    </tr>
    <tr>
        <td class="var-label" style="background: #f8f9fa; font-weight: bold; border: 1px solid #000; padding: 5px;">Name:</td>
        <td class="var-value" style="border: 1px solid #000; padding: 5px;">
            <input type="text" class="form-input" name="inspector_name" id="inspector_name" value="{{ isset($certificate) ? ($certificate->inspector_name ?? 'Ahmed Yousry') : 'Ahmed Yousry' }}" style="width: 100%">
        </td>
        <td class="var-label" style="background: #f9f9fa; font-weight: bold; border: 1px solid #000; padding: 5px;">Name:</td>
    </tr>
    <tr>
        <td class="var-label" style="background: #f8f9fa; font-weight: bold; border: 1px solid #000; padding: 5px;">Signature:</td>
        <td class="var-value" style="height: 25px; border: 1px solid #000; padding: 5px; position: relative;">
            <button type="button" class="btn btn-sm btn-primary" id="inspector-sign-btn" style="position: relative; z-index: 10;">Sign</button>
            <div id="inspector-signature-preview" style="display: inline-block; height: 40px; margin-left: 10px;">
                @if(isset($certificate) && $certificate->inspector_signature_data)
                    <img src="{{ $certificate->inspector_signature_data }}" alt="Inspector Signature" style="max-height: 40px;">
                @endif
            </div>
            <input type="hidden" name="inspector_signature_data" id="inspector_signature_data" value="{{ isset($certificate) ? ($certificate->inspector_signature_data ?? '') : '' }}">
        </td>
        <td class="var-label" style="background: #f9f9fa; font-weight: bold; border: 1px solid #000; padding: 5px;">Signature:</td>
    </tr>
    <tr>
        <td class="var-label" style="background: #f8f9fa; font-weight: bold; border: 1px solid #000; padding: 5px;">Stamp:</td>
        <td class="var-value stamp-cell" style="height: 45px; text-align: center; border: 1px solid #000;">
            @if(\App\Models\AppSetting::getValue('company_stamp_path'))
                <img src="{{ asset('storage/' . \App\Models\AppSetting::getValue('company_stamp_path')) }}" 
                     alt="Company Stamp" class="official-stamp" style="max-width: 50px; max-height: 50px;">
            @endif
        </td>
        <td class="var-label" style="background: #f9f9fa; font-weight: bold; border: 1px solid #000; padding: 5px;">Stamp:</td>
    </tr>
    <tr>
        <td class="var-label" style="background: #f8f9fa; font-weight: bold; border: 1px solid #000; padding: 5px;">Date:</td>
        <td class="var-value" style="border: 1px solid #000; padding: 5px;">
            <input type="date" class="form-input" name="inspector_date" value="{{ (isset($certificate) && isset($certificate->inspector_date)) ? $certificate->inspector_date->format('Y-m-d') : date('Y-m-d') }}" style="width: 100%">
        </td>
        <td class="var-label" style="background: #f9f9fa; font-weight: bold; border: 1px solid #000; padding: 5px;">Date:</td>
    </tr>
</table>

<!-- Signature Modal -->
<div class="modal" id="signatureModal" tabindex="-1" role="dialog" aria-labelledby="signatureModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="signatureModalLabel">Add Signature</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="signature-container">
                    <div class="form-group">
                        <div class="signature-pad-container">
                            <canvas id="modal-signature-pad" class="signature-pad" width="500" height="200"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="clear-modal-signature">Clear</button>
                <button type="button" class="btn btn-primary" id="save-modal-signature">Save Signature</button>
            </div>
        </div>
    </div>
</div>

<style>
.official-stamp {
    max-width: 50px;
    max-height: 50px;
    object-fit: contain;
    opacity: 0.8;
}

.signature-pad-container {
    border: 1px solid #e0e0e0;
    border-radius: 4px;
    overflow: hidden;
}

.signature-pad {
    width: 100%;
    background-color: #fff;
}
</style>

<!-- JavaScript for signature functionality -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize signature pad when modal is shown
        let modalSignaturePad;
        let currentSignatureTarget;
        
        // Initialize modal signature pad
        $('#signatureModal').on('shown.bs.modal', function() {
            const canvas = document.getElementById('modal-signature-pad');
            modalSignaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgb(255, 255, 255)',
                penColor: 'rgb(0, 0, 0)'
            });
        });
        
        // Clear signature pad
        $('#clear-modal-signature').on('click', function() {
            if (modalSignaturePad) {
                modalSignaturePad.clear();
            }
        });
        
        // Handle inspector signature button
        $('#inspector-sign-btn').on('click', function() {
            currentSignatureTarget = 'inspector';
            $('#signatureModal').modal('show');
        });
        
        // Save signature from modal
        $('#save-modal-signature').on('click', function() {
            if (modalSignaturePad && !modalSignaturePad.isEmpty()) {
                const signatureData = modalSignaturePad.toDataURL();
                
                if (currentSignatureTarget === 'inspector') {
                    $('#inspector_signature_data').val(signatureData);
                    $('#inspector-signature-preview').html(`<img src="${signatureData}" alt="Inspector Signature" style="max-height: 40px;">`);
                }
                
                $('#signatureModal').modal('hide');
            } else {
                alert('Please provide a signature first.');
            }
        });
    });
</script>
