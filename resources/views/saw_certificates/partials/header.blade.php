<!-- Header with logos - exactly like Excel template -->
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
        <h2 style="font-size: 14px; font-weight: bold; margin-top: 5px;">WELDING OPERATOR PERFORMANCE QUALIFICATIONS</h2>
    </div>
    <div class="logo-right">
        <div id="company-code-display" style="font-size: 14px; font-weight: bold; text-align: center;">
            <span style="color: #dc3545; font-size: 16px;">AIC</span><span style="color: #999; font-size: 12px;">steel</span>
        </div>
    </div>
</div>