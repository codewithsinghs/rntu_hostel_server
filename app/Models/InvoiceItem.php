<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InvoiceItem extends Model
{
    protected $fillable = [
        'invoice_id',
        'item_type', // fee | accessory | other
        'description',
        'price',
        'total_amount',
        'from_date',
        'to_date',
        'month',
        'item_id',
        'status',
        'item_id',
        'item_type',
    ];

    protected $casts = [
        'from_date' => 'date',
        'to_date'   => 'date',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'created_by',
        // 'from_date',
        // 'to_date',
        // 'month',
        // 'item_id', 'item_type',
    ];

    // Relationships
    // public function invoice()
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function fee()
    {
        return $this->belongsTo(Fee::class, 'fee_id');
    }

    public function accessory()
    {
        return $this->belongsTo(Accessory::class, 'item_id');
    }
}
