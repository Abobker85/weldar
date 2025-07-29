<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welder Certificate Verification Result</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 50px;
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

        .certificate-info {
            background-color: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }

        .info-row {
            display: flex;
            margin-bottom: 15px;
            align-items: center;
        }

        .info-label {
            font-weight: bold;
            width: 180px;
            color: #495057;
        }

        .info-value {
            flex: 1;
        }

        .verification-result {
            text-align: center;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .result-valid {
            background-color: #d1e7dd;
            color: #0f5132;
            border: 1px solid #badbcc;
        }

        .result-invalid {
            background-color: #f8d7da;
            color: #842029;
            border: 1px solid #f5c2c7;
        }

        .verification-actions {
            text-align: center;
            margin-top: 30px;
        }

        .footer {
            text-align: center;
            margin-top: 40px;
            color: #6c757d;
            font-size: 0.9rem;
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
                <h2>Certificate Verification Result</h2>
            </div>

            <div class="verification-result {{ $isValid ? 'result-valid' : 'result-invalid' }}">
                @if($isValid)
                    <i class="fas fa-check-circle fa-2x me-2"></i>
                    <span class="fs-5 fw-bold">{{ $message }}</span>
                @else
                    <i class="fas fa-exclamation-triangle fa-2x me-2"></i>
                    <span class="fs-5 fw-bold">{{ $message }}</span>
                @endif
            </div>

            @if($isValid)
                <div class="certificate-info">
                    <div class="info-row">
                        <div class="info-label">Certificate Number:</div>
                        <div class="info-value">{{ $certificate->certificate_no }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Welder Name:</div>
                        <div class="info-value">{{ $certificate->welder->name }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Welder ID Number:</div>
                        <div class="info-value">{{ $certificate->welder->welder_no }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Company:</div>
                        <div class="info-value">{{ $certificate->company->name }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Test Date:</div>
                        <div class="info-value">{{ $certificate->test_date->format('Y-m-d') }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Welding Process:</div>
                        <div class="info-value">SMAW</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Test Position:</div>
                        <div class="info-value">{{ $certificate->test_position }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Test Result:</div>
                        <div class="info-value">
                            <span class="badge {{ $certificate->test_result ? 'bg-success' : 'bg-danger' }}">
                                {{ $certificate->test_result ? 'PASS' : 'FAIL' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="verification-actions">
                    <a href="{{ route('smaw-certificates.certificate', $certificate->id) }}" class="btn btn-primary" target="_blank">
                        <i class="fas fa-file-pdf me-2"></i> View Complete Certificate
                    </a>
                </div>
            @else
                <div class="text-center mt-4">
                    <p>The certificate you are trying to verify either does not exist or has an invalid verification code.</p>
                    <p>Please check the information and try again, or contact us for assistance.</p>
                </div>
            @endif

            <div class="verification-actions">
                <a href="{{ route('smaw-certificates.verify-form') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-search me-2"></i> Verify Another Certificate
                </a>
            </div>

            <div class="footer">
                <p>For any questions regarding this certificate verification, please contact:</p>
                <p><strong>{{ \App\Models\AppSetting::getValue('company_email', 'info@example.com') }}</strong></p>
                <p>&copy; {{ date('Y') }} {{ \App\Models\AppSetting::getValue('company_name', 'Company Name') }}. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
