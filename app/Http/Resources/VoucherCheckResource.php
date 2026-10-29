<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoucherCheckResource extends JsonResource
{
    /**
     * Matikan pembungkusan 'data' default agar response tetap flat dan backward-compatible dengan AJAX view.
     */
    public static $wrap = null;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'success' => $this->resource['success'],
            'code' => $this->resource['voucher']?->code ?? null,
            'name' => $this->resource['voucher']?->name ?? null,
            'discount_amount' => $this->resource['discount_amount'],
            'final_total' => $this->resource['final_total'],
            'message' => $this->resource['message'],
        ];
    }
}
