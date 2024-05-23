<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'number' => $this->number,
            'check_digit' => $this->check_digit,
            'balance' => $this->formatted_balance,
            'bank_name' => $this->bank_name,
            'description' => $this->description,
            'color' => $this->color,
        ];
    }
}
