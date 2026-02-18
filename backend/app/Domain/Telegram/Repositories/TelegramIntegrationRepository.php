<?php

namespace App\Domain\Telegram\Repositories;

use App\Models\Shop;
use App\Models\TelegramIntegration;

class TelegramIntegrationRepository
{
    public function __construct(
        private readonly TelegramIntegration $model
    ) {}

    public function find(int $id): ?TelegramIntegration
    {
        return $this->model->newQuery()->find($id);
    }

    public function findByShop(Shop $shop): ?TelegramIntegration
    {
        return $this->model->newQuery()
            ->where('shop_id', $shop->id)
            ->first();
    }

    public function create(array $attributes): TelegramIntegration
    {
        return $this->model->newQuery()->create($attributes);
    }

    public function update(TelegramIntegration $integration, array $attributes): bool
    {
        return $integration->update($attributes);
    }

    public function delete(TelegramIntegration $integration): bool
    {
        return $integration->delete();
    }

    public function upsertForShop(Shop $shop, array $attributes): TelegramIntegration
    {
        $integration = $this->findByShop($shop);

        if ($integration) {
            $integration->update($attributes);
            return $integration;
        }

        return $this->create(array_merge($attributes, ['shop_id' => $shop->id]));
    }
}
