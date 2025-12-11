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
        Schema::create('dishes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id')->index();
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->string('dish_name')->length(255)->index();
            $table->text('description')->length(255)->nullable()->index(); 
            $table->string('allergen_string'); 
            $table->decimal('price', 8, 2);           
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dishes');
    }
};
