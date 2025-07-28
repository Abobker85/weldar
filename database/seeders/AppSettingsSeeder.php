<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AppSetting;

class AppSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define default app settings
        $settings = [
            'company_name' => 'Your Company Name',
            'company_logo_path' => null, // This will be updated through the admin panel
            'doc_prefix' => 'WQT',
            'address' => 'Your Company Address',
            'phone' => '+1234567890',
            'email' => 'info@yourcompany.com',
            'website' => 'www.yourcompany.com',
        ];

        // Insert or update each setting
        foreach ($settings as $key => $value) {
            AppSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
