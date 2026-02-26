<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Which store sells this product
            $table->foreignId('vendor_id')
                  ->constrained('vendors')
                  ->cascadeOnDelete();

            // Which category this product belongs to
            $table->foreignId('category_id')
                  ->constrained('categories')
                  ->cascadeOnDelete();

            // Localization
            $table->string('name_en');
            $table->string('name_ar');

            $table->text('description_en')->nullable();
            $table->text('description_ar')->nullable();

            // Product price
            $table->decimal('price', 8, 2);

            // Product photo
            $table->string('image')->nullable();

            // Vendor can hide a product (out of stock, etc)
            $table->boolean('is_active')->default(true);

            // Display order within the vendor's menu
            $table->unsignedInteger('sort_order')->default(0);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};