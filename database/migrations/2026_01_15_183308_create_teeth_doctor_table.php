<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('teeth_doctor', function (Blueprint $table) {
            $table->id();

            $table->foreignId('p_id')
                ->constrained('panorama_photos_doctors')
                ->cascadeOnDelete();

            $table->string('name');
            $table->string('photo_panorama_generated');
            $table->string('descripe')->nullable();
            $table->integer('number');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teeth_doctor');
    }
};
