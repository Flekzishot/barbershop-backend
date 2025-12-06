<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barber_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->string('type')->default('salon'); // salon / domicile
            $table->string('visibility')->default('standard'); // standard / boosted
            $table->string('status')->default('active'); // active / inactive
            $table->timestamp('boosted_until')->nullable();
            $table->json('photos')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('listings');
    }
};
