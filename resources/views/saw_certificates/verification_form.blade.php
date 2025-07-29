<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welder Certificate Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            padding-top: 50px;
        }

        .verification-container {
            max-width: 600px;
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

        .form-title {
            text-align: center;
            margin-bottom: 30px;
        }

        .verification-form {
            margin-bottom: 20px;
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

            <div class="form-title">
                <h2>Certificate Verification</h2>
                <p class="text-muted">Verify the authenticity of a welder qualification certificate</p>
            </div>

            @if(isset($error))
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i> {{ $error }}
                </div>
            @endif

            <form class="verification-form" method="POST" action="{{ route('smaw-certificates.verify-submit') }}">
                @csrf
                <div class="mb-4">
                    <label for="certificate_no" class="form-label">Certificate Number</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-certificate"></i></span>
                        <input type="text" class="form-control" id="certificate_no" name="certificate_no"
                               value="{{ old('certificate_no', $certificate_no ?? '') }}"
                               placeholder="Enter certificate number (e.g. EEA-AIC-SMAW-0001)" required>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-2"></i> Verify
                        </button>
                    </div>
                    <div class="form-text">Enter the certificate number exactly as it appears on the certificate.</div>
                </div>
            </form>

            <div class="text-center mt-4">
                <p>If you have any issues with certificate verification, please contact:</p>
                <p><strong>{{ \App\Models\AppSetting::getValue('company_email', 'info@example.com') }}</strong></p>
            </div>

            <div class="footer">
                <p>&copy; {{ date('Y') }} {{ \App\Models\AppSetting::getValue('company_name', 'Company Name') }}. All rights reserved.</p>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
