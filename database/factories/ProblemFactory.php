<?php

namespace Database\Factories;

use App\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Problem>
 */
class ProblemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $code = '#include<iostream>
                using namespace std ;
                int main(){
                    int x ; 
                    cin>>x ;
                    cout << x ;
                    return 0 ;
                }';
        return [
            'name' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'teacher_code_solve' => $code ,
            'teacher_id' => Teacher::inRandomOrder()->value('id'),
        ];
    }
}
