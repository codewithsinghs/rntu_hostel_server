<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $table = 'courses';

    protected $fillable = [
        'department_id',
        'name',
        // 'code',
        'status',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];


    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // through relationship (optional shortcut)
    public function faculty()
    {
        return $this->hasOneThrough(Faculty::class, Department::class, 'id', 'id', 'department_id', 'faculty_id');
    }

}
