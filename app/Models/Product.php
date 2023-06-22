<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Image;
use App\Models\Category;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'brand', 'code', 'thumbnail', 'price', 'description', 'quantity', 'status', 'cat_id'
    ];
    public function category()
    {
        return $this->belongsTo(Category::class, 'cat_id');
    }

    public function images()
    {
        return $this->hasMany(Image::class, 'product_id');
    }
}
