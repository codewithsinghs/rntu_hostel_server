<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileHistory extends Model
{
    use HasFactory;

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
