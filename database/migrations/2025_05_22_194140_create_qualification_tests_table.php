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
        Schema::create('qualification_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('welder_no')->constrained()->onDelete('cascade');
            $table->string('sr_no')->nullable();
            $table->string('work_order_no')->nullable();
            $table->string('location')->nullable();
            $table->string('passport_id_no')->nullable();
            $table->string('welder_no')->nullable();
            $table->string('wps_no');
            $table->string('welding_process');
            $table->string('test_coupon')->nullable();
            $table->string('dia_inch')->nullable();
            $table->string('qualified_dia_inch')->nullable();
            $table->string('coupon_material')->nullable();
            $table->string('qualified_material')->nullable();
            $table->decimal('coupon_thickness_mm', 8, 2)->nullable();
            $table->string('deposit_thickness')->nullable();
            $table->string('qualified_thickness_range')->nullable();
            $table->string('welding_positions');
            $table->string('qualified_position');
            $table->string('filler_metal_f_no')->nullable();
            $table->string('aws_spec_no')->nullable();
            $table->string('filler_metal_classif')->nullable();
            $table->string('backing')->nullable();
            $table->string('qualified_backing')->nullable();
            $table->string('electric_char')->nullable();
            $table->string('qualified_ec')->nullable();
            $table->date('vt_date')->nullable();
            $table->string('vt_report_no')->nullable();
            $table->string('vt_result')->nullable();
            $table->date('rt_date')->nullable();
            $table->string('rt_report_no')->nullable();
            $table->string('rt_result')->nullable();
            $table->string('cert_no')->nullable();
            $table->string('qualification_code')->nullable();
            $table->string('remarks')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qualification_tests');
    }
};
