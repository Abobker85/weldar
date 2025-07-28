<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welder Performance Qualifications</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: white;
            padding: 10px;
            font-size: 9px;
        }

        .certificate {
            max-width: 210mm;
            margin: 0 auto;
            border: 2px solid #000;
            background: white;
        }

        /* Header with logos */
        .header-row {
            display: flex;
            height: 80px;
            border-bottom: 2px solid #000;
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

        .header-center h1 {
            font-size: 16px;
            margin-bottom: 5px;
            color: #000;
        }

        .header-center h2 {
            font-size: 14px;
            color: #000;
        }

        .header-center .contact-info {
            font-size: 8px;
            margin-top: 5px;
            color: #666;
        }

        /* Certificate details row */
        .cert-details-row {
            display: flex;
            height: 25px;
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

        /* Main content table */
        .content-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
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

        .photo-cell {
            width: 90px;
            height: 120px;
            text-align: center;
            vertical-align: middle;
            background: #f8f9fa;
            border: 1px solid #000;
        }

        /* Section headers */
        .section-header {
            background: #e9ecef;
            font-weight: bold;
            text-align: center;
            font-size: 10px;
            padding: 5px;
        }

        /* Welding variables table */
        .variables-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8px;
            margin-top: 5px;
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

        /* Results table */
        .results-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
            margin-top: 5px;
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

        .test-result {
            text-align: center;
            font-weight: bold;
        }

        .acc-result {
            color: green;
        }

        .rej-result {
            color: red;
        }

        /* Signature section */
        .signature-section {
            margin-top: 10px;
            border: 1px solid #000;
        }

        .cert-statement {
            text-align: center;
            padding: 10px;
            font-size: 9px;
            border-bottom: 1px solid #000;
        }

        .confirmation-header {
            background: #f0f0f0;
            text-align: center;
            font-weight: bold;
            padding: 5px;
            border-bottom: 1px solid #000;
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
            height: 30px;
        }

        .org-section {
            width: 35%;
            text-align: center;
        }

        .qr-section {
            width: 15%;
            text-align: center;
            vertical-align: middle;
        }

        .qr-code {
            width: 80px;
            height: 80px;
            border: 1px solid #000;
            background: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 8px;
            margin: 0 auto;
        }

        /* Position qualification boxes */
        .position-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin: 10px 0;
        }

        .position-box {
            border: 1px solid #000;
            padding: 10px;
            text-align: center;
            background: #f8f9fa;
        }

        .position-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .position-details {
            font-size: 8px;
        }

        @media print {
            body { padding: 0; }
            .certificate { max-width: none; border: 2px solid #000; }
            button { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="certificate">
        <!-- Header with logos -->
        <div class="header-row">
            <div class="logo-left">
                @php
                    $mainLogoPath = \App\Models\AppSetting::getValue('company_logo_path');
                    $mainCompanyName = \App\Models\AppSetting::getValue('company_name', 'ELITE ENGINEERING ARABIA');
                @endphp
                @if($mainLogoPath)
                    <img src="{{ asset('storage/' . $mainLogoPath) }}" alt="{{ $mainCompanyName }} logo" height="60">
                @else
                    <div style="font-size: 10px; font-weight: bold;">{{ $mainCompanyName }}</div>
                @endif
            </div>
            <div class="header-center">
                <h1>{{ $mainCompanyName }}</h1>
                <div class="contact-info">
                    e-mail: ahmed.yousry@eliteengineeringarabia.com &nbsp;&nbsp;&nbsp;&nbsp; www.
                </div>
                <h2>WELDER PERFORMANCE QUALIFICATIONS</h2>
            </div>
            <div class="logo-right">
                @if(isset($qualification->company) && isset($qualification->company->logo_path))
                    <img src="{{ asset('storage/' . $qualification->company->logo_path) }}" alt="{{ $qualification->company->name }} logo" height="60">
                @elseif(isset($qualification->company))
                    <div style="font-size: 10px; font-weight: bold;">{{ $qualification->company->name }}</div>
                @else
                    <div style="font-size: 10px; font-weight: bold;">AIC STEEL</div>
                @endif
            </div>
        </div>

        <!-- Certificate details row -->
        <div class="cert-details-row">
            <div class="cert-left">Certificate No: {{ \App\Models\AppSetting::getValue('doc_prefix', 'EEA') }}-AIC-WQT-{{ str_pad($qualification->id ?? 1, 4, '0', STR_PAD_LEFT) }}</div>
            <div class="cert-center">{{ $qualification->welder->name ?? 'Welder Name' }}</div>
            <div class="cert-right">Welder ID No: {{ $qualification->welder->id ?? '27792' }}</div>
        </div>

        <!-- Second details row -->
        <div class="cert-details-row">
            <div class="cert-left">Gov ID Iqama number: {{ $qualification->welder->iqama_no ?? '2581413107' }}</div>
            <div class="cert-center">{{ $qualification->company->name ?? 'Arabian International Company (AIC)' }}</div>
            <div class="cert-right">Passport No: {{ $qualification->welder->passport_no ?? 'XXXXXXX' }}</div>
        </div>

        <!-- Main content table -->
        <table class="content-table">
            <tr>
                <td class="label-cell">Identification of WPS followed:</td>
                <td class="value-cell">{{ $qualification->wps_no ?? 'AIC-WPS-SCM-041 Rev.01' }}</td>
                <td class="label-cell">Test coupon</td>
                <td class="value-cell-center">☐ Production weld</td>
                <td rowspan="4" class="photo-cell">
                    @if(isset($qualification->welder->photo_path))
                        <img src="{{ asset('storage/' . $qualification->welder->photo_path) }}" alt="Welder's Photo" style="width:80px; height:110px; object-fit:cover;">
                    @else
                        <div style="width:80px; height:110px; background:#e0e0e0; display:flex; align-items:center; justify-content:center; font-size:8px; color:#333;">
                            No Photo
                        </div>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="label-cell">Base Metal Specification:</td>
                <td class="value-cell">{{ $qualification->base_metal_spec ?? 'ASTM A106 Gr B' }}</td>
                <td class="label-cell">Date of Test:</td>
                <td class="value-cell-center">{{ $qualification->test_date ? \Carbon\Carbon::parse($qualification->test_date)->format('M d Y') : 'April 21 2025' }}</td>
            </tr>
            <tr>
                <td class="label-cell">Dia / Thickness:</td>
                <td class="value-cell">{{ $qualification->dia_thickness ?? '8 inch/18.26 mm' }}</td>
                <td class="label-cell"></td>
                <td class="value-cell-center"></td>
            </tr>
            <tr>
                <td colspan="4" class="section-header">Testing Variables and Qualification Limits</td>
            </tr>
        </table>

        <!-- Welding Variables Table -->
        <table class="variables-table">
            <tr>
                <td class="var-label">Welding Variables (QW-350)</td>
                <td class="var-value" style="width: 150px;"><strong>Actual Values</strong></td>
                <td class="var-range" style="width: 200px;"><strong>Range Qualified</strong></td>
            </tr>
            <tr>
                <td class="var-label">Welding process(es):</td>
                <td class="var-value">{{ $qualification->welding_process ?? 'SMAW (Filling/Cap)' }}</td>
                <td class="var-range">SMAW</td>
            </tr>
            <tr>
                <td class="var-label">Type (i.e., manual, semi-automatic) used:</td>
                <td class="var-value">Manual</td>
                <td class="var-range">Manual</td>
            </tr>
            <tr>
                <td class="var-label">Backing (with/without):</td>
                <td class="var-value">{{ $qualification->backing ?? 'With Backing' }}</td>
                <td class="var-range">With Backing</td>
            </tr>
            <tr>
                <td class="var-label">☐ Plate ■ Pipe (enter diameter if pipe or tube)</td>
                <td class="var-value">{{ $qualification->pipe_diameter ?? 'NPS 8' }}</td>
                <td class="var-range">2 7/8 inch OD to unlimited</td>
            </tr>
            <tr>
                <td class="var-label">Base metal P-Number to P-Number:</td>
                <td class="var-value">{{ $qualification->base_metal_p_no ?? 'P NO.1 TO P NO.1' }}</td>
                <td class="var-range">P-NO. 1 through P-NO. 15F, P-NO. 34, and P-NO. 41 through P-NO. 49</td>
            </tr>
            <tr>
                <td class="var-label">Filler metal or electrode specification(s) (SFA) (info. only):</td>
                <td class="var-value">{{ $qualification->filler_metal_spec ?? '5.1' }}</td>
                <td class="var-range">........</td>
            </tr>
            <tr>
                <td class="var-label">Filler metal or electrode classification(s) (info. only):</td>
                <td class="var-value">{{ $qualification->filler_metal_class ?? 'E7018-1' }}</td>
                <td class="var-range">........</td>
            </tr>
            <tr>
                <td class="var-label">Filler metal F-Number(s):</td>
                <td class="var-value">{{ $qualification->filler_metal_f_no ?? 'F4' }}</td>
                <td class="var-range">F1,F2,F3 & F4 With Backing</td>
            </tr>
            <tr>
                <td class="var-label">Consumable insert (GTAW, PAW, LBW):</td>
                <td class="var-value">........</td>
                <td class="var-range">........</td>
            </tr>
            <tr>
                <td class="var-label">Filler Metal Product Form (QW-404.23) (GTAW or PAW):</td>
                <td class="var-value">........</td>
                <td class="var-range">........</td>
            </tr>
            <tr>
                <td class="var-label">Deposit thickness for each process:</td>
                <td class="var-value">{{ $qualification->deposit_thickness ?? '4mm &14.26 mm' }}</td>
                <td class="var-range">........</td>
            </tr>
            <tr>
                <td class="var-label">Process 1 __ GTAW __ 3 layers minimum &nbsp;&nbsp;&nbsp; ☐ YES &nbsp;&nbsp;&nbsp; ☐ NO</td>
                <td class="var-value">........</td>
                <td class="var-range">........</td>
            </tr>
            <tr>
                <td class="var-label">Process 2 __ SMAW __ 3 layers minimum &nbsp;&nbsp;&nbsp; ■ YES &nbsp;&nbsp;&nbsp; ☐ NO</td>
                <td class="var-value">{{ $qualification->smaw_thickness ?? '14.26 mm' }}</td>
                <td class="var-range">Max. to be welded</td>
            </tr>
        </table>

        <!-- Position qualification section -->
        <div class="position-grid">
            <div class="position-box">
                <div class="position-title">Groove Plate and Pipe Over 24 in. (610 mm) O.D. in all Position</div>
                <div class="position-details">
                    Position(s): {{ $qualification->welding_positions ?? '6G' }}
                </div>
            </div>
            <div class="position-box">
                <div class="position-title">Groove Pipe ≤24 in. (610 mm) O.D. in all Position</div>
                <div class="position-details">
                    Fillet or Tack Plate and Pipe in all Position
                </div>
            </div>
        </div>

        <!-- Additional variables -->
        <table class="variables-table">
            <tr>
                <td class="var-label">Vertical progression (uphill or downhill):</td>
                <td class="var-value">{{ $qualification->vertical_progression ?? 'Uphill' }}</td>
                <td class="var-range">Uphill</td>
            </tr>
            <tr>
                <td class="var-label">Type of fuel gas (OFW):</td>
                <td class="var-value">........</td>
                <td class="var-range">........</td>
            </tr>
            <tr>
                <td class="var-label">Use of backing gas (GTAW, PAW, GMAW, LBW):</td>
                <td class="var-value">........</td>
                <td class="var-range">........</td>
            </tr>
            <tr>
                <td class="var-label">Transfer mode (spray, globular, or pulse to short circuit-GMAW):</td>
                <td class="var-value">........</td>
                <td class="var-range">........</td>
            </tr>
            <tr>
                <td class="var-label">GTAW current type and polarity (AC, DCEP, DCEN) For LBW or LLBW:</td>
                <td class="var-value">........</td>
                <td class="var-range">........</td>
            </tr>
            <tr>
                <td class="var-label">Type of equipment</td>
                <td class="var-value">........</td>
                <td class="var-range">........</td>
            </tr>
            <tr>
                <td class="var-label">Technique (keyhole LBW or melt-in)</td>
                <td class="var-value">........</td>
                <td class="var-range">........</td>
            </tr>
            <tr>
                <td class="var-label">Torch/Gun/Beam oscillation &nbsp;&nbsp;&nbsp; ☐YES &nbsp;&nbsp;&nbsp; ☐ NO</td>
                <td class="var-value">........</td>
                <td class="var-range">........</td>
            </tr>
            <tr>
                <td class="var-label">Mode of operation (pulsed or continuous)</td>
                <td class="var-value">........</td>
                <td class="var-range">........</td>
            </tr>
        </table>

        <!-- Results Section -->
        <table class="results-table">
            <tr>
                <td colspan="3" class="section-header">RESULTS</td>
            </tr>
            <tr>
                <td class="test-header">Visual examination of completed weld (QW-302.4)</td>
                <td class="test-header">ACC (Report No.EEA-AIC-VT-0566)</td>
                <td class="test-header">■ ■ RT &nbsp;&nbsp;&nbsp; ☐ UT</td>
            </tr>
            <tr>
                <td class="var-label">☐ Transverse face and root bends (QW-462.3(a))</td>
                <td class="var-value">☐ Longitudinal bends (QW-462.3(b))</td>
                <td class="var-value">☐ Side bends (QW-462.3(c))</td>
            </tr>
            <tr>
                <td class="var-label">☐ Pipe bend specimen, corrosion-resistant weld metal overlay (QW-462.5(c))</td>
                <td colspan="2" class="var-value"></td>
            </tr>
            <tr>
                <td class="var-label">☐ Plate bend specimen, corrosion-resistant weld metal overlay (QW-462.5(b))</td>
                <td colspan="2" class="var-value"></td>
            </tr>
            <tr>
                <td class="var-label">☐ Pipe specimen, macro test for fusion (QW-462.5(b))</td>
                <td class="var-value">☐ Plate specimen, macro test for fusion (QW-462.5(a))</td>
                <td class="var-value"></td>
            </tr>
            <tr>
                <td class="var-label">TYPE</td>
                <td class="var-value">RESULT</td>
                <td class="var-value">TYPE</td>
            </tr>
            <tr>
                <td class="var-label">Visual examination of completed weld (QW-302.4)</td>
                <td class="var-value test-result acc-result">ACC</td>
                <td class="var-value">RESULT</td>
            </tr>
        </table>

        <!-- Alternative Volumetric Examination -->
        <table class="results-table">
            <tr>
                <td class="test-header">Alternative Volumetric Examination Results</td>
                <td class="test-header">ACC (Report No.EEA-AIC-RT-0566 ( Doc No.# :SD-629764)</td>
                <td class="test-header">■ ■ RT &nbsp;&nbsp;&nbsp; ☐ UT</td>
            </tr>
            <tr>
                <td class="var-label">Fillet weld-fracture test (QW-181.2):</td>
                <td class="var-value">Length and percent of defects</td>
                <td class="var-value">........</td>
            </tr>
            <tr>
                <td class="var-label">fillet welds in plate (QW-462.4(e))</td>
                <td class="var-value">☐ Fillet welds in pipe (QW-462.4(c))</td>
                <td class="var-value"></td>
            </tr>
            <tr>
                <td class="var-label">Macro examination (QW-184)</td>
                <td class="var-value">........</td>
                <td class="var-value">Fillet size (in.)</td>
            </tr>
            <tr>
                <td class="var-label">Other tests</td>
                <td class="var-value">........</td>
                <td class="var-value">Linearity or convexity (in.)</td>
            </tr>
        </table>

        <!-- Film examination -->
        <table class="results-table">
            <tr>
                <td class="var-label">Film or specimens evaluated by</td>
                <td class="var-value">{{ $qualification->evaluated_by ?? 'Kalith Majeedh' }}</td>
                <td class="var-value">Company</td>
            </tr>
            <tr>
                <td class="var-label">Mechanical tests conducted by</td>
                <td class="var-value">........</td>
                <td class="var-value">Laboratory test no.</td>
            </tr>
            <tr>
                <td class="var-label">Welding supervised by</td>
                <td class="var-value">{{ $qualification->supervised_by ?? 'Ahmed Yousry' }}</td>
                <td class="var-value">Company</td>
            </tr>
        </table>

        <!-- Certification statement -->
        <div class="signature-section">
            <div class="cert-statement">
                <strong>We certify that the statements in this record are correct and that the test coupons were prepared, welded, and tested in accordance with the requirements of Section IX of the ASME BOILER AND PRESSURE VESSEL CODE.</strong>
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
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr class="sig-row">
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
                <tr class="sig-row">
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>

        <!-- Final signature section -->
        <table class="signature-table" style="margin-top: 10px;">
            <tr>
                <td colspan="3" class="sig-header">Organization</td>
                <td class="sig-header">QR CODE</td>
            </tr>
            <tr>
                <td class="var-label">Test Witnessed by:</td>
                <td class="var-value">{{ \App\Models\AppSetting::getValue('company_name', 'ELITE ENGINEERING ARABIA') }}</td>
                <td class="var-value">Reviewed / Approved by:</td>
                <td rowspan="6" class="qr-section">
                    @if(isset($qrCodeUrl))
                        <img src="{{ $qrCodeUrl }}" alt="QR Code" style="width:80px; height:80px;">
                    @else
                        <div class="qr-code">QR Code</div>
                    @endif
                </td>
            </tr>
            <tr>
                <td class="var-label">Name:</td>
                <td class="var-value">{{ Auth::user()->name ?? 'Ahmed Yousry' }}</td>
                <td class="var-label">Name:</td>
            </tr>
            <tr>
                <td class="var-label">Signature:</td>
                <td class="var-value" style="height: 25px;"></td>
                <td class="var-label">Signature:</td>
            </tr>
            <tr>
                <td class="var-label">Stamp:</td>
                <td class="var-value" style="height: 25px;"></td>
                <td class="var-label">Stamp:</td>
            </tr>
            <tr>
                <td class="var-label">Date:</td>
                <td class="var-value">{{ now()->format('d-M-y') }}</td>
                <td class="var-label">Date:</td>
            </tr>
        </table>
    </div>

    <script>
        function printCertificate() { window.print(); }
        document.addEventListener('DOMContentLoaded', function() {
            if (window.self === window.top) { 
                const printBtn = document.createElement('button');
                printBtn.textContent = 'Print Certificate';
                printBtn.id = 'certPrintButton'; 
                printBtn.style.cssText = `position: fixed; top: 10px; right: 10px; background: #007bff; color: white; border: none; padding: 8px 15px; border-radius: 5px; cursor: pointer; font-size: 12px; z-index: 1000; box-shadow: 0 2px 4px rgba(0,0,0,0.2);`;
                printBtn.onclick = printCertificate;
                document.body.appendChild(printBtn);
                
                const qrInfo = document.createElement('div');
                qrInfo.textContent = 'Scan QR code to verify certificate authenticity';
                qrInfo.style.cssText = `position: fixed; bottom: 10px; right: 10px; background: rgba(255,255,255,0.8); color: #333; border: 1px solid #ccc; padding: 5px 10px; border-radius: 3px; font-size: 10px; z-index: 1000;`;
                document.body.appendChild(qrInfo);
                
                window.addEventListener('beforeprint', function() {
                    printBtn.style.display = 'none';
                    qrInfo.style.display = 'none';
                });
                
                window.addEventListener('afterprint', function() {
                    printBtn.style.display = 'block';
                    qrInfo.style.display = 'block';
                });
            }
        });
    </script>
</body>
</html>