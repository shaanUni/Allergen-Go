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
            //
            $table->boolean('vegan')->default(false);
            $table->boolean('vegetarian')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('searches', function (Blueprint $table) {
            //
            $table->dropColumn('vegan');
            $table->dropColumn('vegetarian');
        });
    }
};
