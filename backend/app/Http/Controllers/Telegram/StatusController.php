<?php

namespace App\Http\Controllers\Telegram;

use App\Domain\Telegram\Services\TelegramIntegrationService;
use App\Http\Controllers\Controller;
use App\Models\Shop;
use Illuminate\Http\JsonResponse;

class StatusController extends Controller
{
    public function __construct(
        private readonly TelegramIntegrationService $integrationService
    ) {}

    public function __invoke(Shop $shop): JsonResponse
    {
        $status = $this->integrationService->getStatus($shop);

        if ($status === null) {
            return response()->json(['connected' => false], 200);
        }

        return response()->json($status);
    }
}
