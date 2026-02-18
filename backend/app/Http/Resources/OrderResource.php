<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'amount' => (string) $this->amount,
            'shop_id' => $this->shop_id,
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
