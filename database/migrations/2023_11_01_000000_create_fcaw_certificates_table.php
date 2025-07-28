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
        Schema::create('fcaw_certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('welder_id')->constrained()->onDelete('cascade');
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('certificate_no')->unique()->nullable();
            $table->integer('revision_no')->default(0);
            $table->string('wps_followed');
            $table->date('test_date');
            $table->string('base_metal_spec');
            $table->string('diameter')->nullable();
            $table->string('thickness');
            $table->boolean('test_coupon')->default(true);
            $table->boolean('production_weld')->default(false);
            $table->boolean('plate_specimen')->default(false);
            $table->boolean('pipe_specimen')->default(true);
            $table->string('pipe_diameter_type')->nullable();
            $table->string('pipe_diameter_manual')->nullable();
            $table->string('diameter_range')->nullable();
            $table->string('base_metal_p_no');
            $table->string('base_metal_p_no_manual')->nullable();
            $table->string('p_number_range')->nullable();
            $table->string('p_number_range_manual')->nullable();
            $table->string('process')->default('FCAW');
            $table->string('filler_product_form');
            $table->string('filler_product_form_manual')->nullable();
            $table->string('filler_product_form_range')->nullable();
            $table->string('deposit_thickness')->nullable();
            $table->string('deposit_thickness_range')->nullable();
            $table->string('test_position')->default('6G');
            $table->string('position_range')->nullable();
            $table->string('position_range_manual')->nullable();
            $table->string('backing');
            $table->string('backing_manual')->nullable();
            $table->string('backing_range')->nullable();
            $table->string('filler_spec');
            $table->string('filler_spec_manual')->nullable();
            $table->string('filler_spec_range')->nullable();
            $table->string('filler_class');
            $table->string('filler_class_manual')->nullable();
            $table->string('filler_class_range')->nullable();
            $table->string('filler_f_no');
            $table->string('filler_f_no_manual')->nullable();
            $table->string('f_number_range')->nullable();
            $table->string('vertical_progression');
            $table->string('vertical_progression_range')->nullable();
            $table->string('inspector_name');
            $table->date('inspector_date');
            $table->text('certification_text')->nullable();
            $table->boolean('test_result')->default(true);
            $table->string('photo_path')->nullable();
            $table->string('signature_data')->nullable();
            $table->string('inspector_signature_data')->nullable();
            $table->boolean('rt')->default(false);
            $table->boolean('ut')->default(false);
            $table->string('vt_report_no')->nullable();
            $table->string('rt_report_no')->nullable();
            $table->string('rt_doc_no')->nullable();
            $table->string('visual_examination_result')->nullable();
            $table->string('evaluated_by')->nullable();
            $table->string('evaluated_company')->nullable();
            $table->string('mechanical_tests_by')->nullable();
            $table->string('lab_test_no')->nullable();
            $table->string('supervised_by')->nullable();
            $table->string('supervised_company')->nullable();
            $table->string('verification_code')->nullable();
            $table->string('fuel_gas')->nullable();
            $table->string('fuel_gas_range')->nullable();
            $table->string('backing_gas')->nullable();
            $table->string('backing_gas_range')->nullable();
            $table->string('polarity')->nullable();
            $table->string('polarity_range')->nullable();
            $table->string('transfer_mode')->nullable();
            $table->string('transfer_mode_range')->nullable();
            $table->string('current')->nullable();
            $table->string('current_range')->nullable();
            $table->string('equipment_type')->nullable();
            $table->string('equipment_type_range')->nullable();
            $table->string('technique')->nullable();
            $table->string('technique_range')->nullable();
            $table->string('oscillation')->nullable();
            $table->string('oscillation_value')->nullable();
            $table->string('oscillation_range')->nullable();
            $table->string('operation_mode')->nullable();
            $table->string('operation_mode_range')->nullable();
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
        Schema::dropIfExists('fcaw_certificates');
    }
};
