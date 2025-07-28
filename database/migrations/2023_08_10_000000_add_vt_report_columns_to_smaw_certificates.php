<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('smaw_certificates', function (Blueprint $table) {
            if (!Schema::hasColumn('smaw_certificates', 'vt_report_no')) {
                $table->string('vt_report_no')->nullable()->after('vertical_progression_range');
            }
            
            if (!Schema::hasColumn('smaw_certificates', 'visual_examination_result')) {
                $table->string('visual_examination_result')->default('ACC')->after('vt_report_no');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('smaw_certificates', function (Blueprint $table) {
            if (Schema::hasColumn('smaw_certificates', 'vt_report_no')) {
                $table->dropColumn('vt_report_no');
            }
            
            if (Schema::hasColumn('smaw_certificates', 'visual_examination_result')) {
                $table->dropColumn('visual_examination_result');
            }
        });
    }
};
