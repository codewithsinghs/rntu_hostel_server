<?php

namespace App\Models\Finance;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    use HasFactory;
    
    protected $table = 'audit_logs';

    protected $fillable = [
        'performed_by',
        'action',
        // 'model_type',
        // 'model_id',
        'auditable_type',
        'auditable_id',
        'meta',
        // 'ip_address',
        // 'user_agent',
    ];

    protected $casts = [
        'meta' => 'array',
    ];

    /* =====================
     | Relationships
     ===================== */

    public function user()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    /* =====================
     | Factory helper
     ===================== */

    public static function record(
        string $action,
        string $modelType,
        int $modelId,
        int $userId,
        array $meta = []
    ): self {
        return self::create([
            'action'       => $action,
            'auditable_type'   => $modelType,
            'auditable_id'     => $modelId,
            'performed_by' => $userId,
            'meta'         => $meta,
            // 'ip_address'   => request()->ip(),
            // 'user_agent'   => request()->userAgent(),
        ]);
    }
}
