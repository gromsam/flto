<?php

namespace App\Domain\Orders\DTO;

readonly class CreateOrderDTO
{
    public function __construct(
        public string $orderId,
        public string $amount,
    ) {}
}
