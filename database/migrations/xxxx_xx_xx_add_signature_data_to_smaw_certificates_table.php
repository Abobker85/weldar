<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSignatureDataToSmawCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('smaw_certificates', function (Blueprint $table) {
            $table->text('signature_data')->nullable()->after('rt_report_no');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('smaw_certificates', function (Blueprint $table) {
            $table->dropColumn('signature_data');
        });
    }
}
