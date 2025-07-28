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
        Schema::create('smaw_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('welder_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('certificate_no')->unique()->nullable();
            $table->string('wps_followed');
            $table->date('test_date');
            $table->string('base_metal_spec');
            $table->boolean('smaw_yes')->default(true);
            $table->boolean('plate_specimen')->default(false);
            $table->boolean('pipe_specimen')->default(true);
            $table->string('pipe_diameter_type');
            $table->string('pipe_diameter_manual')->nullable();
            $table->string('diameter_range');
            $table->string('diameter_range_manual')->nullable();
            $table->string('base_metal_p_no');
            $table->string('base_metal_p_no_manual')->nullable();
            $table->string('p_number_range');
            $table->string('p_number_range_manual')->nullable();
            $table->string('smaw_thickness');
            $table->string('test_position')->default('6G');
            $table->string('position_range')->nullable();
            $table->string('position_range_manual')->nullable();
            $table->string('backing');
            $table->string('backing_manual')->nullable();
            $table->string('backing_range');
            $table->string('backing_range_manual')->nullable();
            $table->string('filler_spec');
            $table->string('filler_spec_manual')->nullable();
            $table->string('filler_class');
            $table->string('filler_class_manual')->nullable();
            $table->string('filler_f_no');
            $table->string('filler_f_no_manual')->nullable();
            $table->string('f_number_range');
            $table->string('f_number_range_manual')->nullable();
            $table->string('vertical_progression');
            $table->string('vertical_progression_range');
            $table->string('inspector_name')->nullable();
            $table->date('inspector_date')->nullable();
            $table->boolean('test_result')->default(true);
            $table->string('photo_path')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smaw_certificates');
    }
};
