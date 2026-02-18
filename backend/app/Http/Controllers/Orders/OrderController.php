<?php

namespace App\Http\Controllers\Orders;

use App\Domain\Orders\Services\OrderService;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Shop;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderService $orderService
    ) {}

    public function store(CreateOrderRequest $request, Shop $shop): JsonResponse
    {
        $result = $this->orderService->create($shop, $request->toDTO());

        return response()->json([
            'order' => new OrderResource($result->order),
            'telegram_status' => $result->telegramStatus,
        ], 201);
    }
}
