<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Faculty extends Model
{
    use HasFactory;
    protected $table = 'faculties';

    protected $fillable = [
        'university_id',
        'name',
        'code',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    // protected $casts = [
    //     'status' => 'boolean'
    // ];

    /* =======================================
     * GLOBAL UNIVERSITY SCOPE (OPTIONAL)
     * ======================================= */
    // protected static function booted()
    // {
    //     static::addGlobalScope('university', function ($query) {
    //         if (auth()->check() && auth()->user()->university_id) {
    //             $query->where('university_id', auth()->user()->university_id);
    //         }
    //     });
    // }

    // protected static function booted()
    // {
    //     static::creating(function ($faculty) {

    //         if (! $faculty->code) {

    //             $prefix = strtoupper(
    //                 substr(
    //                     preg_replace('/[^A-Za-z]/', '', $faculty->name),
    //                     0,
    //                     3
    //                 )
    //             );

    //             $lastId = self::max('id') + 1;

    //             $faculty->code = $prefix . str_pad($lastId, 4, '0', STR_PAD_LEFT);
    //         }
    //     });
    // }


    // public function university()
    // {
    //     return $this->belongsTo(University::class);
    // }


    protected static function booted()
    {
        static::creating(function ($faculty) {

            // If code is already provided, don't override
            if (! empty($faculty->code)) {
                return;
            }

            // Generate prefix from name
            $prefix = strtoupper(
                substr(
                    preg_replace('/[^A-Za-z]/', '', $faculty->name),
                    0,
                    3
                )
            );

            // Fallback if name is too short
            $prefix = $prefix ?: 'FAC';

            // Generate unique numeric suffix
            $lastId = self::max('id') + 1;

            $faculty->code = $prefix . str_pad($lastId, 4, '0', STR_PAD_LEFT);
        });
    }

    // Relations
    public function university()
    {
        return $this->belongsTo(University::class);
    }




    public function guests()
    {
        return $this->hasMany(Guest::class, 'faculty_id');
    }

    // public function university()
    // {
    //     return $this->belongsTo(University::class, 'university_id');
    // }
    public function departments()
    {
        return $this->hasMany(Department::class, 'faculty_id');
    }
}
