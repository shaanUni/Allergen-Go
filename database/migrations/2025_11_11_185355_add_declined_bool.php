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
        Schema::table('dish_share', function (Blueprint $table) {
            //Did the receiver decline the request to share
            $table->boolean('declined')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dish_share', function (Blueprint $table) {
            //
            $table->dropColumn('declined');
        });
    }
};
