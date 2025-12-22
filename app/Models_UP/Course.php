<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $table = 'courses';

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
