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
        Schema::create('saw_certificates', function (Blueprint $table) {
            $table->id();
            
            // Basic Certificate Information
            $table->string('certificate_no')->unique();
            $table->unsignedBigInteger('welder_id');
            $table->unsignedBigInteger('company_id');
            $table->string('revision_no')->nullable();
            
            // Test Description
            $table->string('wps_followed');
            $table->date('test_date');
            $table->boolean('test_coupon')->default(false);
            $table->boolean('production_weld')->default(false);
            $table->string('base_metal_spec');
            $table->string('diameter')->nullable();
            $table->string('thickness');
            
            // Base Metal Information
            $table->string('base_metal_p_no_from')->default('P-Number 1');
            $table->string('base_metal_p_no_to')->default('P-Number 1');
            $table->boolean('plate_specimen')->default(false);
            $table->boolean('pipe_specimen')->default(false);
            $table->string('pipe_diameter')->nullable();
            
            // Filler Metal Information
            $table->string('filler_metal_sfa_spec')->nullable(); // e.g., 5.17
            $table->string('filler_metal_classification')->nullable(); // e.g., F7A2 EM12K
            
            // SAW Specific Testing Variables (QW-361.1 - Automatic)
            $table->string('welding_type')->default('Machine'); // Machine/Automatic
            $table->string('welding_process')->default('SAW');
            $table->boolean('filler_metal_used')->default(true);
            $table->string('laser_type')->nullable(); // For LBW
            $table->string('drive_type')->nullable(); // Continuous drive or inertia welding
            $table->string('vacuum_type')->nullable(); // For EBW
            
            // Machine Welding Variables (QW-361.2)
            $table->string('visual_control_type')->default('Direct Visual Control'); // Direct/Remote
            $table->string('arc_voltage_control')->nullable(); // For GTAW
            $table->string('joint_tracking')->default('With Automatic joint tracking');
            
            // Position Information
            $table->string('test_position')->default('1G');
            $table->text('position_range')->nullable();
            
            // Consumables and Backing
            $table->string('consumable_inserts')->nullable();
            $table->string('backing')->default('With backing');
            $table->string('backing_range')->nullable();
            $table->string('passes_per_side')->default('multiple passes per side');
            $table->string('passes_range')->nullable();
            
            // Test Results
            $table->string('visual_examination_result')->default('Accepted');
            $table->string('vt_report_no')->nullable();
            
            // Bend Test Results
            $table->boolean('transverse_face_root_bends')->default(false);
            $table->boolean('longitudinal_bends')->default(false);
            $table->boolean('side_bends')->default(false);
            $table->boolean('pipe_bend_corrosion')->default(false);
            $table->boolean('plate_bend_corrosion')->default(false);
            $table->boolean('pipe_macro_fusion')->default(false);
            $table->boolean('plate_macro_fusion')->default(false);
            
            // Alternative Testing
            $table->string('alternative_volumetric_result')->default('ACC');
            $table->string('rt_report_no')->nullable();
            $table->string('rt_doc_no')->nullable();
            $table->boolean('rt_selected')->default(false);
            $table->boolean('ut_selected')->default(false);
            
            // Additional Test Results
            $table->string('fillet_fracture_test')->nullable();
            $table->string('defects_length_percent')->nullable();
            $table->boolean('fillet_welds_plate')->default(false);
            $table->boolean('fillet_welds_pipe')->default(false);
            $table->string('macro_examination')->nullable();
            $table->string('fillet_size')->nullable();
            $table->string('concavity_convexity')->nullable();
            $table->string('other_tests')->nullable();
            
            // Personnel Information
            $table->string('film_evaluated_by')->nullable();
            $table->string('evaluated_company')->nullable();
            $table->string('mechanical_tests_by')->nullable();
            $table->string('lab_test_no')->nullable();
            $table->string('welding_supervised_by');
            $table->string('supervised_company')->nullable();
            
            // Certification Statement
            $table->text('certification_text')->nullable();
            
            // Confirmation Section (6 month validity)
            $table->date('confirm_date_1')->nullable();
            $table->string('confirm_signature_1')->nullable();
            $table->string('confirm_position_1')->nullable();
            $table->date('confirm_date_2')->nullable();
            $table->string('confirm_signature_2')->nullable();
            $table->string('confirm_position_2')->nullable();
            $table->date('confirm_date_3')->nullable();
            $table->string('confirm_signature_3')->nullable();
            $table->string('confirm_position_3')->nullable();
            
            // Organization/Approval Section
            $table->string('test_witnessed_by')->default('ELITE ENGINEERING ARABIA');
            $table->string('reviewed_approved_by')->nullable();
            $table->string('witness_name')->default('Ahmed Yousry');
            $table->string('approver_name')->nullable();
            $table->text('witness_signature')->nullable(); // Base64 signature data
            $table->text('approver_signature')->nullable(); // Base64 signature data
            $table->string('witness_stamp')->nullable();
            $table->string('approver_stamp')->nullable();
            $table->date('witness_date');
            $table->date('approver_date')->nullable();
            
            // System Fields
            $table->string('verification_code')->unique();
            $table->boolean('test_result')->default(true);
            $table->string('photo_path')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Foreign Key Constraints
            $table->foreign('welder_id')->references('id')->on('welders')->onDelete('cascade');
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            
            // Indexes
            $table->index(['certificate_no']);
            $table->index(['test_date']);
            $table->index(['welder_id']);
            $table->index(['company_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('saw_certificates');
    }
};