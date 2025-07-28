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
        // Fields to add if they don't exist
        $columns = [
            'joint_diagram_path', 'joint_type', 'joint_description', 'joint_angle', 
            'joint_total_angle', 'root_gap', 'root_face', 'pipe_outer_diameter',
            'base_metal_p_no', 'filler_metal_form', 'inert_gas_backing', 
            'gtaw_thickness', 'smaw_thickness', 'vertical_progression'
        ];
        
        Schema::table('qualification_tests', function (Blueprint $table) use ($columns) {
            foreach ($columns as $column) {
                if (!Schema::hasColumn('qualification_tests', $column)) {
                    $table->string($column)->nullable();
                }
            }
            // test_date already added in previous migrations
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Columns to drop if they exist
        $columns = [
            'joint_diagram_path', 'joint_type', 'joint_description', 'joint_angle', 
            'joint_total_angle', 'root_gap', 'root_face', 'pipe_outer_diameter',
            'base_metal_p_no', 'filler_metal_form', 'inert_gas_backing', 
            'gtaw_thickness', 'smaw_thickness', 'vertical_progression'
        ];
        
        Schema::table('qualification_tests', function (Blueprint $table) use ($columns) {
            $existingColumns = [];
            foreach ($columns as $column) {
                if (Schema::hasColumn('qualification_tests', $column)) {
                    $existingColumns[] = $column;
                }
            }
            
            if (!empty($existingColumns)) {
                $table->dropColumn($existingColumns);
            }
        });
    }
};
