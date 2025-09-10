<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ArticleImage extends Model
{
    use HasFactory;

    protected $table = 'article_images';

    protected $fillable = [
        'article_id',
        'image',
    ];

    public $timestamps = true;

    // Quan hệ ngược: ảnh thuộc bài viết nào
    public function article()
    {
        return $this->belongsTo(Article::class);
    }

    // Xóa file ảnh khi xóa record
    public function deleteImageFile()
    {
        if ($this->image) {
            Storage::disk('public')->delete($this->image);
        }
    }
}

