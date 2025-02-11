<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('discounts', function (Blueprint $table) {
            // Tambahkan kolom baru untuk menyimpan data dalam format JSON
            $table->json('applicable_ids')->nullable()->after('applicable_to');
        });

        // Pindahkan data dari applicable_id ke applicable_ids dalam format JSON
        DB::table('discounts')->get()->each(function ($discount) {
            DB::table('discounts')
                ->where('id', $discount->id)
                ->update([
                    'applicable_ids' => $discount->applicable_id ? json_encode([$discount->applicable_id]) : json_encode([])
                ]);
        });

        Schema::table('discounts', function (Blueprint $table) {
            // Hapus kolom applicable_id setelah migrasi data selesai
            $table->dropColumn('applicable_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('discounts', function (Blueprint $table) {
            // Tambahkan kembali kolom applicable_id
            $table->unsignedBigInteger('applicable_id')->nullable()->after('applicable_to');
        });

        // Kembalikan data dari applicable_ids ke applicable_id (hanya ambil ID pertama jika ada)
        DB::table('discounts')->get()->each(function ($discount) {
            $applicableIds = json_decode($discount->applicable_ids, true);
            DB::table('discounts')
                ->where('id', $discount->id)
                ->update([
                    'applicable_id' => !empty($applicableIds) ? $applicableIds[0] : null
                ]);
        });

        Schema::table('discounts', function (Blueprint $table) {
            // Hapus kolom applicable_ids setelah rollback selesai
            $table->dropColumn('applicable_ids');
        });
    }
};
