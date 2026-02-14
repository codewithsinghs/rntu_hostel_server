<?php


namespace App\Services\Checkout;

use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use App\Models\Checkout\CheckoutRequest;

class CheckoutService
{
    public function create(array $data, $user)
    {
        return DB::transaction(function () use ($data, $user) {

            // Prevent duplicate active request
            $exists = CheckoutRequest::where('resident_id', $data['resident_id'])
                ->whereNotIn('status', ['completed', 'cancelled'])
                ->exists();

            if ($exists) {
                throw new \Exception('Active checkout already exists.');
            }

            $checkout = CheckoutRequest::create([
                ...$data,
                'status'       => 'submitted',
                'requested_by' => $user->id,
            ]);

            // Update resident lifecycle
            $checkout->resident()->update([
                'status' => 'checkout_requested'
            ]);

            // Generate approval tasks
            app(ApprovalWorkflowService::class)
                ->generate($checkout);

            // Audit log
            AuditLog::create([
                'performed_by'  => $user->id,
                'action'        => 'checkout.requested',
                'auditable_type'=> CheckoutRequest::class,
                'auditable_id'  => $checkout->id,
            ]);

            return $checkout;
        });
    }
}
