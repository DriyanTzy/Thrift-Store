<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * @return array<string, array<string>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:1',
            'badge' => 'nullable|string|max:100',
            'sizes' => 'nullable|array',
            'size_stocks' => 'nullable|array',
            'description' => 'required|string',
            'images' => 'required|array|min:1|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'images.required' => 'Upload minimal 1 foto barang.',
            'images.max' => 'Maksimal 5 foto per barang.',
            'images.*.image' => 'File harus berupa gambar.',
            'images.*.max' => 'Ukuran foto maksimal 2MB per file.',
        ];
    }
}
