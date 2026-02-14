<?php

namespace App\Services\Checkout;

use DB;
use App\Models\Room;
use App\Models\Resident;
use App\Models\Room\RoomAllocation;
use App\Models\Checkout\CheckoutRequest;

class FinalExitService
{
    public static function execute(int $checkoutId)
    {
        DB::transaction(function () use ($checkoutId) {

            $checkout = CheckoutRequest::with('resident')
                ->lockForUpdate()
                ->findOrFail($checkoutId);

            if ($checkout->status !== 'ready_for_exit') {
                throw new \Exception('Checkout not authorized');
            }

            /** 1️⃣ Close Room Allocation */
            $allocation = RoomAllocation::where('resident_id', $checkout->resident_id)
                ->where('status', 'active')
                ->lockForUpdate()
                ->first();

            if (!$allocation) {
                throw new \Exception('Active room allocation not found');
            }

            $allocation->update([
                'status' => 'released',
                'allocated_to' => now()->toDateString(),
                'released_at' => now(),
                'released_by' => auth()->id(),
            ]);

            /** 2️⃣ Update Room Occupancy */
            $room = Room::lockForUpdate()->find($allocation->room_id);

            $room->decrement('current_occupancy');

            if ($room->current_occupancy < $room->capacity) {
                $room->update(['status' => 'available']);
            }

            /** 3️⃣ Update Resident Status */
            $checkout->resident->update([
                'status' => 'inactive',
                'checked_out_at' => now(),
            ]);

            /** 4️⃣ Complete Checkout */
            $checkout->update([
                'status' => 'completed',
                'actual_exit_date' => now()->toDateString(),
            ]);

            /** 5️⃣ (Optional) Revoke Access */
            // AccessControl::revoke($checkout->resident_id);

        });
    }
}


// room_allocations

// id
// resident_id
// room_id
// allocated_from DATE
// allocated_to DATE NULL
// status ENUM('active','released')
// released_at DATETIME NULL
// released_by BIGINT NULL
