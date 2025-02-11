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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id(); // Primary key, auto-increment
            $table->string('name'); // Nama diskon
            $table->string('slug')->unique(); // Slug unik untuk identifikasi
            $table->float('discount_percentage', 5, 2); // Persentase diskon
            $table->dateTime('start_date'); // Tanggal mulai diskon
            $table->dateTime('end_date'); // Tanggal akhir diskon
            $table->string('applicable_to'); // Berlaku untuk: All, Specific Products, Resellers
            $table->unsignedBigInteger('applicable_id')->nullable(); // ID terkait (produk/reseller tertentu)
            $table->timestamps(); // Kolom created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
