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
        Schema::create('display_cases', function (Blueprint $table) {
    $table->id();
    $table->foreignId('d_id')->constrained('doctors')->onDelete('cascade');
    $table->string('photo_before');
    $table->string('photo_after');
    $table->unsignedBigInteger('booking_id')->nullable();
    $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
    $table->boolean('favorite_flag')->default(false);
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('display_cases');
    }
};
