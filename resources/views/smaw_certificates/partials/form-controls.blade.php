<!-- Form Controls -->
<div class="form-buttons">
    <a href="{{ route('smaw-certificates.index') }}" class="btn btn-secondary me-2">
        <i class="fas fa-arrow-left"></i> Back to List
    </a>
    <button type="button" class="btn btn-secondary" onclick="resetForm()">
        Reset Form
    </button>
    <button type="button" class="btn btn-primary" onclick="validateForm()">
        Validate & Preview
    </button>
    <button type="button" class="btn btn-success" id="submitBtn" onclick="submitCertificateForm()">
        <span class="spinner-border spinner-border-sm d-none" id="submitSpinner" role="status"
            aria-hidden="true"></span>
        Save & Generate Certificate
    </button>
</div>
