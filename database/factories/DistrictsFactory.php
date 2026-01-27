<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Districts>
 */
class DistrictsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'district_name' => $this->faker->randomElement([
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
            'nominees' => $this->faker->numberBetween(5, 20),
            'registered_voters' => $this->faker->numberBetween(100, 1000),
            'votes_cast' => $this->faker->numberBetween(0, 1000),
            'status' => $this->faker->randomElement(['Active', 'Inactive']),
        ];
    }
}
