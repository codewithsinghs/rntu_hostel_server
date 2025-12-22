<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    
    protected $fillable = ['room_number', 'building_id', 'floor_no', 'status']; // Added floor_no & status

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

