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
        Schema::table('saw_certificates', function (Blueprint $table) {
            // Add missing validation fields if they don't exist
            if (!Schema::hasColumn('saw_certificates', 'welding_type')) {
                $table->string('welding_type')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'welding_process')) {
                $table->string('welding_process')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'visual_control_type')) {
                $table->string('visual_control_type')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'joint_tracking')) {
                $table->string('joint_tracking')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'consumable_inserts')) {
                $table->string('consumable_inserts')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'passes_per_side')) {
                $table->string('passes_per_side')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'film_evaluated_by')) {
                $table->string('film_evaluated_by')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'witness_name')) {
                $table->string('witness_name')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'witness_date')) {
                $table->date('witness_date')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'dia_thickness')) {
                $table->string('dia_thickness')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'welding_supervised_by')) {
                $table->string('welding_supervised_by')->nullable();
            }

            // Add boolean test fields
            if (!Schema::hasColumn('saw_certificates', 'rt_selected')) {
                $table->boolean('rt_selected')->default(false);
            }
            if (!Schema::hasColumn('saw_certificates', 'ut_selected')) {
                $table->boolean('ut_selected')->default(false);
            }
            if (!Schema::hasColumn('saw_certificates', 'transverse_face_root_bends')) {
                $table->boolean('transverse_face_root_bends')->default(false);
            }
            if (!Schema::hasColumn('saw_certificates', 'longitudinal_bends')) {
                $table->boolean('longitudinal_bends')->default(false);
            }
            if (!Schema::hasColumn('saw_certificates', 'side_bends')) {
                $table->boolean('side_bends')->default(false);
            }
            if (!Schema::hasColumn('saw_certificates', 'pipe_bend_corrosion')) {
                $table->boolean('pipe_bend_corrosion')->default(false);
            }
            if (!Schema::hasColumn('saw_certificates', 'plate_bend_corrosion')) {
                $table->boolean('plate_bend_corrosion')->default(false);
            }
            if (!Schema::hasColumn('saw_certificates', 'pipe_macro_fusion')) {
                $table->boolean('pipe_macro_fusion')->default(false);
            }
            if (!Schema::hasColumn('saw_certificates', 'plate_macro_fusion')) {
                $table->boolean('plate_macro_fusion')->default(false);
            }
            if (!Schema::hasColumn('saw_certificates', 'fillet_welds_plate')) {
                $table->boolean('fillet_welds_plate')->default(false);
            }
            if (!Schema::hasColumn('saw_certificates', 'fillet_welds_pipe')) {
                $table->boolean('fillet_welds_pipe')->default(false);
            }

            // Add range fields
            if (!Schema::hasColumn('saw_certificates', 'visual_control_range')) {
                $table->text('visual_control_range')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'joint_tracking_range')) {
                $table->text('joint_tracking_range')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'passes_range')) {
                $table->text('passes_range')->nullable();
            }

            // Add test result fields
            if (!Schema::hasColumn('saw_certificates', 'alternative_volumetric_result')) {
                $table->string('alternative_volumetric_result')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'test_type_2')) {
                $table->string('test_type_2')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'test_result_2')) {
                $table->string('test_result_2')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'additional_type_1')) {
                $table->string('additional_type_1')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'additional_result_1')) {
                $table->string('additional_result_1')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'additional_type_2')) {
                $table->string('additional_type_2')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'additional_result_2')) {
                $table->string('additional_result_2')->nullable();
            }

            // Add additional test fields
            if (!Schema::hasColumn('saw_certificates', 'fillet_fracture_test')) {
                $table->string('fillet_fracture_test')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'defects_length_percent')) {
                $table->string('defects_length_percent')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'macro_examination')) {
                $table->string('macro_examination')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'fillet_size')) {
                $table->string('fillet_size')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'other_tests')) {
                $table->string('other_tests')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'concavity_convexity')) {
                $table->string('concavity_convexity')->nullable();
            }

            // Add confirmation fields
            if (!Schema::hasColumn('saw_certificates', 'confirm_date_1')) {
                $table->date('confirm_date_1')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'confirm_position_1')) {
                $table->string('confirm_position_1')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'confirm_date_2')) {
                $table->date('confirm_date_2')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'confirm_position_2')) {
                $table->string('confirm_position_2')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'confirm_date_3')) {
                $table->date('confirm_date_3')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'confirm_position_3')) {
                $table->string('confirm_position_3')->nullable();
            }

            // Add signature fields if missing
            if (!Schema::hasColumn('saw_certificates', 'witness_signature')) {
                $table->longText('witness_signature')->nullable();
            }

            // Add automatic welding variables if missing
            if (!Schema::hasColumn('saw_certificates', 'automatic_welding_type')) {
                $table->string('automatic_welding_type')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'automatic_welding_type_range')) {
                $table->string('automatic_welding_type_range')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'automatic_welding_process')) {
                $table->string('automatic_welding_process')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'automatic_welding_process_range')) {
                $table->string('automatic_welding_process_range')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'filler_metal_used_auto')) {
                $table->string('filler_metal_used_auto')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'filler_metal_used_auto_range')) {
                $table->string('filler_metal_used_auto_range')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'laser_type')) {
                $table->string('laser_type')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'laser_type_range')) {
                $table->string('laser_type_range')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'drive_type')) {
                $table->string('drive_type')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'drive_type_range')) {
                $table->string('drive_type_range')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'vacuum_type')) {
                $table->string('vacuum_type')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'vacuum_type_range')) {
                $table->string('vacuum_type_range')->nullable();
            }

            // Add machine welding variables if missing  
            if (!Schema::hasColumn('saw_certificates', 'arc_voltage_control')) {
                $table->string('arc_voltage_control')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'arc_voltage_control_range')) {
                $table->string('arc_voltage_control_range')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'position_actual')) {
                $table->string('position_actual')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'consumable_inserts_range')) {
                $table->string('consumable_inserts_range')->nullable();
            }

            // Add test witnessed by if missing
            if (!Schema::hasColumn('saw_certificates', 'test_witnessed_by')) {
                $table->string('test_witnessed_by')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('saw_table_edit_migrations');
    }
};
