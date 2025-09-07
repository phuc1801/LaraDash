<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Các trường có thể gán hàng loạt
    protected $fillable = [
        'name',
        'slug',
        'description',
        'parent_id',
        'icon',
        'show_in_slide'
    ];

    /**
     * Một category có nhiều sản phẩm
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
