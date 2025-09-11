<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;

class ArticleStatsController extends Controller
{
    // API thống kê bài viết
    public function index()
    {
        $totalArticles = Article::count(); // Tổng bài viết
        $publishedArticles = Article::where('type', 1)->count(); // type = 1 là đã xuất bản
        $unpublishedArticles = Article::where('type', 0)->count(); // type = 0 là chưa xuất bản

        return response()->json([
            'total' => $totalArticles,
            'published' => $publishedArticles,
            'unpublished' => $unpublishedArticles,
        ]);
    }
}
