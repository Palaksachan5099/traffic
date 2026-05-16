<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class TrafficReport extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'traffic_reports';

    protected $fillable = [
        'user_id', 'location', 'coordinates', 'congestion_level', 'cause',
        'delay_minutes', 'status', 'reported_at', 'resolved_at',
        'admin_notes', 'completion', 'image_path',
    ];

    protected $casts = [
        'coordinates' => 'array',
        'reported_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
