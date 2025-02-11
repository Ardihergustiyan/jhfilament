<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Menentukan nama tabel jika tidak mengikuti konvensi penamaan
    protected $table = 'categories';

    // Kolom yang boleh diisi (mass assignable)
    protected $fillable = [
        'general_category_id',
        'name', 
        'slug', 
        'is_active'
    ];

    /**
     * Relasi kategori dengan kategori umum (GeneralCategory).
     * Setiap kategori memiliki satu general category (Men/Women).
     */
    public function generalCategory()
    {
        return $this->belongsTo(GeneralCategory::class, 'general_category_id');
    }

     /**
     * Relasi kategori dengan produk.
     * Setiap kategori memiliki banyak produk.
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
