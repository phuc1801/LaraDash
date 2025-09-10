<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;

    protected $table = 'articles';

    protected $fillable = [
        'title',
        'content',
        'type',
        'user_id',
    ];

    public $timestamps = true;

    // Quan hệ 1 bài viết có nhiều ảnh
    public function images()
    {
        return $this->hasMany(ArticleImage::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

