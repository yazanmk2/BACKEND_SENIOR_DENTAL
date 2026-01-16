<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orthodontics_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('p_id')
                ->constrained('panorama_photos')
                ->cascadeOnDelete();

            $table->string('upper')->nullable();
            $table->string('lower')->nullable();
            $table->string('final')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orthodontics_results');
    }
};
