<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Scopes\ResidentVisibilityScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Resident extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'gender',
        'scholar_no',
        'number',
        'parent_no',
        'guardian_no',
        'fathers_name',
        'mothers_name',
        'user_id',
        'bed_id',
        'check_in_date',
        'check_out_date',
        'status',
        'guest_id',
        'created_by',
        'checkin_date',
        'checkout_date'
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
        'user_id',
        'bed_id',
        'status',
        'guest_id',
        'created_by'
    ];

    protected $casts = [
        'check_in_date'  => 'datetime',
        'check_out_date' => 'datetime',
        'checkin_date'   => 'datetime',
        'checkout_date'  => 'datetime',
    ];



    protected static function booted()
    {
        static::addGlobalScope(new ResidentVisibilityScope);
    }
    //     Bypass Scope (Only When Needed)

    // For super admin reports / exports:

    // Resident::withoutGlobalScope(ResidentVisibilityScope::class)->get();


    //     protected function serializeDate(\DateTimeInterface $date)
    // {
    //     return $date->setTimezone(new \DateTimeZone(config('app.timezone')))
    //                 ->format('Y-m-d\TH:i:sP');
    // }

    protected function serializeDate(\DateTimeInterface $date)
    {
        return $date->setTimezone(new \DateTimeZone(config('app.timezone')))
            ->format('Y-m-d\TH:i');
    }

    public function getCheckInDateAttribute($value)
    {
        return $value
            ? \Carbon\Carbon::parse($value)->timezone(config('app.timezone'))->format('Y-m-d\TH:i')
            : null;
    }


    // Relationship with Users table
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function invoices()
    {
        return $this->hasMany(\App\Models\Invoice::class, 'resident_id');
    }

    public function subscription()
    {
        return $this->hasOne(\App\Models\Subscription::class, 'resident_id');
    }

    public function bed()
    {
        return $this->belongsTo(Bed::class);
    }

    /**
     * Get the Room through Bed
     */
    public function room()
    {
        return $this->bed?->room();
    }

    /**
     * Get Building through Bed -> Room
     */
    public function hostel()
    {
        return $this->bed?->room?->building();
    }

    /**
     * Get University through Bed -> Room -> Building
     */
    public function university()
    {
        return $this->bed?->room?->building?->university();
    }

    public function getUniversity(): ?University
    {
        return $this->bed?->room?->building?->university;
    }

    public function profileHistories()
    {
        return $this->hasMany(ProfileHistory::class, 'resident_id');
    }

    public function getHostelInfo()
    {
        $bed = $this->bed()->select('id', 'bed_number', 'room_id')->first();
        $room = $bed?->room()->select('id', 'room_number', 'building_id', 'floor_no')->first();
        $building = $room?->building()->select('id', 'name', 'university_id', 'building_code', 'floors', 'gender')->first();
        $university = $building?->university()->select('id', 'name')->first();

        // return [
        //     'university' => $university ? [
        //         'id' => $university->id,
        //         'name' => $university->name,
        //     ] : null,

        //     'building' => $building ? [
        //         'id' => $building->id,
        //         'name' => $building->name,
        //     ] : null,

        //     'room' => $room ? [
        //         'id' => $room->id,
        //         'number' => $room->room_number,
        //     ] : null,

        //     'bed' => $bed ? [
        //         'id' => $bed->id,
        //         'number' => $bed->bed_number,
        //     ] : null,
        // ];
        return [
            'university' => $university?->name,
            'building'   => $building?->name,
            'floor'   => $room?->floor_no,
            // 'floor'      => $room?->floor_no ? $this->ordinal($room->floor_no) : null,
            'room'       => $room?->room_number,
            'bed'        => $bed?->bed_number,
        ];
    }

    private function ordinal($number)
    {
        $ends = ['th', 'st', 'nd', 'rd', 'th', 'th', 'th', 'th', 'th', 'th'];

        if ((($number % 100) >= 11) && (($number % 100) <= 13)) {
            return $number . 'th';
        }

        return $number . $ends[$number % 10];
    }



    public function getFullInfo()
    {
        // Load all related data
        $this->load([
            'user:id,name,email',
            // 'profile',
            'bed:id,bed_number,room_id',
            'bed.room:id,room_number,building_id,floor_no',
            'bed.room.building:id,name,university_id',
            'bed.room.building.university:id,name',
            // 'documents',   // optional if you have documents table
            // 'address'      // optional if you have resident address
        ]);

        // Bed, Room, Building, University
        $bed      = $this->bed;
        $room     = $bed?->room;
        $building = $room?->building;
        $univ     = $building?->university;



        return [
            'resident_id'   => $this->id,
            'name'          => $this->user?->name ?? 'N/A',
            'email'         => $this->user?->email ?? 'N/A',
            // 'profile_image' => $this->user?->profile_image ?? null,
            // 'gender'        => $this->profile?->gender ?? 'N/A',
            // 'dob'           => $this->profile?->dob ?? 'N/A',
            // 'contact'       => $this->profile?->contact ?? 'N/A',

            // Address if exists
            // 'address'       => $this->address?->full_address ?? 'N/A',

            // Hostel info
            'university'    => $univ?->name ?? 'N/A',
            'hostel'      => $building?->name ?? 'N/A',
            'floor'         => $room?->floor_no ?? 'N/A',
            'room_number'   => $room?->room_number ?? 'N/A',
            'bed_number'    => $bed?->bed_number ?? 'N/A',

            // 'profile' => $this->profile,

            // Documents if exists
            // 'documents'     => $this->documents->map(fn($d) => [
            //     'id'   => $d->id,
            //     'name' => $d->file_name,
            //     'url'  => $d->file_url,
            // ])->all(),

            //  'history' => $this->profileHistories()
            //             ->orderBy('changed_at', 'desc')
            //             ->get(),
        ];
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class, 'resident_id');
    }

    public function checkouts()
    {
        return $this->hasMany(Checkout::class);
    }
    public function accessories()
    {
        return $this->hasMany(StudentAccessory::class);
    }

    public function guest()
    {
        return $this->belongsTo(Guest::class, 'guest_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'resident_id');
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculty_id');
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    // Additioonal
    public function buildProfileData()
    {
        $source = $this->user ?? $this->guest ?? null;

        return [
            'resident_id' => $this->id,
            'full_name'   => $source?->name ?? $this->name ?? null,

            'email'       => $source?->email ?? null,
            'phone'       => $source?->phone ?? null,

            'dob'         => $this->dob ?? null,
            'gender'      => $this->gender ?? null,

            'father_name' => $this->father_name ?? null,
            'mother_name' => $this->mother_name ?? null,
            'address'     => $this->address ?? null,

            'aadhaar'     => $this->aadhaar ?? null,
            'category'    => $this->category ?? null,

            'other_details' => json_encode([
                'admission_no' => $this->admission_no ?? null,
                'enrollment_no' => $this->enrollment_no ?? null,
                'course'       => $this->course ?? null,
                'created_from' => $source instanceof \App\Models\User ?  'user' : 'guest'
            ])
        ];
    }

    public function syncProfile()
    {
        $data = $this->buildProfileData();

        return $this->profile()->updateOrCreate(
            ['resident_id' => $this->id],
            $data
        );
    }

    // public function scopeForUniversity($query, $universityId)
    // {
    //     return $query->where('university_id', $universityId);
    // }

    // public function scopeVisibleFor($query, $user)
    // {
    //     if (!$user) {
    //         // safety fallback (should never happen if sanctum is applied)
    //         return $query->whereRaw('1 = 0');
    //     }

    //     // Roles that can see everything
    //     if ($user->hasAnyRole(['SUPER_ADMIN', 'SYSTEM_ADMIN'])) {
    //         return $query;
    //     }

    //     // University-based restriction (ONLY reliable filter)
    //     return $query->where('university_id', $user->university_id);
    // }

    // Final working till 22012026
    // public function scopeVisibleFor($query, $user)
    // {
    //     if (!$user) {
    //         return $query->whereRaw('1 = 0');
    //     }

    //     // Roles with full access
    //     if ($user->hasAnyRole(['SUPER_ADMIN', 'SYSTEM_ADMIN'])) {
    //         return $query;
    //     }

    //     // If user itself has no university → no data
    //     if (empty($user->university_id)) {
    //         return $query->whereRaw('1 = 0');
    //     }

    //     return $query->where(function ($q) use ($user) {
    //         $q->whereHas('user', function ($uq) use ($user) {
    //             $uq->where('university_id', $user->university_id);
    //         });
    //     });
    // }

    public function scopeVisibleFor($query, $user)
    {
        //  return $query->withoutGlobalScopes()
        //          ->where(/* same logic */);
        if (! $user) {
            return $query->whereRaw('1 = 0');
        }

        /* =====================================
     | SUPER / SYSTEM ADMIN → FULL ACCESS
     ===================================== */
        if ($user->hasAnyRole(['SUPER_ADMIN', 'SYSTEM_ADMIN'])) {
            return $query;
        }

        /* =====================================
     | NO UNIVERSITY → NO DATA
     ===================================== */
        if (empty($user->university_id)) {
            return $query->whereRaw('1 = 0');
        }

        /* =====================================
     | WARDEN → UNIVERSITY + BUILDING
     ===================================== */
        if (
            $user->hasRole('WARDEN') &&
            ! empty($user->building_id)
        ) {
            return $query
                ->whereHas('user', function ($uq) use ($user) {
                    $uq->where('university_id', $user->university_id);
                })
                ->whereIn('building_id', (array) $user->building_id);
        }

        /* =====================================
     | UNIVERSITY ADMIN → UNIVERSITY ONLY
     ===================================== */
        return $query->whereHas('user', function ($uq) use ($user) {
            $uq->where('university_id', $user->university_id);
        });
    }


    public function getAcademicFromUser(): array
    {
        $user = $this->user;

        if (!$user || !$user->university_id) {
            return [
                'university' => null,
                'faculty'    => null,
                'department' => null,
                'course'     => null,
            ];
        }

        // Safe access to relations
        $university = $user->university;  // Assuming User has `university()` relation
        $faculty    = $user->faculty;     // Assuming User has `faculty()` relation
        $department = $user->department;  // Assuming User has `department()` relation
        $course     = $user->course;      // Assuming User has `course()` relation

        return [
            'university' => $university ? [
                'id'   => $university->id,
                'name' => $university->name,
            ] : null,

            'faculty' => $faculty ? [
                'id'   => $faculty->id,
                'name' => $faculty->name,
            ] : null,

            'department' => $department ? [
                'id'   => $department->id,
                'name' => $department->name,
            ] : null,

            'course' => $course ? [
                'id'   => $course->id,
                'name' => $course->name,
            ] : null,
        ];
    }


    public function getAcademicFromUserHierarchy(): array
    {
        $user = $this->user;

        if (!$user || !$user->university_id) {
            return [
                'university' => null,
                'faculty'    => null,
                'department' => null,
                'course'     => null,
            ];
        }

        // Load university
        $university = \App\Models\University::find($user->university_id);

        // Load faculty belonging to this university (optional: pick first or assigned)
        $faculty = \App\Models\Faculty::where('university_id', $user->university_id)
            ->first();

        // Load department belonging to this faculty
        $department = $faculty
            ? \App\Models\Department::where('faculty_id', $faculty->id)->first()
            : null;

        // Load course belonging to this department
        $course = $department
            ? \App\Models\Course::where('department_id', $department->id)->first()
            : null;

        return [
            'university' => $university ? ['id' => $university->id, 'name' => $university->name] : null,
            'faculty'    => $faculty    ? ['id' => $faculty->id, 'name' => $faculty->name]     : null,
            'department' => $department ? ['id' => $department->id, 'name' => $department->name] : null,
            'course'     => $course     ? ['id' => $course->id, 'name' => $course->name]         : null,
        ];
    }


    public function getAcademicInfo(): array
    {
        $user    = $this->user;
        // Log::info('user' . json_encode($user));
        $profile = $this->profile;
        // Log::info('profile' . json_encode($profile));
        $courseName = $profile?->course;
        // Log::info('courseName' . json_encode($courseName));
        // Step 1: Validate base data
        // Base validation
        if (!$user || !$user->university_id || empty($courseName)) {
            return [
                'university' => null,
                'faculty'    => null,
                'department' => null,
                'course'     => $courseName ? ['name' => $courseName] : null,
            ];
        }

        // Log::info('courseName' . json_encode($courseName));

        try {
            /**
             * 1️⃣ University (SOURCE OF TRUTH)
             */
            $university = University::find($user->university_id);

            /**
             * 2️⃣ Course (restricted to this university)
             */
            $course = Course::where('name', $courseName)
                ->whereHas('department.faculty', function ($q) use ($user) {
                    $q->where('university_id', $user->university_id);
                })
                ->with('department.faculty')
                ->first();

            // Course not found under this university
            if (!$course) {
                return [
                    'university' => $university ? [
                        'id'   => $university->id,
                        'name' => $university->name,
                    ] : null,
                    'faculty'    => null,
                    'department' => null,
                    'course'     => ['name' => $courseName],
                ];
            }
            $department = $course->department;
            $faculty    = $department?->faculty;

            return [
                // 'university' => ['id'   => $university->id,'name' => $university->name,],

                // 'faculty' => $faculty ? ['id'   => $faculty->id,'name' => $faculty->name,] : null,

                // 'department' => $department ? [ 'id'   => $department->id,'name' => $department->name,] : null,

                // 'course' => ['id'   => $course->id,'name' => $course->name,],
                'university' => $university->name ?? null,
                'faculty'    => $faculty->name ?? null,
                'department' => $department->name ?? null,
                'course'     => $course->name ?? null,
            ];
        } catch (\Throwable $e) {

            Log::error('Academic resolution failed (profile based)', [
                'resident_id' => $this->id,
                'user_id'     => $user->id ?? null,
                'university'  => $user->university_id ?? null,
                'course'      => $courseName,
                'exception'   => $e->getMessage(),
            ]);

            return [
                'university' => null,
                'faculty'    => null,
                'department' => null,
                'course'     => ['name' => $courseName],
            ];
        }
    }
}
