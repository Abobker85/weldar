document.addEventListener('DOMContentLoaded', function () {
    // Initialize welder search with Select2
    const welderSelect = $('.welder-search');

    if (welderSelect.length) {
        welderSelect.select2({
            theme: 'bootstrap-5',
            placeholder: 'Search for a welder...',
            minimumInputLength: 2,
            ajax: {
                url: '/api/welders/search', // Note: We will need to create this route
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        q: params.term // search term
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data, function (welder) {
                            return {
                                id: welder.id,
                                text: welder.name + ' (' + welder.welder_no + ')'
                            };
                        })
                    };
                },
                cache: true
            }
        });

        // Handle welder selection
        welderSelect.on('select2:select', function (e) {
            const welderId = e.params.data.id;
            if (welderId) {
                fetch(`/api/welders/${welderId}/details`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.welder) {
                            $('#welder_id_no').val(data.welder.welder_id_no || '');
                            $('#iqama_no').val(data.welder.iqama_no || '');
                            $('#passport_no').val(data.welder.passport_no || '');

                            // Update photo if available
                            const photoPreview = $('#photo-preview');
                            if (data.welder.photo_path && photoPreview.length) {
                                photoPreview.html(`<img src="${data.welder.photo_path}" alt="Welder Photo" class="preview-image">`);
                            } else {
                                photoPreview.html('<div class="photo-placeholder">No Photo</div>');
                            }

                            // Set company if it exists
                            if (data.company) {
                                $('#company_id').val(data.company.id).trigger('change');
                            }

                            // Set certificate and report numbers
                            $('#certificate_no').val(data.certificate_no || '');
                            $('#vt_report_no').val(data.vt_report_no || '');
                            $('#rt_report_no').val(data.rt_report_no || '');
                        }
                    })
                    .catch(error => console.error('Error fetching welder details:', error));
            }
        });
    }

    // Function to preview photo on file input change
    window.previewPhoto = function(input) {
        const preview = document.getElementById('photo-preview');
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" alt="Photo Preview" class="preview-image">`;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
});
