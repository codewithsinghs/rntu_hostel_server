<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; // ✅ this is important
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class Guest extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'faculty_id',
        'department_id',
        'course_id',
        'gender',
        'scholar_no',
        'fathers_name',
        'mothers_name',
        'local_guardian_name',
        'emergency_no',
        'number',
        'parent_no',
        'guardian_no',
        'room_preference',
        'fee_waiver',
        'bihar_credit_card',
        'tnsd',
        'remarks',
        'status',
        'months',
        'attachment_path',
        'days',
        'admin_remarks',
        'token',
        'token_expiry',
    ];

    protected $hidden = [
        'updated_at',
        'remember_token',
        'token',
        'token_expiry',
        // add other sensitive or recursive fields
    ];


    // public function accessory()
    // {
    //     return $this->belongsToMany(Accessory::class, 'guest_accessory', 'guest_id', 'item_id');
    // }
    // public function accessories()
    // {
    //     return $this->belongsToMany(Accessory::class, 'guest_accessory', 'guest_id', 'accessory_head_id','id','accessory_head_id')
    //         ->withPivot(['price', 'total_amount', 'from_date', 'to_date'])
    //         ->with('accessoryHead');
    // }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'guest_id');
    }

    // public function invoiceItems()
    // {
    //     return $this->hasManyThrough(InvoiceItem::class, Invoice::class);
    // }

    public function invoiceItems()
    {
        return $this->hasManyThrough(
            InvoiceItem::class,
            Invoice::class,
            'guest_id',   // FK on invoices table
            'invoice_id', // FK on invoice_items table
            'id',         // Local key on guests table
            'id'          // Local key on invoices table
        );
    }

    public function accessories()
    {
        return $this->invoiceItems()
            ->where('item_type', 'accessory')
            ->with('accessory.accessoryHead');
    }

    public function feeException()
    {
        return $this->hasOne(FeeException::class);
    }

    public function faculty()
    {
        return $this->belongsTo(Faculty::class, 'faculty_id');
    }
    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}
