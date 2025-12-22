<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Profile extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'profiles';

    protected $fillable = [
        'user_id',
        'resident_id',

        // Basic Info
        'name',
        'gender',
        'dob',
        'mobile',
        'alternate_mobile',
        'email',

        // Address
        'address_line1',
        'address_line2',
        'city',
        'state',
        'country',
        'pincode',

        // Family
        'father_name',
        'father_mobile',
        'mother_name',
        'mother_mobile',

        'parent_mobile',

        // Guardian
        'guardian_name',
        'guardian_mobile',
        'guardian_relation',

        // Emergency
        'emergency_name',
        'emergency_relation',
        'emergency_mobile',

        // Identity
        'aadhaar_number',
        'aadhaar_document',
        'image',
        'signature',

        // Academic
        'scholar_number',
        'course',
        'branch',
        'semester',
        'admission_year',

        // Hostel
        'is_hosteler',
        'hostel_status',
        'joining_date',
        'leaving_date',

        // Medical
        'blood_group',
        'medical_conditions',

        // Other
        'remarks',
        // ⭐ JSON Column
        'others'
    ];

    protected $casts = [
        'dob'            => 'date',
        'joining_date'   => 'date',
        'leaving_date'   => 'date',
        'is_hosteler'    => 'boolean',
        // ⭐ Treat JSON as array
        'others' => 'array',
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
        'deleted_at',
        'user_id',
        'status',
        'blood_group',
        'medical_conditions',
        'remarks',
        'others'

        // add other sensitive or recursive fields
    ];

    /*------------------------------------------
     | Relationships
     |------------------------------------------*/
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function histories()
    {
        return $this->hasMany(ProfileHistory::class);
    }

    /*------------------------------------------
     | Accessors (Optional – for clean UI)
     |------------------------------------------*/

    public function getFullAddressAttribute()
    {
        return trim(
            collect([
                $this->address_line1,
                $this->address_line2,
                $this->city,
                $this->state,
                $this->country,
                $this->pincode
            ])->filter()->implode(', ')
        );
    }

    public function getFatherDetailsAttribute()
    {
        return trim("{$this->father_name} ({$this->father_phone})");
    }

    public function getGuardianDetailsAttribute()
    {
        return trim("{$this->guardian_name} ({$this->guardian_relation}) - {$this->guardian_phone}");
    }

    public function getEmergencyDetailsAttribute()
    {
        return trim("{$this->emergency_name} ({$this->emergency_relation}) - {$this->emergency_phone}");
    }

    // protected static function booted()
    // {
    //     static::updating(function ($profile) {

    //         // Capture old + new values
    //         $oldData = $profile->getOriginal();
    //         $newData = $profile->getDirty();

    //         // Save only if something actually changed
    //         if (!empty($newData)) {
    //             \App\Models\ProfileHistory::create([
    //                 'profile_id' => $profile->id,
    //                 'user_id'    => $profile->user_id,
    //                 'resident_id'    => $profile->resident_id,
    //                 'old_data'   => json_encode($oldData),
    //                 'new_data'   => json_encode($newData),
    //                 'updated_by' => auth()->id(),  // NULL if updated by system/cron
    //                 'changed_at'  => now(),
    //             ]);
    //         }
    //     });
    // }

    protected static function booted()
    {
        static::updating(function ($profile) {

            $changed = $profile->getDirty();

            if (empty($changed)) return;

            $oldValues = [];
            $newValues = [];

            foreach ($changed as $field => $newValue) {
                $oldValues[$field] = $profile->getRawOriginal($field);
                $newValues[$field] = $newValue;
            }

            \App\Models\ProfileHistory::create([
                'profile_id'     => $profile->id,
                'user_id'        => $profile->user_id,
                'resident_id'    => $profile->resident_id,

                'old_data'       => $oldValues,
                'new_data'       => $newValues,
                'changed_fields' => array_keys($changed),

                'updated_by'     => auth()->id(),
                'changed_at'     => now(),
            ]);
        });
    }
}


// $profile->others = [
//     'occupation' => 'Engineer',
//     'hobbies' => ['Music', 'Drawing'],
//     'father_aadhaar' => 'XXXX-XXXX'
// ];
// $profile->save();
