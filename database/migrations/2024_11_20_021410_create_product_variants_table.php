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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id(); // Primary key, auto-increment
            $table->unsignedBigInteger('product_id'); // Foreign key ke tabel products
            $table->string('color'); // Warna varian
            $table->json('image')->nullable(); // Gambar khusus untuk varian warna ini
            $table->integer('stock')->default(0); // Stok varian
            $table->timestamps(); // Kolom created_at dan updated_at

            // Relasi ke tabel products
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_variants');
    }
};
