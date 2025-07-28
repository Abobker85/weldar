<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('welders', function (Blueprint $table) {
            $table->string('ut_report')->nullable()->after('rt_report_serial');
            $table->string('ut_report_serial')->nullable()->after('ut_report');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('welders', function (Blueprint $table) {
            $table->dropColumn('ut_report');
            $table->dropColumn('ut_report_serial');
        });
    }
};
