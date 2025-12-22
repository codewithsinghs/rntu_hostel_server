<?php

namespace App\Models;

use App\Models\User;
use App\Models\Order;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'txn_id',
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

    // Relationship with Resident
    public function resident() {
        return $this->belongsTo(Resident::class);
    }

    // Relationship with Accessory
    public function accessory() {
        return $this->belongsTo(Accessory::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'student_accessory_id');
    }

    public function accessoryHead()
    {
        return $this->belongsTo(AccessoryHead::class, 'accessory_head_id');
    }
    
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

}
