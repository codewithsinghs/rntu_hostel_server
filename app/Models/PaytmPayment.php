<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaytmPayment extends Model
{
    use HasFactory;

    protected $fillable = [
       'order_id',
        'status',
        'bank_name',
        'payment_mode',
        'txn_amount',
        'currency',
        'response_code',
        'response_message',
        'bank_txn_id',
        'm_id',
        'response_payload',
    ];

    protected $hidden = [
        'updated_at',
        'created_at',

        // add other sensitive or recursive fields
    ];
    

    // protected $casts = [
    //     'response_data' => 'array',
    // ];

    // public function user(): BelongsTo
    // {
    //     return $this->belongsTo(User::class);
    // }
}
