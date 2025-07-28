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
        // Add system logo setting
        AppSetting::setValue('system_name', 'ELITE Engineering Consultants');
        AppSetting::setValue('system_logo', 'images/default-logo.png');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // We'll leave system_name in place and just remove system_logo
        AppSetting::where('key', 'system_logo')->delete();
    }
};
