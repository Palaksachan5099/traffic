<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Accident extends Model
{
    protected $connection = 'mongodb';

    protected $collection = 'accidents';

    protected $fillable = [
        'user_id', 'location', 'coordinates', 'description',
        'severity', 'status', 'reported_at', 'resolved_at',
        'assigned_officer_id', 'assigned_at', 'resolved_by_id', 'resolved_by_role',
        'admin_notes', 'completion', 'image_path',
    ];

    protected $casts = [
        'coordinates' => 'array',
        'reported_at' => 'datetime',
        'resolved_at' => 'datetime',
        'assigned_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedOfficer()
    {
        return $this->belongsTo(User::class, 'assigned_officer_id');
    }
}
