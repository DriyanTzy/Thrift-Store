<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStoreProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'store_name' => 'required|string|max:255',
            'store_slogan' => 'nullable|string|max:255',
            'store_city' => 'required|string|max:100',
            'store_phone' => 'nullable|string|max:25',
            'store_description' => 'nullable|string|max:1500',
            'store_logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'store_banner' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ];
    }
}
