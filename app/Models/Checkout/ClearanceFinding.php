<?php

namespace App\Models\Checkout;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClearanceFinding extends Model
{
    use HasFactory;

    protected $table = 'clearance_findings';

    protected $fillable = [
        // 'checkout_id',
        'source_type',
        'source_id',
        'category',
        'item',
        'amount',
        'remarks',
        'status',
        'created_by',
        'approved_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /* =====================
     | Relationships
     ===================== */

    public function checkout()
    {
        return $this->belongsTo(CheckoutRequest::class, 'checkout_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /* =====================
     | Scopes
     ===================== */

    public function scopeSuggested($query)
    {
        return $query->where('status', 'suggested');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /* =====================
     | Domain helpers
     ===================== */

    public function approve(int $userId): void
    {
        $this->update([
            'status'      => 'approved',
            'approved_by' => $userId,
        ]);
    }

    public function reject(int $userId): void
    {
        $this->update([
            'status'      => 'rejected',
            'approved_by' => $userId,
        ]);
    }
}
