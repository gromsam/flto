<?php

namespace App\Http\Controllers\Telegram;

use App\Domain\Telegram\Services\TelegramIntegrationService;
use App\Http\Controllers\Controller;
use App\Http\Requests\ConnectTelegramRequest;
use App\Models\Shop;
use Illuminate\Http\JsonResponse;

class ConnectController extends Controller
{
    public function __construct(
        private readonly TelegramIntegrationService $integrationService
    ) {}

    public function __invoke(ConnectTelegramRequest $request, Shop $shop): JsonResponse
    {
        $this->integrationService->connect($shop, $request->toDTO());

        return response()->json(['success' => true]);
    }
}
