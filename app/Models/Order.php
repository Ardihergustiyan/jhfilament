<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'status_id',
        'total_price',
        'discount_amount',
        'shipping_method',
        'notes',
        'discount_id',
        'voucher_id',
    ];

    /**
     * Relasi: Pesanan milik seorang pengguna.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Pesanan memiliki status tertentu.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo(OrderStatus::class, 'status_id');
    }

    /**
     * Relasi: Pesanan dapat memiliki diskon (opsional).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function discount()
    {
        return $this->belongsTo(Discount::class);
    }
    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    /**
     * Relasi: Pesanan dapat memiliki banyak item.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function Items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'product_variant_id');
    }
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
    public function orderItems() {
        return $this->hasMany(OrderItem::class);
    }
    
}
