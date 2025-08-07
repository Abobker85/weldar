
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
            <input type="text" class="form-input" name="inspector_name" placeholder="Ahmed Yousry" value="{{ old('inspector_name', isset($certificate) ? $certificate->inspector_name : '') }}" style="width: 100%">
        </td>
        <td class="var-label" style="background: #f9f9fa; font-weight: bold; border: 1px solid #000; padding: 5px;">Name:</td>
    </tr>
    <tr>
        <td class="var-label" style="background: #f8f9fa; font-weight: bold; border: 1px solid #000; padding: 5px;">Signature:</td>
        <td class="var-value" style="height: 25px; border: 1px solid #000; padding: 5px; position: relative;">
            <button type="button" class="btn btn-sm btn-primary" id="inspector-sign-btn" style="position: relative; z-index: 10;">Sign</button>
            <div id="inspector-signature-preview" style="display: inline-block; height: 40px; margin-left: 10px;"></div>
            <input type="hidden" name="inspector_signature_data" id="inspector_signature_data">
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
            <input type="date" class="form-input" name="inspector_date" value="{{ date('Y-m-d') }}" style="width: 100%">
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
.custom-certification-wrapper {
    display: inline-block;
    margin-left: 5px;
}

.custom-certification-input {
    width: 100%;
    min-width: 200px;
    padding: 2px 5px;
    font-size: 9px;
    font-family: Arial, sans-serif;
    border: 1px solid #ddd;
    border-radius: 3px;
    background-color: #f8f9fa;
    transition: all 0.3s;
}

.custom-certification-input:focus {
    border-color: #007bff;
    background-color: #fff;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
}

.custom-certification-input::placeholder {
    color: #aaa;
    font-style: italic;
}

/* Signature Pad Styles */
.welder-signature-container {
    margin: 15px 0;
    padding: 15px;
    border-bottom: 1px solid #000;
}

.welder-signature-container h4 {
    margin-top: 0;
    margin-bottom: 10px;
    font-weight: bold;
}

.signature-pad-container {
    position: relative;
    width: 100%;
    border: 1px solid #ccc;
    border-radius: 4px;
    background-color: #fff;
    margin-bottom: 10px;
}

.signature-pad {
    width: 100%;
    height: 150px;
    touch-action: none;
}

.signature-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-bottom: 10px;
}

/* Signature Cell Styles */
.signature-cell {
    position: relative;
    padding: 5px;
}

.signature-preview {
    display: inline-block;
    height: 100%;
    width: calc(100% - 60px);
    vertical-align: middle;
}

.signature-preview img {
    max-height: 40px;
    max-width: 100%;
}

/* Modal Styles */
.modal {
    display: none;
    position: fixed;
    z-index: 1050;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.4);
}

.modal-dialog {
    position: relative;
    width: auto;
    margin: 10px auto;
    max-width: 600px;
}

.modal-content {
    position: relative;
    background-color: #fefefe;
    margin: auto;
    padding: 0;
    border: 1px solid #888;
    width: 100%;
    box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
    border-radius: 4px;
}

.modal-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    padding: 1rem;
    border-bottom: 1px solid #e9ecef;
    border-top-left-radius: 3px;
    border-top-right-radius: 3px;
}

.modal-title {
    margin-bottom: 0;
    line-height: 1.5;
    font-size: 1.25rem;
}

.close {
    float: right;
    font-size: 1.5rem;
    font-weight: 700;
    line-height: 1;
    color: #000;
    text-shadow: 0 1px 0 #fff;
    opacity: .5;
    background: none;
    border: 0;
    padding: 1rem;
    margin: -1rem -1rem -1rem auto;
}

.modal-body {
    position: relative;
    padding: 1rem;
}

.modal-footer {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding: 1rem;
    border-top: 1px solid #e9ecef;
}

.modal-footer > :not(:first-child) {
    margin-left: .25rem;
}

.modal-footer > :not(:last-child) {
    margin-right: .25rem;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const canvas = document.getElementById('signature-pad');
    const signatureDataInput = document.getElementById('signature_data');
    
    if (canvas) {
        const signaturePad = new SignaturePad(canvas, {
            backgroundColor: 'rgba(255, 255, 255, 0.8)',
            penColor: 'black'
        });
        
        // Clear signature button
        document.getElementById('clear-signature').addEventListener('click', function() {
            signaturePad.clear();
            signatureDataInput.value = '';
        });
        
        // Update hidden input when signature changes
        signaturePad.addEventListener("endStroke", () => {
            signatureDataInput.value = signaturePad.toDataURL();
            console.log("Signature captured and stored in hidden field");
        });
        
        // If there's an existing signature, load it
        if (signatureDataInput.value) {
            signaturePad.fromDataURL(signatureDataInput.value);
        }
        
        // Handle window resize to maintain signature pad aspect ratio
        function resizeCanvas() {
            const ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            
            // Redraw signature if it exists
            if (signatureDataInput.value) {
                signaturePad.fromDataURL(signatureDataInput.value);
            } else {
                signaturePad.clear(); // Otherwise isEmpty() might return incorrect value
            }
        }
        
        window.addEventListener('resize', resizeCanvas);
        resizeCanvas();
    }
    
    // Inspector signature functionality
    let currentSignatureTarget = null;
    let modalSignaturePad = null;
    
    // Initialize the modal signature pad
    function initModalSignaturePad() {
        const canvas = document.getElementById('modal-signature-pad');
        if (canvas) {
            modalSignaturePad = new SignaturePad(canvas, {
                backgroundColor: 'rgba(255, 255, 255, 0.8)',
                penColor: 'black'
            });
            
            // Clear signature button
            document.getElementById('clear-modal-signature').addEventListener('click', function() {
                modalSignaturePad.clear();
            });
            
            // Save signature button
            document.getElementById('save-modal-signature').addEventListener('click', function() {
                if (modalSignaturePad.isEmpty()) {
                    alert('Please provide a signature first.');
                    return;
                }
                
                const signatureData = modalSignaturePad.toDataURL();
                
                if (currentSignatureTarget === 'inspector-signature') {
                    // Save inspector signature
                    document.getElementById('inspector_signature_data').value = signatureData;
                    document.getElementById('inspector-signature-preview').innerHTML = 
                        `<img src="${signatureData}" alt="Inspector Signature">`;
                }
                
                // Close the modal
                hideModal();
            });
        }
    }
    
    // Show modal function
    function showModal() {
        const modal = document.getElementById('signatureModal');
        modal.style.display = 'block';
        
        // Resize canvas to fit modal
        setTimeout(() => {
            if (modalSignaturePad) {
                const canvas = document.getElementById('modal-signature-pad');
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
                modalSignaturePad.clear();
            }
        }, 100);
    }
    
    // Hide modal function
    function hideModal() {
        const modal = document.getElementById('signatureModal');
        modal.style.display = 'none';
    }
    
    // Setup event listeners for the modal
    document.querySelectorAll('.open-signature-modal').forEach(button => {
        button.addEventListener('click', function() {
            currentSignatureTarget = this.getAttribute('data-target');
            showModal();
        });
    });
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        const modal = document.getElementById('signatureModal');
        if (event.target === modal) {
            hideModal();
        }
    });
    
    // Close button functionality
    document.querySelector('.modal .close').addEventListener('click', hideModal);
    
    // Initialize modal signature pad
    initModalSignaturePad();
    
    // If there's an existing inspector signature, show it
    const inspectorSignatureData = document.getElementById('inspector_signature_data').value;
    if (inspectorSignatureData) {
        document.getElementById('inspector-signature-preview').innerHTML = 
            `<img src="${inspectorSignatureData}" alt="Inspector Signature">`;
    }
    
    // Add inspector signature modal if it doesn't exist
    if (!document.getElementById('inspector-signature-modal')) {
        const modalHtml = `
        <div id="inspector-signature-modal" class="modal" style="display: none; position: fixed; z-index: 1050; left: 0; top: 0; width: 100%; height: 100%; overflow: auto; background-color: rgba(0,0,0,0.4);">
            <div class="modal-dialog" style="position: relative; width: 600px; margin: 60px auto;">
                <div class="modal-content" style="background-color: #fefefe; padding: 0; border: 1px solid #888; border-radius: 5px; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                    <div class="modal-header" style="display: flex; justify-content: space-between; align-items: center; padding: 15px; border-bottom: 1px solid #e9ecef;">
                        <h5 style="margin: 0;">Add Inspector Signature</h5>
                        <button type="button" class="close-modal" style="background: none; border: none; font-size: 20px; cursor: pointer;">&times;</button>
                    </div>
                    <div class="modal-body" style="padding: 20px;">
                        <div class="signature-pad-wrapper" style="width: 100%; border: 1px solid #ccc; border-radius: 4px;">
                            <canvas id="inspector-signature-canvas" width="560" height="200" style="width: 100%; height: 200px;"></canvas>
                        </div>
                    </div>
                    <div class="modal-footer" style="display: flex; justify-content: flex-end; padding: 15px; border-top: 1px solid #e9ecef;">
                        <button type="button" id="clear-inspector-signature" class="btn btn-secondary" style="background-color: #6c757d; color: white; border: none; padding: 8px 16px; margin-right: 10px; border-radius: 4px; cursor: pointer;">Clear</button>
                        <button type="button" id="save-inspector-signature" class="btn btn-primary" style="background-color: #007bff; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;">Save Signature</button>
                    </div>
                </div>
            </div>
        </div>`;
        
        // Add modal to the document body
        document.body.insertAdjacentHTML('beforeend', modalHtml);
    }
    
    // Initialize inspector signature functionality
    const inspectorSignBtn = document.getElementById('inspector-sign-btn');
    const inspectorModal = document.getElementById('inspector-signature-modal');
    const inspectorCanvas = document.getElementById('inspector-signature-canvas');
    const inspectorSignatureDataInput = document.getElementById('inspector_signature_data');
    const inspectorSignaturePreview = document.getElementById('inspector-signature-preview');
    
    let inspectorSignaturePad = null;
    
    if (inspectorSignBtn && inspectorCanvas) {
        // Initialize signature pad
        inspectorSignaturePad = new SignaturePad(inspectorCanvas, {
            backgroundColor: 'rgba(255, 255, 255, 0.8)',
            penColor: 'black'
        });
        
        // Show modal when sign button is clicked
        inspectorSignBtn.addEventListener('click', function() {
            inspectorModal.style.display = 'block';
            
            // Resize canvas
            setTimeout(() => {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                inspectorCanvas.width = inspectorCanvas.offsetWidth * ratio;
                inspectorCanvas.height = inspectorCanvas.offsetHeight * ratio;
                inspectorCanvas.getContext("2d").scale(ratio, ratio);
                
                // If there's already a signature, load it
                if (inspectorSignatureDataInput.value) {
                    inspectorSignaturePad.fromDataURL(inspectorSignatureDataInput.value);
                }
            }, 100);
        });
        
        // Hide modal when clicking close button
        document.querySelector('.close-modal').addEventListener('click', function() {
            inspectorModal.style.display = 'none';
        });
        
        // Hide modal when clicking outside
        window.addEventListener('click', function(event) {
            if (event.target === inspectorModal) {
                inspectorModal.style.display = 'none';
            }
        });
        
        // Clear signature
        document.getElementById('clear-inspector-signature').addEventListener('click', function() {
            inspectorSignaturePad.clear();
        });
        
        // Save signature
        document.getElementById('save-inspector-signature').addEventListener('click', function() {
            if (inspectorSignaturePad.isEmpty()) {
                alert('Please provide a signature');
                return;
            }
            
            const signatureData = inspectorSignaturePad.toDataURL();
            inspectorSignatureDataInput.value = signatureData;
            inspectorSignaturePreview.innerHTML = `<img src="${signatureData}" alt="Inspector Signature" style="max-height: 40px;">`;
            inspectorModal.style.display = 'none';
            
            console.log('Inspector signature saved');
        });
        
        // Load existing signature if available
        if (inspectorSignatureDataInput.value) {
            inspectorSignaturePreview.innerHTML = `<img src="${inspectorSignatureDataInput.value}" alt="Inspector Signature" style="max-height: 40px;">`;
        }
    } else {
        console.error('Inspector signature elements not found');
    }
});
</script>
