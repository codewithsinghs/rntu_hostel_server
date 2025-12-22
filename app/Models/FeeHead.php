<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FeeHead extends Model
{
    
    use HasFactory;

    protected $fillable = [
        'name',
        'created_by',
        'is_mandatory',
        'university_id',
        'is_one_time',
        'status'
    ];

    public function university()
    {
        return $this->belongsTo(University::class, 'university_id');
    }
}
