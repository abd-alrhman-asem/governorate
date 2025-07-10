<?php

namespace Database\Factories;

use App\Domain\Complaint\Models\Complaint;
use App\Domain\Complaint\Models\ComplaintCategory;
use App\Domain\Complaint\Models\ComplaintType;
use App\Domain\Complaint\Models\destination;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
class ComplaintFactory extends Factory
{
    protected $model = Complaint::class;

    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'destination_id' => destination::inRandomOrder()->first()?->id ?? destination::factory(),
            'category_id' => ComplaintCategory::inRandomOrder()->first()?->id ?? ComplaintCategory::factory(),
            'type_id' => ComplaintType::inRandomOrder()->first()?->id ?? ComplaintType::factory(),
            'title' => $this->faker->sentence(),
            'text' => $this->faker->paragraph(),
            'LocationText' => $this->faker->address(),
            'LocationLat' => $this->faker->latitude(),
            'LocationLng' => $this->faker->longitude(),
        ];
    }
}
