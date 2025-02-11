<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Voucher extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'discount_percentage',
        'start_date',
        'end_date',
        'user_id',
        'status',
        'applicable_id',
    ];

    /**
     * Relasi: Voucher bisa dimiliki oleh pengguna tertentu.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi: Voucher bisa diterapkan ke produk tertentu.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function applicableProduct()
    {
        return $this->belongsTo(Product::class, 'applicable_id');
    }
    public function order()
    {
        return $this->hasOne(Order::class);
    }
}
