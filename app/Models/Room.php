<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['building_id', 'floor_no', 'room_number', 'room_type', 'capacity', 'facilities', 'status']; // Added floor_no & status

    // protected $casts = [
    //     'dob'            => 'date',
    //     'joining_date'   => 'date',
    //     'leaving_date'   => 'date',
    //     'is_hosteler'    => 'boolean',
    //     // ⭐ Treat JSON as array
    //     'others' => 'array',
    // ];

    protected $hidden = [
        'updated_at',
        'created_at',


        // add other sensitive or recursive fields
    ];

    public const STATUS_ACTIVE      = 'active';
    public const STATUS_INACTIVE    = 'inactive';
    public const STATUS_RESERVED    = 'reserved';
    public const STATUS_MAINTENANCE = 'maintenance';
    public const STATUS_BLOCKED     = 'blocked';

    public const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_INACTIVE,
        self::STATUS_RESERVED,
        self::STATUS_MAINTENANCE,
        self::STATUS_BLOCKED,
    ];

    protected $casts = [
        'status' => 'string',
    ];


    public function scopeAccessible($query)
    {
        $user = auth()->user();

        // if ($user && $user->university_id && ! $user->is_super_admin) {
        //     $query->whereHas('building', function ($q) use ($user) {
        //         $q->where('university_id', $user->university_id);
        //     });
        // }

        // return $query;

        if (! $user || $user->is_super_admin) {
            return $query;
        }

        return $query->whereHas('building', function ($q) use ($user) {

            // 1️⃣ University isolation (MANDATORY)
            $q->where('university_id', $user->university_id);

            // 2️⃣ Building-level restriction (OPTIONAL)
            if (! empty($user->building_id)) {

                $buildingIds = is_array($user->building_id)
                    ? $user->building_id
                    : json_decode($user->building_id, true);

                $q->whereIn('id', $buildingIds);
            }
        });
    }

    // For building_user
    // public function scopeAccessible($query)
    // {
    //     $user = auth()->user();

    //     if (! $user || $user->is_super_admin) {
    //         return $query;
    //     }

    //     return $query->whereHas('building', function ($q) {
    //         $q->accessible();
    //     });
    // }



    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function hostel()
    {
        return $this->belongsTo(Building::class);
    }

    public function beds()
    {
        return $this->hasMany(Bed::class);
    }
}
