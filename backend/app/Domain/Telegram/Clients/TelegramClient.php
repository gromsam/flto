<?php

namespace App\Domain\Telegram\Clients;

interface TelegramClient
{
    public function sendMessage(string $token, string $chatId, string $text): void;
}
