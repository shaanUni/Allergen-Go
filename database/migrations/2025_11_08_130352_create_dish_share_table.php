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
        Schema::create('dish_share', function (Blueprint $table) {
            $table->id();
            //who shares the dish
            $table->unsignedBigInteger('parent_admin_id');
            $table->foreign('parent_admin_id')->references('id')->on('admins')->onDelete('cascade');
            
            //who receives the dish
            $table->unsignedBigInteger('child_admin_id');
            $table->foreign('child_admin_id')->references('id')->on('admins')->onDelete('cascade');

            //has it been accepted by the reciver yet
            $table->boolean('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dish_share');
    }
};
