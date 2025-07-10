<?php

namespace Database\Seeders;

use App\Domain\Attachment\Models\Attachment;
use Illuminate\Database\Seeder;

class AttachmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Attachment::factory()->count(50)->create(
        );
    }
}
