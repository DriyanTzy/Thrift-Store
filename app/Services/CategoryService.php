<?php

namespace App\Services;

use App\DTO\CategoryDTO;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CategoryService
{
    /**
     * Buat kategori baru beserta gambar thumbnail via CategoryDTO.
     */
    public function createCategory(CategoryDTO $dto): Category
    {
        $attributes = $dto->toModelAttributes();
        $attributes['slug'] = Str::slug($dto->name);

        if ($dto->image) {
            $attributes['image'] = $dto->image->store('categories', 'public');
        }

        return Category::create($attributes);
    }

    /**
     * Perbarui kategori dan kelola siklus hidup berkas gambar via CategoryDTO.
     */
    public function updateCategory(Category $category, CategoryDTO $dto): Category
    {
        $attributes = $dto->toModelAttributes();
        $attributes['slug'] = Str::slug($dto->name);

        if ($dto->image) {
            if ($category->image && Storage::disk('public')->exists($category->image)) {
                Storage::disk('public')->delete($category->image);
            }
            $attributes['image'] = $dto->image->store('categories', 'public');
        }

        $category->update($attributes);

        return $category;
    }

    /**
     * Hapus kategori beserta gambar yang tersimpan di storage.
     */
    public function deleteCategory(Category $category): void
    {
        if ($category->image && Storage::disk('public')->exists($category->image)) {
            Storage::disk('public')->delete($category->image);
        }

        $category->delete();
    }
}
