<?php

namespace Database\Factories;

use App\Models\Administrator;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exam>
 */
class ExamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'passwd' => $this->faker->password,
            'administrator_id' => Administrator::inRandomOrder()->value('id'),
            'name' => $this->faker->word,
            'time' => $this->faker->dateTime(),
        ];
    }
}
