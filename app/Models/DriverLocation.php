<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverLocation extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'order_id',
        'latitude',
        'longitude',
        'recorded_at',
    ];

    protected $casts = [
        'latitude'    => 'decimal:7',
        'longitude'   => 'decimal:7',
        'recorded_at' => 'datetime',
    ];

    // -----------------------------------------------
    // Relationships
    // -----------------------------------------------

    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}