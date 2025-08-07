function sawLoadWelderData(welderId) {
    if (!welderId) {
        return;
    }

    fetch(`/welders/${welderId}/details`)
        .then(response => response.json())
        .then(data => {
            if (data.welder) {
                document.getElementById('welder_id_no').value = data.welder.welder_id_no || '';
                document.getElementById('iqama_no').value = data.welder.iqama_no || '';
                document.getElementById('passport_no').value = data.welder.passport_no || '';

                // Update photo if available
                const photoPreview = document.getElementById('photo-preview');
                if (data.welder.photo_path) {
                    photoPreview.innerHTML = `<img src="${data.welder.photo_path}" alt="Welder Photo" class="preview-image">`;
                } else {
                    photoPreview.innerHTML = '<div class="photo-placeholder">No Photo</div>';
                }

                // Set company if it exists
                if (data.company) {
                    const companySelect = document.getElementById('company_id');
                    companySelect.value = data.company.id;
                    // Trigger change event if you are using other libraries that depend on it
                    // var event = new Event('change');
                    // companySelect.dispatchEvent(event);
                }

                // Set certificate and report numbers
                document.getElementById('certificate_no').value = data.certificate_no || '';

                const vtReportNo = document.getElementById('vt_report_no');
                if(vtReportNo) {
                    vtReportNo.value = data.vt_report_no || '';
                }

                const rtReportNo = document.getElementById('rt_report_no');
                if(rtReportNo) {
                    rtReportNo.value = data.rt_report_no || '';
                }
            }
        })
        .catch(error => console.error('Error fetching welder details:', error));
}

function previewPhoto(input) {
    const preview = document.getElementById('photo-preview');
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `<img src="${e.target.result}" alt="Photo Preview" class="preview-image">`;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
