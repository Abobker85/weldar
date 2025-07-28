<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add test_date column if it doesn't exist
        if (!Schema::hasColumn('qualification_tests', 'test_date')) {
            Schema::table('qualification_tests', function (Blueprint $table) {
                $table->date('test_date')->nullable()->after('qualified_ec');
            });
            
            // Copy data from vt_date to test_date
            DB::statement('UPDATE qualification_tests SET test_date = vt_date');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('qualification_tests', 'test_date')) {
            Schema::table('qualification_tests', function (Blueprint $table) {
                $table->dropColumn('test_date');
            });
        }
    }
};
