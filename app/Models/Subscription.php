<?php

namespace App\Models;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Subscription extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'guest_id',
        'resident_id',
        'fee_head_id',
        'item_type',
        'item_id',
        'next_invoice_generation',
        'fee_id',
        'price',
        'total_amount',
        'subscription_type',
        'start_date',
        'end_date',
        'status',
        'remarks',
        'created_by'
    ];

    protected $hidden = [
        'updated_at',
        'created_at',
        // add other sensitive or recursive fields
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }

    public function fee()
    {
        return $this->belongsTo(Fee::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function feeHead()
    {
        return $this->belongsTo(FeeHead::class, 'fee_head_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'subscription_id');
    }
}
