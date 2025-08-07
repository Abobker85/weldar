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

function updateSawRange(fieldType) {
    const value = document.querySelector(`[name="${fieldType}"]`).value;
    const rangeSpan = document.getElementById(`${fieldType}_range`);
    const hiddenField = document.querySelector(`[name="${fieldType}_range"]`);

    let range = '';

    switch (fieldType) {
        case 'welding_type':
            range = value;
            break;
        case 'welding_process':
            range = value;
            break;
        case 'visual_control_type':
            range = value;
            break;
        case 'joint_tracking':
            if (value === 'With Automatic joint tracking') {
                range = 'With Automatic joint tracking';
            } else {
                range = 'With & Without Automatic joint tracking';
            }
            break;
        case 'backing':
            if (value === 'With backing') {
                range = 'With backing';
            } else {
                range = 'With or Without backing';
            }
            break;
        case 'passes_per_side':
            if (value === 'Single passes per side') {
                range = 'Single passes per side';
            } else {
                range = 'Single & multiple passes per side';
            }
            break;
    }

    if (rangeSpan) {
        rangeSpan.textContent = range;
    }

    if (hiddenField) {
        hiddenField.value = range;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    updateSawRange('welding_type');
    updateSawRange('welding_process');
    updateSawRange('visual_control_type');
    updateSawRange('joint_tracking');
    updateSawRange('backing');
    updateSawRange('passes_per_side');
});
