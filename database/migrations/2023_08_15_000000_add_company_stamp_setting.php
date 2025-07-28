<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\AppSetting;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Add default company stamp path setting
        if (!AppSetting::where('key', 'company_stamp_path')->exists()) {
            AppSetting::create([
                'key' => 'company_stamp_path',
                'value' => ''
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Remove company stamp path setting
        AppSetting::where('key', 'company_stamp_path')->delete();
    }
};
