<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'guest_id',
        'resident_id',
        'type',
        'invoice_number',
        'invoice_date',
        'billing_upto',
        'due_date',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'status', // pending, unpaid, partial, paid, cancelled
        'remarks'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'created_by',
        // 'type',
        'invoice_date',
        'billing_upto',
        'due_date',
        // 'total_amount',
        // 'paid_amount',
        // 'remaining_amount',
        'remarks',
    ];

    protected $casts = [
        'paid_amount' => 'float',
        'total_amount' => 'float',
        'remaining_amount' => 'float',
        'remarks' => 'array',
    ];

    // Relationships
    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    public function items()
    {
        // return $this->hasMany(InvoiceItem::class);
        return $this->hasMany(
            \App\Models\InvoiceItem::class,
            'invoice_id',   // FK
            'id'            // PK
        );
    }

    public function recomputeStatus(): void
    {
        $this->remaining_amount = max(
            0,
            $this->total_amount - $this->paid_amount
        );

        $this->status = match (true) {
            $this->paid_amount <= 0 => 'unpaid',
            $this->paid_amount < $this->total_amount => 'partial',
            default => 'paid',
        };
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
    public function resident()
    {
        return $this->belongsTo(Resident::class);
    }


    public static function generateInvoiceNumber($type = 'G')
    {
        $prefix = 'INV';
        // $year = date('Y');
        // $date = now()->format('ymd');
        $date = now()->format('ym');

        $count = self::count();
        $seq = $count + 1;
        $seqStr = strval($seq);

        // Pad to minimum 4 digits
        if (strlen($seqStr) < 4) {
            $seqStr = str_pad($seqStr, 4, '0', STR_PAD_LEFT);
        }

        $char = chr(rand(65, 90));

        $invoice_number = "{$prefix}-{$type}-{$date}-{$char}{$seqStr}";
        // return [
        //     'invoice_number' => $invoice_number,
        // ];
        return  $invoice_number;
    }

    // public function billable()
    // {
    //     return $this->morphTo(); // Student, Employee
    // }

    public function orders()
    {
        return $this->belongsToMany(Order::class)
            ->withPivot('amount_paid', 'paid_at')
            ->withTimestamps();
    }

    public function fee()
    {
        return $this->belongsTo(Fee::class, 'fee_head_id');
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }


    public function studentAccessory()
    {
        return $this->belongsTo(StudentAccessory::class, 'student_accessory_id');
    }

    // Access accessory head name through StudentAccessory → Accessory → AccessoryHead
    public function accessoryHead()
    {
        return $this->hasOneThrough(
            AccessoryHead::class,
            Accessory::class,
            'accessory_head_id',  // Foreign key on Accessory table
            'id',                 // Local key on AccessoryHead table
            'student_accessory_id', // Local key on Invoice
            'id'                  // Foreign key on Accessory
        );
    }

    // public function syncPaymentStatusFromTransactions()
    // {
    //     $paid = Transaction::where('invoice_id', $this->id)->where('status', 'Completed')->sum('amount');
    //     $remaining = $this->total_amount - $paid;

    //     $this->update([
    //         'paid_amount' => $paid,
    //         'remaining_amount' => $remaining,
    //         'status' => $remaining <= 0 ? 'paid' : ($paid > 0 ? 'partial' : 'unpaid'),
    //     ]);
    // }


    // 22092025
    // App\Models\Invoice.php

    // public function transactions()
    // {
    //     return $this->hasMany(Transaction::class);
    // }

    // public function syncPaymentStatus()
    // {
    //     $paid = $this->transactions()->where('status', 'Completed')->sum('amount');
    //     $remaining = $this->total_amount - $paid;

    //     $this->update([
    //         'paid_amount' => $paid,
    //         'remaining_amount' => $remaining,
    //         'status' => $remaining <= 0 ? 'paid' : ($paid > 0 ? 'partial' : 'unpaid'),
    //     ]);
    // }

    // public function annotateItemsWithPayment(string $orderNumber)
    // {
    //     foreach ($this->items as $item) {
    //         $remarks = $item->remarks ?? [];
    //         $remarks['payment'] = 'Settled via order ' . $orderNumber . ' on ' . now()->toDateString();
    //         $item->update(['remarks' => $remarks]);
    //     }
    // }
}
