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
        Schema::table('smaw_certificates', function (Blueprint $table) {
            if (!Schema::hasColumn('smaw_certificates', 'stamp_path')) {
                $table->string('stamp_path')->nullable()->after('photo_path');
            }
            
            // Remove system setting fields since we're using a simpler approach
            if (Schema::hasColumn('smaw_certificates', 'mechanical_tester')) {
                $table->dropColumn('mechanical_tester');
            }
            
            if (Schema::hasColumn('smaw_certificates', 'evaluator_company')) {
                $table->dropColumn('evaluator_company');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('smaw_certificates', function (Blueprint $table) {
            if (Schema::hasColumn('smaw_certificates', 'stamp_path')) {
                $table->dropColumn('stamp_path');
            }
        });
    }
};
