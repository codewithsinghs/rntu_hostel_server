<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hod extends Model
{
    use HasFactory;

    protected $hidden = [
        'updated_at',
        'created_at',

        // add other sensitive or recursive fields
    ];

}
