<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welder Qualification Card Back - {{ $certificate->welder->name }}</title>
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
        }
        .card-header {
            background-color: #003366;
            color: white;
            padding: 10px 15px;
            text-align: center;
            font-weight: bold;
        }
        .card-body {
            padding: 10px 15px;
        }
        .section {
            margin-bottom: 8px;
        }
        .section-title {
            font-weight: bold;
            font-size: 12px;
            color: #003366;
            margin-bottom: 3px;
        }
        .section-content {
            font-size: 10px;
            line-height: 1.3;
        }
        .two-columns {
            display: flex;
            justify-content: space-between;
        }
        .column {
            width: 48%;
        }
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 10px;
            font-size: 10px;
        }
        .signature {
            text-align: center;
            width: 45%;
        }
        .signature-line {
            width: 100%;
            border-bottom: 1px solid #000;
            margin-bottom: 3px;
            height: 20px;
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
            QUALIFICATION DETAILS - GTAW-SMAW
        </div>
        
        <div class="card-body">
            <div class="two-columns">
                <div class="column">
                    <div class="section">
                        <div class="section-title">Process:</div>
                        <div class="section-content">GTAW-SMAW</div>
                    </div>
                    
                    <div class="section">
                        <div class="section-title">Welding Position:</div>
                        <div class="section-content">{{ $certificate->test_position ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="section">
                        <div class="section-title">Thickness Range:</div>
                        <div class="section-content">{{ $certificate->thickness ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="section">
                        <div class="section-title">Filler Metal:</div>
                        <div class="section-content">{{ $certificate->filler_spec ?? 'N/A' }} {{ $certificate->filler_class ?? '' }}</div>
                    </div>
                </div>
                
                <div class="column">
                    <div class="section">
                        <div class="section-title">Base Material:</div>
                        <div class="section-content">{{ $certificate->base_metal_spec ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="section">
                        <div class="section-title">Diameter (if pipe):</div>
                        <div class="section-content">{{ $certificate->diameter ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="section">
                        <div class="section-title">Backing:</div>
                        <div class="section-content">{{ $certificate->backing ?? 'N/A' }}</div>
                    </div>
                    
                    <div class="section">
                        <div class="section-title">Vertical Progression:</div>
                        <div class="section-content">{{ $certificate->vertical_progression ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>
            
            <div class="section">
                <div class="section-title">Qualification Range:</div>
                <div class="section-content">
                    {{ $certificate->position_range ?? 'All positions as qualified' }} |
                    {{ $certificate->backing_range ?? 'With backing or backing and gouging' }}
                </div>
            </div>
            
            <div class="signatures">
                <div class="signature">
                    <div class="signature-line">
                        @if($certificate->inspector_signature_data)
                            <img src="{{ $certificate->inspector_signature_data }}" alt="Inspector Signature" style="max-height: 20px; max-width: 100%;">
                        @endif
                    </div>
                    <div>Inspector</div>
                </div>
                
                <div class="signature">
                    <div class="signature-line">
                        @if($certificate->signature_data)
                            <img src="{{ $certificate->signature_data }}" alt="Authorized Signature" style="max-height: 20px; max-width: 100%;">
                        @endif
                    </div>
                    <div>Authorized Signatory</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
