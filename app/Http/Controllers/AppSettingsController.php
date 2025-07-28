<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AppSetting;
use Illuminate\Support\Facades\Storage;

class AppSettingsController extends Controller
{    // Show settings form
    public function edit()
    {
        // Create a settings array with all values from database or defaults
        $settings = [
            'doc_prefix' => AppSetting::getValue('doc_prefix', 'EEA'),
            'system_name' => AppSetting::getValue('system_name', 'ELITE'),
            'evaluator_company' => AppSetting::getValue('evaluator_company', 'SOGEC'),
            'mechanical_tester' => AppSetting::getValue('mechanical_tester', ''),
            'company_logo_path' => AppSetting::getValue('company_logo_path', ''),
            'company_stamp_path' => AppSetting::getValue('company_stamp_path', ''),
            'company_name' => AppSetting::getValue('company_name', 'Your Company Name'),
            'address' => AppSetting::getValue('address', 'Your Company Address'),
            'phone' => AppSetting::getValue('phone', '+1234567890'),
            'email' => AppSetting::getValue('email', 'info@yourcompany.com'),
            'website' => AppSetting::getValue('website', 'www.yourcompany.com')
        ];
        
        return view('admin.settings.edit', compact('settings'));
    }

    // Update settings
    public function update(Request $request)
    {
        $validated = $request->validate([
            'company_name' => 'required|string|max:255',
            'doc_prefix' => 'required|string|max:10',
            'company_logo' => 'nullable|image|max:2048',
            'address' => 'nullable|string',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|string|max:255',
            'company_stamp' => 'nullable|image|max:2048', // New validation rule for stamp
        ]);

        // Handle logo upload if provided
        if ($request->hasFile('company_logo')) {
            $oldLogoPath = AppSetting::getValue('company_logo_path');
            if ($oldLogoPath) {
                Storage::disk('public')->delete($oldLogoPath);
            }
            
            $logoPath = $request->file('company_logo')->store('company-logos', 'public');
            AppSetting::setValue('company_logo_path', $logoPath);
        }

        // Handle stamp upload if provided
        if ($request->hasFile('company_stamp')) {
            $oldStampPath = AppSetting::getValue('company_stamp_path');
            if ($oldStampPath) {
                Storage::disk('public')->delete($oldStampPath);
            }
            
            $stampPath = $request->file('company_stamp')->store('company-stamps', 'public');
            AppSetting::setValue('company_stamp_path', $stampPath);
        }

        // Update text settings
        AppSetting::setValue('company_name', $validated['company_name']);
        AppSetting::setValue('doc_prefix', $validated['doc_prefix']);
        AppSetting::setValue('address', $validated['address'] ?? '');
        AppSetting::setValue('phone', $validated['phone'] ?? '');
        AppSetting::setValue('email', $validated['email'] ?? '');
        AppSetting::setValue('website', $validated['website'] ?? '');

        return redirect()->route('admin.settings.edit')->with('success', 'Settings updated successfully');
    }

    // Update certificate settings
    public function updateCertSettings(Request $request)
    {
        $validated = $request->validate([
            'doc_prefix' => 'required|string|max:10',
            'system_name' => 'required|string|max:255',
        ]);
        
        // Update certificate settings
        AppSetting::setValue('doc_prefix', $validated['doc_prefix']);
        AppSetting::setValue('system_name', $validated['system_name']);
        
        return redirect()->route('admin.settings.edit')->with('success', 'Certificate settings updated successfully');
    }
}


