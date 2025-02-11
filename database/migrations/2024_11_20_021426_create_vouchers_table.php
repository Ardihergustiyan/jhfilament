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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('code')->unique(); // Kode unik voucher
            $table->float('discount_percentage', 5, 2); // Persentase diskon
            $table->dateTime('start_date'); // Tanggal mulai voucher
            $table->dateTime('end_date'); // Tanggal berakhir voucher
            $table->unsignedBigInteger('user_id')->nullable(); // ID pengguna terkait (null jika untuk umum)
            $table->string('status')->default('Unused'); // Status voucher: Used/Unused
            $table->unsignedBigInteger('applicable_id')->nullable(); // ID produk/reseller terkait
            $table->timestamps(); // Kolom created_at dan updated_at

            // Relasi ke tabel users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
