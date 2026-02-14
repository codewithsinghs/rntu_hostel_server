<?php

namespace App\Services\Checkout;

use DB;
use App\Models\Bed;
use App\Models\Room;
use App\Models\Resident;
use App\Models\Hostel\BedAllocation;
use App\Models\Checkout\CheckoutRequest;

class FinalExitService
{
    public static function execute(int $checkoutId)
    {
        DB::transaction(function () use ($checkoutId) {

            $checkout = CheckoutRequest::lockForUpdate()
                ->findOrFail($checkoutId);

            if ($checkout->status !== 'ready_for_exit') {
                throw new \Exception('Checkout not authorized');
            }

            /** 1️⃣ Fetch Active Bed Allocation */
            $allocation = BedAllocation::where('resident_id', $checkout->resident_id)
                ->where('status', 'active')
                ->lockForUpdate()
                ->firstOrFail();

            /** 2️⃣ Release Bed */
            Bed::lockForUpdate()
                ->where('id', $allocation->bed_id)
                ->update(['status' => 'available']);

            /** 3️⃣ Release Allocation */
            $allocation->update([
                'status' => 'released',
                'allocated_to' => now()->toDateString(),
                'released_at' => now(),
                'released_by' => auth()->id(),
            ]);

            /** 4️⃣ Update Room Status (Derived) */
            $occupiedBeds = Bed::where('room_id', $allocation->room_id)
                ->where('status', 'occupied')
                ->count();

            Room::where('id', $allocation->room_id)
                ->update([
                    'status' => $occupiedBeds > 0 ? 'available' : 'available'
                ]);

            /** 5️⃣ Update Resident Status */
            Resident::where('id', $checkout->resident_id)
                ->update([
                    'status' => 'checked_out',
                    'checked_out_at' => now(),
                ]);

            /** 6️⃣ Complete Checkout */
            $checkout->update([
                'status' => 'completed',
                'actual_exit_date' => now()->toDateString(),
            ]);
        });
    }
}


// bed_allocations

// id
// resident_id
// room_id
// bed_id

// allocated_from DATE
// allocated_to DATE NULL

// status ENUM('active','released')
// released_at DATETIME NULL
// released_by BIGINT NULL
