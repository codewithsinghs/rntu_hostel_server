<?php

namespace App\Http\Controllers\ApiV1;

use App\Models\Leave;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AttachmentController extends Controller
{
    //
    public function download($file)
    {
        // Decode the Base64 string 
        $decoded = base64_decode($file);
        // Build the full path 
        $path = storage_path('app/public/' . $decoded);
        if (!file_exists($path)) {
            abort(404, 'File not found');
        }
        // Stream or download 
        // return response()->download($path);

        // return response()->file($path, [
        //     'Content-Type' => 'application/pdf'
        // ]);

        // return response()->file($path)->header('Content-Disposition', 'inline; filename="'.basename($path).'"');

        // return response()->file($path, [
        //     'Content-Type' => mime_content_type($path),
        // ])->withHeaders([
        //     'Content-Disposition' => 'inline; filename="' . basename($path) . '"'
        // ]);

        return response()->file($path, ['Content-Type' => mime_content_type($path),])->setContentDisposition('inline', basename($path));
    }




    public function getReceipt($id)
    {
        $leave = Leave::with('resident')->findOrFail($id);

        // Only generate QR if token exists
        $qr = null;
        if ($leave->token) {
            $qr = QrCode::size(150)->generate(route('leave.verify', $leave->token));
        }

        return response()->json([
            'leave' => $leave,
            'qr'    => $qr, // inline SVG string
        ]);
    }


    public function verify($token)
    {
        Log::info("Verifying leave with token: " . $token);
        $leave = Leave::where('token', $token)->firstOrFail();

        // return view('leave.verify', [
        //     'leave' => $leave
        // ]);

        return response()->json(['Name' => $leave->resident->name, 'status' => $leave->status, 'remarks' => $leave->admin_remarks, 'action_at' => $leave->admin_action_at,]);
    }

    // public function verifyPage($token)
    // {
    //     $leave = Leave::where('token', $token)->first();

    //     if (!$leave) {
    //         return view('leave.verify', [
    //             'error' => 'Invalid or expired token.'
    //         ]);
    //     }

    //     return view('leave.verify', [
    //         'data' => [
    //             'name'      => $leave->resident->name,
    //             'email'      => $leave->resident->email,
    //             'mobile'      => $leave->resident->mobile,
    //             'start_date'      => $leave->start_date,
    //             'end_date'      => $leave->end_date,
    //             'applied_on'      => optional($leave->created_at)->format('d M Y, h:i A'),
    //             'status'    => ucfirst($leave->status),
    //             'remarks'   => $leave->admin_remarks ?? 'No remarks',
    //             'action_at' => optional($leave->admin_action_at)->format('d M Y, h:i A'),
    //         ]
    //     ]);
    // }

    public function verifyPage($token)
    {
        $leave = Leave::with('resident.user', 'resident.course', 'resident.department')
            ->where('token', $token)
            ->first();

        if (!$leave) {
            return view('leave.verify', [
                'error' => 'Invalid or expired token.'
            ]);
        }

        return view('leave.verify', [
            'data' => [
                'name'        => $leave->resident->name,
                'email'       => $leave->resident->email,
                'mobile'      => $leave->resident->mobile,
                'course'      => $leave->resident->course->name ?? 'N/A',
                'department'  => $leave->resident->department->name ?? 'N/A',
                'start_date'  => optional($leave->start_date)->format('d M Y'),
                'end_date'    => optional($leave->end_date)->format('d M Y'),
                'applied_on'  => optional($leave->created_at)->format('d M Y, h:i A'),
                'status'      => ucfirst($leave->status),
                'remarks'     => $leave->admin_remarks ?? 'No remarks',
                'action_at'   => optional($leave->admin_action_at)->format('d M Y, h:i A'),
            ]
        ]);
    }
}
