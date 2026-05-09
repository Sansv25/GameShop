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
        Schema::table('messages', function (Blueprint $table) {
            $table->foreignId('account_id')->nullable()->after('receiver_id')->constrained('game_accounts')->onDelete('set null');
            $table->string('account_title')->nullable()->after('account_id');
            $table->boolean('is_auto_message')->default(false)->after('is_read');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropForeign(['account_id']);
            $table->dropColumn(['account_id', 'account_title', 'is_auto_message']);
        });
    }
};
