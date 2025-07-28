@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card mb-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h4><i class="fas fa-eye"></i> SMAW Certificate Preview</h4>
                        <p class="mb-0 text-muted">This is a preview of how your certificate will look</p>
                    </div>
                    <div>
                        <button type="button" class="btn btn-sm btn-primary me-2" onclick="printCertificate()">
                            <i class="fas fa-print"></i> Print Preview
                        </button>
                        <button type="button" class="btn btn-sm btn-secondary" onclick="window.close()">
                            <i class="fas fa-times"></i> Close Preview
                        </button>
                    </div>
                </div>
            </div>

            <div id="certificate-preview">
                <div class="alert alert-info mb-3">
                    <i class="fas fa-info-circle"></i> Loading certificate preview...
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    @media print {
        header, .card-header, .alert, .btn, .no-print {
            display: none !important;
        }
        body {
            background-color: white;
        }
        .container-fluid {
            padding: 0;
            margin: 0;
        }
    }

    .form-container {
        max-width: 210mm;
        margin: 0 auto;
        background: white;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }

    .header-row {
        display: flex;
        height: 80px;
        border: 2px solid #000;
        border-bottom: 1px solid #000;
    }

    .logo-left, .logo-right {
        width: 120px;
        border-right: 2px solid #000;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f9fa;
        text-align: center;
        padding: 5px;
    }

    .logo-right {
        border-right: none;
        border-left: 2px solid #000;
    }

    .header-center {
        flex: 1;
        background: white;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        font-weight: bold;
        padding: 10px;
    }

    .cert-details-row {
        display: flex;
        height: 25px;
        border: 2px solid #000;
        border-top: none;
        border-bottom: 1px solid #000;
        background: #f8f9fa;
        align-items: center;
        font-weight: bold;
        font-size: 9px;
    }

    .cert-left, .cert-right {
        width: 200px;
        border-right: 1px solid #000;
        padding: 0 10px;
        text-align: left;
    }

    .cert-center {
        flex: 1;
        text-align: center;
        padding: 0 10px;
    }

    .cert-right {
        border-right: none;
        border-left: 1px solid #000;
        text-align: right;
    }

    .content-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 9px;
        border: 2px solid #000;
        border-top: none;
    }

    .content-table td, .content-table th {
        border: 1px solid #000;
        padding: 3px 5px;
        vertical-align: top;
    }

    .label-cell {
        background: #f0f0f0;
        font-weight: bold;
        width: 180px;
    }

    .value-cell {
        background: white;
    }

    .value-cell-center {
        background: white;
        text-align: center;
    }

    .variables-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 8px;
        border: 2px solid #000;
        border-top: none;
    }

    .variables-table td, .variables-table th {
        border: 1px solid #000;
        padding: 2px 4px;
        vertical-align: top;
    }

    .var-label {
        background: #f8f9fa;
        font-weight: bold;
        width: 200px;
    }

    .var-value {
        background: white;
        text-align: center;
    }

    .var-range {
        background: white;
        text-align: center;
    }

    .section-header {
        background: #f0f0f0;
        font-weight: bold;
        text-align: center;
        font-size: 10px;
        padding: 5px;
        border: 1px solid #000;
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Retrieve form data from session storage
        const formDataJson = sessionStorage.getItem('smawCertificateFormData');
        if (!formDataJson) {
            document.getElementById('certificate-preview').innerHTML = `
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle"></i> No preview data found. Please go back to the form and try again.
                </div>
            `;
            return;
        }
        
        const formData = JSON.parse(formDataJson);
        
        // Build certificate preview HTML
        renderCertificatePreview(formData);
    });
    
    function renderCertificatePreview(data) {
        // Format the data for display
        const welderName = document.querySelector('#welder_id option[value="' + data.welder_id + '"]')?.textContent || 'Welder Name';
        const companyName = document.querySelector('#company_id option[value="' + data.company_id + '"]')?.textContent || 'Company Name';
        
        const html = `
            <div class="form-container">
                <!-- Header with logos -->
                <div class="header-row">
                    <div class="logo-left">
                        <div style="font-size: 12px; font-weight: bold; text-align: center; color: #0066cc;">
                            <div style="background: #0066cc; color: white; padding: 2px 8px; border-radius: 15px; margin-bottom: 3px;">ELITE</div>
                            <div style="font-size: 8px; color: #666;">ENGINEERING ARABIA</div>
                        </div>
                    </div>
                    <div class="header-center">
                        <h1 style="font-size: 16px; margin-bottom: 2px;">Elite Engineering Arabia</h1>
                        <div class="contact-info" style="font-size: 8px; margin: 2px 0;">
                            e-mail: info@eliteengineeringarabia.com &nbsp;&nbsp;&nbsp;&nbsp; www.eliteengineeringarabia.com
                        </div>
                        <h2 style="font-size: 14px; font-weight: bold; margin-top: 5px;">WELDER PERFORMANCE QUALIFICATIONS</h2>
                    </div>
                    <div class="logo-right">
                        <div style="font-size: 14px; font-weight: bold; text-align: center;">
                            <span style="color: #dc3545; font-size: 16px;">AIC</span><span style="color: #999; font-size: 12px;">steel</span>
                        </div>
                    </div>
                </div>

                <!-- Certificate details rows -->
                <div class="cert-details-row">
                    <div class="cert-left">
                        Certificate No: <span style="font-weight: bold;">${data.certificate_no}</span>
                    </div>
                    <div class="cert-center">
                        <strong>Welder's name:</strong> ${welderName}
                    </div>
                    <div class="cert-right">
                        <strong>Welder ID No:</strong> ${data.welder_id_no || 'N/A'}
                    </div>
                </div>

                <div class="cert-details-row">
                    <div class="cert-left">
                        <strong>Gov ID Iqama number:</strong> ${document.getElementById('iqama_display')?.textContent || 'N/A'}
                    </div>
                    <div class="cert-center">
                        <strong>Company:</strong> ${companyName}
                    </div>
                    <div class="cert-right">
                        <strong>Passport No:</strong> ${document.getElementById('passport_display')?.textContent || 'N/A'}
                    </div>
                </div>

                <!-- Additional row for Test Description -->
                <div class="cert-details-row" style="height: 35px;">
                    <div style="width: 100%; text-align: center; padding: 5px; border-right: 1px solid #000; background: #f0f0f0;">
                        <strong>Test Description</strong>
                    </div>
                </div>

                <div class="cert-details-row">
                    <div class="cert-left">
                        <strong>Identification of WPS followed:</strong> ${data.wps_followed || 'N/A'}
                    </div>
                    <div style="width: 120px; border-right: 1px solid #000; padding: 0 10px; text-align: center;">
                        <div class="checkbox-container">
                            <strong>■ Test coupon</strong>
                        </div>
                    </div>
                    <div style="flex: 1; padding: 0 10px; text-align: center;">
                        <div class="checkbox-container">
                            <strong>${data.production_weld ? '■' : '□'} Production weld</strong>
                        </div>
                    </div>
                </div>

                <div class="cert-details-row">
                    <div class="cert-left">
                        <strong>Base Metal Specification:</strong> ${data.base_metal_spec || 'N/A'}
                    </div>
                    <div style="width: 120px; border-right: 1px solid #000; padding: 0 10px; text-align: center;">
                        <strong>Date of Test:</strong>
                    </div>
                    <div style="flex: 1; padding: 0 10px; text-align: center;">
                        <strong>${formatDate(data.test_date)}</strong>
                    </div>
                </div>

                <div class="cert-details-row">
                    <div class="cert-left">
                        <strong>Dia / Thickness:</strong> ${data.dia_thickness_display || `8 inch/14.26 mm`}
                    </div>
                    <div style="width: 120px; border-right: 1px solid #000; padding: 0 10px; text-align: center;">
                        <!-- Empty cell -->
                    </div>
                    <div style="flex: 1; padding: 0 10px; text-align: center;">
                        <!-- Photo would be here -->
                    </div>
                </div>
                
                <!-- Welding Variables Table -->
                <table class="variables-table">
                    <tr>
                        <td colspan="3" class="section-header">WELDING VARIABLES</td>
                    </tr>
                    <tr>
                        <td class="var-label">Welding Process</td>
                        <td class="var-value">SMAW (Shielded Metal Arc Welding)</td>
                        <td class="var-range">SMAW Only</td>
                    </tr>
                    <tr>
                        <td class="var-label">Backing</td>
                        <td class="var-value">${data.backing || 'With Backing'}</td>
                        <td class="var-range">${data.backing_range || 'With backing or backing and gouging'}</td>
                    </tr>
                    <tr>
                        <td class="var-label">Base Metal P-No.</td>
                        <td class="var-value">${data.base_metal_p_no || 'P NO.1 TO P NO.1'}</td>
                        <td class="var-range">${data.p_number_range || 'P-No.1 Group 1 or 2'}</td>
                    </tr>
                    <tr>
                        <td class="var-label">Filler Metal F-No.</td>
                        <td class="var-value">${data.filler_f_no || 'F-No.4 With Backing'}</td>
                        <td class="var-range">${data.f_number_range || 'F-No.4 Only'}</td>
                    </tr>
                    <tr>
                        <td class="var-label">Filler Metal Specification</td>
                        <td class="var-value" colspan="2">AWS A${data.filler_spec || '5.1'} / ${data.filler_class || 'E7018-1'}</td>
                    </tr>
                    <tr>
                        <td class="var-label">Vertical Progression</td>
                        <td class="var-value">${data.vertical_progression || 'Uphill'}</td>
                        <td class="var-range">${data.vertical_progression_range || 'Uphill only'}</td>
                    </tr>
                </table>
                
                <!-- Position Qualification Section -->
                <table class="variables-table">
                    <tr>
                        <td colspan="3" class="section-header">POSITION QUALIFICATION</td>
                    </tr>
                    <tr>
                        <td class="var-label">Test Position</td>
                        <td class="var-value">${data.test_position || '6G'}</td>
                        <td class="var-range" style="font-weight: bold;">${data.position_range || 'All Position Groove Plate and Pipe'}</td>
                    </tr>
                    <tr>
                        <td class="var-label">Type of Weld</td>
                        <td class="var-value">
                            ${data.plate_specimen ? '☑' : '☐'} Plate &nbsp;&nbsp;&nbsp; ${data.pipe_specimen ? '☑' : '☐'} Pipe
                        </td>
                        <td class="var-range" style="font-weight: bold;">Diameter Range: ${data.diameter_range || 'Pipe of diameter ≥ 219.1 mm (8" NPS)'}</td>
                    </tr>
                    <tr>
                        <td class="var-label">Thickness Range</td>
                        <td class="var-value" colspan="2">
                            1/8" (3.18 mm) to 2x base metal thickness (${Number(data.smaw_thickness || 14.26) * 2} mm)
                        </td>
                    </tr>
                </table>
                
                <!-- RESULTS Section -->
                <div class="results-section" style="border: 2px solid #000; border-top: none;">
                    <div class="section-header">TEST RESULTS</div>
                    <table class="results-table" style="width: 100%; border-collapse: collapse;">
                        <tr class="test-header">
                            <td style="border: 1px solid #000; font-weight: bold; text-align: center; background: #f0f0f0;">Type of Test</td>
                            <td style="border: 1px solid #000; font-weight: bold; text-align: center; background: #f0f0f0;">Result</td>
                            <td style="border: 1px solid #000; font-weight: bold; text-align: center; background: #f0f0f0;">Remarks</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #000; text-align: center;">Visual Examination</td>
                            <td style="border: 1px solid #000; text-align: center; font-weight: bold; ${data.test_result == 1 ? 'color: green;' : 'color: red;'}">
                                ${data.test_result == 1 ? 'PASS' : 'FAIL'}
                            </td>
                            <td style="border: 1px solid #000; text-align: center;">
                                Acceptable
                            </td>
                        </tr>
                    </table>
                </div>
                
                <!-- Certification Statement -->
                <div class="signature-section" style="border: 2px solid #000; border-top: none;">
                    <div style="text-align: center; padding: 10px; font-size: 9px; border-bottom: 1px solid #000;">
                        We certify that the statements in this record are correct and that the test welds were prepared, welded, and 
                        tested in accordance with the requirements of ASME Section IX of the ASME BOILER AND PRESSURE VESSEL CODE.
                    </div>
                </div>
                
                <!-- Final signature section -->
                <div class="signature-section" style="border: 2px solid #000; border-top: none;">
                    <div style="background: #f0f0f0; text-align: center; font-weight: bold; padding: 5px; border-bottom: 1px solid #000;">
                        Confirmation of Certification
                    </div>
                    <table class="signature-table" style="width: 100%; border-collapse: collapse;">
                        <tr>
                            <td style="border: 1px solid #000; font-weight: bold; text-align: center; height: 25px; background: #f8f9fa;">
                                Inspected by
                            </td>
                            <td style="border: 1px solid #000; font-weight: bold; text-align: center; height: 25px; background: #f8f9fa;">
                                Date
                            </td>
                            <td style="border: 1px solid #000; font-weight: bold; text-align: center; height: 25px; background: #f8f9fa;">
                                Approved by
                            </td>
                            <td style="border: 1px solid #000; font-weight: bold; text-align: center; height: 25px; background: #f8f9fa;">
                                Date
                            </td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #000; text-align: center; height: 40px;">
                                ${data.inspector_name || ''}
                            </td>
                            <td style="border: 1px solid #000; text-align: center; height: 40px;">
                                ${formatDate(data.inspector_date) || formatDate(data.test_date)}
                            </td>
                            <td style="border: 1px solid #000; text-align: center; height: 40px;">
                                
                            </td>
                            <td style="border: 1px solid #000; text-align: center; height: 40px;">
                                ${formatDate(data.test_date)}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        `;
        
        document.getElementById('certificate-preview').innerHTML = html;
    }
    
    function formatDate(dateString) {
        if (!dateString) return '';
        
        const date = new Date(dateString);
        const day = date.getDate().toString().padStart(2, '0');
        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        const year = date.getFullYear();
        
        return `${day}/${month}/${year}`;
    }
    
    function printCertificate() {
        window.print();
    }
</script>
@endpush
