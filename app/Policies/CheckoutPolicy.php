<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Checkout\CheckoutRequest;

class CheckoutPolicy
{
    /**
     * Create a new policy instance.
     */
    // public function __construct()
    // {
    //     //
    // }

    public function viewAny(User $user)
    {
        return in_array($user->role, [
            'resident',
            'warden',
            'accountant',
            'admin'
        ]);
    }

    public function view(User $user, CheckoutRequest $checkout)
    {
        if ($user->role === 'resident') {
            return $user->resident_id === $checkout->resident_id;
        }

        return true;
    }

    public function approve(User $user, CheckoutRequest $checkout)
    {
        return in_array($user->role, [
            'warden',
            'accountant',
            'admin'
        ]);
    }
}
