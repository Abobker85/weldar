<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRtReportSerialToWeldersTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('welders', function (Blueprint $table) {
            $table->string('rt_report_serial')->after('rt_report');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('welders', function (Blueprint $table) {
            $table->dropColumn('rt_report_serial');
        });
    }
}
