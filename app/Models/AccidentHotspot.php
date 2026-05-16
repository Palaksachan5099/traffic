<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

/**
 * Curated high-risk zones (intersections / corridors) for planning and map overlays.
 */
class AccidentHotspot extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'accident_hotspots';

    protected $fillable = [
        'title',
        'description',
        'center',
        'radius_meters',
        'risk_level',
        'accident_count',
        'active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'center' => 'array',
        'radius_meters' => 'integer',
        'accident_count' => 'integer',
        'active' => 'boolean',
    ];

    protected $attributes = [
        'radius_meters' => 250,
        'risk_level' => 'medium',
        'accident_count' => 0,
        'active' => true,
    ];

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
