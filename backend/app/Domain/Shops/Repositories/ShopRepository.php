<?php

namespace App\Domain\Shops\Repositories;

use App\Models\Shop;
use Illuminate\Database\Eloquent\Collection;

class ShopRepository
{
    public function __construct(
        private readonly Shop $model
    ) {}

    public function find(int $id): ?Shop
    {
        return $this->model->newQuery()->find($id);
    }

    public function findOrFail(int $id): Shop
    {
        return $this->model->newQuery()->findOrFail($id);
    }

    public function all(): Collection
    {
        return $this->model->newQuery()->get();
    }

    public function create(array $attributes): Shop
    {
        return $this->model->newQuery()->create($attributes);
    }

    public function update(Shop $shop, array $attributes): bool
    {
        return $shop->update($attributes);
    }
}
