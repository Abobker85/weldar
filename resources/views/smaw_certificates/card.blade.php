<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welder Qualification Card</title>
    <style>
        @page {
            margin: 15mm;
            size: A4;
        }
        
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        
        .card-container {
            width: 87.6mm;
            height: 60mm;
            border: 2px solid #000;
            padding: 3mm;
            box-sizing: border-box;
            background-color: white;
            transform: scale(2.2);
            transform-origin: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }

        .card {
            width: 100%;
            height: 100%;
            position: relative;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            page-break-inside: avoid;
        }

        /* Header Section */
        .card-top-header {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1mm;
            height: 7mm;
        }
        
        .card-top-header .logo {
            width: 18mm;
            height: 7mm;
            margin-right: 2mm;
            background-color: #e8e8e8;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 6px;
            border: 1px solid #ccc;
        }
        
        .card-top-header .issuing-body-info {
            flex-grow: 1;
        }
        
        .card-top-header .issuing-body-info p {
            margin: 0;
            line-height: 1.2;
        }
        
        .issuing-body-info .company-name {
            font-weight: bold;
            font-size: 7px;
        }
        
        .issuing-body-info .address {
            font-size: 5.5px;
            color: #333;
        }

        /* Main Title */
        .card-main-title {
            text-align: center;
            margin-bottom: 1mm;
            padding-bottom: 0.5mm;
            border-bottom: 1px solid #333;
        }
        
        .card-main-title h1 {
            margin: 0;
            font-size: 9px;
            font-weight: bold;
            letter-spacing: 0.5px;
        }

        /* Main Content Area */
        .card-content-area {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1mm;
            flex-grow: 1;
            min-height: 18mm;
        }
        
        .info-left-column {
            width: calc(100% - 20mm); /* Adjusted width to accommodate photo */
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }
        
        .photo-right-column {
            width: 18mm;
            height: 18mm;
            border: 1px solid #000;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #f8f8f8;
            font-size: 5px;
            color: #666;
        }
        
        .info-row {
            display: flex;
            margin-bottom: 0.6mm;
            line-height: 1.1;
        }
        
        .info-row .label {
            font-weight: bold;
            width: 35%;
            padding-right: 1mm;
            white-space: nowrap;
            font-size: 6px;
        }
        
        .info-row .value {
            width: 65%;
            word-break: break-word;
            font-size: 6px;
        }

        /* Certification Statement */
        .certification-statement-section {
            text-align: center;
            font-style: italic;
            margin-bottom: 1mm;
            padding: 0.5mm 0;
            border-top: 1px solid #333;
            border-bottom: 1px solid #333;
            font-size: 6px;
            line-height: 1.2;
        }
        
        .certification-statement-section p {
            margin: 0;
        }

        /* Authorization Section */
        .authorization-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1mm;
            height: 7mm;
        }
        
        .auth-details {
            text-align: left;
            flex-grow: 1;
        }
        
        .auth-details .auth-label {
            font-size: 6px;
            margin: 0;
        }
        
        .auth-details .auth-name {
            font-weight: bold;
            font-size: 6.5px;
            margin: 0 0 1mm 0;
        }
        
        .signature-placeholder {
            width: 20mm;
            height: 3mm;
            border-bottom: 1px solid #000;
            display: flex;
            align-items: center;
            justify-content: center;
            font-style: italic;
            font-size: 5px;
        }

        .auth-date-container {
            text-align: right;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            height: 100%;
        }
        
        .auth-date {
            font-size: 6px;
            margin-bottom: 0.5mm;
        }

        /* Barcode Section */
        .barcode-section {
            text-align: center;
            padding: 1.5mm 0;
            border-top: 1px solid #333;
            margin-top: -9mm; /* Changed from 7mm to -9mm */
        }
        
        .barcode-text {
            font-size: 5.5px;
            margin-top: 0.5mm;
            font-weight: bold;
        }

        /* Print Styles */
        @media print {
            body {
                background-color: white;
                padding: 0;
                margin: 0;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
            }
            
            .card-container {
                box-shadow: none;
                /* Remove scaling for print, show actual card size */
                transform: none !important;
                page-break-inside: avoid;
                margin: auto;
            }
            
            .print-button, .download-button {
                display: none;
            }
        }

        /* Buttons */
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 12px 24px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
        }
        
        .download-button {
            position: fixed;
            top: 70px;
            right: 20px;
            padding: 12px 24px;
            background: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
        }
    </style>
</head>
<body>
    <button class="print-button" onclick="window.print()">Print Card</button>
    
    <div class="card-container" id="welderCard">
        <div class="card">
            <!-- Card Header with Logo and Company Info -->
            <div class="card-top-header">
                <div class="logo">
                    @php
                        $companyLogoPath = App\Models\AppSetting::getValue('company_logo_path', '');
                    @endphp
                    @if(!empty($companyLogoPath))
                        <img src="{{ asset('storage/' . $companyLogoPath) }}" alt="Logo" style="max-width: 100%; max-height: 100%;">
                    @else
                        <strong>{{ App\Models\AppSetting::getValue('company_name', 'COMPANY') }}</strong>
                    @endif
                </div>
                <div class="issuing-body-info">
                    <p class="company-name">{{ App\Models\AppSetting::getValue('company_name', 'COMPANY NAME') }}</p>
                    <p class="address">{{ App\Models\AppSetting::getValue('address', 'Company Address') }}</p>
                    <p class="address">{{ App\Models\AppSetting::getValue('email', 'Email') }} | {{ App\Models\AppSetting::getValue('phone', 'Phone') }}</p>
                </div>
            </div>
            
            <!-- Main Title -->
            <div class="card-main-title">
                <h1>SMAW WELDER QUALIFICATION CARD</h1>
            </div>
            
            <!-- Main Content Area -->
            <div class="card-content-area">
                <div class="info-left-column">
                    <div class="info-row">
                        <div class="label">Company:</div>
                        <div class="value">{{ $certificate->company->name ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="label">Welder Name:</div>
                        <div class="value">{{ $certificate->welder->name ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="label">ID/Iqama No:</div>
                        <div class="value">{{ $certificate->welder->iqama_no ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="label">Welder ID:</div>
                        <div class="value">{{ $certificate->welder->welder_no ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="label">Certificate No:</div>
                        <div class="value">{{ $certificate->certificate_no ?? 'N/A' }}</div>
                    </div>
                </div>               
                
                <div class="photo-right-column">
                    @if($certificate->photo_path)
                        <img src="{{ asset('storage/' . $certificate->photo_path) }}" alt="Welder Photo" style="max-width: 100%; max-height: 100%;">
                    @elseif($certificate->welder->photo)
                        <img src="{{ asset('storage/' . $certificate->welder->photo) }}" alt="Welder Photo" style="max-width: 100%; max-height: 100%;">
                    @else
                        PHOTO
                    @endif
                </div>
            </div>
            
            <!-- Certification Statement -->
            <div class="certification-statement-section">
                <p>This is to certify that the above named welder has been qualified according to ASME IX standards</p>
            </div>
            
            <!-- Authorization Section -->
            <div class="authorization-section">
                <div class="auth-details">
                    <p class="auth-label">Authorized By</p>
                    <p class="auth-name">{{ $certificate->inspector_name ?? 'Inspector' }}</p>
                    <div class="signature-placeholder">
                        Signature
                    </div>
                </div>
                
                <div class="auth-date-container">
                    <p class="auth-date">Date: {{ $certificate->test_date ? $certificate->test_date->format('d-m-Y') : 'N/A' }}</p>
                    <p class="auth-date">Expiry: {{ $certificate->test_date ? $certificate->test_date->addMonths(6)->format('d-m-Y') : 'N/A' }}</p>
                </div>
            </div>
              
            <!-- QR Code Section -->
            <div class="barcode-section">
                <img src="{{ $qrCodeUrl }}" alt="QR Code" style="width:45px; height:45px;">
                <div class="barcode-text">{{ $certificate->certificate_no ?? 'N/A' }}</div>
                <div style="font-size: 4px; color: #666; margin-top: 1px;">Scan to verify qualification</div>
            </div>
        </div>
    </div>

    <script>
        // No additional scripts needed
    </script>
</body>
</html>
