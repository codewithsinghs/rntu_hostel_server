<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\Resident;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Leave extends Model
{
    use HasFactory;

    // Table name (optional if it matches plural of class name)
    protected $table = 'leaves';

    // Mass assignable fields
    protected $fillable = [
        'resident_id',
        'application_no',
        'bed_number',
        'room_number',
        'type',
        'reason',
        'description',
        'attachment',
        'start_date',
        'end_date',
        'hod_status',
        'hod_remarks',
        'hod_action_at',
        'admin_status',
        'admin_remarks',
        'admin_action_at',
        'status',
        'approvals',
        'token',
    ];

    // Casts for dates and enums
    protected $casts = [
        'start_date'       => 'date',
        'end_date'         => 'date',
        'hod_action_at'  => 'datetime',
        'admin_action_at' => 'datetime',
        'approvals' => 'array', // Laravel will auto decode/encode JSON
    ];

    protected $appends = [
        'admin_meta',
        'hod_meta',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($leave) {
            if (empty($leave->application_no)) {
                // Example: LEAVE-202601301650-ABXQZ 
                $leave->application_no = 'LEAVE-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(5));
            }
        });
    }

    /**
     * Relationship: A leave belongs to a Resident
     */
    public function resident()
    {
        return $this->belongsTo(Resident::class, 'resident_id');
    }

    public function scopeVisibleFor($query, $user)
    {
        return $query->whereHas('resident', fn($q) => $q->visibleFor($user));
    }

    public function getFormattedApprovals(): array
    {
        $approvals = $this->approvals ?? [];

        return collect($approvals)->map(function ($a) {

            $attachmentUrl = !empty($a['attachment'])
                ? asset('storage/' . $a['attachment'])
                : null;

            return [
                'role'        => $a['role'] ?? 'N/A',
                'status'      => $a['status'] ?? 'pending',
                'badge'       => match ($a['status'] ?? '') {
                    'approved' => 'success',
                    'rejected' => 'danger',
                    default    => 'secondary',
                },
                'remarks'     => $a['remarks'] ?? null,
                'action_by'   => $a['action_by'] ?? null,
                'action_at'   => !empty($a['action_at'])
                    ? \Carbon\Carbon::parse($a['action_at'])->format('d M Y, h:i A')
                    : null,

                // frontend helpers
                'attachment' => [
                    'path' => $a['attachment'] ?? null,
                    'url'  => $attachmentUrl,
                    'name' => $attachmentUrl
                        ? basename($a['attachment'])
                        : null,
                ],

                // tooltip-ready text (🔥 IMPORTANT)
                'tooltip' => trim("
                    Role: {$a['role']}
                    Status: {$a['status']}
                    By: {$a['action_by']}
                    At: {$a['action_at']}
                    Remarks: {$a['remarks']}
                                "),
            ];
        })->values()->toArray();
    }

    public function getFormattedApprovalsAttribute(): array
    {
        if (empty($this->approvals) || !is_array($this->approvals)) {
            return [];
        }

        return collect($this->approvals)->map(function ($approval) {
            return [
                'role'       => ucfirst($approval['role'] ?? 'N/A'),
                'status'     => ucfirst($approval['status'] ?? 'N/A'),
                'remarks'    => $approval['remarks'] ?? null,
                'action_by'  => $approval['action_by'] ?? null,
                'action_at'  => !empty($approval['action_at'])
                    ? Carbon::parse($approval['action_at'])
                    ->timezone('Asia/Kolkata')
                    ->format('d M Y, h:i A')
                    : null,
            ];
        })->values()->toArray();
    }

    public function getAdminMetaAttribute(): array
    {
        $approvals = $this->approvals ?? [];

        $admin = collect($approvals)->first(function ($item) {
            return strtolower($item['role'] ?? '') === 'admin';
        });

        if (!$admin) {
            return [
                'status'      => $this->admin_status,
                'remarks'     => $this->admin_remarks,
                'action_at'   => $this->admin_action_at,
                'action_at_f' => null,
                'attachment'  => null,
            ];
        }

        return [
            // 'status'      => $admin['status'] ?? $this->admin_status,
            'status'      =>   $this->admin_status ?? $admin['status'] ?? null,
            // 'remarks'     => $admin['remarks'] ?? $this->admin_remarks,
            'remarks'     =>   $this->admin_remarks ?? $admin['remarks'] ?? null,
            // 'action_at'   => $admin['action_at'] ?? $this->admin_action_at,
            'action_at'   =>   $this->admin_action_at ?? $admin['action_at'] ?? null,
            'action_at_f' => isset($admin['action_at'])
                ? \Carbon\Carbon::parse($admin['action_at'])
                ->timezone('Asia/Kolkata')
                ->format('d M Y, h:i A')
                : null,
            'attachment'  => $admin['attachment'] ?? null,
            'action_by'   => $admin['action_by'] ?? null,
        ];
    }


    public function getHodMetaAttribute(): array
    {
        $approvals = $this->approvals ?? [];

        $hod = collect($approvals)->first(
            fn($item) =>
            strtolower($item['role'] ?? '') === 'hod'
        );

        return [
            // 'status'     => $hod['status'] ?? $this->hod_status,
            'status'     => $this->hod_status ?? $hod['status'] ?? null,
            // 'remarks'    => $hod['remarks'] ?? $this->hod_remarks,
            'remarks'    =>  $this->hod_remarks ?? $hod['remarks'] ?? null,
            // 'action_at'   => $hod['action_at'] ?? $this->hod_action_at,
            'action_at'   =>  $this->hod_action_at ?? $hod['action_at'] ?? null,
            'action_at_f' => isset($hod['action_at'])
                ? \Carbon\Carbon::parse($hod['action_at'])
                ->timezone('Asia/Kolkata')
                ->format('d M Y, h:i A')
                : null,
            'attachment' => $hod['attachment'] ?? null,
            'action_by'   => $hod['action_by'] ?? null,
        ];
    }

    public function getApprovalMeta(string $role): array
    {
        $role = strtolower($role);

        $approval = collect($this->approvals ?? [])
            ->reverse() // latest action wins
            ->first(function ($item) use ($role) {
                return strtolower($item['role'] ?? '') === $role;
            });

        if (!$approval) {
            return [
                'status'     => null,
                'remarks'    => null,
                'action_at'  => null,
                'action_at_f' => null,
                'attachment' => null,
                'action_by'  => null,
            ];
        }

        return [
            'status'      => $approval['status'] ?? null,
            'remarks'     => $approval['remarks'] ?? null,
            'action_at'   => $approval['action_at'] ?? null,
            'action_at_f' => isset($approval['action_at'])
                ? \Carbon\Carbon::parse($approval['action_at'])
                ->timezone('Asia/Kolkata')
                ->format('d M Y, h:i A')
                : null,
            'attachment'  => $approval['attachment'] ?? null,
            'action_by'   => $approval['action_by'] ?? null,
        ];
    }
}


// ✅ BEST PRACTICE (Future-proof)

// When saving approval (you already almost did it right):

// $approvals[] = [
//     'role'       => strtolower($config['roles'][0]), // 🔥 force lowercase
//     'status'     => $status,
//     'remarks'    => $validated['remarks'] ?? null,
//     'attachment' => $attachmentPath, // ALWAYS set (even null)
//     'action_by'  => $user->name,
//     'action_at'  => now()->toDateTimeString(),
// ];