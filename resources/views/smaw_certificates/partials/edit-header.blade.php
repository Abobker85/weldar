<!-- Header with logos for edit form -->
<div class="header-row" style="margin-bottom: 0; height: 80px;">
    <div class="logo-left">
        @php
            $companyLogoPath = \App\Models\AppSetting::getValue('company_logo_path');
            $logoExists = !empty($companyLogoPath) && file_exists(public_path('storage/' . $companyLogoPath));
        @endphp
        
        @if($logoExists)
            <img src="{{ asset('storage/' . $companyLogoPath) }}" alt="Company Logo" style="max-width: 90px; max-height: 50px;">
        @else
            <div style="font-size: 12px; font-weight: bold; text-align: center; color: #0066cc;">
                <div style="background: #0066cc; color: white; padding: 2px 8px; border-radius: 15px; margin-bottom: 3px;">
                    ELITE</div>
                <div style="font-size: 8px; color: #666;">ENGINEERING ARABIA</div>
            </div>
        @endif
    </div>
    <div class="header-center">
        <h1 style="font-size: 16px; margin-bottom: 2px;">{{ \App\Models\AppSetting::getValue('company_name', 'Elite Engineering Arabia') }}</h1>
        <div class="contact-info" style="font-size: 8px; margin: 2px 0;">
            e-mail: {{ \App\Models\AppSetting::getValue('email', 'ahmed.yousry@eliteengineeringarabia.com') }} &nbsp;&nbsp;&nbsp;&nbsp; {{ \App\Models\AppSetting::getValue('website', 'www.') }}
        </div>
        <h2 style="font-size: 14px; font-weight: bold; margin-top: 5px;">WELDER PERFORMANCE QUALIFICATIONS
        </h2>
    </div>
    <div class="logo-right">
        <div id="company-code-display" style="font-size: 14px; font-weight: bold; text-align: center;">
            @if($certificate->welder && $certificate->welder->company)
                <span style="color: #dc3545; font-size: 16px;">{{ $certificate->welder->company->code ?: 'AIC' }}</span>
                <span style="color: #999; font-size: 12px;">{{ $certificate->welder->company->name ?: 'steel' }}</span>
            @else
                <span style="color: #dc3545; font-size: 16px;">AIC</span>
                <span style="color: #999; font-size: 12px;">steel</span>
            @endif
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update company code display when welder/company changes
    const welderIdElement = document.getElementById('welder_id');
    if (welderIdElement) {
        welderIdElement.addEventListener('change', function() {
            if (this.value) {
                // Fetch welder data to update company code
                fetch(`/api/welders/${this.value}`)
                    .then(response => response.json())
                    .then(welder => {
                        const companyCodeDisplay = document.getElementById('company-code-display');
                        if (companyCodeDisplay && welder.company) {
                            companyCodeDisplay.innerHTML = `<span style="color: #dc3545; font-size: 16px;">${welder.company.code || 'AIC'}</span>`;
                        }
                    })
                    .catch(error => {
                        console.error('Error fetching welder data for header:', error);
                    });
            }
        });
    }
});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const welderSelect = document.getElementById('welder_id');
    if (welderSelect) {
        welderSelect.addEventListener('change', function() {
            const welderId = this.value;
            if (welderId) {
                // Fetch welder details including company
                fetch(`/welders/${welderId}/details`)
                    .then(response => response.json())
                    .then(data => {
                        const companyCodeDisplay = document.getElementById('company-code-display');
                        if (companyCodeDisplay && data.company) {
                            companyCodeDisplay.innerHTML = `
                                <span style="color: #dc3545; font-size: 16px;">${data.company.code || 'AIC'}</span>
                                <span style="color: #999; font-size: 12px;">${data.company.name || 'steel'}</span>
                            `;
                        }
                    })
                    .catch(error => console.error('Error fetching welder details:', error));
            }
        });
    }
});
</script>
