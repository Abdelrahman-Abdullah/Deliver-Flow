<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'avatar',
        'latitude',
        'longitude',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_active'         => 'boolean',
        'latitude'          => 'decimal:7',
        'longitude'         => 'decimal:7',
    ];

    // -----------------------------------------------
    // Relationships
    // -----------------------------------------------

    // User as a VENDOR OWNER — has one store
    public function vendor()
    {
        return $this->hasOne(Vendor::class, 'owner_id');
    }

    // User as a CUSTOMER — has many orders they placed
    public function ordersAsCustomer()
    {
        return $this->hasMany(Order::class, 'customer_id');
    }

    // User as a DRIVER — has many orders they deliver
    public function ordersAsDriver()
    {
        return $this->hasMany(Order::class, 'driver_id');
    }

    // User as a DRIVER — location history
    public function driverLocations()
    {
        return $this->hasMany(DriverLocation::class, 'driver_id');
    }

    // Get the driver's LATEST location only
    public function latestLocation()
    {
        return $this->hasOne(DriverLocation::class, 'driver_id')
                    ->latestOfMany('recorded_at');
    }

    // -----------------------------------------------
    // Helper Methods
    // -----------------------------------------------

    public function isActive(): bool
    {
        return $this->is_active;
    }

    public function isVendor(): bool
    {
        return $this->hasRole('vendor');
    }

    public function isDriver(): bool
    {
        return $this->hasRole('driver');
    }

    public function isCustomer(): bool
    {
        return $this->hasRole('customer');
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }
}