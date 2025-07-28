/**
 * SMAW Certificate Form Signature Pads
 * Handles initialization and management of signature pads
 */

// Immediately-invoked function expression (IIFE) to avoid polluting global scope
(function() {
    'use strict';
    
    /**
     * Initialize signature pads for the form
     */
    function initializeSignaturePads() {
        try {
            console.log("Initializing signature pads...");
            
            // Initialize inspector signature pad
            const inspectorPad = document.getElementById('inspector_signature_pad');
            if (inspectorPad) {
                console.log("Found inspector signature pad element");
                const inspectorSignaturePad = new SignaturePad(inspectorPad, {
                    backgroundColor: 'rgba(255, 255, 255, 0)',
                    penColor: 'black'
                });
                
                // Load existing inspector signature if available
                const inspectorSignatureInput = document.getElementById('inspector_signature');
                if (inspectorSignatureInput && inspectorSignatureInput.value) {
                    try {
                        console.log("Loading existing inspector signature");
                        inspectorSignaturePad.fromDataURL(inspectorSignatureInput.value);
                    } catch (e) {
                        console.error("Error loading inspector signature:", e);
                    }
                }
                
                // Save signature when stroke ends
                inspectorSignaturePad.addEventListener('endStroke', () => {
                    if (inspectorSignatureInput) {
                        inspectorSignatureInput.value = inspectorSignaturePad.toDataURL();
                        console.log("Inspector signature updated");
                    }
                });
                
                // Setup clear button
                const inspectorClearBtn = document.querySelector('button[data-pad="inspector_signature_pad"]');
                if (inspectorClearBtn) {
                    console.log("Found inspector signature clear button");
                    inspectorClearBtn.addEventListener('click', function() {
                        inspectorSignaturePad.clear();
                        if (inspectorSignatureInput) {
                            inspectorSignatureInput.value = '';
                        }
                        console.log("Inspector signature cleared");
                    });
                }
            } else {
                console.log("Inspector signature pad element not found");
            }
            
            // Initialize welder signature pad
            const welderPad = document.getElementById('welder_signature_pad');
            if (welderPad) {
                console.log("Found welder signature pad element");
                const welderSignaturePad = new SignaturePad(welderPad, {
                    backgroundColor: 'rgba(255, 255, 255, 0)',
                    penColor: 'black'
                });
                
                // Load existing welder signature if available
                const welderSignatureInput = document.getElementById('welder_signature');
                if (welderSignatureInput && welderSignatureInput.value) {
                    try {
                        console.log("Loading existing welder signature");
                        welderSignaturePad.fromDataURL(welderSignatureInput.value);
                    } catch (e) {
                        console.error("Error loading welder signature:", e);
                    }
                }
                
                // Save signature when stroke ends
                welderSignaturePad.addEventListener('endStroke', () => {
                    if (welderSignatureInput) {
                        welderSignatureInput.value = welderSignaturePad.toDataURL();
                        console.log("Welder signature updated");
                    }
                });
                
                // Setup clear button
                const welderClearBtn = document.querySelector('button[data-pad="welder_signature_pad"]');
                if (welderClearBtn) {
                    console.log("Found welder signature clear button");
                    welderClearBtn.addEventListener('click', function() {
                        if (welderSignaturePad) {
                            welderSignaturePad.clear();
                        }
                        if (welderSignatureInput) {
                            welderSignatureInput.value = '';
                        }
                        console.log("Welder signature cleared");
                    });
                }
            } else {
                console.log("Welder signature pad element not found");
            }
            
            // Legacy signature handling (if needed)
            const signatureCanvas = document.getElementById('signature-pad');
            if (signatureCanvas) {
                const signaturePad = new SignaturePad(signatureCanvas, {
                    backgroundColor: 'rgba(255, 255, 255, 0)',
                    penColor: 'black'
                });
                
                // Save signature data on form submission
                signatureCanvas.addEventListener('mouseup', function() {
                    if (!signaturePad.isEmpty()) {
                        document.getElementById('signature_data').value = signaturePad.toDataURL();
                    }
                });
                
                signatureCanvas.addEventListener('touchend', function() {
                    if (!signaturePad.isEmpty()) {
                        document.getElementById('signature_data').value = signaturePad.toDataURL();
                    }
                });
            }
            
            // Initialize inspector signature pad (legacy format)
            const inspectorSignatureCanvas = document.getElementById('inspector-signature-pad');
            if (inspectorSignatureCanvas) {
                const inspectorSignaturePad = new SignaturePad(inspectorSignatureCanvas, {
                    backgroundColor: 'rgba(255, 255, 255, 0)',
                    penColor: 'black'
                });
                
                // Load existing signature if available
                const inspectorSignatureElement = document.getElementById('inspector_signature_data');
                const existingInspectorSignature = inspectorSignatureElement ? inspectorSignatureElement.value : null;
                if (existingInspectorSignature) {
                    try {
                        inspectorSignaturePad.fromDataURL(existingInspectorSignature);
                    } catch (e) {
                        console.error("Error loading inspector signature:", e);
                    }
                }
                
                // Clear button
                const clearInspectorButton = document.getElementById('clear-inspector-signature');
                if (clearInspectorButton) {
                    clearInspectorButton.addEventListener('click', function() {
                        inspectorSignaturePad.clear();
                        document.getElementById('inspector_signature_data').value = '';
                    });
                }
                
                // Save signature data on form submission
                inspectorSignatureCanvas.addEventListener('mouseup', function() {
                    if (!inspectorSignaturePad.isEmpty()) {
                        document.getElementById('inspector_signature_data').value = inspectorSignaturePad.toDataURL();
                    }
                });
                
                inspectorSignatureCanvas.addEventListener('touchend', function() {
                    if (!inspectorSignaturePad.isEmpty()) {
                        document.getElementById('inspector_signature_data').value = inspectorSignaturePad.toDataURL();
                    }
                });
            }
        } catch (e) {
            console.error('Error initializing signature pads:', e);
        }
    }
    
    // Expose function to global scope
    window.initializeSignaturePads = initializeSignaturePads;
})();
