<?php

namespace App\Http\Requests;

use App\Domain\Orders\DTO\CreateOrderDTO;
use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'orderId' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function toDTO(): CreateOrderDTO
    {
        return new CreateOrderDTO(
            orderId: $this->input('orderId'),
            amount: (string) $this->input('amount'),
        );
    }
}
