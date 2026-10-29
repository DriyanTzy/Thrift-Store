<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStockRequest extends FormRequest
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
            'type' => 'required|in:in,adjustment',
            'quantity' => 'required|integer|min:1',
            'price' => 'nullable|numeric|min:0',
            'cost_price' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string|max:255',
        ];
    }
}
