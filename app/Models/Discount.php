<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'discount_percentage',
        'start_date',
        'end_date',
        'applicable_to',
        'applicable_ids',
    ];
    
    protected $casts = [
        'applicable_ids' => 'array', // Cast applicable_ids sebagai array
    ];

    /**
     * Relasi: Diskon bisa berlaku untuk produk tertentu.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function products()
    {
        return $this->belongsToMany(Product::class, 'discount_product', 'discount_id', 'product_id');
    }

    /**
     * Relasi: Diskon bisa berlaku untuk reseller tertentu.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reseller()
    {
        return $this->belongsTo(User::class, 'applicable_id')->where('applicable_to', 'Resellers');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
