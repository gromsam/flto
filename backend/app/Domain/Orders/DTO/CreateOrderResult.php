<?php

namespace App\Domain\Orders\DTO;

use App\Models\Order;

readonly class CreateOrderResult
{
    public function __construct(
        public Order $order,
        public string $telegramStatus,
    ) {}
}
