<?php

namespace App\Domain\Telegram\Clients;

class FakeTelegramClient implements TelegramClient
{
    public function sendMessage(string $token, string $chatId, string $text): void
    {
        // No-op for tests; can be extended to record calls for assertions
    }
}
