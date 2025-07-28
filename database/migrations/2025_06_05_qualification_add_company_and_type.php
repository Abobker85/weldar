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
        Schema::table('qualification_tests', function (Blueprint $table) {
            $table->foreignId('company_id')->after('welder_no')->nullable()->constrained()->onDelete('cascade');
            $table->string('qualification_type', 10)->after('company_id')->default('WQT');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qualification_tests', function (Blueprint $table) {
            $table->dropForeign(['company_id']);
            $table->dropColumn(['company_id', 'qualification_type']);
        });
    }
};
