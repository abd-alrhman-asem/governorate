<?php

namespace Database\Factories;

use App\Domain\Complaint\Models\destination;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domain\Complaint\Models\destination>
 */
class DestinationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = destination::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $destinations = ['Customer Service', 'Technical Support', 'Management', 'Sales Department', 'Billing Department', 'Returns Department'];
        // Remove ->unique() here as you're picking from a fixed, small list
        return [
            'name' => $this->faker->randomElement($destinations),
        ];
    }
}
