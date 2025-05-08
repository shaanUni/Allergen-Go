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
        Schema::table('searches', function (Blueprint $table) {
            // There are instances where a user with certain allergies will not have any dishes availible to them.
            // The failure bool will be set to true when this happens, so the controller can detect it, and the restaurant can 
            // investigate on the stats page.
            $table->boolean('failure')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('searches', function (Blueprint $table) {
            //
            $table->dropColumn('failure');
        });
    }
};
