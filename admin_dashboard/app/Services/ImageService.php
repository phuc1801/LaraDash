<?php

namespace App\Services;

use Illuminate\Support\Facades\File;

class ImageService
{
    protected $uploadPath;

    public function __construct($type = 'article_images')
    {
        // Ví dụ: article_images -> public/assets/img/articles
        $this->uploadPath = public_path(config("paths.$type"));

        // Nếu thư mục chưa tồn tại thì tạo
        if (!File::exists($this->uploadPath)) {
            File::makeDirectory($this->uploadPath, 0777, true, true);
        }
    }

    /**
     * Lưu danh sách ảnh cho 1 model (ví dụ Article)
     */
    public function storeImages($files, $model, $prefix = 'article')
    {
        $count = $model->images()->count();
        $saved = [];

        foreach ($files as $file) {
            $count++;
            $extension = $file->getClientOriginalExtension();
            $fileName = "{$prefix}-{$model->id}-{$count}.{$extension}";

            // di chuyển file vào public/assets/img/articles
            $file->move($this->uploadPath, $fileName);

            // lưu DB (chỉ lưu đường dẫn tương đối để frontend dùng)
            $saved[] = $model->images()->create([
                'image' => config("paths.{$prefix}_images") . "/{$fileName}",
            ]);
        }

        return $saved;
    }

    /**
     * Xóa danh sách ảnh trong DB + filesystem
     */
    public function deleteImages($images)
    {
        foreach ($images as $img) {
            $filePath = public_path($img->image);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            $img->delete();
        }
    }
}
