/**
 * FCAW Certificate Helper Functions
 * Specific helper functions for FCAW certificates
 */

/**
 * Generate a certificate number for FCAW based on company ID
 */
function generateFcawCertificateNumber(companyId) {
    if (!companyId) return '';
    
    // Get CSRF token
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Get base URL
    const baseUrl = window.location.origin;
    const apiUrl = `${baseUrl}/api/companies/${companyId}/fcaw-code`;
    
    return fetch(apiUrl, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token,
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        return data.prefix;
    })
    .catch(error => {
        console.error('Error generating certificate number:', error);
        
        // Default certificate number pattern
        return `FCAW-${companyId}-0001`;
    });
}

/**
 * Update certificate number when company changes
 */
document.addEventListener('DOMContentLoaded', function() {
    // Set up event listener for company ID change
    const companySelect = document.getElementById('company_id');
    const certificateNoField = document.getElementById('certificate_no');
    
    if (companySelect && certificateNoField) {
        companySelect.addEventListener('change', function() {
            const companyId = this.value;
            if (companyId) {
                // Show loading indicator
                certificateNoField.value = 'Generating...';
                
                // Get new certificate number
                generateFcawCertificateNumber(companyId)
                    .then(certNo => {
                        certificateNoField.value = certNo;
                    })
                    .catch(error => {
                        console.error('Error updating certificate number:', error);
                        certificateNoField.value = '';
                    });
            }
        });
    }
});
