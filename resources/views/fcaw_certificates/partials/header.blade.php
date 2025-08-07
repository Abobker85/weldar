<!-- Header with logos - exactly like certificate -->
<div class="header-row" style="margin-bottom: 0; height: 80px;">
    <div class="logo-left">
        @php
            $companyLogoPath = \App\Models\AppSetting::getValue('company_logo_path');
            $logoExists = !empty($companyLogoPath) && file_exists(public_path('storage/' . $companyLogoPath));
        @endphp
        
        @if($logoExists)
            <img src="{{ asset('storage/' . $companyLogoPath) }}" alt="Company Logo" style="max-width: 90px; max-height: 50px;">
        @else
            <div style="font-size: 12px; font-weight: bold; text-align: center; color: #0066cc;">
                <div style="background: #0066cc; color: white; padding: 2px 8px; border-radius: 15px; margin-bottom: 3px;">
                    ELITE</div>
                <div style="font-size: 8px; color: #666;">ENGINEERING ARABIA</div>
            </div>
        @endif
    </div>
    <div class="header-center">
        <h1 style="font-size: 16px; margin-bottom: 2px;">{{ \App\Models\AppSetting::getValue('company_name', 'Elite Engineering Arabia') }}</h1>
        <div class="contact-info" style="font-size: 8px; margin: 2px 0;">
            e-mail: {{ \App\Models\AppSetting::getValue('email', 'ahmed.yousry@eliteengineeringarabia.com') }} &nbsp;&nbsp;&nbsp;&nbsp; {{ \App\Models\AppSetting::getValue('website', 'www.') }}
        </div>
        <h2 style="font-size: 14px; font-weight: bold; margin-top: 5px;">WELDER PERFORMANCE QUALIFICATIONS
        </h2>
    </div>
    <div class="logo-right">
        <div id="company-code-display" style="font-size: 14px; font-weight: bold; text-align: center;">
            <span style="color: #dc3545; font-size: 16px;">AIC</span><span
                style="color: #999; font-size: 12px;">steel</span>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initial setup - call these functions on page load
    setTimeout(() => {
        // Find any functions we need to call to initialize the form properly
        if (typeof handleSpecimenToggle === 'function') {
            console.log('Calling handleSpecimenToggle on load');
            handleSpecimenToggle();
        }
        
        if (typeof updateTestFields === 'function') {
            console.log('Calling updateTestFields on load');
            updateTestFields();
        }
    }, 300);

    const welderSelect = document.getElementById('welder_id');
    if (welderSelect) {
        welderSelect.addEventListener('change', function() {
            const welderId = this.value;
            if (welderId) {
                // Fetch welder details including company
                fetch(`public/welders/${welderId}/details`)
                    .then(response => response.json())
                    .then(data => {
                        const companyCodeDisplay = document.getElementById('company-code-display');
                        if (companyCodeDisplay && data.company) {
                            companyCodeDisplay.innerHTML = `
                                <span style="color: #dc3545; font-size: 16px;">${data.company.code || 'AIC'}</span>
                                <span style="color: #999; font-size: 12px;">${data.company.name || 'steel'}</span>
                            `;
                        }
                    })
                    .catch(error => console.error('Error fetching welder details:', error));
            }
        });
    }
});

function submitCertificateForm() {
    // Clear previous error messages
    clearValidationErrors();
    
    // Get the form
    const form = document.getElementById('certificate-form');
    
    // Perform pre-submission validation checks for special conditions
    preSubmitValidationFixes(form);
    
    const formData = new FormData(form);
    
    // Debug form data
    console.log('Form action:', form.action);
    console.log('Form method:', form.method);
    
    // Check for form encoding and method attributes
    console.log('Form enctype:', form.enctype);
    console.log('Form attributes:', Array.from(form.attributes).map(attr => `${attr.name}=${attr.value}`).join(', '));
    
    // Add CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Use fetch API to submit the form
    // Log what we're sending to help with debugging
    console.log('Submitting form to:', form.action);
    
    // Convert FormData to a more readable format for debugging
    console.log('Form data entries:');
    for (let pair of formData.entries()) {
        if (typeof pair[1] === 'string' && pair[1].length < 100) {
            console.log(pair[0] + ': ' + pair[1]);
        } else {
            console.log(pair[0] + ': [data too long to display]');
        }
    }
    
    // Make sure we're explicitly handling the method override for PUT requests
    if (form.method.toUpperCase() === 'POST' && form.querySelector('input[name="_method"][value="PUT"]')) {
        console.log('Detected PUT method override');
    }
    
    fetch(form.action, {
        method: form.method, // Use the form's method (POST or PUT with method override)
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
            // Note: Do not set Content-Type when using FormData, the browser will set it automatically
        },
        body: formData,
        credentials: 'same-origin' // Include credentials like cookies
    })
    .then(response => {
        console.log('Response status:', response.status);
        console.log('Response headers:', Array.from(response.headers.entries()));
        
        if (!response.ok) {
            return response.json().then(errorData => {
                throw errorData;
            });
        }
        
        // Clone the response so we can log it as text and also parse it as JSON
        return response.text().then(text => {
            console.log('Raw response text:', text);
            try {
                return JSON.parse(text);
            } catch (e) {
                console.error('Failed to parse response as JSON:', e);
                throw new Error('Invalid JSON response from server');
            }
        });
    })
    .then(data => {
        // Debug response data with more details
        console.log('Response data type:', typeof data);
        console.log('Response data:', data);
        console.log('Response keys:', Object.keys(data));
        
        // Extract certificate URL - try multiple ways to be thorough
        let certificateUrl = null;
        
        if (typeof data === 'object' && data !== null) {
            // Try direct property access with logging
            console.log('certificate_url present?', 'certificate_url' in data);
            if ('certificate_url' in data) {
                console.log('certificate_url value:', data.certificate_url);
                certificateUrl = data.certificate_url;
            }
            
            // If we didn't find it, look for nested properties or alternative names
            if (!certificateUrl) {
                const props = ['certificateUrl', 'certificate', 'print_url', 'url', 'open_certificate'];
                for (const prop of props) {
                    if (prop in data) {
                        console.log(`Found alternative in data.${prop}:`, data[prop]);
                        if (typeof data[prop] === 'string' && data[prop].includes('/certificate')) {
                            certificateUrl = data[prop];
                            break;
                        } else if (typeof data[prop] === 'object' && data[prop] !== null && 'url' in data[prop]) {
                            certificateUrl = data[prop].url;
                            break;
                        }
                    }
                }
            }
        }
        
        if (data.success) {
            // Show success message
            showNotification('success', data.message || 'Certificate created successfully');
            
            // Open certificate in new tab if URL was found - USING MORE DIRECT APPROACH
            if (certificateUrl) {
                console.log('Opening certificate in new tab:', certificateUrl);
                
                // Create a hidden link element and click it programmatically
                // This approach is more reliable for opening new tabs
                const link = document.createElement('a');
                link.href = certificateUrl;
                link.target = '_blank';
                link.rel = 'noopener noreferrer';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);
                
                // Also show a message that certificate is opening in new tab
                showNotification('info', 'Certificate is opening in a new tab');
            } else {
                console.warn('No certificate URL found in the response');
                // Fallback - attempt to construct the URL if we have the certificate ID
                if (data.certificate && data.certificate.id) {
                    const fallbackUrl = `/fcaw-certificates/${data.certificate.id}/certificate`;
                    console.log('Using fallback URL:', fallbackUrl);
                    
                    const link = document.createElement('a');
                    link.href = fallbackUrl;
                    link.target = '_blank';
                    link.rel = 'noopener noreferrer';
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);
                }
            }
            
            // Reset form instead of redirecting to index page
            console.log('Resetting form to create a new certificate');
            setTimeout(() => {
                // Reset the form
                form.reset();
                
                // Re-initialize any select2 or other enhanced form elements
                const select2Elements = document.querySelectorAll('select.select2');
                if (typeof $ !== 'undefined' && $.fn && $.fn.select2) {
                    select2Elements.forEach(el => {
                        try {
                            $(el).val('').trigger('change');
                        } catch (e) {
                            console.error('Error resetting select2:', e);
                        }
                    });
                }
                
                // Show a message that form has been reset
                showNotification('info', 'Form reset. You can create a new certificate now.');
            }, 500);
        } else {
            // Show error message
            showNotification('error', data.message || 'An error occurred');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Handle validation errors
        if (error.errors) {
            displayValidationErrors(error.errors);
        }
        
        // Show error notification
        showNotification('error', error.message || 'An error occurred while processing your request');
    });
    
    // Prevent default form submission
    return false;
}

// Function to fix validation issues before form submission
function preSubmitValidationFixes(form) {
    // Special case 1: Diameter field shouldn't be required for plate specimens
    const plateCheckbox = document.getElementById('plate_specimen');
    const pipeCheckbox = document.getElementById('pipe_specimen');
    const diameterField = document.getElementById('diameter');
    
    if (plateCheckbox && plateCheckbox.checked && (!pipeCheckbox || !pipeCheckbox.checked)) {
        // If only plate is checked, diameter is not required
        console.log('Fixing diameter field validation for plate specimen');
        if (diameterField) {
            diameterField.required = false;
            diameterField.removeAttribute('required');
        }
    }
    
    // Special case 2: evaluated_by and supervised_by shouldn't be required with RT/UT
    const rtChecked = document.getElementById('rt') && document.getElementById('rt').checked;
    const utChecked = document.getElementById('ut') && document.getElementById('ut').checked;
    
    if (rtChecked || utChecked) {
        // If either RT or UT is checked, evaluated_by and supervised_by are not required
        console.log('Fixing evaluated_by and supervised_by validation for RT/UT');
        const evaluatedBy = document.getElementById('evaluated_by');
        const supervisedBy = document.getElementById('supervised_by');
        
        if (evaluatedBy) {
            evaluatedBy.required = false;
            evaluatedBy.removeAttribute('required');
        }
        
        if (supervisedBy) {
            supervisedBy.required = false;
            supervisedBy.removeAttribute('required');
        }
    }
}

// Function to clear validation errors
function clearValidationErrors() {
    // Remove all error messages
    const errorMessages = document.querySelectorAll('.error-message');
    errorMessages.forEach(el => el.remove());
    
    // Remove error classes from inputs
    const invalidInputs = document.querySelectorAll('.is-invalid');
    invalidInputs.forEach(el => el.classList.remove('is-invalid'));
}

// Function to display validation errors
function displayValidationErrors(errors) {
    for (const field in errors) {
        const input = document.querySelector(`[name="${field}"]`);
        if (input) {
            // Add error class
            input.classList.add('is-invalid');
            
            // Create error message element
            const errorDiv = document.createElement('div');
            errorDiv.className = 'invalid-feedback error-message';
            errorDiv.textContent = errors[field][0]; // First error message
            
            // Insert error message after the input
            if (input.parentNode) {
                input.parentNode.insertBefore(errorDiv, input.nextSibling);
            }
            
            // Scroll to first error
            if (field === Object.keys(errors)[0]) {
                input.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    }
}

// Function to show notifications
function showNotification(type, message) {
    // Create notification element if it doesn't exist
    let notificationContainer = document.getElementById('notification-container');
    
    if (!notificationContainer) {
        notificationContainer = document.createElement('div');
        notificationContainer.id = 'notification-container';
        notificationContainer.style.position = 'fixed';
        notificationContainer.style.top = '20px';
        notificationContainer.style.right = '20px';
        notificationContainer.style.zIndex = '9999';
        document.body.appendChild(notificationContainer);
    }
    
    // Create notification
    const notification = document.createElement('div');
    notification.className = `alert alert-${type === 'success' ? 'success' : 'danger'} fade show`;
    notification.style.minWidth = '300px';
    notification.style.marginBottom = '10px';
    notification.innerHTML = `
        <div class="d-flex justify-content-between align-items-center">
            <span>${message}</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    // Add to container
    notificationContainer.appendChild(notification);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        notification.classList.remove('show');
        setTimeout(() => notification.remove(), 300);
    }, 5000);
}
</script>
