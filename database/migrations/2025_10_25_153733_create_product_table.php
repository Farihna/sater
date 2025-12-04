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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('sku')->unique();
            $table->foreignId('category_id') 
                  ->constrained('categories') 
                  ->onDelete('restrict');
            $table->foreignId('user_id') 
                  ->constrained('users') 
                  ->onDelete('cascade');
            $table->string('nama');
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 10, 2);
            $table->string('image_url')->nullable();
            $table->integer('stok')->default(1);
            $table->enum('status', ['active', 'inactive', 'pending'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
