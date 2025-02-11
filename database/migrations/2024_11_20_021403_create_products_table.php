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
            $table->id(); // Primary key
            $table->unsignedBigInteger('category_id'); // Foreign key ke tabel categories
            $table->string('name'); // Nama produk
            $table->string('slug')->unique(); // Slug unik
            $table->json('image'); // Gambar produk
            $table->text('description'); // Deskripsi produk
            $table->decimal('base_price', 10, 2); // Harga dasar produk
            $table->json('external_product')->nullable(); 
            $table->timestamps(); // Kolom created_at dan updated_at

            // Relasi ke tabel categories
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
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
