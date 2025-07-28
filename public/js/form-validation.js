// Add to existing or create new file

document.addEventListener('DOMContentLoaded', function() {
    // Fix for RT/UT checkboxes to ensure they submit correct boolean values
    const booleanCheckboxes = document.querySelectorAll('input[type="checkbox"][name="rt"], input[type="checkbox"][name="ut"]');
    booleanCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            // Find hidden input with same name and update its value
            const hiddenInput = document.querySelector(`input[type="hidden"][name="${this.name}"]`);
            if (hiddenInput) {
                // Remove it to avoid duplicate submissions
                hiddenInput.parentNode.removeChild(hiddenInput);
            }
            
            // Set checkbox value directly - will be "1" when checked, or won't be submitted when unchecked
            this.value = this.checked ? '1' : '0';
        });
    });
    
    // Initialize the form validation
    const form = document.getElementById('certificate-form');
    if (form) {
        form.addEventListener('submit', function(e) {
            // Process RT/UT values correctly
            const rtCheckbox = document.getElementById('rt');
            const utCheckbox = document.getElementById('ut');
            
            if (rtCheckbox) {
                rtCheckbox.value = rtCheckbox.checked ? '1' : '0';
            }
            
            if (utCheckbox) {
                utCheckbox.value = utCheckbox.checked ? '1' : '0';
            }
        });
    }
    
    // Add print style preservation
    const printButton = document.querySelector('button[onclick="window.print();"]');
    if (printButton) {
        printButton.addEventListener('click', function() {
            // Add print-specific styles to maintain design
            const style = document.createElement('style');
            style.type = 'text/css';
            style.id = 'print-style';
            
            style.innerHTML = `
                @media print {
                    body { margin: 0; padding: 0; }
                    .form-container {
                        width: 210mm;
                        height: auto;
                        margin: 0;
                        padding: 0;
                        box-shadow: none;
                    }
                    .print-buttons { display: none; }
                    
                    /* Keep exact same design and layout */
                    .header-row, .cert-details-row, .welder-info-row,
                    .section-title, .data-grid, .data-cell, .cert-statement,
                    .footer-row, .footer-cell, table, tr, td {
                        box-sizing: border-box !important;
                        page-break-inside: avoid;
                    }
                    
                    .logo-left img, .logo-right img, .official-stamp, .welder-photo img {
                        max-width: 100% !important;
                        max-height: 100% !important;
                    }
                    
                    /* Preserve all font sizes and spacing */
                    * {
                        font-size: inherit !important;
                        line-height: inherit !important;
                    }
                }
            `;
            
            // Remove previous print styles if any
            const oldStyle = document.getElementById('print-style');
            if (oldStyle) {
                oldStyle.remove();
            }
            
            // Add new print styles
            document.head.appendChild(style);
        });
    }
});
