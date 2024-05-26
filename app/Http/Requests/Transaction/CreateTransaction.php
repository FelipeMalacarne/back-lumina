<?php

namespace App\Http\Requests\Transaction;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class CreateTransaction extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'amount' => ['required', 'numeric'],
            'date_posted' => ['required', 'date'],
            'fitid' => ['nullable', 'string'],
            'memo' => ['nullable', 'string'],
            'currency' => ['required', 'string'],
            'account_id' => ['required', 'string'],
        ];
    }
}
