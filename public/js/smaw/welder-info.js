/**
 * SMAW Certificate Form Welder Management
 * Handles welder information display and updates for the SMAW Certificate form
 */

// Immediately-invoked function expression (IIFE) to avoid polluting global scope
(function() {
    'use strict';
    
    /**
     * Display welder and company information
     */
    function displayWelderAndCompanyInfo() {
        try {
            console.log("Setting up welder and company information...");

            // Get the selected welder ID
            const welderIdElement = document.getElementById('welder_id');
            if (!welderIdElement || !welderIdElement.value) {
                console.log("No welder selected, skipping welder/company info setup");
                return;
            }

            const welderId = welderIdElement.value;
            console.log(`Selected welder ID: ${welderId}`);

            // Set welder name in the search field if it exists
            const welderSearch = document.getElementById('welder_search');
            const welderOption = welderIdElement.querySelector(`option[value="${welderId}"]`);
            if (welderSearch && welderOption) {
                welderSearch.value = welderOption.textContent.trim();
                console.log(`Set welder search to: ${welderOption.textContent.trim()}`);
            }

            // Fetch welder data via AJAX for the most up-to-date information
            fetch(`/api/welders/${welderId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`API response error: ${response.status}`);
                    }
                    return response.json();
                })
                .then(welder => {
                    console.log("Received welder data:", welder);

                    // Set welder ID number
                    const welderIdNoElement = document.getElementById('welder_id_no');
                    if (welderIdNoElement) {
                        welderIdNoElement.value = welder.welder_no || '';
                    }

                    // Set iqama number
                    const iqamaElement = document.getElementById('iqama_no');
                    if (iqamaElement) {
                        iqamaElement.value = welder.iqama_no || '';
                    }

                    // Set passport number
                    const passportElement = document.getElementById('passport_no');
                    if (passportElement) {
                        passportElement.value = welder.passport_no || '';
                    }

                    // Set company information
                    const companyIdElement = document.getElementById('company_id');
                    const companyNameElement = document.getElementById('company_name');
                    if (companyIdElement && welder.company_id) {
                        companyIdElement.value = welder.company_id;
                    }

                    if (companyNameElement && welder.company && welder.company.name) {
                        companyNameElement.value = welder.company.name;
                    }

                    // Display welder photo if available
                    const photoPreviewElement = document.getElementById('photo-preview');
                    if (photoPreviewElement && welder.photo_path) {
                        photoPreviewElement.innerHTML =
                            `<img src="/storage/${welder.photo_path}" class="img-fluid" style="max-height: 110px;">`;
                        console.log(`Set welder photo from: /storage/${welder.photo_path}`);
                    } else if (photoPreviewElement) {
                        photoPreviewElement.innerHTML = 'Click to upload<br>welder photo';
                        console.log("No welder photo available");
                    }
                })
                .catch(error => {
                    console.error("Error fetching welder data:", error);
                });
        } catch (e) {
            console.error("Error in displayWelderAndCompanyInfo:", e);
        }
    }

    // Register as SMAW-specific welder loader for use with welder-search.js
    window.smawLoadWelderData = function(welderId) {
        // Set the welder_id field value
        const welderIdField = document.getElementById('welder_id');
        if (welderIdField) {
            welderIdField.value = welderId;
            // Call our main display function
            displayWelderAndCompanyInfo();
        } else {
            console.error('Could not find welder_id field to set value', welderId);
        }
    };

    // Export functions to global scope
    window.displayWelderAndCompanyInfo = displayWelderAndCompanyInfo;
    
})();
