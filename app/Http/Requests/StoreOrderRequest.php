<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<string>|string>
     */
    public function rules(): array
    {
        return [
            'items' => 'required|string',
            'player_tag' => 'required|string|min:3',
            'payment_method' => 'required|string',
            'voucher_code' => 'nullable|string',
        ];
    }
}
