<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DepartmentsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "department_id" => $this->faker->words(2, true),
            "title" => $this->faker->sentences(1, true),
            "description" => $this->faker->text(300),
            "active" => $this->faker->boolean(0.5),
        ];
    }
}
