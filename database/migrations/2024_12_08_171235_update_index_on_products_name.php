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
        Schema::table('products', function (Blueprint $table) {
            $table->dropFullText('name'); // Menghapus Full-Text Index jika ada
            $table->index('name'); // Menambahkan Index biasa
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['name']); // Menghapus Index biasa
            $table->fullText('name'); // Mengembalikan Full-Text Index
        });
    }
};
