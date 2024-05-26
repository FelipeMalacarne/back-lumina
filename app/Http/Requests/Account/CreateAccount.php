<?php

namespace App\Http\Requests\Account;

use App\Enums\AccountColor;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CreateAccount extends FormRequest
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
            'name' => ['required', 'string', 'min:3', 'max:50'],
            'bank_id' => ['required', 'exists:banks,id'],
            'color' => ['string', Rule::enum(AccountColor::class), 'nullable'],
            'description' => ['string', 'max:255', 'nullable'],
            'number' => ['string', 'max:8', 'nullable'],
            'check_digit' => ['string', 'max:1', 'nullable'],
        ];
    }
}
