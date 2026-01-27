<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bed extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'bed_number',
        'bed_type',
        'status',
        'created_by',
    ];

    /* =====================================================
     | STATUS ENUM (SINGLE SOURCE OF TRUTH)
     ===================================================== */

    public const STATUS_AVAILABLE   = 'available';
    public const STATUS_OCCUPIED    = 'occupied';
    public const STATUS_MAINTENANCE = 'maintenance';
    public const STATUS_BLOCKED     = 'blocked';
    public const STATUS_RESERVED    = 'reserved';
    public const STATUS_INACTIVE    = 'inactive';
    public const STATUS_DECOMMISSIONED = 'decommissioned';

    /* =====================================================
     | STATUS COLLECTIONS
     ===================================================== */

    /** All valid statuses */
    public const STATUSES = [
        self::STATUS_AVAILABLE,
        self::STATUS_OCCUPIED,
        self::STATUS_MAINTENANCE,
        self::STATUS_BLOCKED,
        self::STATUS_RESERVED,
        self::STATUS_INACTIVE,
        self::STATUS_DECOMMISSIONED,
    ];
    /** Beds that can be allocated */
    public const ALLOCATABLE_STATUSES = [
        self::STATUS_AVAILABLE,
    ];

    /** Beds that are not usable */
    public const NON_ALLOCATABLE_STATUSES = [
        self::STATUS_OCCUPIED,
        self::STATUS_MAINTENANCE,
        self::STATUS_BLOCKED,
        self::STATUS_RESERVED,
        self::STATUS_INACTIVE,
        self::STATUS_DECOMMISSIONED,
    ];

    /* =====================================================
     | QUERY SCOPES
     ===================================================== */

    public function scopeAvailable($query)
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    public function scopeAllocatable($query)
    {
        return $query->whereIn('status', self::ALLOCATABLE_STATUSES);
    }

    public function scopeUnavailable($query)
    {
        return $query->whereIn('status', self::NON_ALLOCATABLE_STATUSES);
    }

    public function resident()
    {
        return $this->hasOne(Resident::class, 'bed_id', 'id'); // One bed has only one resident
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function building()
    {
        return $this->hasOneThrough(
            Building::class,
            Room::class,
            'id',
            'id',
            'room_id',
            'building_id'
        );
    }

    public function university()
    {
        return $this->hasOneThrough(
            University::class,
            Building::class,
            'id',
            'id',
            'building_id',
            'university_id'
        );
    }

    /* ---------------- Access Control ---------------- */

    public function scopeAccessible($query)
    {
        $user = auth()->user();

        if ($user && ! $user->is_super_admin) {

            $query->whereHas('room.building', function ($q) use ($user) {

                // University restriction
                if ($user->university_id) {
                    $q->where('university_id', $user->university_id);
                }

                // Building-level restriction (warden case)
                if (! empty($user->building_id)) {
                    $q->whereIn(
                        'id',
                        is_array($user->building_id)
                            ? $user->building_id
                            : json_decode($user->building_id, true)
                    );
                }
            });
        }

        return $query;
    }

    public function accessories()
    {
        return $this->belongsToMany(Accessory::class, 'student_accessories')
            ->withPivot('price');
    }

    // public function scopeAccessible($query)
    // {
    //     return $query->whereHas('room', function ($q) {
    //         $q->accessible();
    //     });
    // }





    /* ---------------- ACCESS CONTROL ---------------- */

    // public function scopeAccessible($query)
    // {
    //     $user = auth()->user();

    //     if (! $user || $user->is_super_admin) {
    //         return $query;
    //     }

    //     return $query->whereHas('room.building', function ($q) use ($user) {

    //         if ($user->university_id) {
    //             $q->where('university_id', $user->university_id);
    //         }

    //         if (! empty($user->building_ids)) {
    //             $q->whereIn('id', (array) $user->building_ids);
    //         }
    //     });
    // }

    /* ---------------- STATUS NORMALIZATION ---------------- */

    public const STATUS_MAP = [
        'available'   => ['active', 'available', 'free'],
        'occupied'    => ['occupied', 'assigned'],
        'reserved'    => ['reserved', 'blocked'],
        'maintenance' => ['maintenance', 'repair'],
    ];

    public function normalizedStatus(): string
    {
        foreach (self::STATUS_MAP as $key => $values) {
            if (in_array(strtolower($this->status), $values)) {
                return $key;
            }
        }

        return 'available';
    }

    /* ---------------- SUMMARY (NO SQL) ---------------- */

    public static function summary()
    {
        $beds = self::accessible()->get();

        return [
            'total'       => $beds->count(),
            'available'   => $beds->filter(fn($b) => $b->normalizedStatus() === 'available')->count(),
            'occupied'    => $beds->filter(fn($b) => $b->normalizedStatus() === 'occupied')->count(),
            'reserved'    => $beds->filter(fn($b) => $b->normalizedStatus() === 'reserved')->count(),
            'maintenance' => $beds->filter(fn($b) => $b->normalizedStatus() === 'maintenance')->count(),
        ];
    }
}
