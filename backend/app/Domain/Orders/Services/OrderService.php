<?php

namespace App\Domain\Orders\Services;

use App\Domain\Orders\DTO\CreateOrderDTO;
use App\Domain\Orders\DTO\CreateOrderResult;
use App\Domain\Orders\Repositories\OrderRepository;
use App\Domain\Shops\Repositories\ShopRepository;
use App\Domain\Telegram\Services\TelegramNotifierService;
use App\Models\Order;
use App\Models\Shop;

class OrderService
{
    public function __construct(
        private readonly OrderRepository $orderRepository,
        private readonly ShopRepository $shopRepository,
        private readonly TelegramNotifierService $telegramNotifier,
    ) {}

    public function createOrder(int $shopId, array $data): CreateOrderResult
    {
        $shop = $this->shopRepository->findOrFail($shopId);
        $dto = new CreateOrderDTO(
            orderId: (string) $data['orderId'],
            amount: (string) $data['amount'],
        );

        $order = $this->orderRepository->create([
            'shop_id' => $shop->id,
            'order_id' => $dto->orderId,
            'amount' => $dto->amount,
        ]);

        //TODO: перенести в брокер
        $telegramStatus = $this->telegramNotifier->notifyOrder($shop, $order);

        return new CreateOrderResult($order, $telegramStatus);
    }

    public function create(Shop $shop, CreateOrderDTO $dto): CreateOrderResult
    {
        return $this->createOrder($shop->id, [
            'orderId' => $dto->orderId,
            'amount' => $dto->amount,
        ]);
    }
}
