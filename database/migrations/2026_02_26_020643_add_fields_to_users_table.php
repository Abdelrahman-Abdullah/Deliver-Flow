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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');

            // Soft delete instead of hard delete
            // We never permanently delete users — orders history must stay intact
            $table->softDeletes();


            // Data For Real time location
            $table->decimal('latitude', 10, 7)->nullable()->after('phone');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');

           // Profile photo
            $table->string('avatar')->nullable()->after('longitude');

            // Block a user without deleting them
            $table->boolean('is_active')->default(true)->after('avatar');
       });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'latitude', 'longitude', 'avatar', 'is_active']);
            $table->dropSoftDeletes();
        });
    }
};
