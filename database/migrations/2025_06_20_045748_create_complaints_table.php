<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            // when user deleted complaints archived
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('destination_id')->constrained('destinations')->onDelete('restrict');
            $table->foreignId('category_id')->constrained('complaint_categories')->onDelete('restrict');
            $table->foreignId('type_id')->nullable()->constrained('complaint_types')->onDelete('restrict');
            $table->foreignId('status_id')->nullable()->constrained('complaint_statuses')->onDelete('restrict');
            $table->text('text');
            $table->string('title');
            $table->string('LocationText');
            $table->string('LocationLat');
            $table->string('LocationLng');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('complaints');
    }
};


