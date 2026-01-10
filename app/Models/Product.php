<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'sale_price',
        'sku',
        'stock',
        'image',
        'category_id',
        'is_featured',
        'is_new',
        'is_preorder',
        'release_date',
        'platform',
        'status'
    ];
    
    protected $casts = [
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'is_preorder' => 'boolean',
        'release_date' => 'date',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
    ];
    
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }
    
    public function collections()
    {
        return $this->belongsToMany(Collection::class, 'collection_product');
    }
    
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    
    public function getDisplayPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }
}
