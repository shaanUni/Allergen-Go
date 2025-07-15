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
        Schema::create('gdpr_logs', function (Blueprint $table) {
            $table->id();
            $table->string('session_uuid');
            $table->boolean('consent_given'); //did they give it?
            $table->string('consent_version'); // what version of the consent message did they see
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gdpr_logs');
    }
};
