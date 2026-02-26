<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;


    const STATUS_PENDING   = 'pending';
    const STATUS_ACCEPTED  = 'accepted';
    const STATUS_PREPARING = 'preparing';
    const STATUS_READY     = 'ready';
    const STATUS_PICKED_UP = 'picked_up';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'customer_id',
        'vendor_id',
        'driver_id',
        'status',
        'total_amount',
        'delivery_address',
        'delivery_latitude',
        'delivery_longitude',
        'notes',
        'delivered_at',
    ];

    protected $casts = [
        'total_amount'        => 'decimal:2',
        'delivery_latitude'   => 'decimal:7',
        'delivery_longitude'  => 'decimal:7',
        'delivered_at'        => 'datetime',
    ];

    // -----------------------------------------------
    // Relationships
    // -----------------------------------------------

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function driverLocations()
    {
        return $this->hasMany(DriverLocation::class);
    }

    // Get only the latest driver location for this order
    public function latestDriverLocation()
    {
        return $this->hasOne(DriverLocation::class)
                    ->latestOfMany('recorded_at');
    }

    // -----------------------------------------------
    // Helper Methods
    // -----------------------------------------------

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isDelivered(): bool
    {
        return $this->status === self::STATUS_DELIVERED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isAssignedToDriver(): bool
    {
        return !is_null($this->driver_id);
    }

    // Check if this order can be cancelled
    // You can't cancel an order that's already picked up or delivered
    public function isCancellable(): bool
    {
        return in_array($this->status, [
            self::STATUS_PENDING,
            self::STATUS_ACCEPTED,
        ]);
    }
}