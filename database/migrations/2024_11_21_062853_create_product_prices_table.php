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
        Schema::create('product_prices', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id'); // Mengacu pada tabel products
            $table->unsignedBigInteger('reseller_level_id'); // Mengacu pada tabel reseller_levels
            $table->decimal('price', 10, 2); // Harga manual untuk level reseller
            $table->timestamps(); // created_at dan updated_at

            // Foreign key constraints
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('reseller_level_id')->references('id')->on('reseller_levels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_prices');
    }
};
