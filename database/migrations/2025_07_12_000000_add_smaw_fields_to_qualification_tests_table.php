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
        Schema::table('qualification_tests', function (Blueprint $table) {
            // SMAW specific fields
            $table->string('smaw_thickness')->nullable();
            $table->boolean('smaw_yes')->default(false);
            $table->boolean('plate_specimen')->default(false);
            $table->boolean('pipe_specimen')->default(true);
            $table->string('pipe_diameter_type')->nullable();
            $table->string('pipe_diameter_manual')->nullable();
            $table->string('diameter_range')->nullable();
            $table->string('diameter_range_manual')->nullable();
            $table->string('base_metal_spec')->nullable();
            $table->string('wps_followed')->nullable();
            $table->string('filler_spec')->nullable();
            $table->string('filler_class')->nullable();
            $table->string('filler_f_no')->nullable();
            $table->string('f_number_range')->nullable();
            $table->string('f_number_range_manual')->nullable();
            $table->string('vertical_progression')->nullable();
            $table->string('vertical_progression_range')->nullable();
            $table->string('inspector_name')->nullable();
            $table->date('inspector_date')->nullable();
            $table->string('certificate_type')->default('SMAW');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qualification_tests', function (Blueprint $table) {
            // Remove SMAW specific fields
            $table->dropColumn([
                'smaw_thickness',
                'smaw_yes',
                'plate_specimen',
                'pipe_specimen',
                'pipe_diameter_type',
                'pipe_diameter_manual',
                'diameter_range',
                'diameter_range_manual',
                'base_metal_spec',
                'wps_followed',
                'filler_spec',
                'filler_class',
                'filler_f_no',
                'f_number_range',
                'f_number_range_manual',
                'vertical_progression',
                'vertical_progression_range',
                'inspector_name',
                'inspector_date',
                'certificate_type'
            ]);
        });
    }
};
