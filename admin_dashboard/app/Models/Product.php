<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'name',
        'description',
        'price',
        'real_price',
        'quantity',
        'category_id',
        'status',
        'outline',
        'unit',
        'slug',
        'guarantee',
        'rating_avg',
        'rating_count',
    ];

    // Thêm attribute ảo vào JSON
    protected $appends = ['primary_image_url', 'all_image_urls'];

    /**
     * Relation: sản phẩm thuộc danh mục nào
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relation: tất cả ảnh của sản phẩm
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'id');
    }

    /**
     * Relation: ảnh chính của sản phẩm
     */
    public function primaryImage()
    {
        return $this->hasOne(ProductImage::class, 'product_id', 'id')
                    ->where('is_primary', 1);
    }

    /**
     * Helper: trả về URL ảnh chính (nếu có)
     */
    public function getPrimaryImageUrlAttribute()
    {
        if ($this->primaryImage) {
            return url('assets/img/products/' . $this->id . '/' . $this->primaryImage->image);
        }
        return null; // hoặc trả về ảnh mặc định nếu muốn
    }


    /**
     * Helper: trả về tất cả URL ảnh của sản phẩm
     */
    public function getAllImageUrlsAttribute()
    {
        return $this->images->map(function($img) {
            return url('assets/img/products/' . $this->id . '/' . $img->image);
        })->toArray();
    }

}
