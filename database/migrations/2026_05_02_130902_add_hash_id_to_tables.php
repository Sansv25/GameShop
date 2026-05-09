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
        Schema::table('users', function (Blueprint $table) {
            $table->string('hash_id', 10)->nullable()->unique()->after('id');
        });

        Schema::table('game_accounts', function (Blueprint $table) {
            $table->string('hash_id', 10)->nullable()->unique()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('hash_id');
        });

        Schema::table('game_accounts', function (Blueprint $table) {
            $table->dropColumn('hash_id');
        });
    }
};
