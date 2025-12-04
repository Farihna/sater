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
        Schema::create('village_postal_codes', function (Blueprint $table) {
            $table->id();
            $table->string('village_code', 20)->index(); 
            $table->foreignId('postal_code_id')->constrained('postal_codes')->onDelete('cascade');
            $table->unique(['village_code', 'postal_code_id']);
            $table->foreign('village_code')
                    ->references('village_code')
                    ->on('villages')
                    ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('village_postal_codes');
    }
};
