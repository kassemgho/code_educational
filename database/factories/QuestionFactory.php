<?php

namespace Database\Factories;

use App\Models\Exam;
use App\Models\Problem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'problem_id' => Problem::inRandomOrder()->value('id'),
            'exam_id' => Exam::inRandomOrder()->value('id'),
            'mark' => $this->faker->randomDigit,
        ];
    }
}
