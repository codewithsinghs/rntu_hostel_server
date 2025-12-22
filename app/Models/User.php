<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;



class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;
    

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'email', 'gender', 'password', 'university_id', 'building_id', 'department_id', 'status', 'created_by'];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'token',
        'token_expiry',
        'created_at',
        'updated_at'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'building_id' => 'array',
    ];

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
    
    public function staff()
    {
        return $this->belongsToMany(Role::class, 'model_has_roles', 'model_id', 'role_id')
            ->wherePivot('model_type', User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function resident()
    {
        // return $this->belongsTo(Resident::class);
        return $this->hasOne(Resident::class, 'user_id', 'id');
    }

    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id');
    }
    public function assignedFines()
    {
        return $this->hasMany(Fine::class, 'assigned_by_admin_id');
    }

    public function approvedFines()
    {
        return $this->hasMany(Fine::class, 'approved_by_accountant_id');
    }

    public function university()
    {
        return $this->belongsTo(University::class, 'university_id');
    }
}
