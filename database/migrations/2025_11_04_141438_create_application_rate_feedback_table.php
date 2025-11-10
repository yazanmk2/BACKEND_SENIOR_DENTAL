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
      Schema::create('application_rates_feedbacks', function (Blueprint $table) {
    $table->id();
    $table->foreignId('u_id')->constrained('users')->onDelete('cascade');
    $table->integer('rate');
    $table->text('feedback')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('application_rate_feedback');
    }
};
