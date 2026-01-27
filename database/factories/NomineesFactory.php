<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Nominees>
 */
class NomineesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'Town' => $this->faker->randomElement([
                'Tubigon',
                'Clarin',
                'Tagbilaran',
                'Cortes',
                'Inabanga',
                'Talibon',
                'Loon',
                'Calape',
                'Maribojoc',
            ]),
            'current_votes' => $this->faker->numberBetween(0, 1000),    
        ];
    }
}
