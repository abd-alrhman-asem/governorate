<?php

namespace Database\factories\Attachment;

use App\Domain\Attachment\Models\Attachment;
use App\Domain\Complaint\Models\Complaint;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\{{ model }}>
 */
class AttachmentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Attachment::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        Complaint::factory()->count(10)->create();
        User::factory()->count(10)->create();
        return [
         'path' => fake()->filePath(),
            'user_id' => User::inRandomOrder()->first()?->id ?? User::factory(),
            'complaint_id' => Complaint::inRandomOrder()->first()?->id ?? Complaint::factory(),
            '',
        ];
    }
}
