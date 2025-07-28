/**
 * Welder search and selection functionality
 */

// Load welder data when a welder is selected
function loadWelderData(welderId) {
    console.log('⚠️ GENERIC loadWelderData called from welder-search.js with ID:', welderId);
    console.trace('Call stack for loadWelderData:');
    
    // Check if we should delegate to a specific certificate handler
    const certificateHandlers = {
        'gtaw': window.gtawLoadWelderData,
        'smaw': window.smawLoadWelderData,
        'gtaw-smaw': window.gtawSmawLoadWelderData,
        'fcaw': window.fcawLoadWelderData
    };
    
    // Try to detect certificate type from URL
    const url = window.location.pathname.toLowerCase();
    let certificateType = null;
    
    if (url.includes('gtaw-smaw') || url.includes('gtaw_smaw')) {
        certificateType = 'gtaw-smaw';
    } else if (url.includes('gtaw')) {
        certificateType = 'gtaw';
    } else if (url.includes('smaw')) {
        certificateType = 'smaw';
    } else if (url.includes('fcaw')) {
        certificateType = 'fcaw';
    }
    
    // If we detected a certificate type and have a handler, use it
    if (certificateType && typeof certificateHandlers[certificateType] === 'function') {
        console.log(`Delegating to ${certificateType}LoadWelderData function`);
        return certificateHandlers[certificateType](welderId);
    }
    
    if (!welderId) return;
    
    // Get CSRF token
    const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    // Show loading indicator
    const welderIdField = document.getElementById('welder_id_no');
    const welderNameField = document.getElementById('welder_search');
    
    if (welderIdField) welderIdField.value = 'Loading...';
    if (welderNameField) welderNameField.value = 'Loading welder data...';
    
    // Get base URL
   const baseUrl = (() => {
    // Get base domain
    const origin = window.location.origin;
    
    // Check if we're in a subfolder deployment
    if (window.location.pathname.includes('/Weldar/public')) {
        return `${origin}/Weldar/public`;
    }
    
    return origin;
})();
    // Use web-based API route instead of API route
    const apiUrl = `${baseUrl}/api/welders/${welderId}`;
    
    console.log('Fetching welder data from:', apiUrl);
    
    // Fetch welder data from server
    fetch(apiUrl, {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': token || '',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        console.log('API response status:', response.status);
        
        if (!response.ok) {
            // Try to get more info about the error
            return response.text().then(text => {
                console.error('API error response:', text);
                throw new Error(`Network response was not ok: ${response.status} ${response.statusText}`);
            });
        }
        
        return response.json();
    })
    .then(data => {
        // Log the data we received for debugging
        console.log('API Response data:', data);
        
        // Update form fields with welder data
        let welder;
        
        // Handle different API response formats
        if (data && data.welder) {
            // Standard format with welder object inside data
            welder = data.welder;
        } else if (data && data.id) {
            // Alternative format where welder data is directly in the response
            welder = data;
        } else {
            console.error('Unexpected API response format:', data);
            throw new Error('Unexpected API response format');
        }
            
        // Update welder ID field
        if (welderIdField) welderIdField.value = welder.welder_id || '';
        
        // Update welder name field
        if (welderNameField) welderNameField.value = welder.name || '';
        
        // Update other welder fields
        const fieldsToUpdate = {
            'welder_id_no': 'welder_id',
            'iqama_no': 'iqama_no',
            'passport_no': 'passport_no',
            'company_id': 'company_id',
            'company_name': 'company_name'
        };
        
        for (const [fieldId, dataKey] of Object.entries(fieldsToUpdate)) {
            const field = document.getElementById(fieldId);
            if (field && welder[dataKey] !== undefined) {
                field.value = welder[dataKey];
            }
        }
        
        // Update photo preview if available
        const photoPreview = document.getElementById('photo-preview');
        if (photoPreview && welder.photo_path) {
            // Check if photo_path already has a full URL
            let photoUrl = welder.photo_path;
            if (!photoUrl.startsWith('http')) {
                // If not, assume it's a storage path and construct the URL
                photoUrl = `/storage/${photoUrl}`;
            }
            
            photoPreview.innerHTML = `<img src="${photoUrl}" class="img-fluid" style="max-height: 110px;">`;
        }
        
        // If company dropdown exists, select the company
        const companySelect = document.getElementById('company_id');
        if (companySelect && welder.company_id) {
            try {
                // Check if options is defined and has a length property
                if (companySelect.options && companySelect.options.length > 0) {
                    for (let i = 0; i < companySelect.options.length; i++) {
                        if (companySelect.options[i].value == welder.company_id) {
                            companySelect.selectedIndex = i;
                            break;
                        }
                    }
                }
            } catch (e) {
                console.error('Error setting company in dropdown:', e);
            }
        }
        
        // Trigger a change event on company select if needed
        if (companySelect) {
            const event = new Event('change');
            companySelect.dispatchEvent(event);
        }
    })
    .catch(error => {
        console.error('Error fetching welder data:', error);
        
        // Clear loading indicators
        if (welderIdField) welderIdField.value = '';
        if (welderNameField) welderNameField.value = 'Error loading welder data';
        
        // Display user-friendly error message with debugging info for developers
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Error Loading Welder Data',
                html: `
                    <p>There was a problem loading the welder information.</p>
                    <p>Please try again or select a different welder.</p>
                    <details>
                        <summary>Technical details (for support)</summary>
                        <pre>${error.message}</pre>
                        <p>URL: ${apiUrl}</p>
                    </details>
                `,
                confirmButtonText: 'OK'
            });
        } else {
            alert('Error loading welder data: ' + error.message);
        }
    });
}

// Set up welder search functionality
document.addEventListener('DOMContentLoaded', function() {
    const welderSearch = document.getElementById('welder_search');
    const welderResults = document.getElementById('welder_results');
    const welderSelect = document.getElementById('welder_id');
    
    if (!welderSearch || !welderResults || !welderSelect) return;
    
    // Initialize with selected welder if any
    if (welderSelect.value) {
        const selectedOption = welderSelect.options[welderSelect.selectedIndex];
        welderSearch.value = selectedOption.text;
        loadWelderData(welderSelect.value);
    }
    
    // Filter welder results as user types
    welderSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const items = welderResults.getElementsByClassName('welder-item');
        
        let hasVisibleItems = false;
        for (let i = 0; i < items.length; i++) {
            const text = items[i].textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                items[i].style.display = 'block';
                hasVisibleItems = true;
            } else {
                items[i].style.display = 'none';
            }
        }
        
        welderResults.style.display = hasVisibleItems && searchTerm ? 'block' : 'none';
    });
    
    // Handle welder selection
    welderResults.addEventListener('click', function(e) {
        if (e.target.classList.contains('welder-item')) {
            const welderId = e.target.getAttribute('data-id');
            welderSearch.value = e.target.textContent.trim();
            welderSelect.value = welderId;
            welderResults.style.display = 'none';
            
            console.log('Welder item clicked with ID:', welderId);
            
            // Detect certificate type by URL or document class
            let certificateType = 'unknown';
            const currentUrl = window.location.pathname.toLowerCase();
            
            if (currentUrl.includes('gtaw-smaw') || currentUrl.includes('gtaw_smaw')) {
                certificateType = 'gtaw-smaw';
            } else if (currentUrl.includes('gtaw')) {
                certificateType = 'gtaw';
            } else if (currentUrl.includes('smaw')) {
                certificateType = 'smaw';
            } else if (currentUrl.includes('fcaw')) {
                certificateType = 'fcaw';
            }
            
            console.log(`Detected certificate type: ${certificateType}`);
            
            // Use the appropriate certificate-specific function if available
            if (certificateType === 'gtaw' && typeof window.gtawLoadWelderData === 'function') {
                console.log('Using gtawLoadWelderData function');
                window.gtawLoadWelderData(welderId);
            } else if (certificateType === 'smaw' && typeof window.smawLoadWelderData === 'function') {
                console.log('Using smawLoadWelderData function');
                window.smawLoadWelderData(welderId);
            } else if (certificateType === 'gtaw-smaw' && typeof window.gtawSmawLoadWelderData === 'function') {
                console.log('Using gtawSmawLoadWelderData function');
                window.gtawSmawLoadWelderData(welderId);
            } else if (certificateType === 'fcaw' && typeof window.fcawLoadWelderData === 'function') {
                console.log('Using fcawLoadWelderData function');
                window.fcawLoadWelderData(welderId);
            } else {
                console.log('Using generic loadWelderData function - no certificate-specific function found');
                // Fall back to generic function
                loadWelderData(welderId);
            }
            
            // Record which function was used for debugging
            window.lastWelderSelection = {
                id: welderId,
                certificateType: certificateType,
                timestamp: new Date()
            };
        }
    });
    
    // Show/hide results when search field gains/loses focus
    welderSearch.addEventListener('focus', function() {
        if (this.value) {
            welderResults.style.display = 'block';
        }
    });
    
    // Close results when clicking outside
    document.addEventListener('click', function(e) {
        if (e.target !== welderSearch && e.target !== welderResults) {
            welderResults.style.display = 'none';
        }
    });
});

/**
 * Preview the photo upload
 */
function previewPhoto(input) {
    const preview = document.getElementById('photo-preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML =
                `<img src="${e.target.result}" style="width: 80px; height: 110px; object-fit: cover;">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
