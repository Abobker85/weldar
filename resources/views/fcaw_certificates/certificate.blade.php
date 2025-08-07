<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SMAW Welder Qualification Certificate</title>
    
    <!-- Define the formatDate helper function -->
    @php
    function formatDate($date) {
        if (!$date) return 'N/A';
        
        if (!$date instanceof \Carbon\Carbon) {
            $date = \Carbon\Carbon::parse($date);
        }
        
        return $date->format('d F, Y');
    }
    @endphp
    
    <style>
        @page {
            size: A4;
            margin: 0;
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
            font-weight: bold; /* Make all text bold */
        }
        
        .form-container {
            width: 210mm;
            height: 297mm;
            margin: 0 auto;
            background: white;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        /* Updated header styling to match new design */
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

        .logo-left {
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
            width: 120px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f9fa;
            text-align: center;
            padding: 5px 15px;
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

        .title-section {
            background: #f8f9fa;
            padding: 10px;
            text-align: center;
            border-bottom: 1px solid #000;
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
            padding: 0px 8px;
            font-size: 9px;
        }

        .info-table .label {
            font-weight: bold;
            background: #f8f9fa;
            width: 120px;
            height: 20px; /* Ensure consistent height */
        }

        .info-table .value {
            font-weight: bold;
        }

        .photo-cell {
            width: 90px;
            text-align: center;
            vertical-align: middle;
        }

        /* Test description styling */
        .test-description-header {
            text-align: center;
            padding: 8px;
            font-weight: bold;
            font-size: 10px;
            background: #f0f0f0;
            border: 1px solid #000;
            border-top: none;
        }

        /* Certificate details rows - exactly like create form */
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
            font-weight: bold; /* Ensure all values are bold */
        }

        .var-range {
            background: white;
            text-align: center;
            font-weight: bold; /* Ensure all ranges are bold */
        }
        
        /* Position qualification styling */
        .position-qualification-row {
            background: #333;
            color: white;
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
            padding: 1px 5px;
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
            font-size: 11px;
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

        .sig-row {
            height: 10px0px;
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
            
            /* Preserve exact form layout */
            .header-row, .cert-details-row, .content-table,
            .variables-table, .results-table, .signature-section {
                page-break-inside: avoid;
                break-inside: avoid;
            }
            
            /* Preserve all colors and backgrounds */
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
        <!-- Updated Header Section -->
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
                    <div class="main-title">WELDER PERFORMANCE QUALIFICATIONS</div>
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
                <td class="label">Welder's name:</td>
                <td class="value">{{ $certificate->welder->name }}</td>
                <td rowspan="3" class="photo-cell">
                    @if($certificate->welder->photo)
                        <img src="{{ asset('storage/' . $certificate->welder->photo) }}" alt="{{ $certificate->welder->name }}" style="max-width: 80px; max-height: 100px; border: 1px solid #000;">
                    @else
                        <div style="width: 80px; height: 100px; border: 1px dashed #999; display: flex; align-items: center; justify-content: center; color: #999; font-size: 8px;">
                            No Photo
                        </div>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label">Gov ID Iqama number:</td>
                <td class="value">{{ $certificate->welder->iqama_no }}</td>
                <td class="label">Passport No:</td>
                <td class="value">{{ $certificate->welder->passport_id_no }}</td>
            </tr>
            <tr>
                <td class="label">Welder ID No:</td>
                <td class="value">{{ $certificate->welder->welder_no }}</td>
                <td class="label">Company:</td>
                <td class="value">{{ $certificate->company->name }}</td>
            </tr>
        </table>

        <!-- Test Description Header -->
        <div class="test-description-header">Test Description</div>

        <!-- Test Details -->
        <table class="info-table">
            <tr>
                <td class="label">Identification of WPS followed:</td>
                <td class="value">{{ $certificate->wps_followed }}</td>
                <td class="label">Revision No:</td>
                <td class="value">{{ $certificate->revision_no }}</td>
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
                <td class="value">{{ $certificate->test_date ? formatDate($certificate->test_date) : 'N/A' }}</td>
                <td></td>
                   <td></td>

            </tr>
            <tr>
                <td class="label">Dia:</td>
                <td class="value">{{ $certificate->diameter ?? 'N/A' }} mm</td>
                <td class="label">Thickness:</td>
                <td class="value">{{ $certificate->thickness ?? 'N/A' }} mm</td>
                      <td></td>
                   <td></td>
            </tr>
          
        </table>
        
        <!-- Testing Variables Header -->
        <table class="content-table">
            <tr>
                <td colspan="5" class="section-header">Testing Variables and Qualification Limits</td>
            </tr>
        </table>
        
        <!-- Variables Table with integrated Position Qualification -->
        <table class="variables-table">
            <tr>
                <td class="var-label">Welding Variables (QW-350)</td>
                <td class="var-value" style="width: 150px;"><strong>Actual Values</strong></td>
                <td class="var-range" style="width: 200px;"><strong>Range Qualified</strong></td>
            </tr>
            <tr>
                <td class="var-label">Welding process(es):</td>
                <td class="var-value">FCAW</td>
                <td class="var-range">FCAW OR GTAW</td>
            </tr>
            <tr>
                <td class="var-label">Type (i.e., manual, semi-automatic) used:</td>
                <td class="var-value">semi-automatic</td>
                <td class="var-range">semi-automatic</td>
            </tr>
            <tr>
                <td class="var-label">Backing (with/without):</td>
                <td class="var-value">{{ $certificate->backing_manual ?? $certificate->backing }}</td>
                <td class="var-range">{{ $certificate->backing_range }}</td>
            </tr>
            <tr>
                <td class="var-label">
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
                    (enter diameter if pipe or tube)
                </td>
                <td class="var-value">
                    @if($certificate->pipe_diameter_type == '__manual__')
                        {{ $certificate->pipe_diameter_manual }}
                    @elseif($certificate->pipe_diameter_type == '8_nps')
                        8" NPS (219.1 mm)
                    @elseif($certificate->pipe_diameter_type == '6_nps')
                        6" NPS (168.3 mm)
                    @elseif($certificate->pipe_diameter_type == '4_nps')
                        4" NPS (114.3 mm)
                    @elseif($certificate->pipe_diameter_type == '2_nps')
                        2" NPS (60.3 mm)
                    @else
                        {{ $certificate->pipe_diameter_type }}
                    @endif
                </td>
                <td class="var-range">{{ $certificate->diameter_range }}</td>
            </tr>
            <tr>
                <td class="var-label">Base metal P-Number to P-Number:</td>
                <td class="var-value">{{ $certificate->base_metal_p_no_manual ?? $certificate->base_metal_p_no }}</td>
                <td class="var-range">{{ $certificate->p_number_range }}</td>
            </tr>
            <tr>
                <td class="var-label">Filler metal or electrode specification(s) (SFA) (info. only):</td>
                <td class="var-value">
                    @if($certificate->filler_spec == '__manual__')
                        {{ $certificate->filler_spec_manual }}
                    @else
                        {{ $certificate->filler_spec }}
                    @endif
                </td>
                <td class="var-range">
                    @if($certificate->filler_spec_range == '__manual__')
                        {{ $certificate->filler_spec_range_manual }}
                    @else
                        {{ $certificate->filler_spec_range }}
                    @endif
                </td>
            </tr>
            <tr>
                <td class="var-label">Filler metal or electrode classification(s) (info. only):</td>
                <td class="var-value">{{ $certificate->filler_class_manual ?? $certificate->filler_class }}</td>
                <td class="var-range">
                    @if($certificate->filler_class_range == '__manual__')
                        {{ $certificate->filler_class_range_manual }}
                    @else
                        {{ $certificate->filler_class_range }}
                    @endif
                </td>
            </tr>
            <tr>
                <td class="var-label">Filler metal F-Number(s):</td>
                <td class="var-value">
                    @if($certificate->filler_f_no == '__manual__')
                        {{ $certificate->filler_f_no_manual }}
                    @elseif($certificate->filler_f_no == 'F4_with_backing')
                        F-No.4 With Backing
                    @elseif($certificate->filler_f_no == 'F5_with_backing')
                        F-No.5 With Backing
                    @elseif($certificate->filler_f_no == 'F4_without_backing')
                        F-No.4 Without Backing
                    @elseif($certificate->filler_f_no == 'F5_without_backing')
                        F-No.5 Without Backing
                    @elseif($certificate->filler_f_no == 'F43')
                        F-No.43
                    @else
                        {{ $certificate->filler_f_no }}
                    @endif
                </td>
                <td class="var-range">{{ $certificate->f_number_range }}</td>
            </tr>
            <tr>
                

                <td class="var-label">Deposit thickness for each process:</td>
                <td class="var-value">FCAW({{ $certificate->deposit_thickness }})</td>
                <td class="var-range">FCAW({{ $certificate->deposit_thickness_range }})</td>
            </tr>
            <tr>
                <td class="var-label">
                    Process 1 __ 3 layers minimum
                    <div style="display: inline-block; margin-left: 10px;">
                        <span style="display: inline-block; width: 14px; height: 14px; border: 1px solid #000; margin-right: 5px; text-align: center; line-height: 12px;">
                            {!! $certificate->smaw_yes == 1 ? '✓' : '&nbsp;' !!}
                        </span>
                        YES
                        <span style="display: inline-block; width: 14px; height: 14px; border: 1px solid #000; margin: 0 5px 0 10px; text-align: center; line-height: 12px;">
                            {!! $certificate->smaw_yes == 0 ? '✓' : '&nbsp;' !!}
                        </span>
                        NO
                    </div>
                </td>
                <td class="var-value">FCAW({{ $certificate->fcaw_thickness }})</td>
                <td class="var-range">FCAW({{ $certificate->fcaw_thickness_range }})</td>
            </tr>
           
            
            <!-- Position Qualification integrated with special styling -->
            <tr>
                <td class="var-label" style="">Position(s):</td>
                <td class="var-value">{{ $certificate->test_position }}</td>
                <td class="var-range" style="">
                    @if(strpos($certificate->position_range, '|') !== false)
                        {{ explode('|', $certificate->position_range)[0] }}
                    @else
                        {{ $certificate->position_range }}
                    @endif
                      @if(strpos($certificate->position_range, '|') !== false && isset(explode('|', $certificate->position_range)[1]))
                        {{ explode('|', $certificate->position_range)[1] }}
                    @endif
                       @if(strpos($certificate->position_range, '|') !== false && isset(explode('|', $certificate->position_range)[2]))
                        {{ explode('|', $certificate->position_range)[2] }}
                    @endif
                </td>
            </tr>
            <tr>
                <td class="var-label">Vertical progression (uphill or downhill):</td>
                <td class="var-value">{{ $certificate->vertical_progression }}</td>
                <td class="var-range">{{ $certificate->vertical_progression_range }}</td>
            </tr>
            <tr>
                <td class="var-label">Type of fuel gas (OFW):</td>
                <td class="var-value">{{ $certificate->fuel_gas ?? '------' }}</td>
                <td class="var-range">{{ $certificate->fuel_gas_range ?? '------' }}</td>
            </tr>
            <tr>
                <td class="var-label">Use of backing gas (GTAW, PAW, GMAW, LBW):</td>
                <td class="var-value">{{ $certificate->backing_gas ?? '------' }}</td>
                <td class="var-range">{{ $certificate->backing_gas_range ?? '------' }}</td>
            <tr>
                <td class="var-label">GTAW current type and polarity (AC, DCEP, DCEN) For LBW or LLBW:</td>
                <td class="var-value">
                    {{ $certificate->gtaw_polarity ?? '------' }}
                </td>
                <td class="var-range">
                    {{ $certificate->gtaw_polarity ?? '------' }}
                </td>
            </tr>
            <tr>
                <td class="var-label">Transfer mode (spray, globular, or pulse to short circuit-GMAW):</td>
                <td class="var-value">
                    {{ $certificate->transfer_mode ?? '------' }}
                </td>
                <td class="var-range">
                    {{ $certificate->transfer_mode_range ?? '------' }}
                </td>
            </tr>
            <tr>
                <td class="var-label">Type of equipment:</td>
                <td class="var-value">
                    {{ $certificate->equipment_type ?? '------' }}
                </td>
                <td class="var-range">
                    {{ $certificate->equipment_type_range ?? '------' }}
                </td>
            </tr>
            <tr>
                <td class="var-label">Technique (keyhole LBW or melt-in):</td>
                <td class="var-value">
                    {{ $certificate->technique ?? '------' }}
                </td>
                <td class="var-range">
                    {{ $certificate->technique_range ?? '------' }}
                </td>
            </tr>
            <tr>
                <td class="var-label">
                    Torch/Gun/Beam oscillation
                    <div style="display: inline-block; margin-left: 10px;">
                        <span style="display: inline-block; width: 14px; height: 14px; border: 1px solid #000; margin-right: 5px; text-align: center; line-height: 12px;">
                            {!! $certificate->oscillation == 'yes' ? '✓' : '&nbsp;' !!}
                        </span>
                        YES
                        <span style="display: inline-block; width: 14px; height: 14px; border: 1px solid #000; margin: 0 5px 0 10px; text-align: center; line-height: 12px;">
                            {!! $certificate->oscillation == 'no' || !$certificate->oscillation ? '✓' : '&nbsp;' !!}
                        </span>
                        NO
                    </div>
                </td>
                <td class="var-value">
                    {{ $certificate->oscillation_value ?? '------' }}
                </td>
                <td class="var-range">
                    {{ $certificate->oscillation_range ?? '------' }}
                </td>
            </tr>
            <tr>
                <td class="var-label">Mode of operation (pulsed or continuous):</td>
                <td class="var-value">
                   ------
                </td>
                <td class="var-range">
                    ------
                </td>
            </tr>
             {{-- <tr>
        <td class="var-label">Transfer mode (spray, globular, or pulse to short circuit-GMAW):</td>
        <td class="var-value">
            <span> ..... </span>
        </td>
        <td class="var-range">
            <span> ..... </span>
        </td>
    </tr>
    
    <tr>
        <td class="var-label">Type of equipment</td>
        <td class="var-value">
            <span> ..... </span>
        </td>
        <td class="var-range">
            <span> ..... </span>
        </td>
    </tr>
    <tr>
        <td class="var-label">Technique (keyhole LBW or melt-in)</td>
        <td class="var-value">
            <span> ..... </span>
        </td>
        <td class="var-range">
            <span> ..... </span>
        </td>
    </tr>
    <tr>
        <td class="var-label">
            Torch/Gun/Beam oscillation
            <div class="checkbox-container" style="display: inline-block; margin-left: 10px;">
                <input type="radio" name="oscillation" id="oscillation_yes" value="yes">
                <label for="oscillation_yes">YES</label>
                <input type="radio" name="oscillation" id="oscillation_no" value="no" checked>
                <label for="oscillation_no">NO</label>
            </div>
        </td>
        <td class="var-value">
            <span> ..... </span>
        </td>
        <td class="var-range">
            <span> ..... </span>
        </td>
    </tr>
    <tr>
        <td class="var-label">Mode of operation (pulsed or continuous):</td>
        <td class="var-value">
            <span> ..... </span>
        </td>
        <td class="var-range">
            <span> ..... </span>
        </td>
    </tr> --}}
        </table>
        
        <!-- RESULTS Section -->
        <div class="signature-section">
            <table class="results-table">
                <tr>
                    <td colspan="4" class="section-header">RESULTS</td>
                </tr>
                
                <tr>
                    <td class="var-label" colspan="2">Visual examination of completed weld (QW-302.4)</td>
                    <td class="var-value" style="text-align: center; font-weight: bold;" colspan="2">
                        {{ $certificate->visual_examination_result ?? 'ACC' }} (Report No. {{ $certificate->vt_report_no }})
                    </td>
                </tr>
                
                <tr>
                    <td class="test-header" style="width: 25%;">TYPE</td>
                    <td class="test-header" style="width: 25%;">RESULT</td>
                    <td class="test-header" style="width: 25%;">TYPE</td>
                    <td class="test-header" style="width: 25%;">RESULT</td>
                </tr>
                
                <tr>
                    <td class="var-label">Visual examination of completed weld (QW-302.4)</td>
                    <td class="var-value" style="text-align: center; font-weight: bold;">ACC</td>
                    <td class="var-value" style="text-align: center;">
                        {{ $certificate->additional_type_1 ?? '' }}
                    </td>
                    <td class="var-value" style="text-align: center;">
                        {{ $certificate->additional_result_1 ?? '' }}
                    </td>
                </tr>
                
                <tr>
                    <td class="var-label">Alternative Volumetric Examination Results (QW-191):</td>
                    <td class="var-value" style="text-align: center; font-weight: bold;">ACC</td>
                    <td class="var-value" style="text-align: center;">
                        {{ $certificate->additional_type_2 ?? '' }}
                    </td>
                    <td class="var-value" style="text-align: center;">
                        {{ $certificate->additional_result_2 ?? '' }}
                    </td>
                </tr>
                
                <tr>
                    <td class="var-label">Alternative Volumetric Examination Results (QW-191):</td>
                    <td class="var-value" style="text-align: center; font-weight: bold;">
                        ACC (Doc No. {{ $certificate->rt_report_no }})
                        @if($certificate->rt_doc_no)
                            <br>Report No.#: {{ $certificate->rt_doc_no }}
                        @endif
                    </td>
                    <td class="var-value" style="text-align: center;">
                        <div style="display: flex; align-items: center; justify-content: center;">
                            <span style="display: inline-block; width: 14px; height: 14px; border: 1px solid #000; margin-right: 5px; text-align: center; line-height: 12px;">
                                {!! $certificate->rt ? '✓' : '&nbsp;' !!}
                            </span>
                            RT
                        </div>
                    </td>
                    <td class="var-value" style="text-align: center;">
                        <div style="display: flex; align-items: center; justify-content: center;">
                            <span style="display: inline-block; width: 14px; height: 14px; border: 1px solid #000; margin-right: 5px; text-align: center; line-height: 12px;">
                                {!! $certificate->ut ? '✓' : '&nbsp;' !!}
                            </span>
                            UT
                        </div>
                    </td>
                </tr>

                  <!-- Remaining test rows -->
        <tr>
            <td class="var-label">Fillet weld-fracture test (QW-181.2):</td>
            <td class="var-value" style="text-align: center;">
                {{ $certificate->fillet_fracture_test ?? '................' }}
            </td>
            <td class="var-label">Length and percent of defects</td>
            <td class="var-value" style="text-align: center;">
                {{ $certificate->defects_length ?? '...........' }}
            </td>
        </tr>

        <tr>
            <td class="var-label">
                <div style="display: flex; align-items: center;">
                    <span style="display: inline-block; width: 14px; height: 14px; border: 1px solid #000; margin-right: 5px; text-align: center; line-height: 12px;">
                        {!! $certificate->fillet_welds_plate ? '✓' : '&nbsp;' !!}
                    </span>
                    Fillet welds in plate [QW-462.4(b)]
                </div>
            </td>
            <td class="var-value"></td>
            <td class="var-label">
                <div style="display: flex; align-items: center;">
                    <span style="display: inline-block; width: 14px; height: 14px; border: 1px solid #000; margin-right: 5px; text-align: center; line-height: 12px;">
                        {!! $certificate->fillet_welds_pipe ? '✓' : '&nbsp;' !!}
                    </span>
                    Fillet welds in pipe [QW-462.4(c)]
                </div>
            </td>
            <td class="var-value"></td>
        </tr>

        <tr>
            <td class="var-label">Macro examination (QW-184)</td>
            <td class="var-value" style="text-align: center;">
                {{ $certificate->macro_exam ?? '................' }}
            </td>
            <td class="var-label">Fillet size (in.)</td>
            <td class="var-value" style="text-align: center;">
                {{ $certificate->fillet_size ?? '............' }}
            </td>
        </tr>

        <tr>
            <td class="var-label">Other tests</td>
            <td class="var-value" style="text-align: center;">
                {{ $certificate->other_tests ?? '................' }}
            </td>
            <td class="var-label">Concavity or convexity (in.)</td>
            <td class="var-value" style="text-align: center;">
                {{ $certificate->concavity_convexity ?? '................' }}
            </td>
        </tr>
            </table>

            <!-- Personnel Information -->
            <table class="results-table">
                <tr>
                    <td class="var-label">Film or specimens evaluated by</td>
                    <td class="var-value" style="text-align: center;">
                        {{ $certificate->evaluated_by }}
                    </td>
                    <td class="var-label">Company</td>
                    <td class="var-value" style="text-align: center;">
                        {{ $certificate->evaluated_company }}
                    </td>
                </tr>
                <tr>
                    <td class="var-label">Mechanical tests conducted by</td>
                    <td class="var-value" style="text-align: center;">
                        {{ $certificate->mechanical_tests_by }}
                    </td>
                    <td class="var-label">Laboratory test no.</td>
                    <td class="var-value" style="text-align: center;">
                        {{ $certificate->lab_test_no }}
                    </td>
                </tr>
                <tr>
                    <td class="var-label">Welding supervised by</td>
                    <td class="var-value" style="text-align: center;">
                        {{ $certificate->supervised_by }}
                    </td>
                    <td class="var-label">Company</td>
                    <td class="var-value" style="text-align: center;">
                        {{ $certificate->supervised_company }}
                    </td>
                </tr>
            </table>
        </div>
        
        <!-- Certification Statement -->
        <div class="signature-section">
            <div class="cert-statement">
                <strong>We certify that the statements in this record are correct and that the test coupons were prepared, welded, and tested in accordance with the requirements of Section IX of the ASME BOILER AND PRESSURE VESSEL CODE.</strong>
                @if($certificate->certification_text)
                    <br>{{ $certificate->certification_text }}
                @endif
            </div>
            
            <div class="confirmation-header">
                Confirmation of the validity by the employer/Welding coordinator for the following 6 month
            </div>
            
            <table class="signature-table">
                <tr>
                    <td class="sig-header">Date</td>
                    <td class="sig-header">Signature</td>
                    <td class="sig-header">Position or Title</td>
                </tr>
                <tr class="sig-row">
                    <td>{{ $certificate->confirm_date1 ?? '' }}</td>
                    <td></td>
                    <td>{{ $certificate->confirm_title1 ?? '' }}</td>
                </tr>
                <tr class="sig-row">
                    <td>{{ $certificate->confirm_date2 ?? '' }}</td>
                    <td></td>
                    <td>{{ $certificate->confirm_title2 ?? '' }}</td>
                </tr>
                <tr class="sig-row">
                    <td>{{ $certificate->confirm_date3 ?? '' }}</td>
                    <td></td>
                    <td>{{ $certificate->confirm_title3 ?? '' }}</td>
                </tr>
            </table>
        </div>

        <!-- Final signature section -->
        <div class="signature-section">
            <table class="signature-table">
                <tr>
                    <td colspan="3" class="sig-header">Organization</td>
                    <td class="sig-header">QR CODE</td>
                </tr>
                <tr>
                    <td class="var-label">Test Witnessed by:</td>
                    <td class="var-value">{{ \App\Models\AppSetting::getValue('company_name', 'ELITE ENGINEERING ARABIA') }}</td>
                    <td class="var-value">Reviewed / Approved by:</td>
                    <td rowspan="5" style="text-align: center; width: 80px;">
                        <img src="{{ $qrCodeUrl }}" alt="Verification QR Code" style="width: 80px; height: 80px;">
                    </td>
                </tr>
                <tr>
                    <td class="var-label">Name:</td>
                    <td class="var-value">{{ $certificate->inspector_name }}</td>
                    <td class="var-label">Name:</td>
                </tr>
                <tr>
                    <td class="var-label">Signature:</td>
                    @if($certificate->inspector_signature_data)
                        <td class="var-value" style="height: 14px; text-align: left;">
                            <img src="{{ $certificate->inspector_signature_data }}" alt="Inspector Signature" style="max-height: 35px; max-width: 200px;">
                        </td>
                    @else
                        <td class="var-value" style="height: 60px; text-align: center; color: #999;">
                          
                        </td>
                    @endif
                </tr>
                <tr>
                    <td class="var-label">Stamp:</td>
                    <td class="var-value" style="height: 34px; text-align: center; position: relative;">
                        @if(\App\Models\AppSetting::getValue('company_stamp_path'))
                            <img src="{{ asset('storage/' . \App\Models\AppSetting::getValue('company_stamp_path')) }}" 
                                alt="Company Stamp" style="max-width: 110px; max-height: 130px; position: absolute; top: 10%; left: 50%; transform: translate(-50%, -50%); opacity: 0.9;">
                        @endif
                    </td>
                    <td class="var-label">Stamp:</td>
                </tr>
                <tr>
                    <td class="var-label">Date:</td>
                    <td class="var-value">{{ $certificate->inspector_date ? formatDate($certificate->inspector_date) : formatDate(now()) }}</td>
                    <td class="var-label">Date:</td>
                </tr>
            </table>
        </div>

        <!-- Inspector Signature Section -->
       
    </div>
    
    <div class="print-buttons" style="margin-top: 80px;">
        <button onclick="window.print();" style="padding: 10px 20px; background: #0066cc; color: white; border: none; border-radius: 5px; cursor: pointer;">
            Print Certificate
        </button>
        <button onclick="window.close();" style="padding: 10px 20px; background: #666; color: white; border: none; border-radius: 5px; cursor: pointer; margin-left: 10px;">
            Close
        </button>
    </div>
</body>
</html>



