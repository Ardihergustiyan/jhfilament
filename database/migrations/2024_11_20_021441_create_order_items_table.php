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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('order_id'); // Foreign key ke tabel orders
            $table->unsignedBigInteger('product_id'); // Foreign key ke tabel products
            $table->unsignedBigInteger('product_variant_id')->nullable(); // Foreign key ke tabel product_variants
            $table->integer('quantity'); // Jumlah produk
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->decimal('total_price', 10, 2)->nullable();
            $table->timestamps(); // Kolom created_at dan updated_at

            // Relasi ke tabel orders
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');

            // Relasi ke tabel products
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            // Relasi ke tabel product_variants
            $table->foreign('product_variant_id')->references('id')->on('product_variants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
