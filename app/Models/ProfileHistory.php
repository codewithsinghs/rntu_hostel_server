<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ProfileHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'profile_id',
        'user_id',
        'updated_by',
        'changed_fields',
        'old_data',
        'new_data',
    ];


    protected $casts = [
        'old_data'       => 'array',
        'new_data'       => 'array',
        'changed_fields' => 'array',
    ];

    public function profile()
    {
        return $this->belongsTo(Profile::class);
    }

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }
}
