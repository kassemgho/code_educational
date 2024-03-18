<?php

namespace Database\Factories;

use App\Models\SubjectTeacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
            'subject_teacher_id' => SubjectTeacher::inRandomOrder()->value('id'),
            'number_of_lessons' => $this->faker->numberBetween(1, 20),
            'number_of_ratings' => $this->faker->numberBetween(1, 100),
            'mark_of_commings' => $this->faker->randomFloat(2, 0, 10),
            'mark_of_ratings' => $this->faker->randomFloat(2, 0, 10),
        ];
    }
}
