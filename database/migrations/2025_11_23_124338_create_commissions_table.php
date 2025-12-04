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
        Schema::create('commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')
                  ->constrained('orders')
                  ->onDelete('cascade');
            $table->foreignId('user_id')
                  ->constrained('users')
                  ->onDelete('cascade');
            $table->decimal('sale_price', 10, 2);
            $table->decimal('platform_fee', 10, 2);
            $table->decimal('platform_commission', 10, 2);
            $table->decimal('vendor_payout', 10, 2);
            $table->enum('payout_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->dateTime('payout_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commissions');
    }
};
