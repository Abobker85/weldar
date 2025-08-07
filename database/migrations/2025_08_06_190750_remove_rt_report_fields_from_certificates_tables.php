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
        $tables = ['smaw_certificates', 'gtaw_certificates', 'fcaw_certificates', 'saw_certificates'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropColumn(['rt_report_no', 'rt_doc_no']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['smaw_certificates', 'gtaw_certificates', 'fcaw_certificates', 'saw_certificates'];

        foreach ($tables as $tableName) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->string('rt_report_no')->nullable();
                $table->string('rt_doc_no')->nullable();
            });
        }
    }
};
