<?php

namespace App\Domain\Telegram\Clients;

use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Support\Facades\Http;

class HttpTelegramClient implements TelegramClient
{
    public function __construct(
        Config $config
    ) {
        $this->baseUrl = $config->get('services.telegram.base_url', 'https://api.telegram.org');
    }

    private readonly string $baseUrl;

    public function sendMessage(string $token, string $chatId, string $text): void
    {
        $url = "{$this->baseUrl}/bot{$token}/sendMessage";

        $response = Http::asForm()->post($url, [
            'chat_id' => $chatId,
            'text' => $text,
        ]);

        $response->throw();
    }
}
