<?php

namespace App\Domain\Orders\Repositories;

use App\Models\Order;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Collection;

class OrderRepository
{
    public function __construct(
        private readonly Order $model
    ) {}

    public function find(int $id): ?Order
    {
        return $this->model->newQuery()->find($id);
    }

    public function findOrFail(int $id): Order
    {
        return $this->model->newQuery()->findOrFail($id);
    }

    public function getByShop(Shop $shop): Collection
    {
        return $this->model->newQuery()
            ->where('shop_id', $shop->id)
            ->orderByDesc('created_at')
            ->get();
    }

    public function create(array $attributes): Order
    {
        return $this->model->newQuery()->create($attributes);
    }

    public function update(Order $order, array $attributes): bool
    {
        return $order->update($attributes);
    }
}
