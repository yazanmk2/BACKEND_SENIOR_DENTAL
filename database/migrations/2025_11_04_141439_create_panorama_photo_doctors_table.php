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
       Schema::create('panorama_photos_doctors', function (Blueprint $table) {
    $table->id();
    $table->foreignId('c_id')->constrained('customers')->onDelete('cascade');
    $table->foreignId('d_id')->constrained('doctors')->onDelete('cascade');
    $table->string('photo');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('panorama_photo_doctors');
    }
};
