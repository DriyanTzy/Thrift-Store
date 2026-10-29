<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use Illuminate\Support\Facades\Log;

class LogOrderPlacedAudit
{
    /**
     * Handle the event.
     */
    public function handle(OrderPlaced $event): void
    {
        Log::info('Audit Domain Event: Pesanan baru telah dibuat.', [
            'order_id' => $event->order->id,
            'order_number' => $event->order->order_number,
            'user_id' => $event->order->user_id,
            'total_price' => $event->order->total_price,
            'payment_status' => $event->order->payment_status?->value ?? $event->order->payment_status,
            'order_status' => $event->order->order_status?->value ?? $event->order->order_status,
        ]);
    }
}
