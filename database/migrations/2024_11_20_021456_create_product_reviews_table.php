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
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('user_id'); // Foreign key ke tabel users
            $table->unsignedBigInteger('product_id'); // Foreign key ke tabel products
            $table->integer('rating'); // Rating (1-5)
            $table->text('review'); // Ulasan
            $table->timestamps(); // Kolom created_at dan updated_at

            // Relasi ke tabel users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // Relasi ke tabel products
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

            // Kombinasi unik user_id dan product_id
            $table->unique(['user_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_reviews');
    }
};
