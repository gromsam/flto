<?php

namespace App\Domain\Telegram\DTO;

readonly class ConnectTelegramDTO
{
    public function __construct(
        public string $botToken,
        public string $chatId,
        public bool $enabled,
    ) {}
}
