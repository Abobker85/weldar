<!-- Organization Section -->
<div class="organization-section" style="border: 2px solid #000; border-top: none;">
    <table class="organization-table" style="width: 100%; border-collapse: collapse; font-size: 9px;">
        <tr>
            <td colspan="3" style="background: #f8f9fa; font-weight: bold; text-align: center; height: 25px; border: 1px solid #000;">
                Organization
            </td>
            <td style="width: 100px; background: #f8f9fa; font-weight: bold; text-align: center; height: 25px; border: 1px solid #000;">
                QR CODE
            </td>
        </tr>
        <tr>
            <td class="var-label" style="width: 25%; background: #f8f9fa; font-weight: bold; border: 1px solid #000; padding: 5px;">
                Test Witnessed by:
            </td>
            <td class="var-value" style="width: 35%; border: 1px solid #000; padding: 5px;">
                <input type="text" class="form-input" name="test_witnessed_by" 
                    value="{{ old('test_witnessed_by', $certificate->test_witnessed_by ?? 'ELITE ENGINEERING ARABIA') }}" 
                    style="width: 100%; border: none;" readonly>
            </td>
            <td class="var-label" style="width: 25%; background: #f8f9fa; font-weight: bold; border: 1px solid #000; padding: 5px;">
                Reviewed / Approved by:
            </td>
            <td rowspan="6" style="text-align: center; width: 100px; border: 1px solid #000; padding: 5px;">
                <div style="width: 90px; height: 90px; border: 1px solid #000; background: #f0f0f0; display: flex; align-items: center; justify-content: center; font-size: 8px; margin: 0 auto;">
                    QR Code<br>Will be<br>Generated
                </div>
            </td>
        </tr>
        <tr>
            <td class="var-label" style="background: #f8f9fa; font-weight: bold; border: 1px solid #000; padding: 5px;">
                Name:
            </td>
            <td class="var-value" style="border: 1px solid #000; padding: 5px;">
                <input type="text" class="form-input" name="witness_name" 
                    value="{{ old('witness_name', $certificate->witness_name ?? 'Ahmed Yousry') }}" 
                    style="width: 100%; border: none;" placeholder="Ahmed Yousry">
            </td>
            <td class="var-label" style="background: #f8f9fa; font-weight: bold; border: 1px solid #000; padding: 5px;">
                Name:
            </td>
        </tr>
        <tr>
            <td class="var-label" style="background: #f8f9fa; font-weight: bold; border: 1px solid #000; padding: 5px;">
                Signature:
            </td>
            <td class="var-value" style="border: 1px solid #000; padding: 5px; height: 50px; position: relative;">
                <button type="button" class="btn btn-sm btn-primary signature-btn" onclick="openSignatureModal('witness')" 
                    style="position: absolute; top: 5px; left: 5px; z-index: 10; font-size: 8px; padding: 2px 6px;">
                    Sign
                </button>
                <div id="witness_signature_preview" style="width: 100%; height: 40px; display: flex; align-items: center; justify-content: center;">
                    @if(isset($certificate) && $certificate->witness_signature)
                        <img src="{{ $certificate->witness_signature }}" alt="Witness Signature" style="max-height: 35px; max-width: 100%;">
                    @else
                        <span style="color: #666; font-style: italic; font-size: 8px;">Click Sign to add signature</span>
                    @endif
                </div>
                <input type="hidden" name="witness_signature" id="witness_signature" value="{{ old('witness_signature', $certificate->witness_signature ?? '') }}">
            </td>
            <td class="var-label" style="background: #f8f9fa; font-weight: bold; border: 1px solid #000; padding: 5px;">
                Signature:
            </td>
        </tr>
        <tr>
            <td class="var-label" style="background: #f8f9fa; font-weight: bold; border: 1px solid #000; padding: 5px;">
                Stamp:
            </td>
            <td class="var-value" style="border: 1px solid #000; padding: 5px; height: 50px; text-align: center; position: relative;">
                @if(\App\Models\AppSetting::getValue('company_stamp_path'))
                    <img src="{{ asset('storage/' . \App\Models\AppSetting::getValue('company_stamp_path')) }}"
                         alt="Company Stamp" style="max-width: 60px; max-height: 45px; opacity: 0.8;">
                @else
                    <div style="color: #666; font-style: italic; font-size: 8px; display: flex; align-items: center; justify-content: center; height: 100%;">
                        Official Stamp
                    </div>
                @endif
            </td>
            <td class="var-label" style="background: #f8f9fa; font-weight: bold; border: 1px solid #000; padding: 5px;">
                Stamp:
            </td>
        </tr>
        <tr>
            <td class="var-label" style="background: #f8f9fa; font-weight: bold; border: 1px solid #000; padding: 5px;">
                Date:
            </td>
            <td class="var-value" style="border: 1px solid #000; padding: 5px;">
                <input type="date" class="form-input" name="witness_date" 
                    value="{{ old('witness_date', isset($certificate) ? $certificate->witness_date?->format('Y-m-d') : date('Y-m-d')) }}" 
                    style="width: 100%; border: none;">
            </td>
            <td class="var-label" style="background: #f8f9fa; font-weight: bold; border: 1px solid #000; padding: 5px;">
                Date:
            </td>
        </tr>
    </table>
</div>

<!-- Signature Modal -->
<div id="signatureModal" class="modal" style="display: none; position: fixed; z-index: 1050; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4);">
    <div class="modal-dialog" style="position: relative; width: 600px; margin: 60px auto; background-color: #fefefe; padding: 0; border: 1px solid #888; border-radius: 5px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
        <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; padding: 15px; border-bottom: 1px solid #e9ecef;">
            <h5 style="margin: 0;">Add Signature</h5>
            <button type="button" class="close-modal" style="background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
        </div>
        <div class="modal-body" style="padding: 20px;">
            <div style="text-align: center; margin-bottom: 10px; font-size: 12px; color: #666;">
                Please sign in the box below
            </div>
            <div style="width: 100%; border: 1px solid #ccc; border-radius: 4px; background-color: #fff;">
                <canvas id="signature_canvas" width="560" height="200" style="width: 100%; height: 200px; cursor: crosshair;"></canvas>
            </div>
        </div>
        <div class="modal-footer" style="display: flex; justify-content: flex-end; padding: 15px; border-top: 1px solid #e9ecef; gap: 10px;">
            <button type="button" id="clear_signature" class="btn btn-secondary" style="background-color: #6c757d; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">
                Clear
            </button>
            <button type="button" id="save_signature" class="btn btn-primary" style="background-color: #007bff; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">
                Save Signature
            </button>
        </div>
    </div>
</div>

<script>
let currentSignatureTarget = null;
let signaturePad = null;

function openSignatureModal(target) {
    currentSignatureTarget = target;
    const modal = document.getElementById('signatureModal');
    modal.style.display = 'block';
    
    // Initialize signature pad
    setTimeout(() => {
        const canvas = document.getElementById('signature_canvas');
        if (canvas && typeof SignaturePad !== 'undefined') {
            signaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgba(255, 255, 255, 0.8)',
                penColor: 'black'
            });
            
            // Resize canvas properly
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
        }
    }, 100);
}

// Close modal when clicking close button
document.addEventListener('DOMContentLoaded', function() {
    const closeBtn = document.querySelector('.close-modal');
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            document.getElementById('signatureModal').style.display = 'none';
        });
    }
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('signatureModal');
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
    
    // Clear signature
    const clearBtn = document.getElementById('clear_signature');
    if (clearBtn) {
        clearBtn.addEventListener('click', function() {
            if (signaturePad) {
                signaturePad.clear();
            }
        });
    }
    
    // Save signature
    const saveBtn = document.getElementById('save_signature');
    if (saveBtn) {
        saveBtn.addEventListener('click', function() {
            if (signaturePad && !signaturePad.isEmpty()) {
                const signatureData = signaturePad.toDataURL();
                
                if (currentSignatureTarget === 'witness') {
                    document.getElementById('witness_signature').value = signatureData;
                    document.getElementById('witness_signature_preview').innerHTML = 
                        `<img src="${signatureData}" alt="Witness Signature" style="max-height: 35px; max-width: 100%;">`;
                }
                
                document.getElementById('signatureModal').style.display = 'none';
            } else {
                alert('Please provide a signature before saving.');
            }
        });
    }
});
</script>

<style>
.signature-btn {
    font-size: 8px !important;
    padding: 2px 6px !important;
    line-height: 1.2 !important;
}

.organization-table input.form-input {
    font-size: 9px;
    padding: 2px 4px;
}

.organization-table input.form-input:focus {
    outline: 1px solid #007bff;
}

.modal {
    font-family: Arial, sans-serif;
}

.btn {
    display: inline-block;
    text-align: center;
    vertical-align: middle;
    user-select: none;
    border: 1px solid transparent;
    text-decoration: none;
    transition: all 0.15s ease-in-out;
}

.btn:hover {
    opacity: 0.85;
}

.btn:focus {
    outline: 0;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
}
</style>