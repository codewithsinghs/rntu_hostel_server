<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResidentSubscription extends Model
{
    use HasFactory;

    protected $table = 'resident_subscriptions';

    protected $fillable = [
        'resident_id',
        'invoice_id',
        'invoice_item_id',
        'service_code',
        'service_type',
        'service_name',
        'unit_price',
        'quantity',
        'billing_type',
        'billing_cycle',
        'start_date',
        'end_date',
        'next_billing_date',
        'status',
        'remarks',
        'last_billed_at',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'last_billed_at' => 'datetime',
        'next_billing_date' => 'date',
    ];

    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }
}
