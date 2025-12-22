<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $table = 'departments';

    protected $fillable = [
        'faculty_id',
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
    
    public function faculty()
    {
         return $this->belongsTo(Faculty::class);
        // return $this->belongsTo(Faculty::class, 'faculty_id');
    }

        public function courses()
    {
        return $this->hasMany(Course::class, 'department_id');
    }

    public function users()
    {
        return $this->hasMany(User::class, 'department_id');
    }

    

}
