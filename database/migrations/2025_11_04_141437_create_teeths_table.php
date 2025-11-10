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
        Schema::create('teeth', function (Blueprint $table) {
    $table->id();
    $table->foreignId('p_id')->constrained('panorama_photos')->onDelete('cascade');
    $table->string('name');
    $table->string('photo_panorama_generated')->nullable();
    $table->string('photo_icon')->nullable();
    $table->text('descripe')->nullable();
    $table->integer('number')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teeths');
    }
};
