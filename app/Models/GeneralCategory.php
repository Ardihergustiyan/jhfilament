<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralCategory extends Model
{
    use HasFactory;

    protected $table = 'general_categories';

    protected $fillable = [
        'name',
        'slug',
    ];

    public function categories()
    {
        return $this->hasMany(Category::class, 'general_category_id');
    }

}
