<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check() && auth()->user()->isAdmin();
    }

    /**
     * @return array<string, array<string>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'banner_color' => 'nullable|string|max:50',
            'icon' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp,svg|max:2048',
        ];
    }
}
