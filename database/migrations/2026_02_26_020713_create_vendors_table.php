<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();

            // The user who owns this vendor/store
            // onDelete('cascade') = if the user is deleted, delete their store too
            $table->foreignId('owner_id')
                  ->constrained('users')
                  ->cascadeOnDelete();

            // Localization: every text field has _en and _ar version
            $table->string('name_en');
            $table->string('name_ar');

            $table->text('description_en')->nullable();
            $table->text('description_ar')->nullable();

            // Store logo image path
            $table->string('logo')->nullable();

            // Store's physical location (used on map)
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('address')->nullable();

            // super_admin can activate/deactivate a vendor
            $table->boolean('is_active')->default(true);

            // Vendor can open/close their store manually
            // is_active = controlled by admin
            // is_open   = controlled by vendor themselves
            $table->boolean('is_open')->default(true);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vendors');
    }
};