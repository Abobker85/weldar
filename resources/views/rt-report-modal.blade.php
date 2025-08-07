<div class="modal fade" id="rt-report-modal" tabindex="-1" role="dialog" aria-labelledby="rt-report-modal-label" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rt-report-modal-label">Upload RT Report</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="upload-error" class="alert alert-danger d-none mb-3"></div>
                <div id="upload-success" class="alert alert-success d-none mb-3"></div>
                <form id="rt-report-form" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="welder_id" id="modal-welder-id">
                    <input type="hidden" name="certificate_id" id="modal-certificate-id">
                    <div class="form-group mb-3">
                        <label for="attachment" class="form-label">RT Report File</label>
                        <input type="file" class="form-control" id="attachment" name="attachment" required>
                        <div class="form-text">Allowed file types: PDF, JPG, JPEG, PNG (Max 10MB)</div>
                    </div>
                    <div class="progress d-none mb-3" id="upload-progress-container">
                        <div class="progress-bar" role="progressbar" id="upload-progress-bar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                    </div>
                    <button type="button" class="btn btn-primary" id="upload-btn" onclick="console.log('Inline click handler triggered');">Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Direct click handler for upload button (no jQuery dependency)
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM ready, checking for upload button');
        var uploadBtn = document.getElementById('upload-btn');
        if (uploadBtn) {
            console.log('Found upload button, attaching native event listener');
            uploadBtn.addEventListener('click', function(e) {
                console.log('Native JS: Upload button clicked');
                // Basic form validation
                var attachment = document.getElementById('attachment');
                if (attachment && attachment.files.length === 0) {
                    var errorDiv = document.getElementById('upload-error');
                    if (errorDiv) {
                        errorDiv.classList.remove('d-none');
                        errorDiv.textContent = 'Please select a file to upload.';
                    }
                }
            });
        } else {
            console.error('Native JS: Upload button not found in the DOM');
        }
        
        // Check if modal is in DOM
        var rtReportModal = document.getElementById('rt-report-modal');
        if (rtReportModal) {
            console.log('RT Report modal found in the DOM');
        } else {
            console.error('RT Report modal not found in the DOM');
        }
    });
    
    // Wait for jQuery and DOM to be ready
    $(document).ready(function() {
        console.log('jQuery ready, binding events');
        
        // Debug if jQuery can find the elements
        console.log('jQuery elements check:', {
            'modal': $('#rt-report-modal').length,
            'form': $('#rt-report-form').length,
            'upload-btn': $('#upload-btn').length
        });
        // Set up CSRF token and general AJAX settings for all AJAX requests
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
        if (csrfToken) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                cache: false,
                dataType: 'json',
                beforeSend: function(xhr) {
                    // Add extra headers for AJAX detection
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                    xhr.setRequestHeader('Accept', 'application/json');
                }
            });
        } else {
            console.error('CSRF token not found: https://laravel.com/docs/csrf#csrf-x-csrf-token');
        }
        
        // Global AJAX error handler for debugging
        $(document).ajaxError(function(event, jqXHR, settings, error) {
            console.group('AJAX Error Details');
            console.error('Status:', jqXHR.status, jqXHR.statusText);
            console.error('URL:', settings.url);
            console.error('Type:', settings.type);
            console.error('Data:', settings.data);
            console.error('Response:', jqXHR.responseText);
            console.groupEnd();
            
            // Show user-friendly error message based on status code
            if (jqXHR.status === 419) {
                $('#upload-error').removeClass('d-none').text('Session expired (CSRF token mismatch). Please refresh the page and try again.');
            } else if (jqXHR.status === 302 || jqXHR.status === 301) {
                $('#upload-error').removeClass('d-none').text('Server redirect detected. Please refresh the page and try again.');
            }
        });
        
        // Helper function for debugging
        function debugRequest(message, data) {
            console.group('RT Report Debug: ' + message);
            console.log('Data:', data);
            console.groupEnd();
        }
        
        const rtReportModal = document.getElementById('rt-report-modal');
        if (rtReportModal) {
            // Handle modal open event
            rtReportModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                var certificateId = button.getAttribute('data-certificate-id');
                var welderId = button.getAttribute('data-welder-id');
                var certificateType = button.getAttribute('data-certificate-type');
                
                console.log('Modal opened with:', {
                    certificateId: certificateId,
                    welderId: welderId,
                    certificateType: certificateType
                });

                // Reset the form and hide alerts
                $('#rt-report-form').trigger('reset');
                $('#upload-error').addClass('d-none').text('');
                $('#upload-success').addClass('d-none').text('');
                $('#upload-progress-container').addClass('d-none');
                $('#upload-progress-bar').css('width', '0%').text('0%');
                
                // Set form values
                var modal = this;
                modal.querySelector('#modal-certificate-id').value = certificateId;
                modal.querySelector('#modal-welder-id').value = welderId;

                // Set the form action based on certificate type
                var form = modal.querySelector('#rt-report-form');
                var action = '/';
                if (certificateType === 'smaw') {
                    action = '{{ route("smaw-rt-reports.store") }}';
                } else if (certificateType === 'gtaw') {
                    action = '{{ route("gtaw-rt-reports.store") }}';
                } else if (certificateType === 'fcaw') {
                    action = '{{ route("fcaw-rt-reports.store") }}';
                } else if (certificateType === 'saw') {
                    action = '{{ route("saw-rt-reports.store") }}';
                } else if (certificateType === 'gtaw-smaw') {
                    action = '{{ route("gtaw-rt-reports.store") }}';  // Using GTAW RT reports for GTAW-SMAW
                }
                form.setAttribute('action', action);
                
                // Store certificate type for later use
                form.setAttribute('data-certificate-type', certificateType);
            });
            
            // Handle form submission through button click instead of form submit
            // Use direct selector and bind event to handle dynamically loaded elements
            $(document).on('click', '#upload-btn', function(e) {
                e.preventDefault();
                e.stopPropagation(); // Stop event propagation
                
                console.log('jQuery: Upload button clicked');
                
                // Get form and submit button
                var form = $('#rt-report-form');
                var submitBtn = $(this);
                var certificateType = form.attr('data-certificate-type');
                
                console.log('Form data check:', {
                    'form-exists': form.length,
                    'action': form.attr('action'),
                    'certificate-type': certificateType,
                    'welder-id': $('#modal-welder-id').val(),
                    'certificate-id': $('#modal-certificate-id').val()
                });
                
                console.log('Form details:', {
                    formExists: form.length > 0,
                    formAction: form.attr('action'),
                    certificateType: certificateType
                });
                
                // Validate file input
                var fileInput = $('#attachment')[0];
                if (fileInput.files.length === 0) {
                    $('#upload-error').removeClass('d-none').text('Please select a file to upload.');
                    return;
                }
                
                // Check file type
                var allowedTypes = ['application/pdf', 'image/jpeg', 'image/jpg', 'image/png'];
                var fileType = fileInput.files[0].type;
                if (!allowedTypes.includes(fileType)) {
                    $('#upload-error').removeClass('d-none').text('Invalid file type. Only PDF, JPG, JPEG, and PNG files are allowed.');
                    return;
                }
                
                // Check file size (max 10MB)
                var maxSize = 10 * 1024 * 1024; // 10MB in bytes
                if (fileInput.files[0].size > maxSize) {
                    $('#upload-error').removeClass('d-none').text('File is too large. Maximum file size is 10MB.');
                    return;
                }
                
                // Disable button and show progress
                submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Uploading...');
                $('#upload-progress-container').removeClass('d-none');
                
                // Create FormData for AJAX submission
                var formData = new FormData(form[0]);
                
                // Ensure CSRF token is included in the form data
                if (!formData.has('_token')) {
                    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                }
                
                // Debug information
                debugRequest('Form submission', {
                    'form_action': form.attr('action'),
                    'certificate_type': certificateType,
                    'has_token': formData.has('_token'),
                    'csrf_token': $('meta[name="csrf-token"]').attr('content')
                });
                
                // Send AJAX request
                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    cache: false,
                    async: true,
                    dataType: 'json',
                    xhr: function() {
                        // Track upload progress
                        var xhr = new window.XMLHttpRequest();
                        xhr.upload.addEventListener('progress', function(e) {
                            if (e.lengthComputable) {
                                var percent = Math.round((e.loaded / e.total) * 100);
                                $('#upload-progress-bar').css('width', percent + '%').text(percent + '%');
                            }
                        }, false);
                        return xhr;
                    },
                    success: function(response) {
                        console.log('Upload success:', response);
                        
                        // Show success message
                        $('#upload-success').removeClass('d-none').text('RT Report uploaded successfully.');
                        
                        // Reset form and re-enable button
                        form.trigger('reset');
                        submitBtn.prop('disabled', false).text('Upload');
                        
                        // Add the new report to the table without refreshing the page
                        var certificateId = $('#modal-certificate-id').val();
                        var reportId = response.data.report_id;
                        var fileName = response.data.file_name;
                        var now = new Date();
                        var dateStr = now.getFullYear() + '-' + 
                                    String(now.getMonth() + 1).padStart(2, '0') + '-' + 
                                    String(now.getDate()).padStart(2, '0');
                        
                        // Create new table row for the report
                        var newRow = '<tr>' +
                            '<td>' + dateStr + '</td>' +
                            '<td>' + fileName + '</td>' +
                            '<td>' +
                            '<a href="/storage/rt-reports/' + certificateType + '/' + fileName + '" class="btn btn-sm btn-primary" target="_blank">' +
                            '<i class="fas fa-eye"></i> View' +
                            '</a>' +
                            '</td>' +
                            '</tr>';
                            
                        // Find the report table and update it
                        var $tableBody = $('table tbody').filter(function() {
                            return $(this).closest('table').find('thead tr th').length === 3 &&
                                  $(this).closest('table').find('thead tr th:first').text().includes('Date');
                        });
                        
                        if ($tableBody.length) {
                            // Remove "no reports" row if it exists
                            $tableBody.find('tr td[colspan="3"]').closest('tr').remove();
                            // Add the new row
                            $tableBody.append(newRow);
                        }
                        
                        // Close modal after 2 seconds
                        setTimeout(function() {
                            var modalInstance = bootstrap.Modal.getInstance(document.getElementById('rt-report-modal'));
                            if (modalInstance) {
                                modalInstance.hide();
                            }
                        }, 2000);
                    },
                    error: function(xhr, status, error) {
                        console.error('Upload Error:', xhr);
                        
                        // Enable button
                        submitBtn.prop('disabled', false).text('Upload');
                        
                        // Handle different error status codes
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.errors) {
                            // Validation errors
                            var errors = xhr.responseJSON.errors;
                            var errorMessage = '';
                            for (var field in errors) {
                                errorMessage += errors[field][0] + '<br>';
                            }
                            $('#upload-error').removeClass('d-none').html(errorMessage);
                        } else if (xhr.status === 302 || xhr.status === 301) {
                            // Handle redirect (this should not happen with proper AJAX)
                            $('#upload-error').removeClass('d-none').text('Session may have expired. Please refresh the page and try again.');
                            console.error('Redirect detected:', xhr.getResponseHeader('Location'));
                        } else if (xhr.status === 419) {
                            // CSRF token mismatch
                            $('#upload-error').removeClass('d-none').text('Security token expired. Please refresh the page and try again.');
                        } else if (xhr.responseJSON && xhr.responseJSON.message) {
                            // Handle errors with specific messages
                            $('#upload-error').removeClass('d-none').text('Error: ' + xhr.responseJSON.message);
                        } else {
                            // Handle other errors
                            $('#upload-error').removeClass('d-none').text('Error uploading RT Report. Please try again.');
                        }
                        
                        // Log detailed error information
                        console.error('Error details:', {
                            status: xhr.status,
                            statusText: xhr.statusText,
                            responseText: xhr.responseText,
                            headers: xhr.getAllResponseHeaders()
                        });
                    }
                });
            });
        }
    });
</script>
@endpush
