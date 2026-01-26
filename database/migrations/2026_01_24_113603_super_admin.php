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
        Schema::table('admins', function (Blueprint $table) {
            $table->boolean('super_admin')->default(false);

            $table->unsignedBigInteger('super_admin_id')->nullable()->index();
            $table->integer('quantity')->default(1);
        
            $table->foreign('super_admin_id')
                  ->references('id')->on('admins')
                  ->nullOnDelete(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('admins', function (Blueprint $table) {
            //
            $table->dropForeign(['super_admin_id']);
            $table->dropColumn(['super_admin_id', 'super_admin', 'quantity']);
        });
    }
};
