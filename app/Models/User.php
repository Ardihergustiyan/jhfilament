<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Filament\Panel;

class User extends Authenticatable implements  HasName

{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'image',         // Baru
        'phone_number',  // Baru
        'is_active',     // Baru
        'address',       // Baru
        'status',        // Baru
        'reseller_level_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    public function getFilamentName(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
    // public function canAccessPanel(Panel $panel): bool
    // {
    //     // Pastikan pengguna memiliki peran 'Admin'
    //     return $this->hasRole('Admin');
    // }

    /**
     * Define a one-to-many relationship with Order.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Define a one-to-one or many-to-many relationship with Voucher.
     */
    public function vouchers()
    {
        return $this->hasMany(Voucher::class);
    }

    public function discount()
    {
        return $this->hasOne(Discount::class, 'applicable_id')->where('applicable_to', 'Resellers');
    }
    public function productReviews()
    {
        return $this->hasMany(ProductReview::class);
    }
   
    public function resellerLevel()
    {
        return $this->belongsTo(ResellerLevel::class, 'reseller_level_id');
    }

    public function isReseller()
    {
        return $this->reseller_level_id !== null; // Cek apakah user punya level reseller
    }

    
}
