<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminLoginRequest extends FormRequest
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
            'login_id' => 'nullable|string',
            'email' => 'nullable|string',
            'password' => 'required|string',
        ];
    }

    public function getIdentifier(): string
    {
        return trim((string) ($this->input('login_id') ?: $this->input('email')));
    }
}
