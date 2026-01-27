<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Building extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'university_id', 'building_code', 'gender', 'floors', 'status', 'created_by'];

    protected $dates = ['deleted_at'];

    // protected static function booted()
    // {
    //     static::creating(function ($building) {

    //         if (!empty($building->building_code)) {
    //             return;
    //         }

    //         $universityCode = strtoupper(
    //             \App\Models\University::where('id', $building->university_id)
    //                 ->value('code')
    //         ); // e.g. RNTU

    //         $typeCode = match ($building->gender) {
    //             'male'  => 'BOYS',
    //             'female' => 'GIRLS',
    //             default => 'MIXED',
    //         };

    //         $lastSeq = self::where('university_id', $building->university_id)
    //             ->where('gender', $building->gender)
    //             ->withTrashed()
    //             ->count() + 1;

    //         $building->building_code = sprintf(
    //             '%s-%s-%03d',
    //             $universityCode,
    //             $typeCode,
    //             $lastSeq
    //         );
    //     });
    // }

    // protected static function booted()
    // {
    //     static::creating(function ($building) {
    //         if (!empty($building->building_code)) {
    //             return;
    //         }

    //         $universityCode = strtoupper(
    //             University::where('id', $building->university_id)->value('code')
    //         );

    //         $typeCode = match ($building->gender) {
    //             'male'   => 'BOYS',
    //             'female' => 'GIRLS',
    //             default  => 'MIXED',
    //         };

    //         // ✅ Use transaction + row lock to avoid race conditions
    //         DB::transaction(function () use ($building, $universityCode, $typeCode) {
    //             // Lock rows for this university+gender so no other transaction can read them simultaneously
    //             $lastCode = Building::where('university_id', $building->university_id)
    //                 ->where('gender', $building->gender)
    //                 ->withTrashed()
    //                 ->lockForUpdate()
    //                 ->orderByDesc('id')
    //                 ->value('building_code');

    //             $lastSeq = 0;
    //             if ($lastCode) {
    //                 $parts = explode('-', $lastCode);
    //                 $lastSeq = (int) end($parts);
    //             }

    //             $building->building_code = sprintf(
    //                 '%s-%s-%03d',
    //                 $universityCode,
    //                 $typeCode,
    //                 $lastSeq + 1
    //             );
    //         });
    //     });
    // }

    protected static function booted()
    {
        /*
    |--------------------------------------------------------------------------
    | CREATE: Auto-generate code if missing
    |--------------------------------------------------------------------------
    */
        static::creating(function ($building) {
            if (!empty($building->building_code)) {
                return;
            }

            self::generateBuildingCode($building);
        });

        /*
    |--------------------------------------------------------------------------
    | UPDATE: Regenerate only when required
    |--------------------------------------------------------------------------
    */
        static::updating(function ($building) {

            // Case 1: legacy / null code
            if (empty($building->building_code)) {
                self::generateBuildingCode($building);
                return;
            }

            // Case 2: gender or university changed → code semantics changed
            if (
                $building->isDirty('gender') ||
                $building->isDirty('university_id')
            ) {
                self::generateBuildingCode($building);
            }
        });
    }

    /*
|--------------------------------------------------------------------------
| Centralized generator (DRY + testable)
|--------------------------------------------------------------------------
*/
    protected static function generateBuildingCode($building): void
    {
        $universityCode = strtoupper(
            University::where('id', $building->university_id)->value('code')
        );

        $typeCode = match ($building->gender) {
            'male'   => 'BOYS',
            'female' => 'GIRLS',
            default  => 'MIXED',
        };

        DB::transaction(function () use ($building, $universityCode, $typeCode) {

            $lastCode = self::where('university_id', $building->university_id)
                ->where('gender', $building->gender)
                ->withTrashed()
                ->lockForUpdate()
                ->orderByDesc('id')
                ->value('building_code');

            $lastSeq = 0;
            if ($lastCode) {
                $parts = explode('-', $lastCode);
                $lastSeq = (int) end($parts);
            }

            $building->building_code = sprintf(
                '%s-%s-%03d',
                $universityCode,
                $typeCode,
                $lastSeq + 1
            );
        });
    }

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function scopeAccessible($query)
    {
        $user = auth()->user();

        if (! $user) {
            return $query->whereRaw('1=0'); // no access
        }

        // if ($user && $user->university_id && ! $user->is_super_admin) {
        //     $query->where('university_id', $user->university_id);
        // }

        // return $query;

        // Super admin → everything
        if ($user->is_super_admin) {
            return $query;
        }

        // University admin → all buildings of university
        if ($user->university_id && empty($user->building_id)) {
            return $query->where('university_id', $user->university_id);
        }

        // Limited hostel access (JSON array)
        if (! empty($user->building_id)) {
            return $query->whereIn('id', $user->building_id);
        }

        return $query;
    }

    // for building users
    public function users()
    {
        return $this->hasMany(User::class);
        // return $this->belongsToMany(User::class, 'building_user');
    }

    // public function scopeAccessible($query)
    // {
    //     $user = auth()->user();

    //     if (! $user || $user->is_super_admin) {
    //         return $query;
    //     }

    //     // University-level admin → all buildings
    //     if ($user->university_id && ! $user->hasLimitedHostelAccess()) {
    //         return $query->where('university_id', $user->university_id);
    //     }

    //     // Limited hostel access
    //     return $query->whereHas('users', function ($q) use ($user) {
    //         $q->where('users.id', $user->id);
    //     });
    // }
}
