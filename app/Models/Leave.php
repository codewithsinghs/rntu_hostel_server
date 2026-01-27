<?php

namespace App\Models;

use App\Models\Resident;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Leave extends Model
{
    use HasFactory;

    // Table name (optional if it matches plural of class name)
    protected $table = 'leaves';

    // Mass assignable fields
    protected $fillable = [
        'resident_id',
        'room_number',
        'type',
        'reason',
        'description',
        'attachment',
        'start_date',
        'end_date',
        'hod_status',
        'hod_remarks',
        'hod_action_at',
        'admin_status',
        'admin_remarks',
        'admin_action_at',
        'status',
        'approvals',
    ];

    // Casts for dates and enums
    protected $casts = [
        'start_date'       => 'date',
        'end_date'         => 'date',
        'hod_action_at'  => 'datetime',
        'admin_action_at'=> 'datetime',
        'approvals' => 'array', // Laravel will auto decode/encode JSON
    ];

    /**
     * Relationship: A leave belongs to a Resident
     */
    public function resident()
    {
        return $this->belongsTo(Resident::class, 'resident_id');
    }

    public function scopeVisibleFor($query, $user)
    {
        return $query->whereHas('resident', fn($q) => $q->visibleFor($user));
    }
}

