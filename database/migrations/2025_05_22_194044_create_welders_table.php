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
        Schema::create('welders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('passport_id_no')->unique();
            $table->string('welder_no')->nullable()->unique();
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->string('photo')->nullable();
            $table->text('additional_info')->nullable();
            $table->string('nationality')->nullable();
            $table->string('location')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('welders');
    }
};
