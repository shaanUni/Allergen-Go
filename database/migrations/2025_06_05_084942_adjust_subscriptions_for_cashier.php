<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // 1) Rename 'type' → 'name'
            if (Schema::hasColumn('subscriptions', 'type')) {
                $table->renameColumn('type', 'name');
            }

            // 2) Add 'user_type' if it doesn’t exist yet:
            if (! Schema::hasColumn('subscriptions', 'user_type')) {
                // We default everything to App\Models\Admin. Adjust if your model is in a different namespace.
                $table->string('user_type')->default('App\\Models\\Admin')->after('id');
            }
        });
    }

    public function down()
    {
        Schema::table('subscriptions', function (Blueprint $table) {
            // Drop 'user_type' if present
            if (Schema::hasColumn('subscriptions', 'user_type')) {
                $table->dropColumn('user_type');
            }
            // Rename 'name' back → 'type'
            if (Schema::hasColumn('subscriptions', 'name')) {
                $table->renameColumn('name', 'type');
            }
        });
    }
};
