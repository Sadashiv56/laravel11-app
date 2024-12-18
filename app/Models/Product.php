<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title', 'short_description','description', 'sku', 'price', 'quantity', 'specifications', 'image', 'category_ids', 'product_ids',
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'products', 'product_ids', 'category_ids');
    }

    public function relatedProduct()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'products');
    }
}

