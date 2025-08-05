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
            // Add missing columns based on the error message
            if (!Schema::hasColumn('saw_certificates', 'evaluated_by')) {
                $table->string('evaluated_by')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'evaluated_company')) {
                $table->string('evaluated_company')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'mechanical_tests_by')) {
                $table->string('mechanical_tests_by')->nullable();
            }
            if (!Schema::hasColumn('saw_certificates', 'welding_supervised_by')) {
                $table->string('welding_supervised_by')->nullable();
            }
            
            // Add thickness field that's required in the database
            if (!Schema::hasColumn('saw_certificates', 'thickness')) {
                $table->string('thickness')->nullable();
            }
            
            // Make sure dia_thickness is correctly added/updated
            if (!Schema::hasColumn('saw_certificates', 'dia_thickness')) {
                $table->string('dia_thickness')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('saw_certificates', function (Blueprint $table) {
            $columns = [
                'evaluated_by',
                'evaluated_company',
                'mechanical_tests_by',
                'welding_supervised_by',
                'thickness',
                'dia_thickness'
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('saw_certificates', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
