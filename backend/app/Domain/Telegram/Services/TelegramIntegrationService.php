<?php

namespace App\Domain\Telegram\Services;

use App\Domain\Telegram\DTO\ConnectTelegramDTO;
use App\Domain\Telegram\Repositories\TelegramIntegrationRepository;
use App\Models\Shop;

class TelegramIntegrationService
{
    public function __construct(
        private readonly TelegramIntegrationRepository $integrationRepository
    ) {}

    public function connect(Shop $shop, ConnectTelegramDTO $dto): void
    {
        $this->integrationRepository->upsertForShop($shop, [
            'bot_token' => $dto->botToken,
            'chat_id' => $dto->chatId,
            'enabled' => $dto->enabled,
        ]);
    }

    public function getStatus(Shop $shop): ?array
    {
        $integration = $this->integrationRepository->findByShop($shop);

        if ($integration === null) {
            return null;
        }

        return [
            'connected' => true,
            'chat_id' => $integration->chat_id,
            'enabled' => $integration->enabled,
        ];
    }
}
