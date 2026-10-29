<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreVoucherRequest extends FormRequest
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
            'code' => 'required|string|max:30|unique:vouchers,code',
            'name' => 'required|string|max:100',
            'discount_type' => 'required|in:percent,fixed',
            'discount_value' => 'required|numeric|min:1',
            'min_spend' => 'nullable|numeric|min:0',
            'max_discount' => 'nullable|numeric|min:0',
            'quota' => 'required|integer|min:1',
        ];
    }
}
