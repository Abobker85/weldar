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
            // Add text field for custom certification statement
            $table->text('certification_text')->nullable()->after('supervised_company');
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
            if (Schema::hasColumn('smaw_certificates', 'certification_text')) {
                $table->dropColumn('certification_text');
            }
        });
    }
};
