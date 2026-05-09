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
            $table->decimal('offer_price', 12, 2)->nullable()->after('account_id')->comment('Price offered by admin in chat');
            $table->boolean('is_price_offer')->default(false)->after('offer_price')->comment('Flag if message contains price offer');
            $table->timestamp('offer_valid_until')->nullable()->after('is_price_offer')->comment('When offer expires');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['offer_price', 'is_price_offer', 'offer_valid_until']);
        });
    }
};
