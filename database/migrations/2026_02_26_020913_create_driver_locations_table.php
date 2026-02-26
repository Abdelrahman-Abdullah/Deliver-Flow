<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('driver_locations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('driver_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Which order this location ping belongs to
            $table->foreignId('order_id')
                  ->constrained('orders')
                  ->cascadeOnDelete();

            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);

            // Exact time this ping was recorded
            // We use this instead of created_at for precision
            $table->timestamp('recorded_at');

            $table->timestamps();

            // Index for fast querying: "get latest location for this driver"
            // Without index, MySQL scans every row — slow with millions of records
            $table->index(['driver_id', 'recorded_at']);
            $table->index(['order_id', 'recorded_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('driver_locations');
    }
};