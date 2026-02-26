<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();

            // Localization
            $table->string('name_en');
            $table->string('name_ar');

            // Category icon/image
            $table->string('image')->nullable();

            // super_admin can hide a category
            $table->boolean('is_active')->default(true);

            // Display order in the app (which category appears first)
            $table->unsignedInteger('sort_order')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};