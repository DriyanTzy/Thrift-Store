<?php

namespace App\Http\Controllers\Admin;

use App\DTO\CategoryDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function __construct(
        private readonly CategoryService $categoryService
    ) {}

    public function index(): View
    {
        $categories = Category::withCount('products')->orderBy('id', 'desc')->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create(): View
    {
        return view('admin.categories.create');
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $dto = CategoryDTO::fromArray(
            data: $request->validated(),
            image: $request->file('image')
        );

        $this->categoryService->createCategory($dto);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori produk berhasil dibuat!');
    }

    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $dto = CategoryDTO::fromArray(
            data: $request->validated(),
            image: $request->file('image')
        );

        $this->categoryService->updateCategory($category, $dto);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori produk berhasil diperbarui!');
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->categoryService->deleteCategory($category);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori produk berhasil dihapus!');
    }
}
