<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Hostel extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'university_id',
        'name',
        'code',
        'type',
        // 'gender_policy',
        'floor_count',
        'total_rooms',
        'total_beds',
        // 'occupied_rooms',
        // 'occupied_beds',
        // 'warden_id',
        'contact_number',
        'address',
        'status',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'floor_count'     => 'integer',
        'total_rooms'    => 'integer',
        'total_beds'     => 'integer',
        'occupied_rooms' => 'integer',
        'occupied_beds'  => 'integer',
        'status'          => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($hostel) {

            if (!empty($hostel->code)) {
                return;
            }

            $universityCode = \App\Models\University::where('id', $hostel->university_id)
                ->value('code'); // e.g. RNTU

            $typeCode = match ($hostel->type) {
                'Boys'  => 'BOYS',
                'Girls' => 'GIRLS',
                default => 'MIXED',
            };

            $lastSeq = self::where('university_id', $hostel->university_id)
                ->where('type', $hostel->type)
                ->withTrashed()
                ->count() + 1;

            $hostel->code = sprintf(
                '%s-%s-%03d',
                $universityCode,
                $typeCode,
                $lastSeq
            );
        });
    }


    /* -------------------------------------------------
     | Relationships
     |--------------------------------------------------*/

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function warden()
    {
        return $this->belongsTo(User::class, 'warden_id');
    }

    public function buildings()
    {
        return $this->hasMany(Building::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function beds()
    {
        return $this->hasMany(Bed::class);
    }

    /* -------------------------------------------------
     | Scopes
     |--------------------------------------------------*/

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeAccessible($query)
    {
        if (
            auth()->check() &&
            auth()->user()->university_id &&
            !auth()->user()->is_super_admin
        ) {
            $query->where('university_id', auth()->user()->university_id);
        }

        return $query;
    }

    /* -------------------------------------------------
     | Computed Attributes (Summary)
     |--------------------------------------------------*/

    public function getVacantRoomsAttribute()
    {
        return max(0, $this->total_rooms - $this->occupied_rooms);
    }

    public function getVacantBedsAttribute()
    {
        return max(0, $this->total_beds - $this->occupied_beds);
    }

    public function getOccupancyPercentageAttribute()
    {
        if ($this->total_beds === 0) {
            return 0;
        }

        return round(($this->occupied_beds / $this->total_beds) * 100, 2);
    }
}
