<?php

namespace Database\Factories;

use App\Enums\ProjectType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Project>
 */
class ProjectFactory extends Factory
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
            'type' => $this->faker->randomElement(ProjectType::values()),
        ];
    }

    public function personal(): self
    {
        return $this->state(fn () => [
            'type' => ProjectType::Personal,
        ]);
    }

    public function professional(): self
    {
        return $this->state(fn () => [
            'type' => ProjectType::Professional,
        ]);
    }
}
