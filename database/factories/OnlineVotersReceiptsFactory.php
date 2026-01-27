<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OnlineVotersReceipts>
 */
class OnlineVotersReceiptsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'profile' => $this->faker->imageUrl(100, 100, 'people'),
            'name' => $this->faker->name(),
            'id_number' => $this->faker->unique()->numerify('ID#######'),
            'date_time_voted' => now(),
            'remarks' => 'Voted Online',
        ];
    }
}
