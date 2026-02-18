<?php

namespace App\Http\Requests;

use App\Domain\Telegram\DTO\ConnectTelegramDTO;
use Illuminate\Foundation\Http\FormRequest;

class ConnectTelegramRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'botToken' => ['required', 'string'],
            'chatId' => ['required', 'string'],
            'enabled' => ['required', 'boolean'],
        ];
    }

    public function toDTO(): ConnectTelegramDTO
    {
        return new ConnectTelegramDTO(
            botToken: $this->input('botToken'),
            chatId: $this->input('chatId'),
            enabled: (bool) $this->input('enabled'),
        );
    }
}
