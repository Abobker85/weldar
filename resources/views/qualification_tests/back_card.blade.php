<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welding Qualification Certificate</title>
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
    <button class="print-button" onclick="window.print()">Print Certificate</button>
    
    <div class="card-container" id="weldingCard">
        <div class="card-header">
            WELDING QUALIFICATION CERTIFICATE
        </div>
        
        <table>
            <tr>
                <td class="label-cell">Card No</td>
                <td class="value-cell">{{ $qualification->cert_no }}</td>
                <td class="label-cell">WPS/PQR NO.</td>
                <td class="value-cell">{{ $qualification->wps_no }}</td>
            </tr>
            <tr>
                <td class="label-cell">Process</td>
                <td class="value-cell">{{ $qualification->welding_process }}</td>
                <td class="label-cell">Joint Type</td>
                <td class="value-cell">{{ $qualification->joint_type ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label-cell">Test Position</td>
                <td class="value-cell">{{ $qualification->welding_positions }}</td>
                <td class="label-cell">Position Qualified</td>
                <td class="value-cell">{{ $qualification->qualified_position }}</td>
            </tr>
            <tr>
                <td class="label-cell">Vertical Progression</td>
                <td class="value-cell">{{ $qualification->vertical_progression ?? 'N/A' }}</td>
                <td class="label-cell">Test Thickness</td>
                <td class="value-cell">{{ $qualification->coupon_thickness_mm }} mm</td>
            </tr>
            <tr>
                <td class="label-cell">Test Dia</td>
                <td class="value-cell">{{ $qualification->dia_inch ?? 'N/A' }}</td>
                <td class="label-cell">Range Qualified</td>
                <td class="value-cell">{{ $qualification->qualified_thickness_range ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="header-cell" colspan="2">QUALIFICATION STATUS</td>
                <td class="label-cell">Electrode Class</td>
                <td class="value-cell">{{ $qualification->filler_metal_classif ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="qualified-cell">@if($qualification->test_result) ✓ Qualified @else ✗ Not Qualified @endif</td>
                <td class="value-cell">{{ $qualification->qualified_material ?? 'N/A' }}</td>
                <td class="label-cell">Test Method</td>
                <td class="value-cell">{{ $qualification->test_method ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="not-qualified-cell">@if(!$qualification->test_result) ✗ Not Qualified @endif</td>
                <td class="value-cell">{{ $qualification->not_qualified_material ?? '' }}</td>
                <td class="label-cell">Result</td>
                <td class="value-cell">{{ $qualification->test_result ? 'OK' : 'NOT OK' }}</td>
            </tr>
            <tr>
                <td class="label-cell">Place of Issue</td>
                <td class="value-cell">{{ $qualification->location ?? 'N/A' }}</td>
                <td class="label-cell" colspan="2"></td>
            </tr>
            <tr>
                <td class="label-cell">Date of Test</td>
                <td class="value-cell">{{ $qualification->vt_date ? \Carbon\Carbon::parse($qualification->vt_date)->format('d-M-Y') : 'N/A' }}</td>
                <td class="label-cell" colspan="2"></td>
            </tr>
        </table>
        
        <div class="footer-note">
            This certificate, on its own, qualifies the welder for 6 months from the date of test. Beyond this date, welding records must be consulted to ensure a welder's qualification has been maintained.
        </div>
          <div class="barcode-container">
            <img src="{{ $qrCodeUrl }}" alt="QR Code" style="width:100px; height:100px;">
            <div style="margin-top: 5px; font-size: 10px; font-weight: bold;">
                {{ $qualification->cert_no }}
            </div>
            <div style="margin-top: 3px; font-size: 8px; color: #666;">
                Scan to verify qualification
            </div>
        </div>
    </div>

    <script>
        // Initialize any other scripts if needed
        window.onload = function() {};
    </script>
</body>
</html>