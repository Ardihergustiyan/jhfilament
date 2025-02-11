<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'color',
        'image',
        'stock',
    ];
    protected $casts = [
        'image' => 'array',
    ];

    /**
     * Relasi: Varian milik satu produk.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function orderItems()
    {
        return $this->belongsToMany(OrderItem::class, 'order_item_product_variants');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'product_variant_id');
    }
}
