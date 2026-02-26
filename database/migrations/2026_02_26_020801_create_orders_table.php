<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Who placed the order
            $table->foreignId('customer_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Which store the order is from
            $table->foreignId('vendor_id')
                  ->constrained('vendors')
                  ->cascadeOnDelete();

            // Who is delivering — nullable because driver is assigned AFTER
            // the vendor accepts the order, not at placement time
            $table->foreignId('driver_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete();

            // Order lifecycle status
            // pending    = customer placed, waiting for vendor to accept
            // accepted   = vendor confirmed the order
            // preparing  = vendor is preparing the order
            // ready      = order is ready for pickup by driver
            // picked_up  = driver picked up the order
            // delivered  = customer received the order
            // cancelled  = order was cancelled (by customer or vendor)
            $table->enum('status', [
                'pending',
                'accepted',
                'preparing',
                'ready',
                'picked_up',
                'delivered',
                'cancelled',
            ])->default('pending');

            // Total price snapshot at time of order
            // We store this separately so price changes don't affect old orders
            $table->decimal('total_amount', 10, 2);

            // Delivery location snapshot — we store it here because
            // the customer might change their profile address later
            $table->string('delivery_address')->nullable();
            $table->decimal('delivery_latitude', 10, 7)->nullable();
            $table->decimal('delivery_longitude', 10, 7)->nullable();

            // Optional note from customer (e.g. "no onions please")
            $table->text('notes')->nullable();

            // When the order was delivered (for analytics)
            $table->timestamp('delivered_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};