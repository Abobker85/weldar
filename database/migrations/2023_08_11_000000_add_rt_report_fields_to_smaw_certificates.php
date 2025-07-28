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
            // Use smaller varchar sizes to reduce row size
            if (!Schema::hasColumn('smaw_certificates', 'rt_report_no')) {
                $table->string('rt_report_no', 100)->nullable()->after('vt_report_no');
            }
            
            if (!Schema::hasColumn('smaw_certificates', 'rt_doc_no')) {
                $table->string('rt_doc_no', 100)->nullable()->after('rt_report_no');
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
            if (Schema::hasColumn('smaw_certificates', 'rt_report_no')) {
                $table->dropColumn('rt_report_no');
            }
            
            if (Schema::hasColumn('smaw_certificates', 'rt_doc_no')) {
                $table->dropColumn('rt_doc_no');
            }
        });
    }
};
