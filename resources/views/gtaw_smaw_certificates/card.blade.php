<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welder Qualification Card - {{ $certificate->welder->name }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f0f0f0;
        }
        .card-container {
            width: 350px;
            height: 220px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            overflow: hidden;
            position: relative;
        }
        .card-header {
            background-color: #003366;
            color: white;
            padding: 10px 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .company-name {
            font-size: 14px;
            font-weight: bold;
        }
        .card-title {
            font-size: 12px;
        }
        .card-body {
            padding: 15px;
            display: flex;
        }
        .welder-photo {
            width: 90px;
            height: 120px;
            border: 1px solid #ddd;
            margin-right: 15px;
            overflow: hidden;
        }
        .welder-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .welder-info {
            flex: 1;
        }
        .info-row {
            margin-bottom: 5px;
            font-size: 12px;
        }
        .label {
            font-weight: bold;
            display: inline-block;
            width: 80px;
        }
        .value {
            display: inline-block;
        }
        .card-footer {
            padding: 10px 15px;
            background-color: #f9f9f9;
            border-top: 1px solid #ddd;
            font-size: 10px;
            position: absolute;
            bottom: 0;
            width: 100%;
            box-sizing: border-box;
        }
        .certificate-no {
            font-weight: bold;
        }
        .qrcode {
            position: absolute;
            bottom: 25px;
            right: 15px;
            width: 60px;
            height: 60px;
        }
        .qrcode img {
            width: 100%;
            height: 100%;
        }
        @media print {
            body {
                margin: 0;
                padding: 0;
                background-color: #fff;
            }
            .card-container {
                margin: 0;
                box-shadow: none;
                page-break-inside: avoid;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin: 20px 0;">
        <button onclick="window.print()">Print Card</button>
        <button onclick="window.close()">Close</button>
    </div>

    <div class="card-container">
        <div class="card-header">
            <div class="company-name">{{ $certificate->company->name ?? 'Company Name' }}</div>
            <div class="card-title">GTAW-SMAW WELDER QUALIFICATION CARD</div>
        </div>
        
        <div class="card-body">
            <div class="welder-photo">
                @if($certificate->photo_path || ($welder && $welder->photo_path))
                    <img src="{{ asset('storage/' . ($certificate->photo_path ?: $welder->photo_path)) }}" alt="Welder Photo">
                @else
                    <div style="text-align: center; padding-top: 40px;">No Photo</div>
                @endif
            </div>
            
            <div class="welder-info">
                <div class="info-row">
                    <span class="label">Name:</span>
                    <span class="value">{{ $welder->name ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">ID No.:</span>
                    <span class="value">{{ $welder->welder_no ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Position:</span>
                    <span class="value">{{ $certificate->test_position ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Material:</span>
                    <span class="value">{{ $certificate->base_metal_spec ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Thickness:</span>
                    <span class="value">{{ $certificate->thickness ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Test Date:</span>
                    <span class="value">{{ optional($certificate->test_date)->format('Y-m-d') ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Expiry Date:</span>
                    <span class="value">{{ optional($certificate->test_date)->addYears(2)->format('Y-m-d') ?? 'N/A' }}</span>
                </div>
            </div>
            
            <div class="qrcode">
                <img src="{{ $qrCodeUrl }}" alt="Verification QR Code">
            </div>
        </div>
        
        <div class="card-footer">
            <div class="certificate-no">Certificate No: {{ $certificate->certificate_no ?? 'N/A' }}</div>
            <div>Scan the QR code to verify this qualification</div>
        </div>
    </div>
</body>
</html>
