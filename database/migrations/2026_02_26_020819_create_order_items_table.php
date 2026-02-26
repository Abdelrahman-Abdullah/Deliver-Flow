<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')
                  ->constrained('orders')
                  ->cascadeOnDelete();

            $table->foreignId('product_id')
                  ->constrained('products')
                  ->cascadeOnDelete();

            // How many of this product was ordered
            $table->unsignedInteger('quantity');

            // Price AT THE TIME OF ORDER — not the current product price
            // This is critical: if vendor changes price from $10 to $15 tomorrow,
            // this order should still show $10
            $table->decimal('price', 8, 2);

            // Subtotal = quantity * price (stored for convenience)
            // Could be calculated, but storing it avoids repeated computation
            $table->decimal('subtotal', 10, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};