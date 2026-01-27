<?php

namespace App\Http\Controllers;

use App\Models\Accessory;
use Illuminate\Http\Request;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\Invoice;
use Exception;
use Carbon\Carbon;
use App\Models\Fee;



class CroneJobsController extends Controller
{
    private function apiResponse($success, $message, $data = null, $statusCode = 200, $errors = null)
    {
        $response = [
            'success' => $success,
            'message' => $message,
        ];

        $response['data'] = $data !== null ? $data : null;
        $response['errors'] = $errors !== null ? $errors : null;

        return response()->json($response, $statusCode);
    }


    // public function generateInvoiceOnSubscriptionsExpiry(Request $request)
    // {
    //     try {            
    //         $subscriptions = Subscription::select(
    //                 'resident_id',
    //                 DB::raw('MIN(id) as id'),
    //                 DB::raw('MIN(guest_id) as guest_id'),
    //                 DB::raw('DATE_ADD(MIN(end_date), INTERVAL 1 DAY) as last_end_date')
    //             )
    //             ->where('status', 'active')
    //             ->where('resident_id', 137) // For testing purpose only, remove this line in production
    //             ->where('next_invoice_generation','0')
    //             ->whereDate('end_date', '<', now())
    //             ->groupBy('resident_id')
    //             ->get();

    //         // return $this->apiResponse(true, 'Subscriptions fetched successfully.', $subscriptions);
    //         $invoices = [];
    //         foreach ($subscriptions as $sub) {
    //             $nextId = (Invoice::max('id') ?? 0) + 1;
    //             $invoiceNumber = 'INV-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
    //             $invoice = Invoice::create([
    //                 'guest_id'         => $sub->guest_id,
    //                 'resident_id'      => $sub->resident_id,
    //                 'invoice_number'   => $invoiceNumber,
    //                 'invoice_date'     => $sub->last_end_date,
    //                 'due_date'         => Carbon::parse($sub->last_end_date)->copy()->addDays(7),  // Due in 30 days
    //                 'total_amount'     => 0,
    //                 'paid_amount'      => 0,
    //                 'remaining_amount' => 0,
    //                 'status'           => 'pending', // created but not yet approved
    //             ]);
    //             $invoices[] = $invoice;
    //             $subscriptionsAllItem = Subscription::select(
    //                     'guest_id',
    //                     'resident_id',
    //                     'item_type',
    //                     'item_id',
    //                     'status'
    //                 )
    //                 ->whereDate('end_date', '<', now())
    //                 ->where('status', 'active')
    //                 ->where('resident_id',$sub->resident_id)
    //                 ->get();
    //                 //Log total items
    //                 // Log::info('Processing subscription #' . $sub->id .'Total Subscription Items: ' . count($subscriptionsAllItem) . ' for resident_id: ' . $sub->resident_id  );
                    
    //             $totalAmount = 0;
    //             foreach ($subscriptionsAllItem as $item) {
    //                 if($item->item_type=='accessory')
    //                 {
    //                     $itemDetails = Accessory::find($item->item_id);
    //                     $itemDetails->accessory_head_id;
    //                     $itemDetail = Accessory::join('accessory_heads', 'accessory.accessory_head_id', '=', 'accessory_heads.id')
    //                         ->where('accessory.accessory_head_id', $itemDetails->accessory_head_id)
    //                         ->where('accessory.is_active', 1)
    //                         ->select(
    //                             'accessory.*',
    //                             'accessory_heads.name as description'
    //                         )
    //                         ->first();
    //                     if($itemDetail!==null)
    //                     {
    //                         $price=$itemDetail->price;
    //                         $description=$itemDetail->description;
    //                         $totalAmt=($price)*3;
    //                     }
    //                 }
    //                 elseif($item->item_type=='fee')
    //                 {
    //                     $itemDetails = Fee::find($item->item_id);
    //                     $itemDetails->fee_head_id;
    //                     $itemDetail = Fee::with('feeHead')
    //                     ->where('id', $item->item_id)
    //                     ->where('is_active', 1)
    //                     ->whereHas('feeHead', function ($q) {
    //                         $q->where('is_one_time', 0);
    //                     })
    //                     ->first();

    //                     if($itemDetail!==null)
    //                     {
    //                         $price=$itemDetail->amount;
    //                         $description=$itemDetail->name;
    //                         $totalAmt=($price)*3;
    //                     }
    //                 }
    //                 else
    //                 {
    //                     $itemDetail=null;
    //                 }
    //                 // Log::info('Item Detail: ', ['itemDetail' => $itemDetail]);

    //                 if($itemDetail!==null)
    //                 {
    //                     $invoice->items()->create([
    //                         'invoice_id'   => $invoice->id,
    //                         'item_type'    => $item->item_type,
    //                         'item_id'      => $itemDetail->id,
    //                         'description'  => $description,
    //                         'price'        => $price,
    //                         'total_amount' => $totalAmt,
    //                         'from_date'    => $sub->last_end_date,
    //                         'to_date'      => Carbon::parse($sub->last_end_date)->addMonths(3),
    //                     ]);
    //                     $totalAmount += $totalAmt;
    //                 }
    //             }
    //             Invoice::where('id', $invoice->id)->update(['total_amount' => $totalAmount, 'remaining_amount' => $totalAmount]);
    //             Subscription::where('status', 'active')->where('resident_id', $sub->resident_id)->update(['next_invoice_generation' => 1]);
    //         }
    //         return $this->apiResponse(true, 'Invoices generated successfully on subscriptions expiry.', $invoices);

    //     } catch (Exception $e) {
    //         Log::error('Error in Cron Job: ' . $e->getMessage());
    //         return $this->apiResponse(false, 'Failed to generate invoices on subscriptions expiry.', null, 500, ['error' => $e->getMessage()]);
    //     }
    // }   


    function createInvoice($sub)
    {
        $nextId = (Invoice::max('id') ?? 0) + 1;
        $invoiceNumber = 'INV-' . str_pad($nextId, 5, '0', STR_PAD_LEFT);
        return Invoice::create([
            'guest_id'         => $sub->guest_id,
            'resident_id'      => $sub->resident_id,
            'invoice_number'   => $invoiceNumber,
            'invoice_date'     => $sub->last_end_date,
            // 'due_date'         => Carbon::parse($sub->last_end_date)->addDays(7),
            'due_date'         => $sub->last_end_date,
            'total_amount'     => 0,
            'paid_amount'      => 0,
            'remaining_amount' => 0,
            'status'           => 'pending',
        ]);
    }

    // public function generateInvoiceOnSubscriptionsExpiry(Request $request)
    // {
    //     DB::beginTransaction();

    //     try {

    //         /** ================= STEP 1: FETCH UNIQUE RESIDENTS ================= */
    //         $subscriptions = Subscription::select(
    //                 'resident_id',
    //                 DB::raw('MIN(id) as id'),
    //                 DB::raw('MIN(guest_id) as guest_id'),
    //                 DB::raw('DATE_ADD(MIN(end_date), INTERVAL 1 DAY) as last_end_date')
    //             )
    //             ->where('status', 'active')
    //             ->where('next_invoice_generation', 0)
    //             ->whereDate('end_date', '<', now())
    //             ->where('resident_id', 137) // testing only
    //             ->groupBy('resident_id')
    //             ->get();

    //         $invoices = [];

    //         /** ================= STEP 2: LOOP PER RESIDENT ================= */
    //         foreach ($subscriptions as $sub) {

    //             $accessoryInvoice = null;
    //             $feeInvoice = null;

    //             $accessoryTotal = 0;
    //             $feeTotal = 0;

    //             /** ===== Fetch all expired subscription items of resident ===== */
    //             $items = Subscription::where('resident_id', $sub->resident_id)
    //                 ->where('status', 'active')
    //                 ->whereDate('end_date', '<', now())
    //                 ->get();

    //             foreach ($items as $item) {

    //                 /** ================= ACCESSORY ================= */
    //                 if ($item->item_type === 'accessory') {

    //                     if (!$accessoryInvoice) {
    //                         $accessoryInvoice = $this->createInvoice($sub);
    //                     }

    //                     $accessory = Accessory::find($item->item_id);

    //                     if (!$accessory) continue;

    //                     $detail = Accessory::join('accessory_heads', 'accessory.accessory_head_id', '=', 'accessory_heads.id')
    //                         ->where('accessory.accessory_head_id', $accessory->accessory_head_id)
    //                         ->where('accessory.is_active', 1)
    //                         ->select('accessory.*', 'accessory_heads.name as description')
    //                         ->first();

    //                     if ($detail) {
    //                         $total = $detail->price * 3;

    //                         $accessoryInvoice->items()->create([
    //                             'item_type'    => 'accessory',
    //                             'item_id'      => $detail->id,
    //                             'description'  => $detail->description,
    //                             'price'        => $detail->price,
    //                             'total_amount' => $total,
    //                             'from_date'    => $sub->last_end_date,
    //                             'to_date'      => Carbon::parse($sub->last_end_date)->addMonths(3),
    //                         ]);

    //                         $accessoryTotal += $total;
    //                     }
    //                 }

    //                 /** ================= FEE ================= */
    //                 if ($item->item_type === 'fee') {

    //                     if (!$feeInvoice) {
    //                         $feeInvoice = $this->createInvoice($sub);
    //                     }

    //                     $fee = Fee::with('feeHead')
    //                         ->where('id', $item->item_id)
    //                         ->where('is_active', 1)
    //                         ->whereHas('feeHead', function ($q) {
    //                             $q->where('is_one_time', 0);
    //                         })
    //                         ->first();

    //                     if ($fee) {
    //                         $total = $fee->amount * 3;

    //                         $feeInvoice->items()->create([
    //                             'item_type'    => 'fee',
    //                             'item_id'      => $fee->id,
    //                             'description'  => $fee->name,
    //                             'price'        => $fee->amount,
    //                             'total_amount' => $total,
    //                             'from_date'    => $sub->last_end_date,
    //                             'to_date'      => Carbon::parse($sub->last_end_date)->addMonths(3),
    //                         ]);

    //                         $feeTotal += $total;
    //                     }
    //                 }
    //             }

    //             /** ================= UPDATE TOTALS ================= */
    //             if ($accessoryInvoice) {
    //                 $accessoryInvoice->update([
    //                     'total_amount'     => $accessoryTotal,
    //                     'remaining_amount' => $accessoryTotal,
    //                 ]);
    //                 $invoices[] = $accessoryInvoice;
    //             }

    //             if ($feeInvoice) {
    //                 $feeInvoice->update([
    //                     'total_amount'     => $feeTotal,
    //                     'remaining_amount' => $feeTotal,
    //                 ]);
    //                 $invoices[] = $feeInvoice;
    //             }

    //             /** ================= MARK SUBSCRIPTIONS PROCESSED ================= */
    //             Subscription::where('resident_id', $sub->resident_id)
    //                 ->where('status', 'active')
    //                 ->update(['next_invoice_generation' => 1]);
    //         }

    //         DB::commit();

    //         return $this->apiResponse(true, 'Invoices generated successfully.', $invoices);

    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         Log::error('Cron Invoice Error: ' . $e->getMessage());

    //         return $this->apiResponse(false, 'Invoice generation failed.', null, 500, [
    //             'error' => $e->getMessage()
    //         ]);
    //     }
    // }   


    public function generateInvoiceOnSubscriptionsExpiry(Request $request)
    {
        DB::beginTransaction();

        try {

            /** ================= STEP 1: FETCH UNIQUE RESIDENTS ================= */

            $subscriptions = Subscription::select(
                    'resident_id',
                    DB::raw('MIN(id) as id'),
                    DB::raw('MIN(guest_id) as guest_id'),
                    DB::raw('DATE_ADD(MIN(end_date), INTERVAL 1 DAY) as last_end_date')
                )
                ->where('status', 'active')
                ->where('next_invoice_generation', 0)
                ->whereDate('end_date', '<', now())
                // ->where('resident_id', 137) // REMOVE AFTER TESTING
                ->groupBy('resident_id')
                ->get();
            $invoices = [];

            /** ================= STEP 2: LOOP PER RESIDENT ================= */
            foreach ($subscriptions as $sub) {

                $feeInvoice = null;
                $accessoryInvoice = null;

                $feeTotal = 0;
                $accessoryTotal = 0;

                /** ===== Fetch expired subscription items ===== */
                $items = Subscription::where('resident_id', $sub->resident_id)
                    ->where('status', 'active')
                    ->whereDate('end_date', '<', now())
                    ->get();

                foreach ($items as $item) {

                    /** ================= ACCESSORY ================= */
                    if ($item->item_type === 'accessory') {

                        $accessory = Accessory::find($item->item_id);
                        if (!$accessory) continue;

                        $detail = Accessory::join('accessory_heads', 'accessory.accessory_head_id', '=', 'accessory_heads.id')
                            ->where('accessory.id', $accessory->id)
                            ->where('accessory.is_active', 1)
                            ->select('accessory.*', 'accessory_heads.name as description')
                            ->first();

                        if (!$detail) continue;

                        $price = $detail->price;
                        $total = $price * 1;

                        /** 🔹 FREE ACCESSORY → ADD TO FEE INVOICE */
                        if ($price == 0) {

                            if (!$feeInvoice) {
                                $feeInvoice = $this->createInvoice($sub);
                            }

                            $feeInvoice->items()->create([
                                'item_type'    => 'accessory',
                                'item_id'      => $detail->id,
                                'description'  => $detail->description,
                                'price'        => 0,
                                'total_amount' => 0,
                                'from_date'    => $sub->last_end_date,
                                'to_date'      => Carbon::parse($sub->last_end_date)->addMonths(3)->subDay(),
                            ]);
                        }

                        /** 🔹 PAID ACCESSORY → SEPARATE ACCESSORY INVOICE */
                        else {

                            if (!$accessoryInvoice) {
                                $accessoryInvoice = $this->createInvoice($sub);
                            }

                            $accessoryInvoice->items()->create([
                                'item_type'    => 'accessory',
                                'item_id'      => $detail->id,
                                'description'  => $detail->description,
                                'price'        => $price,
                                'total_amount' => $total,
                                'from_date'    => $sub->last_end_date,
                                'to_date'      => Carbon::parse($sub->last_end_date)->addMonths(1)->subDay(),
                            ]);

                            $accessoryTotal += $total;
                        }
                    }

                    /** ================= FEE ================= */
                    if ($item->item_type === 'fee') {

                        $fee = Fee::with('feeHead')
                            ->where('id', $item->item_id)
                            ->where('is_active', 1)
                            ->whereHas('feeHead', function ($q) {
                                $q->where('is_one_time', 0);
                            })
                            ->first();

                        if (!$fee) continue;

                        if (!$feeInvoice) {
                            $feeInvoice = $this->createInvoice($sub);
                        }

                        $total = $fee->amount * 3;

                        $feeInvoice->items()->create([
                            'item_type'    => 'fee',
                            'item_id'      => $fee->id,
                            'description'  => $fee->name,
                            'price'        => $fee->amount,
                            'total_amount' => $total,
                            'from_date'    => $sub->last_end_date,
                            'to_date'      => Carbon::parse($sub->last_end_date)->addMonths(3)->subDay(),
                        ]);

                        $feeTotal += $total;
                    }
                }

                /** ================= UPDATE INVOICE TOTALS ================= */
                if ($feeInvoice) {
                    $feeInvoice->update([
                        'total_amount'     => $feeTotal,
                        'remaining_amount' => $feeTotal,
                    ]);
                    $invoices[] = $feeInvoice;
                }

                if ($accessoryInvoice) {
                    $accessoryInvoice->update([
                        'total_amount'     => $accessoryTotal,
                        'remaining_amount' => $accessoryTotal,
                    ]);
                    $invoices[] = $accessoryInvoice;
                }

                /** ================= MARK SUBSCRIPTIONS PROCESSED ================= */
                Subscription::where('resident_id', $sub->resident_id)
                    ->where('status', 'active')
                    ->update(['next_invoice_generation' => 1]);
            }

            DB::commit();

            return $this->apiResponse(true, 'Invoices generated successfully.', $invoices);

        } catch (\Exception $e) {

            DB::rollBack();
            Log::error('Cron Invoice Error: ' . $e->getMessage());

            return $this->apiResponse(false, 'Invoice generation failed.', null, 500, [
                'error' => $e->getMessage()
            ]);
        }
    }

}
