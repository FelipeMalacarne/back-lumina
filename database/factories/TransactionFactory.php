<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transaction>
 */
class TransactionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'amount' => $this->faker->randomNumber(6),
            'date_posted' => $this->faker->dateTime(),
            'fitid' => $this->faker->uuid,
            'memo' => $this->faker->sentence,
            'currency' => $this->faker->currencyCode,
            'account_id' => $this->faker->uuid,
        ];
    }
}
