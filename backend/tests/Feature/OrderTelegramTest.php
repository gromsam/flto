<?php

namespace Tests\Feature;

use App\Domain\Telegram\Clients\FakeTelegramClient;
use App\Domain\Telegram\Clients\TelegramClient;
use App\Models\Shop;
use App\Models\TelegramIntegration;
use App\Models\TelegramSendLog;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTelegramTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->bind(TelegramClient::class, FakeTelegramClient::class);
    }

    public function test_order_created_telegram_sent_log_sent(): void
    {
        $shop = Shop::factory()->create();
        TelegramIntegration::create([
            'shop_id' => $shop->id,
            'bot_token' => 'fake-token',
            'chat_id' => '123',
            'enabled' => true,
        ]);

        $response = $this->postJson("/api/shops/{$shop->id}/orders", [
            'orderId' => 'order-1',
            'amount' => 99.99,
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('telegram_status', 'sent');

        $this->assertDatabaseHas('orders', [
            'shop_id' => $shop->id,
            'order_id' => 'order-1',
        ]);
        $this->assertDatabaseCount('telegram_send_logs', 1);
        $this->assertDatabaseHas('telegram_send_logs', [
            'shop_id' => $shop->id,
            'status' => TelegramSendLog::STATUS_SENT,
        ]);
    }

    public function test_duplicate_send_no_duplicate(): void
    {
        $shop = Shop::factory()->create();
        TelegramIntegration::create([
            'shop_id' => $shop->id,
            'bot_token' => 'fake-token',
            'chat_id' => '123',
            'enabled' => true,
        ]);

        $this->postJson("/api/shops/{$shop->id}/orders", [
            'orderId' => 'order-dup',
            'amount' => 50,
        ])->assertStatus(201);

        $order = $shop->orders()->where('order_id', 'order-dup')->first();
        $this->assertNotNull($order);

        $notifier = $this->app->make(\App\Domain\Telegram\Services\TelegramNotifierService::class);
        $notifier->notifyOrder($shop, $order);

        $this->assertDatabaseCount('telegram_send_logs', 1);
    }

    public function test_telegram_error_log_failed_order_created(): void
    {
        $this->app->bind(TelegramClient::class, ThrowingTelegramClient::class);

        $shop = Shop::factory()->create();
        TelegramIntegration::create([
            'shop_id' => $shop->id,
            'bot_token' => 'fake-token',
            'chat_id' => '123',
            'enabled' => true,
        ]);

        $response = $this->postJson("/api/shops/{$shop->id}/orders", [
            'orderId' => 'order-fail',
            'amount' => 10,
        ]);

        $response->assertStatus(201);
        $response->assertJsonPath('telegram_status', 'failed');

        $this->assertDatabaseHas('orders', [
            'shop_id' => $shop->id,
            'order_id' => 'order-fail',
        ]);
        $this->assertDatabaseHas('telegram_send_logs', [
            'shop_id' => $shop->id,
            'status' => TelegramSendLog::STATUS_FAILED,
        ]);
    }
}

class ThrowingTelegramClient implements TelegramClient
{
    public function sendMessage(string $token, string $chatId, string $text): void
    {
        throw new \RuntimeException('Telegram API error');
    }
}
