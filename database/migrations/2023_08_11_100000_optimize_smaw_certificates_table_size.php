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
            // Convert some larger text fields to TEXT type to reduce row size
            // These fields likely don't need indexing and can be stored as TEXT
            $table->text('position_range_manual')->nullable()->change();
            $table->text('p_number_range')->nullable()->change();
            $table->text('diameter_range')->nullable()->change();
            $table->text('f_number_range')->nullable()->change();
            $table->text('backing_range')->nullable()->change();
            
            // Reduce the size of other VARCHAR fields that don't need 255 chars
            $table->string('certificate_no', 100)->change();
            $table->string('vertical_progression', 50)->change();
            $table->string('vertical_progression_range', 50)->change();
            $table->string('vt_report_no', 100)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Since we can't know the original sizes, this is a best-effort rollback
        Schema::table('smaw_certificates', function (Blueprint $table) {
            $table->string('position_range_manual')->nullable()->change();
            $table->string('p_number_range')->change();
            $table->string('diameter_range')->change();
            $table->string('f_number_range')->change();
            $table->string('backing_range')->change();
            
            $table->string('certificate_no')->change();
            $table->string('vertical_progression')->change();
            $table->string('vertical_progression_range')->change();
            $table->string('vt_report_no')->nullable()->change();
        });
    }
};
