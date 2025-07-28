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
        Schema::table('smaw_certificates', function (Blueprint $table) {
            // Additional welding variables
            $table->string('fuel_gas')->nullable();
            $table->string('fuel_gas_range')->nullable();
            $table->string('backing_gas')->nullable();
            $table->string('backing_gas_range')->nullable();
            $table->string('transfer_mode')->nullable();
            $table->string('transfer_mode_range')->nullable();
            $table->string('gtaw_current')->nullable();
            $table->string('gtaw_current_range')->nullable();
            $table->string('equipment_type')->nullable();
            $table->string('equipment_type_range')->nullable();
            $table->string('technique')->nullable();
            $table->string('technique_range')->nullable();
            $table->string('oscillation')->nullable();
            $table->string('oscillation_range')->nullable();
            $table->string('operation_mode')->nullable();
            $table->string('operation_mode_range')->nullable();
            
            // Test result fields
            $table->string('fillet_fracture_test')->nullable();
            $table->string('defects_length')->nullable();
            $table->boolean('rt')->default(false);
            $table->boolean('ut')->default(false);
            $table->boolean('fillet_welds_plate')->default(false);
            $table->boolean('fillet_welds_pipe')->default(false);
            $table->boolean('pipe_macro_fusion')->default(false);
            $table->boolean('plate_macro_fusion')->default(false);
            $table->string('macro_exam')->nullable();
            $table->string('fillet_size')->nullable();
            
            // Personnel information
            $table->string('evaluated_by')->nullable();
            $table->string('evaluated_company')->nullable();
            $table->string('mechanical_tests_by')->nullable();
            $table->string('lab_test_no')->nullable();
            $table->string('supervised_by')->nullable();
            $table->string('supervised_company')->nullable();
            
            // Confirmation fields
            $table->date('confirm_date1')->nullable();
            $table->date('confirm_date2')->nullable();
            $table->date('confirm_date3')->nullable();
            $table->string('confirm_title1')->nullable();
            $table->string('confirm_title2')->nullable();
            $table->string('confirm_title3')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('smaw_certificates', function (Blueprint $table) {
            // Additional welding variables
            $table->dropColumn('fuel_gas');
            $table->dropColumn('fuel_gas_range');
            $table->dropColumn('backing_gas');
            $table->dropColumn('backing_gas_range');
            $table->dropColumn('transfer_mode');
            $table->dropColumn('transfer_mode_range');
            $table->dropColumn('gtaw_current');
            $table->dropColumn('gtaw_current_range');
            $table->dropColumn('equipment_type');
            $table->dropColumn('equipment_type_range');
            $table->dropColumn('technique');
            $table->dropColumn('technique_range');
            $table->dropColumn('oscillation');
            $table->dropColumn('oscillation_range');
            $table->dropColumn('operation_mode');
            $table->dropColumn('operation_mode_range');
            
            // Test result fields
            $table->dropColumn('fillet_fracture_test');
            $table->dropColumn('defects_length');
            $table->dropColumn('rt');
            $table->dropColumn('ut');
            $table->dropColumn('fillet_welds_plate');
            $table->dropColumn('fillet_welds_pipe');
            $table->dropColumn('pipe_macro_fusion');
            $table->dropColumn('plate_macro_fusion');
            $table->dropColumn('macro_exam');
            $table->dropColumn('fillet_size');
            
            // Personnel information
            $table->dropColumn('evaluated_by');
            $table->dropColumn('evaluated_company');
            $table->dropColumn('mechanical_tests_by');
            $table->dropColumn('lab_test_no');
            $table->dropColumn('supervised_by');
            $table->dropColumn('supervised_company');
            
            // Confirmation fields
            $table->dropColumn('confirm_date1');
            $table->dropColumn('confirm_date2');
            $table->dropColumn('confirm_date3');
            $table->dropColumn('confirm_title1');
            $table->dropColumn('confirm_title2');
            $table->dropColumn('confirm_title3');
        });
    }
};
