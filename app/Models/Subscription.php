<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Enums\BillingStatus;

class Subscription extends Model
{
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
        'last_billed_at',
        'status',
        'remarks',
    ];

    protected $casts = [
        'start_date'        => 'date',
        'end_date'          => 'date',
        'next_billing_date' => 'date',
        'last_billed_at'    => 'date',
        'remarks'        => 'json',
    ];

    /* ===================== RELATIONS ===================== */

    public function resident(): BelongsTo
    {
        return $this->belongsTo(Resident::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function invoiceItem(): BelongsTo
    {
        return $this->belongsTo(InvoiceItem::class);
    }

    /**
     * Historical invoice items for this subscription
     */
    public function invoiceItems(): HasMany
    {
        return $this->hasMany(InvoiceItem::class, 'item_id', 'service_code')
            ->where('item_type', $this->service_type);
    }

    /* ===================== HELPERS ===================== */

    public function isActive(): bool
    {
        return $this->status === BillingStatus::ACTIVE->value;
    }

    public function lockForBilling(): void
    {
        $this->update(['status' => BillingStatus::BILLING_LOCKED->value]);
    }

    public function unlock(): void
    {
        $this->update(['status' => BillingStatus::ACTIVE->value]);
    }
}
