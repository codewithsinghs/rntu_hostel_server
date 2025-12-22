<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomChangeMessage extends Model
{
    protected $fillable = [
        'room_change_request_id',
        'sender',
        'message',
        'created_by',
    ];
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

    public function roomChangeRequest()
    {
        return $this->belongsTo(RoomChangeRequest::class);
    }
}
