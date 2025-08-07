@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>SAW Welding Operator Performance Qualifications - Edit</title>
    <link rel="stylesheet" href="{{ asset('css/certificate-form.css') }}">
</head>

<body>
    {{-- Error Display Section --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="form-container">
        <form id="certificate-form" action="{{ route('saw-certificates.update', $certificate->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <meta name="csrf-token" content="{{ csrf_token() }}">

            {{-- Hidden fields for range calculations --}}
            <input type="hidden" name="diameter_range" id="diameter_range" value="{{ old('diameter_range', $certificate->diameter_range) }}">
            <input type="hidden" name="p_number_range" id="p_number_range" value="{{ old('p_number_range', $certificate->p_number_range) }}">
            <input type="hidden" name="position_range" id="position_range" value="{{ old('position_range', $certificate->position_range) }}">
            <input type="hidden" name="backing_range" id="backing_range" value="{{ old('backing_range', $certificate->backing_range) }}">
            <input type="hidden" name="f_number_range" id="f_number_range" value="{{ old('f_number_range', $certificate->f_number_range) }}">
            <input type="hidden" name="vertical_progression_range" id="vertical_progression_range" value="{{ old('vertical_progression_range', $certificate->vertical_progression_range) }}">
            <input type="hidden" name="visual_control_range" id="visual_control_range" value="{{ old('visual_control_range', $certificate->visual_control_range) }}">
            <input type="hidden" name="joint_tracking_range" id="joint_tracking_range" value="{{ old('joint_tracking_range', $certificate->joint_tracking_range) }}">
            <input type="hidden" name="passes_range" id="passes_range" value="{{ old('passes_range', $certificate->passes_range) }}">

            @include('saw_certificates.partials.header', ['certificate' => $certificate])
            @include('saw_certificates.partials.certificate-details', ['certificate' => $certificate, 'welders' => $welders, 'companies' => $companies])
            @include('saw_certificates.partials.test-description', ['certificate' => $certificate])
            @include('saw_certificates.partials.base-metal-position', ['certificate' => $certificate])
            @include('saw_certificates.partials.testing-variables-machine', ['certificate' => $certificate])
            @include('saw_certificates.partials.results-section', ['certificate' => $certificate])
            @include('saw_certificates.partials.certification-section', ['certificate' => $certificate])
            @include('saw_certificates.partials.organization-section', ['certificate' => $certificate])

            <div class="form-buttons">
                <button type="button" onclick="submitCertificateForm()" class="btn btn-primary">Update Certificate</button>
                <a href="{{ route('saw-certificates.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.4/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.0.18/dist/sweetalert2.all.min.js"></script>
    <script src="{{ asset('js/saw-certificate-form.js') }}?v={{ time() }}"></script>
    <script src="{{ asset('js/saw-certificate-validation.js') }}?v={{ time() }}"></script>

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
                    text: 'Please wait while the certificate is being updated',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Since this is an update, we need to add the _method parameter
                formData.append('_method', 'PUT');

                fetch(form.action, {
                    method: 'POST', // Use POST for multipart/form-data with _method spoofing
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
                            text: data.message || 'Certificate updated successfully',
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

            // Initialize inspector signature pad
            const inspectorCanvas = document.getElementById('inspector_signature_canvas');
            if (inspectorCanvas) {
                const inspectorSignaturePad = new SignaturePad(inspectorCanvas);

                document.getElementById('clear_inspector_signature')?.addEventListener('click', function() {
                    inspectorSignaturePad.clear();
                    document.getElementById('inspector_signature_data').value = '';
                });

                inspectorSignaturePad.addEventListener('endStroke', function() {
                    document.getElementById('inspector_signature_data').value = inspectorSignaturePad.toDataURL();
                });
            }
        }

        function validateRequiredFields() {
            const requiredFields = [
                'certificate_no', 'welder_id', 'company_id', 'wps_followed',
                'test_date', 'base_metal_spec', 'dia_thickness', 'welding_supervised_by'
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
