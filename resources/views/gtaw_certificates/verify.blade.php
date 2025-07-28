<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GTAW Certificate Verification Results</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 50px;
            padding-bottom: 50px;
        }
        
        .verification-container {
            max-width: 800px;
            margin: 0 auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            padding: 30px;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo img {
            max-height: 80px;
        }
        
        .result-title {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .certificate-details {
            margin-top: 30px;
        }
        
        .welder-photo {
            max-width: 150px;
            max-height: 200px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }
        
        .status-valid {
            background-color: #d4edda;
            color: #155724;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .status-invalid {
            background-color: #f8d7da;
            color: #721c24;
            border-radius: 5px;
            padding: 15px;
            margin-bottom: 20px;
        }
        
        .footer {
            text-align: center;
            margin-top: 40px;
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .certificate-table th {
            width: 35%;
            background-color: #f8f9fa;
        }
        
        .qr-code {
            text-align: center;
            margin-top: 20px;
        }
        
        .qr-code img {
            max-width: 120px;
        }
        
        .verification-stamp {
            text-align: center;
            margin-top: 20px;
        }
        
        .stamp {
            border: 2px dashed #155724;
            display: inline-block;
            padding: 10px 20px;
            color: #155724;
            font-weight: bold;
            transform: rotate(-5deg);
            font-size: 1.2rem;
        }
        
        .back-button {
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="verification-container">
            <div class="logo">
                <img src="{{ asset('images/company_logo.png') }}" alt="Company Logo">
            </div>
            
            <div class="result-title">
                <h2>GTAW Certificate Verification Results</h2>
            </div>
            
            @if($isValid)
                <div class="status-valid">
                    <h4><i class="fas fa-check-circle me-2"></i> Certificate Verified Successfully</h4>
                    <p class="mb-0">The GTAW welder certificate is authentic and registered in our system.</p>
                </div>
                
                <div class="certificate-details">
                    <div class="row">
                        <div class="col-md-4 text-center mb-4">
                            @if($certificate->photo_path)
                                <img src="{{ asset('storage/' . $certificate->photo_path) }}" alt="Welder Photo" class="welder-photo">
                            @else
                                <div class="welder-photo d-flex align-items-center justify-content-center bg-light">
                                    <i class="fas fa-user fa-5x text-muted"></i>
                                </div>
                            @endif
                            <div class="verification-stamp mt-3">
                                <div class="stamp">VERIFIED</div>
                            </div>
                        </div>
                        
                        <div class="col-md-8">
                            <h4>Certificate Information</h4>
                            <table class="table table-bordered certificate-table">
                                <tr>
                                    <th>Certificate Number</th>
                                    <td>{{ $certificate->certificate_no }}</td>
                                </tr>
                                <tr>
                                    <th>Welder Name</th>
                                    <td>{{ $certificate->welder->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Welder ID</th>
                                    <td>{{ $certificate->welder->welder_no ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Company</th>
                                    <td>{{ $certificate->company->name ?? 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Test Date</th>
                                    <td>{{ $certificate->test_date ? $certificate->test_date->format('Y-m-d') : 'N/A' }}</td>
                                </tr>
                                <tr>
                                    <th>Result</th>
                                    <td>
                                        @if($certificate->test_result)
                                            <span class="badge bg-success">PASS</span>
                                        @else
                                            <span class="badge bg-danger">FAIL</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-12">
                            <h4>Qualification Details</h4>
                            <table class="table table-bordered certificate-table">
                                <tr>
                                    <th>Process</th>
                                    <td>Gas Tungsten Arc Welding (GTAW)</td>
                                </tr>
                                <tr>
                                    <th>Base Metal</th>
                                    <td>{{ $certificate->base_metal_spec }}</td>
                                </tr>
                                <tr>
                                    <th>WPS Reference</th>
                                    <td>{{ $certificate->wps_followed }}</td>
                                </tr>
                                <tr>
                                    <th>Position</th>
                                    <td>{{ $certificate->test_position }}</td>
                                </tr>
                                <tr>
                                    <th>Thickness</th>
                                    <td>{{ $certificate->gtaw_thickness }}</td>
                                </tr>
                                <tr>
                                    <th>Filler Metal</th>
                                    <td>{{ $certificate->filler_spec }} {{ $certificate->filler_class }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="qr-code">
                    <img src="data:image/png;base64,{{ base64_encode(QrCode::format('png')->size(120)->generate(route('gtaw-certificates.verify', ['id' => $certificate->id, 'code' => $certificate->verification_code]))) }}" alt="Verification QR Code">
                    <p class="text-muted mt-2 small">Scan this QR code to verify this certificate again.</p>
                </div>
                
            @else
                <div class="status-invalid">
                    <h4><i class="fas fa-times-circle me-2"></i> Verification Failed</h4>
                    <p class="mb-0">{{ $message }}</p>
                </div>
                
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i> If you believe this is an error, please contact our office with the certificate details.
                </div>
            @endif
            
            <div class="text-center back-button">
                <a href="{{ route('gtaw-certificates.show-verification-form') }}" class="btn btn-primary">
                    <i class="fas fa-search me-2"></i> Verify Another Certificate
                </a>
                
                @if($isValid)
                    <a href="{{ route('gtaw-certificates.certificate', $certificate->id) }}" class="btn btn-success ms-2" target="_blank">
                        <i class="fas fa-file-pdf me-2"></i> View Full Certificate
                    </a>
                @endif
            </div>
            
            <div class="footer">
                <p>&copy; {{ date('Y') }} {{ \App\Models\AppSetting::getValue('company_name', 'Company Name') }}. All rights reserved.</p>
                <p class="small">Verification performed on: {{ now()->format('Y-m-d H:i:s') }}</p>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
