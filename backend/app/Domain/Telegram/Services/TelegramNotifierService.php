<?php

namespace App\Domain\Telegram\Services;

use App\Domain\Telegram\Clients\TelegramClient;
use App\Domain\Telegram\Repositories\TelegramIntegrationRepository;
use App\Domain\Telegram\Repositories\TelegramSendLogRepository;
use App\Models\Order;
use App\Models\Shop;
use App\Models\TelegramSendLog;

class TelegramNotifierService
{
    public function __construct(
        private readonly TelegramClient $telegramClient,
        private readonly TelegramIntegrationRepository $integrationRepository,
        private readonly TelegramSendLogRepository $sendLogRepository,
    ) {}

    public const STATUS_SENT = 'sent';
    public const STATUS_FAILED = 'failed';
    public const STATUS_SKIPPED = 'skipped';

    public function notifyOrder(Shop $shop, Order $order): string
    {
        $integration = $this->integrationRepository->findByShop($shop);

        if ($integration === null || !$integration->enabled) {
            return self::STATUS_SKIPPED;
        }

        if ($this->sendLogRepository->existsForOrder($shop, $order)) {
            return self::STATUS_SKIPPED;
        }

        $text = sprintf(
            "Новый заказ #%s на сумму %s",
            $order->order_id,
            $order->amount
        );

        try {
            $this->telegramClient->sendMessage(
                $integration->bot_token,
                $integration->chat_id,
                $text
            );
            $this->sendLogRepository->create($shop->id, $order->id, TelegramSendLog::STATUS_SENT);

            return self::STATUS_SENT;
        } catch (\Throwable $e) {

            $this->sendLogRepository->create($shop->id, $order->id, TelegramSendLog::STATUS_FAILED);

            return self::STATUS_FAILED;
        }
    }
}
