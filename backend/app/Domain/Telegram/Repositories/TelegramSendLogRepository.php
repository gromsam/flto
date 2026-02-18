<?php

namespace App\Domain\Telegram\Repositories;

use App\Models\Order;
use App\Models\Shop;
use App\Models\TelegramSendLog;

class TelegramSendLogRepository
{
    public function __construct(
        private readonly TelegramSendLog $model
    ) {}

    public function find(int $id): ?TelegramSendLog
    {
        return $this->model->newQuery()->find($id);
    }

    public function existsForOrder(Shop $shop, Order $order): bool
    {
        return $this->model->newQuery()
            ->where('shop_id', $shop->id)
            ->where('order_id', $order->id)
            ->exists();
    }

    public function create(int $shopId, int $orderId, string $status = TelegramSendLog::STATUS_SENT): TelegramSendLog
    {
        return $this->model->newQuery()->create([
            'shop_id' => $shopId,
            'order_id' => $orderId,
            'status' => $status,
        ]);
    }
}
