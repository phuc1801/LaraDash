<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProductImage extends Model
{
    use HasFactory;

    // Tên bảng
    protected $table = 'product_images';

    // Các cột có thể gán mass assignment
    protected $fillable = [
        'product_id',
        'image',
        'is_primary'
    ];

    // Nếu muốn Laravel tự động quản lý created_at và updated_at
    public $timestamps = true;

    /**
     * Relation: Ảnh thuộc về sản phẩm nào
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /**
     * Scope: chỉ lấy ảnh chính
     */
    public function scopePrimary($query)
    {
        return $query->where('is_primary', 1);
    }

    /**
     * Trả về URL đầy đủ của ảnh
     */
    public function getUrlAttribute()
    {
        return $this->image ? url('images/products/' . $this->image) : null;
    }
}
