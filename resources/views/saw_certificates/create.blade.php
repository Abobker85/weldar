@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SAW Welding Operator Performance Qualifications - Create</title>
    <link rel="stylesheet" href="{{ asset('css/certificate-form.css') }}">
</head>

<body>
    <div class="form-container">
        <form id="certificate-form" action="{{ route('saw-certificates.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <meta name="csrf-token" content="{{ csrf_token() }}">

            @include('saw_certificates.partials.header')
            @include('saw_certificates.partials.certificate-details')
            @include('saw_certificates.partials.test-description')
            @include('saw_certificates.partials.base-metal-position')
            @include('saw_certificates.partials.testing-variables-automatic')
            @include('saw_certificates.partials.testing-variables-machine')
            @include('saw_certificates.partials.results-section')
            @include('saw_certificates.partials.certification-section')
            @include('saw_certificates.partials.organization-section')

            <div class="form-buttons">
                <button type="button" onclick="submitCertificateForm()" class="btn btn-primary">Save Certificate</button>
                <a href="{{ route('saw-certificates.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('js/saw-certificate-form.js') }}?v={{ time() }}"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize form functionality
            initializeSawForm();
            
            // Initialize signature pads
            initializeSignaturePads();
            
            // Set up form submission
            window.submitCertificateForm = function() {
                clearValidationErrors();
                
                if (!validateRequiredFields()) {
                    return false;
                }
                
                const form = document.getElementById('certificate-form');
                const formData = new FormData(form);
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                
                Swal.fire({
                    title: 'Saving...',
                    text: 'Please wait while the certificate is being saved',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
                
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    },
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    Swal.close();
                    
                    if (data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: data.message || 'Certificate saved successfully',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            if (data.redirect) {
                                window.location.href = data.redirect;
                            }
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: data.message || 'An error occurred'
                        });
                        
                        if (data.errors) {
                            displayValidationErrors(data.errors);
                        }
                    }
                })
                .catch(error => {
                    Swal.close();
                    console.error('Error:', error);
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'An unexpected error occurred. Please try again.'
                    });
                });
                
                return false;
            };
        });

        function initializeSawForm() {
            // Initialize specimen type toggles
            const plateCheckbox = document.getElementById('plate_specimen');
            const pipeCheckbox = document.getElementById('pipe_specimen');
            
            if (plateCheckbox && pipeCheckbox) {
                plateCheckbox.addEventListener('change', toggleSpecimenFields);
                pipeCheckbox.addEventListener('change', toggleSpecimenFields);
            }
            
            // Initialize position range updates
            const positionSelect = document.getElementById('test_position');
            if (positionSelect) {
                positionSelect.addEventListener('change', updatePositionRange);
            }
            
            // Initialize backing range updates
            const backingSelect = document.getElementById('backing');
            if (backingSelect) {
                backingSelect.addEventListener('change', updateBackingRange);
            }
        }

        function toggleSpecimenFields() {
            const plateCheckbox = document.getElementById('plate_specimen');
            const pipeCheckbox = document.getElementById('pipe_specimen');
            const pipeField = document.getElementById('pipe_diameter_field');
            
            if (pipeCheckbox && pipeCheckbox.checked && pipeField) {
                pipeField.style.display = 'block';
            } else if (pipeField) {
                pipeField.style.display = 'none';
            }
            
            updatePositionRange();
        }

        function updatePositionRange() {
            const position = document.getElementById('test_position')?.value;
            const isPipe = document.getElementById('pipe_specimen')?.checked;
            const rangeDisplay = document.getElementById('position_range_display');
            
            if (!rangeDisplay) return;
            
            let ranges = [];
            
            switch (position) {
                case '1G':
                    ranges.push('F for Groove Plate and Pipe Over 24 in. (610 mm) O.D.');
                    if (isPipe) {
                        ranges.push('F for Groove Pipe ≥ 2 7∕8 in. (73 mm) O.D.');
                    }
                    ranges.push('F for Fillet or Tack Plate and Pipe');
                    break;
                case '2G':
                    ranges.push('F & H for Groove Plate and Pipe Over 24 in. (610 mm) O.D.');
                    if (isPipe) {
                        ranges.push('F & H for Groove Pipe ≥ 2 7∕8 in. (73 mm) O.D.');
                    }
                    ranges.push('F & H for Fillet or Tack Plate and Pipe');
                    break;
                default:
                    ranges.push('F for Groove Plate and Pipe Over 24 in. (610 mm) O.D.');
                    ranges.push('F for Fillet or Tack Plate and Pipe');
            }
            
            rangeDisplay.innerHTML = ranges.join('<br>');
            
            // Update hidden field
            const hiddenField = document.getElementById('position_range');
            if (hiddenField) {
                hiddenField.value = ranges.join(' | ');
            }
        }

        function updateBackingRange() {
            const backing = document.getElementById('backing')?.value;
            const rangeDisplay = document.getElementById('backing_range_display');
            const hiddenField = document.getElementById('backing_range');
            
            let range = '';
            switch (backing) {
                case 'With backing':
                    range = 'With backing';
                    break;
                case 'Without backing':
                    range = 'With or Without backing';
                    break;
                default:
                    range = 'With backing';
            }
            
            if (rangeDisplay) rangeDisplay.textContent = range;
            if (hiddenField) hiddenField.value = range;
        }

        function initializeSignaturePads() {
            // Initialize witness signature pad
            const witnessCanvas = document.getElementById('witness_signature_canvas');
            if (witnessCanvas) {
                const witnessSignaturePad = new SignaturePad(witnessCanvas);
                
                document.getElementById('clear_witness_signature')?.addEventListener('click', function() {
                    witnessSignaturePad.clear();
                    document.getElementById('witness_signature').value = '';
                });
                
                witnessSignaturePad.addEventListener('endStroke', function() {
                    document.getElementById('witness_signature').value = witnessSignaturePad.toDataURL();
                });
            }
            
            // Initialize approver signature pad
            const approverCanvas = document.getElementById('approver_signature_canvas');
            if (approverCanvas) {
                const approverSignaturePad = new SignaturePad(approverCanvas);
                
                document.getElementById('clear_approver_signature')?.addEventListener('click', function() {
                    approverSignaturePad.clear();
                    document.getElementById('approver_signature').value = '';
                });
                
                approverSignaturePad.addEventListener('endStroke', function() {
                    document.getElementById('approver_signature').value = approverSignaturePad.toDataURL();
                });
            }
        }

        function validateRequiredFields() {
            const requiredFields = [
                'certificate_no', 'welder_id', 'company_id', 'wps_followed',
                'test_date', 'base_metal_spec', 'thickness', 'welding_supervised_by'
            ];
            
            let isValid = true;
            
            requiredFields.forEach(fieldName => {
                const field = document.querySelector(`[name="${fieldName}"]`);
                if (field && !field.value.trim()) {
                    field.classList.add('is-invalid');
                    isValid = false;
                } else if (field) {
                    field.classList.remove('is-invalid');
                }
            });
            
            return isValid;
        }

        function clearValidationErrors() {
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
        }

        function displayValidationErrors(errors) {
            for (const field in errors) {
                const input = document.querySelector(`[name="${field}"]`);
                if (input) {
                    input.classList.add('is-invalid');
                    
                    const errorElement = document.createElement('div');
                    errorElement.className = 'invalid-feedback';
                    errorElement.textContent = errors[field][0];
                    
                    input.parentNode.insertBefore(errorElement, input.nextSibling);
                }
            }
        }
    </script>
</body>
</html>
@endsection