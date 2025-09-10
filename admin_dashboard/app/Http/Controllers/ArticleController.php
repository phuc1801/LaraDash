<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class ArticleController extends Controller
{
    protected $uploadPath;

    public function __construct()
    {
        // Đường dẫn tuyệt đối đến thư mục public/assets/img/articles
        $this->uploadPath = public_path('assets/img/articles');

        // Nếu chưa có thư mục thì tạo
        if (!File::exists($this->uploadPath)) {
            File::makeDirectory($this->uploadPath, 0777, true, true);
        }
    }

    // Lấy danh sách bài viết (có ảnh)
    public function index(Request $request)
    {
        $query = Article::query();

        if ($request->has('search')) {
            $query->where('title', 'like', '%'.$request->search.'%');
        }

        $articles = $query->with('images')->get();

        $articles = $articles->map(function ($article) {
            $article->author_name = $article->user ? $article->user->name : null;
            return $article;
        });
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
            $count = 0;
            foreach ($request->file('images') as $imgFile) {
                $count++;
                $ext = $imgFile->getClientOriginalExtension();
                $fileName = "article-{$article->id}-{$count}.{$ext}";
                $imgFile->move($this->uploadPath, $fileName);

                $article->images()->create([
                    'image' => $fileName
                ]);
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
            'title' => 'sometimes|string|max:255',
            'content' => 'nullable|string',
            'type' => 'integer',
            'images.*' => 'image|max:2048',
            'delete_images' => 'array',
        ]);

        // ✅ Cập nhật nếu có field
        $data = $request->only(['title', 'content', 'type']);
        if (!empty($data)) {
            $article->update($data);
        }

        // ✅ Xóa ảnh nếu có
        if ($request->filled('delete_images')) {
            foreach ($request->delete_images as $imgId) {
                $img = $article->images()->find($imgId);
                if ($img) {
                    $filePath = $this->uploadPath.'/'.$img->image;
                    if (file_exists($filePath)) {
                        unlink($filePath);
                    }
                    $img->delete();
                }
            }
        }

        // ✅ Upload ảnh mới
        if ($request->hasFile('images')) {
            $count = $article->images()->count();
            foreach ($request->file('images') as $imgFile) {
                $count++;
                $ext = $imgFile->getClientOriginalExtension();
                $fileName = "article-{$article->id}-{$count}.{$ext}";

                $imgFile->move($this->uploadPath, $fileName);

                $article->images()->create([
                    'image' => $fileName
                ]);
            }
        }

        return response()->json($article->fresh('images'));
    }


    // Xóa bài viết + xóa tất cả ảnh
    public function destroy($id)
    {
        $article = Article::with('images')->findOrFail($id);

        foreach ($article->images as $img) {
            $filePath = public_path($img->image);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $img->delete();
        }

        $article->delete();

        return response()->json(null, 204);
    }
}
