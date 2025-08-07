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
        Schema::table('fcaw_certificates', function (Blueprint $table) {
            $table->string('deposit_thickness')->nullable()->after('operation_mode_range');
            $table->string('deposit_thickness_range')->nullable()->after('deposit_thickness');
            $table->string('fcaw_thickness')->nullable()->after('deposit_thickness_range');
            $table->string('fcaw_thickness_range')->nullable()->after('fcaw_thickness');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fcaw_certificates', function (Blueprint $table) {
            $table->dropColumn('deposit_thickness');
            $table->dropColumn('deposit_thickness_range');
            $table->dropColumn('fcaw_thickness');
            $table->dropColumn('fcaw_thickness_range');
        });
    }
};
