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
    const formData = new FormData(form);
    
    // Add CSRF token
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    // Use fetch API to submit the form
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: formData
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(errorData => {
                throw errorData;
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success message
            showNotification('success', data.message || 'Certificate created successfully');
            
            // Redirect after success if provided
            if (data.redirect) {
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1500);
            }
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
