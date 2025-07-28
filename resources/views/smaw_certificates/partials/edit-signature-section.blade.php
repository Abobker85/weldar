<!-- Final organization section - Now part of the same container -->
<table style="width: 100%; table-layout: fixed; margin: 0; border-collapse: collapse; border-top: 1px solid #000;">
    <tr>
        <td colspan="3" style="background: #f8f9fa; font-weight: bold; text-align: center; height: 25px; border: 1px solid #000;">Organization</td>
        <td style="width: 80px; background: #f8f9fa; font-weight: bold; text-align: center; height: 25px; border: 1px solid #000;">QR CODE</td>
    </tr>
    <tr>
        <td class="var-label" style="width: 20%; background: #f8f9fa; font-weight: bold; border: 1px solid #000; padding: 5px;">Test Witnessed by:</td>
        <td class="var-value" style="width: 40%; border: 1px solid #000; padding: 5px;">
            <input type="text" class="form-input" name="test_witness_org" value="{{ $certificate->test_witness_org ?? \App\Models\AppSetting::getValue('company_name', 'ELITE ENGINEERING ARABIA') }}" style="width: 100%">
        </td>
        <td class="var-value" style="width: 20%; border: 1px solid #000; padding: 5px;">Reviewed / Approved by:</td>
        <td rowspan="6" style="text-align: center; width: 80px; border: 1px solid #000;">
            @if($certificate->qr_code_path)
                <img src="{{ asset('storage/' . $certificate->qr_code_path) }}" alt="QR Code" style="max-width: 80px; max-height: 80px;">
            @else
                <div style="width: 80px; height: 80px; border: 1px solid #000; background: #f0f0f0; display: flex; align-items: center; justify-content: center; font-size: 8px;">
                    QR Code<br>Will be<br>Generated
                </div>
            @endif
        </td>
    </tr>
    <tr>
        <td class="var-label" style="background: #f8f9fa; font-weight: bold; border: 1px solid #000; padding: 5px;">Name:</td>
        <td class="var-value" style="border: 1px solid #000; padding: 5px;">
            <input type="text" class="form-input" name="inspector_name" id="inspector_name" value="{{ $certificate->inspector_name ?? 'Ibrahim Abdullah' }}" style="width: 100%">
        </td>
        <td class="var-label" style="background: #f9f9fa; font-weight: bold; border: 1px solid #000; padding: 5px;">Name:</td>
    </tr>
    <tr>
        <td class="var-label" style="background: #f8f9fa; font-weight: bold; border: 1px solid #000; padding: 5px;">Signature:</td>
        <td class="var-value" style="height: 25px; border: 1px solid #000; padding: 5px; position: relative;">
            <button type="button" class="btn btn-sm btn-primary" id="inspector-sign-btn" style="position: relative; z-index: 10;">Sign</button>
            <div id="inspector-signature-preview" style="display: inline-block; height: 40px; margin-left: 10px;">
                @if($certificate->inspector_signature_data)
                    <img src="{{ $certificate->inspector_signature_data }}" alt="Inspector Signature" style="max-height: 40px;">
                @endif
            </div>
            <input type="hidden" name="inspector_signature_data" id="inspector_signature_data" value="{{ $certificate->inspector_signature_data ?? '' }}">
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
            <input type="date" class="form-input" name="inspector_date" value="{{ $certificate->inspector_date ?? date('Y-m-d') }}" style="width: 100%">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inspectorSignBtn = document.getElementById('inspector-sign-btn');
    if (inspectorSignBtn) {
        inspectorSignBtn.addEventListener('click', function() {
            // Open signature modal
            $('#signatureModal').modal('show');
            
            // Initialize signature pad if needed
            if (typeof SignaturePad !== 'undefined') {
                const canvas = document.getElementById('modal-signature-pad');
                const signaturePad = new SignaturePad(canvas);
                
                // Clear button
                document.getElementById('clear-modal-signature').addEventListener('click', function() {
                    signaturePad.clear();
                });
                
                // Save button
                document.getElementById('save-modal-signature').addEventListener('click', function() {
                    if (!signaturePad.isEmpty()) {
                        const signatureData = signaturePad.toDataURL();
                        document.getElementById('inspector_signature_data').value = signatureData;
                        document.getElementById('inspector-signature-preview').innerHTML = 
                            `<img src="${signatureData}" alt="Inspector Signature" style="max-height: 40px;">`;
                        $('#signatureModal').modal('hide');
                    } else {
                        alert('Please provide a signature first.');
                    }
                });
            }
        });
    }
});
</script>
