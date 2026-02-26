<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Vendor extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'owner_id',
        'name_en',
        'name_ar',
        'description_en',
        'description_ar',
        'logo',
        'latitude',
        'longitude',
        'address',
        'is_active',
        'is_open',
    ];

    protected $casts = [
        'is_active'  => 'boolean',
        'is_open'    => 'boolean',
        'latitude'   => 'decimal:7',
        'longitude'  => 'decimal:7',
    ];

    // -----------------------------------------------
    // Relationships
    // -----------------------------------------------

    // The user who owns this store
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // All products in this store
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    // Only active products
    public function activeProducts()
    {
        return $this->hasMany(Product::class)->where('is_active', true);
    }

    // All orders received by this store
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    // -----------------------------------------------
    // Helper Methods
    // -----------------------------------------------

    // Get name based on current app locale
    public function getNameAttribute(): string
    {
        return app()->getLocale() === 'ar'
            ? $this->name_ar 
            : $this->name_en;
    }

    public function getDescriptionAttribute(): string
    {
        return app()->getLocale() === 'ar'
            ? $this->description_ar
            : $this->description_en;
    }

    public function isAvailable(): bool
    {
        return $this->is_active && $this->is_open;
    }
}