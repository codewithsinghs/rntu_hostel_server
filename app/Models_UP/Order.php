<?php

namespace App\Models;

use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Helpers\Helper;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference_id',
        'user_id',
        'resident_id',
        'guest_id',
        'order_number',
        'invoice_number',
        'amount',
        'payment_id',
        'status',
        'message',
        'payment_method',
        'purpose',
        'origin_url',
        'redirect_url',
        'callback_route',
        'metadata',
    ];

    // protected $casts = [
    //     'response_data' => 'array',
    // ];

    protected $hidden = [
        'id',
        'user_id',
        'resident_id',
        'metadata',
        'reference_id',
        'payment_id',
        'payment_method',
        'callback_route',
        'metadata',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'metadata' => 'array',
        'invoice_number' => 'array',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // protected static function booted()
    // {
    //     static::creating(function ($order) {
    //         // $lastSeq = Order::max('sequence') ?? 0;
    //         $count = Order::count();
    //         $newSeq = $count + 1;
    //         $order->order_id = self::generateOrderId($newSeq);
    //     });
    // }

    // public static function generateOrderId($seq)
    // {
    //     $seqStr = strval($seq);

    //     // Pad to minimum 4 digits
    //     if (strlen($seqStr) < 4) {
    //         $seqStr = str_pad($seqStr, 4, '0', STR_PAD_LEFT);
    //     }

    //     // Replace each '0' with a random uppercase letter
    //     $seqStr = preg_replace_callback('/0/', function () {
    //         return chr(rand(65, 90)); // A–Z
    //     }, $seqStr);

    //     $prefix = 'ORD';
    //     $date = now()->format('ymd'); // e.g. 250826

    //     return "{$prefix}-{$date}-{$seqStr}";
    // }

    public function guest()
    {
        return $this->belongsTo(Guest::class, 'guest_id', 'id');
    }

    // Relation with Transaction
    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'order_id');
    }



    // public function transactions()
    // {
    //     return $this->hasMany(Transaction::class, 'id', 'order_id');
    // }


    public $userTypeForOrderId = 'guest'; // default fallback


    // 🔹 Automatically generate IDs on model creation
    // protected static function boot()
    // {
    //     parent::boot();

    //     // $user = Helper::get_auth_admin_user($request);
    //     // $userType = $user ? 'resident' : 'guest';
    //     // Log::info('userType: ' . json_encode($userType));

    //     static::creating(function ($order) {
    //         // Generate UUID for reference_id
    //         $order->reference_id = Str::uuid()->toString();

    //         // Generate custom order_id
    //         // $order->order_id = self::generateOrderId();
    //     });
    // }



    // 🔹 Custom order ID generator
    // public static function generateOrderId($userType = null)
    // {
    //     $count = self::count();
    //     $seq = $count + 1;
    //     $seqStr = strval($seq);

    //     // Pad to minimum 4 digits
    //     if (strlen($seqStr) < 4) {
    //         $seqStr = str_pad($seqStr, 4, '0', STR_PAD_LEFT);
    //     }

    //     // Replace each '0' with a random uppercase letter
    //     $seqStr = preg_replace_callback('/0/', function () {
    //         return chr(rand(65, 90)); // A–Z
    //     }, $seqStr);

    //     // $prefix = 'G-ORD';
    //     // Dynamic prefix based on user type
    //     $prefix = $userType === 'resident' ? 'R-ORD' : 'G-ORD' ;
    //     $date = now()->format('ymd'); // e.g. 250826

    //     return "{$prefix}{$date}{$seqStr}";
    // }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            // Generate UUID for reference_id
            $order->reference_id = Str::uuid()->toString();
        });
    }
    // 🔹 Custom order ID generator
    public static function generateOrderId($userType = null)
    {
        $count = self::count();
        $seq = $count + 1;
        $seqStr = strval($seq);

        // Pad to minimum 4 digits
        if (strlen($seqStr) < 4) {
            $seqStr = str_pad($seqStr, 4, '0', STR_PAD_LEFT);
        }

        // Replace each '0' with a random uppercase letter
        $seqStr = preg_replace_callback('/0/', function () {
            return chr(rand(65, 90)); // A–Z
        }, $seqStr);

        // Dynamic prefix based on user type
        $prefix = $userType === 'resident' ? 'R-ORD' : 'G-ORD';
        $date = now()->format('ymd'); // e.g. 250826

        return "{$prefix}{$date}{$seqStr}";
    }







    public function resident()
    {
        // return $this->belongsTo(Resident::class, 'resident_id');
        //  return $this->belongsTo(Resident::class, 'user_id'); 
        // since user_id is foreign key to residents table
        return $this->hasOne(\App\Models\Resident::class, 'user_id', 'user_id');
    }

    // public function invoices()
    // {
    //     return $this->belongsTo(Invoice::class, 'invoice_number', 'invoice_number');
    // }

    public static function prepareInvoiceNumber($input): array
    {
        // return json_encode(is_array($input) ? $input : [$input]); // returns json
        return is_array($input) ? $input : [$input];
    }



    public function invoices()
    {
        return $this->belongsToMany(Invoice::class)
            ->withPivot('amount_paid', 'paid_at')
            ->withTimestamps();
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }



    // 22092025
    // App\Models\Order.php

    // public function getInvoiceNumbers(): array
    // {
    //     try {
    //         $decoded = json_decode($this->invoice_number, true);
    //         if (is_array($decoded)) {
    //             return $decoded;
    //         }
    //     } catch (\Exception $e) {
    //         // fallback handled below
    //     }

    //     return [$this->invoice_number];
    // }

    // public function invoices()
    // {
    //     return Invoice::whereIn('invoice_number', $this->getInvoiceNumbers())->get();
    // }

    // public function completedTransactions()
    // {
    //     return $this->transactions()->where('status', 'Completed')->get();
    // }
}
