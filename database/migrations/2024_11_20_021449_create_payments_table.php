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
        Schema::create('payments', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('order_id'); // Foreign key ke tabel orders
            $table->string('payment_method'); // Metode pembayaran (Midtrans, COD)
            $table->string('payment_status'); // Status pembayaran (Pending, Paid, Failed)
            $table->string('transaction_id')->nullable(); // ID transaksi (opsional)
            $table->timestamps(); // Kolom created_at dan updated_at

            // Relasi ke tabel orders
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
