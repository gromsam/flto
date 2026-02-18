<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Shop;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $shop = Shop::first();
        if (! $shop) {
            return;
        }

        for ($i = 1; $i <= 10; $i++) {
            Order::firstOrCreate(
                [
                    'shop_id' => $shop->id,
                    'order_id' => 'seed-order-' . $i,
                ],
                [
                    'amount' => (string) ($i * 100.50),
                ]
            );
        }
    }
}
