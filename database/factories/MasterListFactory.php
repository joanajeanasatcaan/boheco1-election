<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MasterList>
 */
class MasterListFactory extends Factory
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
            'id_number' => $this->faker->unique()->numerify('ID#######'),
            'district' => $this->faker->randomElement([
                'District 1',
                'District 2',
                'District 3',
                'District 4',
                'District 5',   
                'District 6',
                'District 7',
                'District 8',
                'District 9',
            ]),
            'status' => $this->faker->randomElement(['Voted', 'Pending']),
        ];
    }
}
