<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;

    protected $table = 'order_statuses';

    protected $fillable = [
        'name',
        'slug',
        'sort_order',
        'description',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

    /**
     * Define a one-to-many relationship with Order.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'status_id');
    }
}
