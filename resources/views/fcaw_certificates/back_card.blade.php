<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FCAW Welding Qualification Card</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f5f5f5;
        }
        
        .card-container {
            background: white;
            border: 2px solid #000;
            width: 800px;
            margin: 0 auto;
            padding: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .card-header {
            text-align: center;
            font-weight: bold;
            font-size: 18px;
            margin-bottom: 15px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        
        td, th {
            border: 1px solid #000;
            padding: 4px;
            text-align: left;
            vertical-align: top;
        }
        
        .header-cell {
            background-color: #e0e0e0;
            font-weight: bold;
            text-align: center;
        }
        
        .label-cell {
            background-color: #f0f0f0;
            font-weight: bold;
            width: 120px;
        }
        
        .value-cell {
            background-color: white;
        }
        
        .qualified-cell {
            background-color: #90EE90;
            text-align: center;
            font-weight: bold;
        }
        
        .not-qualified-cell {
            background-color: #FFB6C1;
            text-align: center;
            font-weight: bold;
        }
        
        .footer-note {
            font-size: 10px;
            text-align: center;
            margin-top: 10px;
            padding: 5px;
            border: 1px solid #000;
            background-color: #f9f9f9;
        }
        
        .barcode-container {
            text-align: center;
            margin-top: 15px;
            padding: 10px;
            border-top: 1px solid #000;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }
        
        @media print {
            html, body {
                height: 100%;
                margin: 0 !important;
                padding: 0 !important;
                background: white !important;
            }
            body {
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
            }
            .print-button {
                display: none !important;
            }
            .card-container {
                box-shadow: none;
                border: 2px solid #000;
                margin: 0 !important;
                position: static !important;
                width: 800px;
                page-break-inside: avoid;
            }
            /* Hide everything except the card */
            body > :not(.card-container) {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">Print Card</button>
    
    <div class="card-container" id="weldingCard">
        <div class="card-header">
            SMAW WELDING QUALIFICATION CARD
        </div>
        
        <table>
            <tr>
                <td class="label-cell">Certificate No</td>
                <td class="value-cell">{{ $certificate->certificate_no }}</td>
                <td class="label-cell">WPS Followed</td>
                <td class="value-cell">{{ $certificate->wps_followed }}</td>
            </tr>
            <tr>
                <td class="label-cell">Process</td>
                <td class="value-cell">SMAW</td>
                <td class="label-cell">Joint Type</td>
                <td class="value-cell">{{ $certificate->pipe ? 'Pipe Joint' : 'Plate Joint' }}</td>
            </tr>
            <tr>
                <td class="label-cell">Test Position</td>
                <td class="value-cell">{{ $certificate->test_position }}</td>
                <td class="label-cell">Position Qualified</td>
                <td class="value-cell">{{ $certificate->position_range }}</td>
            </tr>
            <tr>
                <td class="label-cell">Vertical Progression</td>
                <td class="value-cell">{{ $certificate->vertical_progression }}</td>
                <td class="label-cell">Test Thickness</td>
                <td class="value-cell">{{ $certificate->smaw_thickness }} mm</td>
            </tr>
            <tr>
                <td class="label-cell">Test Dia</td>
                <td class="value-cell">{{ $certificate->pipe ? ($certificate->dia_thickness ?? 'N/A') : 'N/A' }}</td>
                <td class="label-cell">Range Qualified</td>
                <td class="value-cell">{{ $certificate->diameter_range }}</td>
            </tr>
            <tr>
                <td class="header-cell" colspan="2">QUALIFICATION STATUS</td>
                <td class="label-cell">Electrode Class</td>
                <td class="value-cell">{{ $certificate->filler_class_manual ?? $certificate->filler_class }}</td>
            </tr>
            <tr>
                <td class="value-cell">Backing: {{ $certificate->backing_manual ?? $certificate->backing }}</td>
                <td class="value-cell">Base Metal: {{ $certificate->base_metal_spec }}</td>
                <td class="label-cell">Test Method</td>
                <td class="value-cell">
                    @if($certificate->rt) RT @endif
                    @if($certificate->ut) UT @endif
                    @if(!$certificate->rt && !$certificate->ut) Visual @endif
                </td>
            </tr>
            <tr>
                <td class="label-cell">F-Number</td>
                <td class="value-cell">{{ $certificate->filler_f_no_manual ?? $certificate->filler_f_no }}</td>
                <td class="label-cell">P-Number</td>
                <td class="value-cell">{{ $certificate->base_metal_p_no_manual ?? $certificate->base_metal_p_no }}</td>
            </tr>
            <tr>
                <td class="label-cell">Place of Issue</td>
                <td class="value-cell">{{ App\Models\AppSetting::getValue('company_location', 'N/A') }}</td>
                <td class="label-cell">Date of Test</td>
                <td class="value-cell">{{ $certificate->test_date ? $certificate->test_date->format('d-M-Y') : 'N/A' }}</td>
            </tr>
        </table>
        
        <div class="footer-note">
            This certificate qualifies the welder for 6 months from the date of test. Beyond this date, welding records must be consulted to ensure qualification is maintained per ASME IX requirements.
        </div>
        <div class="barcode-container">
            <img src="{{ $qrCodeUrl }}" alt="QR Code" style="width:100px; height:100px;">
            <div style="margin-top: 5px; font-size: 10px; font-weight: bold;">
                {{ $certificate->certificate_no }}
            </div>
            <div style="margin-top: 3px; font-size: 8px; color: #666;">
                Scan to verify qualification
            </div>
        </div>
    </div>

    <script>
        // No additional scripts needed
    </script>
</body>
</html>
