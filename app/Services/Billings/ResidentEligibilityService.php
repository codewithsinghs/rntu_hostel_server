<?php

namespace App\Services\Billings;

use Illuminate\Support\Facades\Log;

class ResidentEligibilityService
{
    public static function isBillable($resident, $today): bool
    {
        if ($resident->status !== 'active') {
            Log::debug('[BILLING] Resident inactive', ['id' => $resident->id]);
            return false;
        }

        if ($resident->check_out_date && $resident->check_out_date->lte($today)) {
            Log::debug('[BILLING] Resident checked out', ['id' => $resident->id]);
            return false;
        }

        return true;
    }
}
