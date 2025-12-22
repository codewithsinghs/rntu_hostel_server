<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BedAssignmentHistory extends Model
{
    use HasFactory;
    protected $table = 'bed_assignment_histories';

    protected $fillable = [
        'bed_id',
        'resident_id',
        'assigned_at',
        'discharged_at',
        'notes',
    ];
}
