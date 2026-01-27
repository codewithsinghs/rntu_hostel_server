<?php

namespace App\Transformers;

use App\Models\Leave;
use Carbon\Carbon;

class LeaveTransformer
{
    public static function transform(Leave $leave): array
    {
        return [
            'id' => $leave->id,
            'type' => ucfirst($leave->type),
            'status' => ucfirst($leave->status),

            'from_date' => self::formatDate($leave->from_date),
            'to_date' => self::formatDate($leave->to_date),

            'applied_at' => self::formatDateTime($leave->created_at),

            'resident' => [
                'name' => $leave->resident->name ?? null,
                'scholar_no' => $leave->resident->scholar_no ?? null,
                'email' => $leave->resident->user->email ?? null,
            ],
        ];
    }

    private static function formatDate($date): ?string
    {
        return $date
            ? Carbon::parse($date)->timezone('Asia/Kolkata')->format('d M Y')
            : null;
    }

    private static function formatDateTime($dateTime): ?string
    {
        return $dateTime
            ? Carbon::parse($dateTime)
                ->timezone('Asia/Kolkata')
                ->format('d M Y, h:i A') . ' IST'
            : null;
    }
}
