<?php

namespace App\Providers;

use App\Domain\Telegram\Clients\HttpTelegramClient;
use App\Domain\Telegram\Clients\TelegramClient;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(TelegramClient::class, HttpTelegramClient::class);
    }

    public function boot(): void
    {
        //
    }
}
