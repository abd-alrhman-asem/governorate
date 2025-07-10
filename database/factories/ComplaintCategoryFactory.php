<?php

namespace Database\Factories;

use App\Domain\Complaint\Models\ComplaintCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ComplaintType>
 */
class ComplaintCategoryFactory extends Factory
{
    protected $model = ComplaintCategory::class;

    public function definition(): array
    {
        $types = ['Technical Issue', 'Service Complaint', 'Billing Error', 'Product Defect', 'Suggestion', 'General Inquiry'];
        return [
            'name' => $this->faker->randomElement($types),
        ];
    }
}
