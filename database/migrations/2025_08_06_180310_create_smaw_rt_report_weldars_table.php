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
        Schema::create('smaw_rt_report_weldars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('welder_id')->constrained('welders')->onDelete('cascade');
            $table->foreignId('certificate_id')->constrained('smaw_certificates')->onDelete('cascade');
            $table->string('attachment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smaw_rt_report_weldars');
    }
};
