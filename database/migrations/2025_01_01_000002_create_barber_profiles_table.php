<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('barber_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('salon_name');
            $table->text('description')->nullable();
            $table->json('photos')->nullable();
            $table->json('services')->nullable(); // name, price, duration, tags
            $table->json('availability')->nullable();
            $table->decimal('geo_lat', 10, 6)->nullable();
            $table->decimal('geo_lng', 10, 6)->nullable();
            $table->string('category')->default('mixte'); // homme, femme, enfant, mixte
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('barber_profiles');
    }
};
