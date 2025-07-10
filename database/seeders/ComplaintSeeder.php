<?php

namespace Database\Seeders;

use App\Domain\Complaint\Models\Complaint;
use App\Domain\Complaint\Models\ComplaintCategory;
use App\Domain\Complaint\Models\ComplaintType;
use App\Domain\Complaint\Models\destination;
use App\Models\User;
use Illuminate\Database\Seeder;

class ComplaintSeeder extends Seeder
{
    /**
     * تشغيل بذور قاعدة البيانات.
     */
    public function run(): void
    {
        User::factory()->count(10)->create();
        destination::factory()->count(10)->create();
        ComplaintCategory::factory()->count(10)->create();
        ComplaintType::factory()->count(10)->create();
        Complaint::factory()->count(10)->create();
    }
}
