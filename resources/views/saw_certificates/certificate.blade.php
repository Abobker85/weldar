<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAW Welding Operator Performance Qualification Certificate</title>

    @php
    function formatDate($date) {
        if (!$date) return 'N/A';
        if (!$date instanceof \Carbon\Carbon) {
            $date = \Carbon\Carbon::parse($date);
        }
        return $date->format('F j, Y');
    }
    @endphp

    <style>
        @page {
            size: A4;
            margin: 15mm;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
            font-size: 9px;
            font-weight: bold;
        }

        .form-container {
            width: 210mm;
            height: 297mm;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        /* Header styling */
        .certificate-header {
            border: 2px solid #000;
        }

        .logo-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 70px;
            border-bottom: 1px solid #000;
        }

        .logo-left, .logo-right {
            width: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            text-align: center;
            padding: 5px 15px;
            border-right: 1px solid #000;
        }

        .logo-right {
            border-right: none;
            border-left: 1px solid #000;
        }

        .header-center {
            flex: 1;
            background: white;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 10px;
        }

        .company-name {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .contact-info {
            font-size: 8px;
            color: #666;
            margin-bottom: 5px;
        }

        .main-title {
            font-size: 14px;
            font-weight: bold;
        }

        /* Info table styling */
        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            border: 1px solid #000;
            padding: 4px 8px;
            font-size: 9px;
            height: 25px;
        }

        .info-table .label {
            font-weight: bold;
            background: #f8f9fa;
            width: 120px;
        }

        .info-table .value {
            font-weight: bold;
        }

        .photo-cell {
            width: 90px;
            text-align: center;
            vertical-align: middle;
        }

        /* Content tables */
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

        /* Section headers */
        .section-header {
            background: #f0f0f0;
            font-weight: bold;
            text-align: center;
            font-size: 10px;
            padding: 5px;
        }

        /* Variables table */
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
            font-weight: bold;
        }

        .var-range {
            background: white;
            text-align: center;
            font-weight: bold;
        }

        /* Results section */
        .results-section {
            border: 2px solid #000;
            border-top: none;
        }

        .results-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }

        .results-table td, .results-table th {
            border: 1px solid #000;
            padding: 3px 5px;
            vertical-align: middle;
        }

        .test-header {
            background: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }

        /* Certification statement section */
        .cert-statement {
            text-align: center;
            padding: 10px;
            font-size: 9px;
            border-bottom: 1px solid #000;
            background: white;
        }

        .confirmation-header {
            background: #f0f0f0;
            text-align: center;
            font-weight: bold;
            padding: 5px;
            border-bottom: 1px solid #000;
        }

        /* Signature section */
        .signature-section {
            border: 2px solid #000;
            border-top: none;
        }

        .signature-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
        }

        .signature-table td {
            border: 1px solid #000;
            padding: 3px 5px;
            vertical-align: top;
        }

        .sig-header {
            background: #f8f9fa;
            font-weight: bold;
            text-align: center;
            height: 25px;
        }

        .print-buttons {
            margin: 20px auto;
            text-align: center;
            width: 210mm;
        }

        /* Print styles */
        @media print {
            body {
                background: white;
                margin: 0;
                padding: 0;
            }
            .form-container {
                box-shadow: none;
                margin: 0;
                padding: 0;
                width: 100%;
                height: auto;
            }
            .print-buttons {
                display: none;
            }

            * {
                -webkit-print-color-adjust: exact !important;
                color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <!-- Header Section -->
        <div class="certificate-header">
            <!-- Logo Row -->
            <div class="logo-row">
                <div class="logo-left">
                    @if(\App\Models\AppSetting::getValue('company_logo_path'))
                        <img src="{{ asset('storage/' . \App\Models\AppSetting::getValue('company_logo_path')) }}" alt="Company Logo" style="max-width: 90px; max-height: 50px;">
                    @else
                        <div style="font-size: 14px; font-weight: bold; text-align: center; color: #0066cc;">
                            <div style="background: #0066cc; color: white; padding: 2px 8px; border-radius: 15px; margin-bottom: 3px;">ELITE</div>
                            <div style="font-size: 8px; color: #666;">ENGINEERING ARABIA</div>
                        </div>
                    @endif
                </div>
                <div class="header-center">
                    <div class="company-name">{{ \App\Models\AppSetting::getValue('company_name', 'Elite Engineering Arabia') }}</div>
                    <div class="contact-info">
                        e-mail: {{ \App\Models\AppSetting::getValue('email', 'ahmed.yousry@eliteengineeringarabia.com') }} &nbsp;&nbsp;&nbsp;&nbsp; {{ \App\Models\AppSetting::getValue('website', 'www.') }}
                    </div>
                    <div class="main-title">WELDING OPERATOR PERFORMANCE QUALIFICATIONS</div>
                </div>
                <div class="logo-right">
                    @if($certificate->company && $certificate->company->logo_path)
                        <img src="{{ asset('storage/' . $certificate->company->logo_path) }}" alt="Client Logo" style="max-width: 90px; max-height: 50px;">
                    @else
                        <div style="font-size: 14px; font-weight: bold; text-align: center;">
                            <span style="color: #dc3545; font-size: 16px;">{{ $certificate->company->code ?? 'AIC' }}</span>
                            <span style="color: #999; font-size: 12px;">{{ $certificate->company->short_name ?? 'steel' }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Main Information Table -->
        <table class="info-table">
            <tr>
                <td class="label">Certificate No:</td>
                <td class="value">{{ $certificate->certificate_no }}</td>
                <td class="label">Welding Operator's name:</td>
                <td class="value">{{ $certificate->welder->name }}</td>
                <td rowspan="3" class="photo-cell">
                    @if($certificate->photo_path)
                        <img src="{{ asset('storage/' . $certificate->photo_path) }}" alt="{{ $certificate->welder->name }}" style="max-width: 80px; max-height: 100px; border: 1px solid #000;">
                    @elseif($certificate->welder->photo)
                        <img src="{{ asset('storage/' . $certificate->welder->photo) }}" alt="{{ $certificate->welder->name }}" style="max-width: 80px; max-height: 100px; border: 1px solid #000;">
                    @else
                        <div style="width: 80px; height: 100px; border: 1px dashed #999; display: flex; align-items: center; justify-content: center; color: #999; font-size: 8px;">
                            PHOTO
                        </div>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Gov ID/Iqama number:</td>
                <td class="value">{{ $certificate->welder->iqama_no }}</td>
                <td class="label">Welder ID No:</td>
                <td class="value">{{ $certificate->welder->welder_no }}</td>
            </tr>
            <tr>
                <td class="label">Company:</td>
                <td class="value" colspan="3">{{ $certificate->company->name }}</td>
            </tr>
        </table>

        <!-- Test Description Header -->
        <div style="background: #f0f0f0; text-align: center; font-weight: bold; padding: 5px; border: 1px solid #000; border-top: none;">
            Test Description
        </div>

        <!-- Test Details -->
        <table class="info-table">
            <tr>
                <td class="label">Identification of WPS followed:</td>
                <td class="value">{{ $certificate->wps_followed }}</td>
                <td style="text-align: center; width: 15%;">
                    <div style="display: flex; align-items: center; justify-content: center;">
                        <span style="display: inline-block; width: 14px; height: 14px; border: 1px solid #000; margin-right: 5px; text-align: center; line-height: 12px;">
                            {!! $certificate->test_coupon ? '✓' : '&nbsp;' !!}
                        </span>
                        <strong>Test coupon</strong>
                    </div>
                </td>
                <td style="text-align: center; width: 15%;">
                    <div style="display: flex; align-items: center; justify-content: center;">
                        <span style="display: inline-block; width: 14px; height: 14px; border: 1px solid #000; margin-right: 5px; text-align: center; line-height: 12px;">
                            {!! $certificate->production_weld ? '✓' : '&nbsp;' !!}
                        </span>
                        <strong>Production weld</strong>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="label">Base Metal Specification:</td>
                <td class="value">{{ $certificate->base_metal_spec }}</td>
                <td class="label">Date of Test:</td>
                <td class="value">{{ formatDate($certificate->test_date) }}</td>
            </tr>
            <tr>
                <td class="label">Dia / Thickness:</td>
                <td class="value" colspan="3">{{ $certificate->dia_thickness ?? 'N/A' }}</td>
            </tr>
        </table>

        <!-- Base Metal and Position Section -->
        <table class="info-table">
            <tr>
                <td class="label">Base metal</td>
                <td class="value">{{ $certificate->base_metal_p_no_from }} to {{ $certificate->base_metal_p_no_to }}</td>
                <td class="label">Position</td>
                <td class="value">{{ $certificate->test_position }}</td>
            </tr>
            <tr>
                <td class="label">
                    <div style="display: flex; align-items: center;">
                        <span style="display: inline-block; width: 14px; height: 14px; border: 1px solid #000; margin-right: 5px; text-align: center; line-height: 12px;">
                            {!! $certificate->plate_specimen ? '✓' : '&nbsp;' !!}
                        </span>
                        Plate
                        <span style="display: inline-block; width: 14px; height: 14px; border: 1px solid #000; margin: 0 5px 0 10px; text-align: center; line-height: 12px;">
                            {!! $certificate->pipe_specimen ? '✓' : '&nbsp;' !!}
                        </span>
                        Pipe
                    </div>
                    (enter diameter, if pipe or tube)
                </td>
                <td class="value">{{ $certificate->pipe_diameter ?? ($certificate->pipe_specimen ? 'Pipe' : 'Plate') }}</td>
                <td colspan="2" style="font-size: 7px; padding: 2px;">
                    {!! nl2br(str_replace(' | ', '<br>', $certificate->position_range ?? '')) !!}
                </td>
            </tr>
            <tr>
                <td class="label">Filler metal (SFA) specification</td>
                <td class="value">{{ $certificate->filler_metal_sfa_spec ?? '5.17' }}</td>
                <td class="label">Filler metal or electrode classification</td>
                <td class="value">{{ $certificate->filler_metal_classification ?? 'F7A2 EM12K' }}</td>
            </tr>
        </table>

        <!-- Testing Variables Header -->
        <table class="content-table">
            <tr>
                <td colspan="4" class="section-header">Testing Variables and Qualification Limits When Using Automatic Welding Equipment</td>
            </tr>
        </table>

        <!-- Automatic Variables Table -->
        <table class="variables-table">
            <tr>
                <td class="var-label">Welding Variables (QW-361.1)</td>
                <td class="var-value"><strong>Actual Values</strong></td>
                <td class="var-range"><strong>Range Qualified</strong></td>
            </tr>
            <tr>
                <td class="var-label">Type of welding (automatic):</td>
                <td class="var-value">….....</td>
                <td class="var-range">…...........</td>
            </tr>
            <tr>
                <td class="var-label">Welding process(es):</td>
                <td class="var-value">….....</td>
                <td class="var-range">…...........</td>
            </tr>
            <tr>
                <td class="var-label">Filler metal used (Yes or No) (EBW or LBW):</td>
                <td class="var-value">….....</td>
                <td class="var-range">…...........</td>
            </tr>
            <tr>
                <td class="var-label">Type of laser for LBW (CO2 to YAG, etc.):</td>
                <td class="var-value">….....</td>
                <td class="var-range">…...........</td>
            </tr>
            <tr>
                <td class="var-label">Continuous drive or inertia welding (FW):</td>
                <td class="var-value">….....</td>
                <td class="var-range">…...........</td>
            </tr>
            <tr>
                <td class="var-label">Vacuum or out of vacuum (EBW):</td>
                <td class="var-value">….....</td>
                <td class="var-range">…...........</td>
            </tr>
        </table>

        <!-- Machine Variables Header -->
        <table class="content-table">
            <tr>
                <td colspan="3" class="section-header">Testing Variables and Qualification Limits When Using Machine Welding Equipment</td>
            </tr>
        </table>

        <!-- Machine Variables Table -->
        <table class="variables-table">
            <tr>
                <td class="var-label">Welding Variables (QW-361.2)</td>
                <td class="var-value"><strong>Actual Values</strong></td>
                <td class="var-range"><strong>Range Qualified</strong></td>
            </tr>
            <tr>
                <td class="var-label">Type of welding (Machine):</td>
                <td class="var-value">{{ $certificate->welding_type ?? 'Machine' }}</td>
                <td class="var-range">{{ $certificate->welding_type_range ?? 'Machine' }}</td>
            </tr>
            <tr>
                <td class="var-label">Welding process:</td>
                <td class="var-value">{{ $certificate->welding_process ?? 'SAW' }}</td>
                <td class="var-range">{{ $certificate->welding_process_range ?? 'SAW' }}</td>
            </tr>
            <tr>
                <td class="var-label">Direct or remote visual control:</td>
                <td class="var-value">{{ $certificate->visual_control_type ?? 'Direct Visual Control' }}</td>
                <td class="var-range">{{ $certificate->visual_control_range ?? 'Direct Visual Control' }}</td>
            </tr>
            <tr>
                <td class="var-label">Automatic arc voltage control (GTAW):</td>
                <td class="var-value">{{ $certificate->arc_voltage_control ?? '…..............' }}</td>
                <td class="var-range">{{ $certificate->arc_voltage_control_range ?? '…..............' }}</td>
            </tr>
            <tr>
                <td class="var-label">Automatic joint tracking:</td>
                <td class="var-value">{{ $certificate->joint_tracking ?? 'With Automatic joint tracking' }}</td>
                <td class="var-range">{{ $certificate->joint_tracking_range ?? 'With Automatic joint tracking' }}</td>
            </tr>
            <tr>
                <td class="var-label">Position(s):</td>
                <td class="var-value">{{ $certificate->test_position ?? '1G' }}</td>
                <td class="var-range" style="font-size: 7px; line-height: 1.1;">
                    F for Groove Plate and Pipe Over 24 in. (610 mm) O.D.<br>
                    F for Groove Pipe ≥ 2 7∕8 in. (73 mm) O.D.<br>
                    F for Fillet or Tack Plate and Pipe
                </td>
            </tr>
            <tr>
                <td class="var-label">Consumable inserts (GTAW or PAW):</td>
                <td class="var-value">{{ $certificate->consumable_inserts ?? '….........' }}</td>
                <td class="var-range">{{ $certificate->consumable_inserts_range ?? '….........' }}</td>
            </tr>
            <tr>
                <td class="var-label">Backing (with or without):</td>
                <td class="var-value">{{ $certificate->backing ?? 'With backing' }}</td>
                <td class="var-range">{{ $certificate->backing_range ?? 'With backing' }}</td>
            </tr>
            <tr>
                <td class="var-label">Single or multiple passes per side:</td>
                <td class="var-value">{{ $certificate->passes_per_side ?? 'multiple passes per side' }}</td>
                <td class="var-range">{{ $certificate->passes_range ?? 'Single & multiple passes per side' }}</td>
            </tr>
        </table>

        <!-- RESULTS Section -->
        <div class="results-section">
            <table class="results-table">
                <tr>
                    <td colspan="4" class="section-header">RESULTS</td>
                </tr>

                <tr>
                    <td class="var-label" colspan="4">
                        <strong>Visual examination of completed weld (QW-302.4)</strong>
                        {{ $certificate->visual_examination_result ?? 'Accepted' }} see Report No.{{ $certificate->vt_report_no }}
                    </td>
                </tr>

                <!-- Bend Tests Headers -->
                <tr>
                    <td class="var-label" style="width: 33.33%;">
                        <div style="display: flex; align-items: center;">
                            <span style="display: inline-block; width: 14px; height: 14px; border: 1px solid #000; margin-right: 5px; text-align: center; line-height: 12px;">
                                {!! $certificate->transverse_face_root_bends ? '✓' : '&nbsp;' !!}
                            </span>
                            Transverse face and root bends [QW-462.3(a)]
                        </div>
                    </td>
                    <td class="var-label" style="width: 33.33%;">
                        <div style="display: flex; align-items: center;">
                            <span style="display: inline-block; width: 14px; height: 14px; border: 1px solid #000; margin-right: 5px; text-align: center; line-height: 12px;">
                                {!! $certificate->longitudinal_bends ? '✓' : '&nbsp;' !!}
                            </span>
                            Longitudinal bends [QW-462.3(b)]
                        </div>
                    </td>
                    <td class="var-label" style="width: 33.34%;">
                        <div style="display: flex; align-items: center;">
                            <span style="display: inline-block; width: 14px; height: 14px; border: 1px solid #000; margin-right: 5px; text-align: center; line-height: 12px;">
                                {!! $certificate->side_bends ? '✓' : '&nbsp;' !!}
                            </span>
                            Side bends (QW-462.2)
                        </div>
                    </td>
                </tr>

                <!-- More test results... -->
                <tr>
                    <td class="test-header">TYPE</td>
                    <td class="test-header">RESULT</td>
                    <td class="test-header">TYPE</td>
                    <td class="test-header">RESULT</td>
                </tr>

                <tr>
                    <td class="var-label">Visual examination of completed weld (QW-302.4)</td>
                    <td class="var-value" style="text-align: center;">ACC</td>
                    <td class="var-value" style="text-align: center;">{{ $certificate->additional_type_1 ?? '................' }}</td>
                    <td class="var-value" style="text-align: center;">{{ $certificate->additional_result_1 ?? '...........' }}</td>
                </tr>

                <!-- Personnel Information -->
                <tr>
                    <td class="var-label">Film or specimens evaluated by</td>
                    <td class="var-value" style="text-align: center;">{{ $certificate->film_evaluated_by ?? 'Kalith Majeeth' }}</td>
                    <td class="var-label">Company</td>
                    <td class="var-value" style="text-align: center;">{{ $certificate->evaluated_company ?? '' }}</td>
                </tr>
                <tr>
                    <td class="var-label">Mechanical tests conducted by</td>
                    <td class="var-value" style="text-align: center;">{{ $certificate->mechanical_tests_by ?? '…..........' }}</td>
                    <td class="var-label">Laboratory test no.</td>
                    <td class="var-value" style="text-align: center;">{{ $certificate->lab_test_no ?? '…....' }}</td>
                </tr>
                <tr>
                    <td class="var-label">Welding supervised by</td>
                    <td class="var-value" style="text-align: center;">{{ $certificate->welding_supervised_by ?? 'Ahmed Yousry' }}</td>
                    <td class="var-label">Company</td>
                    <td class="var-value" style="text-align: center;">{{ $certificate->supervised_company ?? '' }}</td>
                </tr>
            </table>
        </div>

        <!-- Certification Statement -->
        <div class="cert-statement">
            <strong>We certify that the statements in this record are correct and that the test coupons were prepared, welded, and tested in accordance with the requirements of Section IX of the ASME BOILER AND PRESSURE VESSEL CODE.</strong>
            @if($certificate->certification_text)
                <br><br>{{ $certificate->certification_text }}
            @endif
        </div>

        <!-- Confirmation Section -->
        <div class="confirmation-header">
            Confirmation of the validity by the employer/Welding coordinator for the following 6 month
        </div>

        <table class="signature-table">
            <tr>
                <td class="sig-header">Date</td>
                <td class="sig-header">Signature</td>
                <td class="sig-header">Position or Title</td>
            </tr>
            <tr>
                <td>{{ $certificate->confirm_date_1 ? formatDate($certificate->confirm_date_1) : '' }}</td>
                <td></td>
                <td>{{ $certificate->confirm_position_1 ?? '' }}</td>
            </tr>
            <tr>
                <td>{{ $certificate->confirm_date_2 ? formatDate($certificate->confirm_date_2) : '' }}</td>
                <td></td>
                <td>{{ $certificate->confirm_position_2 ?? '' }}</td>
            </tr>
            <tr>
                <td>{{ $certificate->confirm_date_3 ? formatDate($certificate->confirm_date_3) : '' }}</td>
                <td></td>
                <td>{{ $certificate->confirm_position_3 ?? '' }}</td>
            </tr>
        </table>

        <!-- Final Organization Section -->
        <div class="signature-section">
            <table class="signature-table">
                <tr>
                    <td colspan="3" class="sig-header">Organization</td>
                    <td class="sig-header">QR CODE</td>
                </tr>
                <tr>
                    <td class="var-label">Test Witnessed by:</td>
                    <td class="var-value">{{ $certificate->test_witnessed_by ?? 'ELITE ENGINEERING ARABIA' }}</td>
                    <td class="var-value">Reviewed / Approved by:</td>
                    <td rowspan="5" style="text-align: center; width: 80px;">
                        @if(isset($qrCodeUrl))
                            <img src="{{ $qrCodeUrl }}" alt="Verification QR Code" style="width: 80px; height: 80px;">
                        @else
                            <div style="width: 80px; height: 80px; border: 1px solid #000; background: #f0f0f0; display: flex; align-items: center; justify-content: center; font-size: 8px;">
                                QR Code
                            </div>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="var-label">Name:</td>
                    <td class="var-value">{{ $certificate->witness_name ?? 'Ahmed Yousry' }}</td>
                    <td class="var-label">Name:</td>
                </tr>
                <tr>
                    <td class="var-label">Signature:</td>
                    <td class="var-value" style="height: 40px;">
                        @if($certificate->witness_signature)
                            <img src="{{ $certificate->witness_signature }}" alt="Witness Signature" style="max-height: 35px; max-width: 200px;">
                        @endif
                    </td>
                    <td class="var-label">Signature:</td>
                </tr>
                <tr>
                    <td class="var-label">Stamp:</td>
                    <td class="var-value" style="height: 45px; text-align: center;">
                        @if(\App\Models\AppSetting::getValue('company_stamp_path'))
                            <img src="{{ asset('storage/' . \App\Models\AppSetting::getValue('company_stamp_path')) }}"
                                alt="Company Stamp" style="max-width: 60px; max-height: 40px; opacity: 0.8;">
                        @endif
                    </td>
                    <td class="var-label">Stamp:</td>
                </tr>
                <tr>
                    <td class="var-label">Date:</td>
                    <td class="var-value">{{ formatDate($certificate->witness_date) }}</td>
                    <td class="var-label">Date:</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="print-buttons">
        <button onclick="window.print();" style="padding: 10px 20px; background: #0066cc; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Print Certificate
        </button>
        <button onclick="window.close();" style="padding: 10px 20px; background: #666; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            Close
        </button>
    </div>
</body>
</html>