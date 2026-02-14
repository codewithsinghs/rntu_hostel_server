<?php

namespace App\Enums;

enum BillingStatus: string
{
    case ACTIVE = 'active';
    case BILLING_LOCKED = 'billing_locked';
    case COMPLETED = 'completed';
    case STOPPED = 'stopped';
}
