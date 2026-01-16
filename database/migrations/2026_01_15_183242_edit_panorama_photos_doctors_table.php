<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('panorama_photos_doctors', function (Blueprint $table) {

            // 1️⃣ Drop foreign key FIRST
            if (Schema::hasColumn('panorama_photos_doctors', 'c_id')) {
                $table->dropForeign('panorama_photos_doctors_c_id_foreign');
                $table->dropColumn('c_id');
            }

            // 2️⃣ Add customer_name
            if (!Schema::hasColumn('panorama_photos_doctors', 'customer_name')) {
                $table->string('customer_name')->nullable()->after('photo');
            }
        });
    }

    public function down(): void
    {
        Schema::table('panorama_photos_doctors', function (Blueprint $table) {

            // Restore c_id
            if (!Schema::hasColumn('panorama_photos_doctors', 'c_id')) {
                $table->foreignId('c_id')
                      ->nullable()
                      ->constrained('customers')
                      ->cascadeOnDelete();
            }

            // Remove customer_name
            if (Schema::hasColumn('panorama_photos_doctors', 'customer_name')) {
                $table->dropColumn('customer_name');
            }
        });
    }
};
