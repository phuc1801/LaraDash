<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    // Lấy danh sách bài viết (có ảnh)
    public function index(Request $request)
    {
        $query = Article::query();

        if ($request->has('search')) {
            $query->where('title', 'like', '%'.$request->search.'%');
        }

        $articles = $query->with('images')->get();
        return response()->json($articles);
    }

    // Tạo bài viết kèm ảnh
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
            'type' => 'integer',
            'user_id' => 'required|exists:users,id',
            'images.*' => 'image|max:2048',
        ]);

        $article = Article::create($request->only(['title','content','type','user_id']));

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imgFile) {
                $path = $imgFile->store('articles', 'public');
                $article->images()->create(['image' => $path]);
            }
        }

        return response()->json($article->load('images'), 201);
    }

    // Lấy chi tiết bài viết
    public function show($id)
    {
        $article = Article::with('images')->findOrFail($id);
        return response()->json($article);
    }

    // Cập nhật bài viết + thêm/xóa ảnh
    public function update(Request $request, $id)
    {
        $article = Article::with('images')->findOrFail($id);

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'content' => 'nullable|string',
            'type' => 'integer',
            'images.*' => 'image|max:2048',
            'delete_images' => 'array', // danh sách id ảnh muốn xóa
        ]);

        // Cập nhật bài viết
        $article->update($request->only(['title','content','type']));

        // Xóa ảnh nếu có
        if ($request->has('delete_images')) {
            foreach ($request->delete_images as $imgId) {
                $img = $article->images()->find($imgId);
                if ($img) {
                    Storage::disk('public')->delete($img->image);
                    $img->delete();
                }
            }
        }

        // Upload ảnh mới
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $imgFile) {
                $path = $imgFile->store('articles', 'public');
                $article->images()->create(['image' => $path]);
            }
        }

        return response()->json($article->load('images'));
    }

    // Xóa bài viết + xóa tất cả ảnh
    public function destroy($id)
    {
        $article = Article::with('images')->findOrFail($id);

        foreach ($article->images as $img) {
            Storage::disk('public')->delete($img->image);
            $img->delete();
        }

        $article->delete();

        return response()->json(null, 204);
    }
}
