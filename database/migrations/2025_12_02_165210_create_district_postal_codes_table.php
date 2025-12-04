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
        Schema::create('district_postal_codes', function (Blueprint $table) {
            $table->id();
            $table->string('district_code', 15)->index(); 
            $table->foreignId('postal_code_id')->constrained('postal_codes')->onDelete('cascade');
            $table->unique(['district_code', 'postal_code_id']); 
            $table->foreign('district_code')
                    ->references('district_code')
                    ->on('districts')
                    ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('district_postal_codes');
    }
};
