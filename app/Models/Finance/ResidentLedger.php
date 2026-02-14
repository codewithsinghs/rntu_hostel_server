<?php

namespace App\Models\Finance;

use App\Models\User;
use App\Models\Resident;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ResidentLedger extends Model
{
    use HasFactory;
    
    protected $table = 'resident_ledger';

    protected $fillable = [
        'resident_id',
        'source_type',
        'source_id',
        'document_no',
        'document_date',
        'description',
        'debit',
        'credit',
        'balance_after',
        'type',
        'status',
        'reference',
        'created_by',
        'approved_by',
        'approved_at',
        'narration',
    ];

     protected $casts = [
        'debit'          => 'decimal:2',
        'credit'         => 'decimal:2',
        'balance_after'  => 'decimal:2',
        'document_date'  => 'date',
        'approved_at'    => 'datetime',
    ];

    /* =====================
     | Relationships
     ===================== */

      public function resident()
    {
        return $this->belongsTo(Resident::class, 'resident_id');
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
      public function scopeForResident($query, int $residentId)
    {
        return $query->where('resident_id', $residentId);
    }

    public function scopeCredits($query)
    {
        return $query->where('credit', '>', 0);
    }

    public function scopeDebits($query)
    {
        return $query->where('debit', '>', 0);
    }

    /* =====================
     | Helpers
     ===================== */

     public function getAmountAttribute(): float
    {
        return $this->credit > 0
            ? (float) $this->credit
            : (float) $this->debit;
    }
}
