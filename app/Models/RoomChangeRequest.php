<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomChangeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'resident_id',
        'reason',
        'preference',
        'action',
        'remark',
        'resident_agree',
        'created_by', 
        'token',
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
    public function resident()
    {
        return $this->belongsTo(Resident::class, 'resident_id');
    }

    public function bedAssignmentHistory()
    {
        return $this->belongsTo(BedAssignmentHistory::class);
    }

    public function Bed()
    {
        return $this->belongsTo(Bed::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
