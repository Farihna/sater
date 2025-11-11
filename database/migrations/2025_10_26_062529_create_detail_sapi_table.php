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
        Schema::create('detail_sapi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->constrained('product')
                ->onDelete('cascade');
                
            $table->decimal('berat', 8, 2);
            $table->string('usia', 50)->nullable();
            $table->enum('gender', ['jantan', 'betina']);
            $table->string('sertifikat_kesehatan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_sapi');
    }
};
