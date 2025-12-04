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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['shipping', 'business', 'other'])->default('shipping');
            $table->boolean('is_default')->default(false);
            $table->string('label')->nullable();
            $table->string('recipient_name');
            $table->string('phone_number', 15)->nullable();
            $table->string('address_line');
            $table->integer('province_id')->nullable();
            $table->bigInteger('city_id')->nullable();
            $table->bigInteger('district_id')->nullable();
            $table->bigInteger('sub_district_id')->nullable();
            $table->string('province');
            $table->string('city');
            $table->string('district');
            $table->string('sub_district')->nullable();
            $table->string('postal_code', 10);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
