<?php

namespace Database\Factories;

use App\Models\Account;
use App\Models\Bank;
use App\Models\Project;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Account>
 */
class AccountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'type' => $this->faker->randomElement(['checking', 'savings']),
            'number' => $this->faker->bankAccountNumber,
            'check_digit' => $this->faker->randomDigit,
            'balance' => $this->faker->randomNumber(8),
            'bank_id' => Bank::inRandomOrder()->first()->id,
            'project_id' => Project::inRandomOrder()->first()->id,
        ];
    }

    public function withTransactions(int $count = 5): static
    {
        return $this->afterCreating(function (Account $account) use ($count) {
            Transaction::factory()->count($count)->create([
                'account_id' => $account->id,
            ]);
        });
    }
}
