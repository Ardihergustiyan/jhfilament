<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'general_category_id',
        'name',
        'slug',
        'image',
        'description',
        'base_price',
        'stock',
        'het_price',
        'external_product',
    ];
    protected $casts = [
        'external_product' => 'array', 
        'image' => 'array',
    ];


    public function scopeTopSelling($query, $categoryName = null)
    {
        return $query->select(
            'products.*', 
            'aggregated_data.total_quantity', 
            'images.main_image',
            'ratings.average_rating',
            'review_counts.total_reviews'
        )
        ->join('categories', 'products.category_id', '=', 'categories.id') // Join dengan categories
        ->leftJoinSub(
            // Subquery untuk menghitung total_quantity
            DB::table('order_items')
                ->select('product_id', DB::raw('SUM(quantity) as total_quantity'))
                ->groupBy('product_id'),
            'aggregated_data',
            'products.id',
            '=',
            'aggregated_data.product_id'
        )
        ->leftJoinSub(
            // Subquery untuk mengambil main_image
            DB::table('product_variants')
                ->select('product_id', DB::raw('MAX(JSON_UNQUOTE(JSON_EXTRACT(image, "$[0]"))) as main_image'))
                ->groupBy('product_id'),
            'images',
            'products.id',
            '=',
            'images.product_id'
        )
        ->leftJoinSub(
            // Subquery untuk menghitung rata-rata rating
            DB::table('product_reviews')
                ->select('product_id', DB::raw('AVG(rating) as average_rating'))
                ->groupBy('product_id'),
            'ratings',
            'products.id',
            '=',
            'ratings.product_id'
        )
        ->leftJoinSub(
            // Subquery untuk menghitung jumlah ulasan
            DB::table('product_reviews')
                ->select('product_id', DB::raw('COUNT(id) as total_reviews'))
                ->groupBy('product_id'),
            'review_counts',
            'products.id',
            '=',
            'review_counts.product_id'
        )
        ->when($categoryName, function ($q) use ($categoryName) {
            $q->where('categories.name', $categoryName); // Filter berdasarkan nama kategori
        })
        ->orderByDesc('aggregated_data.total_quantity') // Urutkan berdasarkan total_quantity
        ->take(10); // Ambil 5 produk terlaris
    }
    
    public function scopeMostReviewed($query, $categoryName = null)
    {
        return $query->select(
            'products.*',
            'review_counts.total_reviews',
            'ratings.average_rating',
            'images.main_image'
        )
        ->join('categories', 'products.category_id', '=', 'categories.id') // Join dengan categories
        ->leftJoinSub(
            // Subquery untuk menghitung jumlah ulasan
            DB::table('product_reviews')
                ->select('product_id', DB::raw('COUNT(id) as total_reviews'))
                ->groupBy('product_id'),
            'review_counts',
            'products.id',
            '=',
            'review_counts.product_id'
        )
        ->leftJoinSub(
            // Subquery untuk rata-rata rating
            DB::table('product_reviews')
                ->select('product_id', DB::raw('AVG(rating) as average_rating'))
                ->groupBy('product_id'),
            'ratings',
            'products.id',
            '=',
            'ratings.product_id'
        )
        ->leftJoinSub(
            // Subquery untuk mengambil main_image
            DB::table('product_variants')
                ->select('product_id', DB::raw('MAX(JSON_UNQUOTE(JSON_EXTRACT(image, "$[0]"))) as main_image'))
                ->groupBy('product_id'),
            'images',
            'products.id',
            '=',
            'images.product_id'
        )
        ->when($categoryName, function ($q) use ($categoryName) {
            $q->where('categories.name', $categoryName); // Filter berdasarkan nama kategori
        })
        ->orderByDesc('review_counts.total_reviews') // Urutkan berdasarkan total_reviews
        ->take(5); // Ambil 5 produk dengan ulasan terbanyak
    }

    public function scopeSearchByName($query, $searchQuery)
    {
        return $query->where('name', 'LIKE', "%{$searchQuery}%");
    }
    public function getTotalStockAttribute()
    {
        $variantStock = $this->productVariant()->sum('stock'); // Total stok dari product_variants
        return $this->stock + $variantStock; // Tambahkan stok dari tabel products
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }
    /**
     * Relasi: Produk milik satu kategori.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Relasi: Produk memiliki banyak varian.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
   
    /**
     * Relasi: Produk memiliki banyak ulasan.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews()
    {
        return $this->hasMany(ProductReview::class);
    }

    /**
     * Relasi: Produk bisa dimiliki oleh banyak order items.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function discount()
    {
        return $this->hasOne(Discount::class, 'applicable_id')->where('applicable_to', 'Specific Products');
    }
    public function vouchers()
    {
        return $this->hasMany(Voucher::class, 'applicable_id');
    }
    public function productPrices()
    {
        return $this->hasMany(ProductPrice::class);
    }
    
    public function productVariant()
    {
        return $this->hasMany(ProductVariant::class);
    }
}
