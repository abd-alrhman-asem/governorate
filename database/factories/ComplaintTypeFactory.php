<?php

namespace Database\Factories;

use App\Domain\Complaint\Models\ComplaintType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ComplaintType>
 */
class ComplaintTypeFactory extends Factory
{
    protected $model = ComplaintType::class;

    public function definition(): array
    {
        $types = ['Technical Issue', 'Service Complaint', 'Billing Error', 'Product Defect', 'Suggestion', 'General Inquiry'];
        return [
            'name' => $this->faker->randomElement($types),
        ];
    }
}
