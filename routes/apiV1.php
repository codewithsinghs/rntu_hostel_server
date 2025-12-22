<?php

use Carbon\Carbon;
use App\Models\User;
use App\Models\Payment;
use PHPUnit\TextUI\Help;
use App\Services\SmsService;
use Illuminate\Http\Request;
use App\Services\MailService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Traits\HasRoles;
use App\Http\Controllers\BedController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FineController;
use App\Http\Controllers\MessController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\StaffController;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\ApiKeyController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\NoticeController;
use App\Http\Middleware\CheckValidReferer;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\FeeHeadController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\ResidentController;
use App\Notifications\CustomAppNotification;
use App\Http\Controllers\AccessoryController;
use App\Http\Controllers\FacultiesController;
use App\Http\Controllers\GrievanceController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\RoomChangeController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\UniversityController;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\FeeExceptionController;
use App\Http\Controllers\GuestPaymentController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\AccessoryHeadController;
use App\Http\Controllers\Apis\V1\LoginController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\StudentAccessoryController;
use App\Http\Controllers\RoomChangeMessageController;

/*
|--------------------------------------------------------------------------
| API Routes (v1) - merged & preserved all old endpoints
|--------------------------------------------------------------------------
|
| - auth:sanctum is used for protected routes
| - admin_api_auth, guest_api_auth, role:* middlewares preserved
| - all original endpoints (including odd/misspelled ones) retained
|
*/

Route::prefix('v1')->group(function () {

    // -----------------------------
    // Public / common routes
    // -----------------------------
    Route::get('/', fn() => response()->json(['message' => 'Welcome to API v1']));

    // Faculties / Departments / Courses - public
    Route::get('/faculties/active', [FacultiesController::class, 'getActiveFaculties']);
    Route::get('/faculties/{faculty_id}/departments', [DepartmentController::class, 'getActiveDepartments']);
    Route::get('/departments/{department_id}/courses', [CourseController::class, 'getActiveCourses']);

    // Auth / OTP public
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/send-otp', [AuthController::class, 'sendOtp']);
    Route::post('otp/send', [OtpController::class, 'send'])->name('otp.send');
    Route::post('otp/verify', [OtpController::class, 'verify'])->name('otp.verify');

    // Guest public
    Route::post('/guests', [GuestController::class, 'register']); // Guest registers
    Route::post('/guest/login', [LoginController::class, 'guestLogin'])->name('guest.guest_login');
    Route::get('accessories/active/{facultyId}', [AccessoryController::class, 'getPublicActiveAccessories']);
    Route::post('/guests/invoice-preview', [AdminController::class, 'guestInvoicePreview']);

    // Misc public
    Route::get('/messes', [MessController::class, 'index']);
    Route::get('/feedbacks', [FeedbackController::class, 'index']);   // View All Feedbacks
    Route::get('/feedbacks/{id}', [FeedbackController::class, 'show']); // View Single Feedback
    Route::get('accessories/active', [AccessoryController::class, 'getActiveAccessories']);
    Route::get('/fees', [FeeController::class, 'getAllFees']);
    Route::get('/payments', [PaymentController::class, 'getAllPayments']);

    // subscription payment view (web)
    Route::get('/subscription_payment', function () {
        return view('resident.subscription_payment');
    })->name('resident.subscription.payment');

    // get payment id helper
    Route::get('/get-payment-id', function (Request $request) {
        $residentId = $request->query('resident_id');
        $subscriptionId = $request->query('subscription_id');

        $payment = Payment::where('resident_id', $residentId)
            ->where('subscription_id', $subscriptionId)
            ->first();

        if ($payment) {
            return response()->json(['payment_id' => $payment->id]);
        } else {
            return response()->json(['error' => 'Payment ID not found'], 404);
        }
    });

    // -----------------------------
    // Role-Specific Authentication Prefix (keeps {role} + admin_api_auth)
    // -----------------------------
    Route::prefix('{role}')->middleware(['admin_api_auth'])->group(function () {
        Route::post('/send-otp', [AuthController::class, 'sendOtp']);
        Route::post('/change-password', [AuthController::class, 'changePassword']);
        // (profile view commented in original - kept out)
    });

    // -----------------------------
    // Guest API Authenticated Routes (guest_api_auth) - preserved
    // -----------------------------
    Route::middleware(['guest_api_auth'])->group(function () {
        Route::get('/guest/profile', [GuestController::class, 'getGuestProfile']);
        Route::post('guest-authentication', [LoginController::class, 'AuthenticateGuests']);
        Route::get('/guest/approved-rejected-guest', [GuestController::class, 'getApprovedOrRejectedGuests']);
        Route::get('/guest/total-amount', [GuestController::class, 'getGuestTotalAmount']);
        Route::get('/guests/paid', [GuestController::class, 'getPaidGuests']);
        Route::get('/guests/pending', [GuestController::class, 'pendingGuests']);
        Route::get('guests/accessories/active', [AccessoryController::class, 'getGuestActiveAccessories']);
        Route::post('guest/initiate-transaction', [PaymentController::class, 'initiateGuestTransaction']);
        Route::post('guests/guest-payments', [PaymentController::class, 'guestPayment']); // Guest makes payment
        Route::post('guest/payment/confirm', [PaymentController::class, 'confirmGuestPayment'])->name('guest.payment.confirm');
        Route::post('guest/payment/initiate', [GuestPaymentController::class, 'initiateGuestTransaction']);
        Route::get('/guest/payment/status', [GuestPaymentController::class, 'guestPaymentStatus']);
    });

    // Admin login & logout (API)
    Route::post('admin/login', [ApiAuthController::class, 'apiLogin']);
    Route::post('logout', [LoginController::class, 'logout']);

    // -----------------------------
    // admin_api_auth protected group (keeps old behavior & many admin routes)
    // -----------------------------
    Route::middleware(['admin_api_auth'])->group(function () {

        Route::post('authenticate-users', [LoginController::class, 'AuthenticateUsers']);

        // -----------------------------
        // Super Admin
        // -----------------------------
        Route::prefix('superadmin')->group(function () {
            Route::get('profile', [AdminController::class, 'getAdminProfile']);

            // Universities (kept explicit to match old file)
            Route::post('universities/create', [UniversityController::class, 'store']);
            Route::get('universities', [UniversityController::class, 'index']);
            Route::put('universities/{id}', [UniversityController::class, 'update']);

            Route::post('create-admin', [SuperAdminController::class, 'createAdmin']);
            Route::get('admins', [SuperAdminController::class, 'getAdmins']);
            Route::get('admins/{id}', [SuperAdminController::class, 'getAdmin']);
            Route::put('admins/{id}', [SuperAdminController::class, 'updateAdmin']);
            // delete admin commented originally
        });

        // -----------------------------
        // HOD
        // -----------------------------
        Route::prefix('hod')->group(function () {
            Route::get('leave-requests', [LeaveRequestController::class, 'allLeaveRequests']);
            Route::patch('leave-requests/{id}/hod-approve', [LeaveRequestController::class, 'hodApprove']);
            Route::patch('leave-requests/{id}/hod-deny', [LeaveRequestController::class, 'hodDeny']);
        });

        // -----------------------------
        // Admin (core)
        // -----------------------------
        Route::prefix('admin')->group(function () {

            // Roles & Staff roles
            Route::get('roles', [AdminController::class, 'getRoles']);
            Route::get('staff-roles', [AdminController::class, 'getStaffRoles']);

            // Buildings CRUD
            Route::get('buildings', [BuildingController::class, 'index']);
            Route::post('buildings/create', [BuildingController::class, 'store']);
            Route::get('buildings/{id}', [BuildingController::class, 'show']);
            Route::put('buildings/{id}', [BuildingController::class, 'update']);
            Route::delete('buildings/{id}', [BuildingController::class, 'destroy']);

            // Faculties CRUD
            Route::get('faculties', [FacultiesController::class, 'index']);
            Route::post('faculties/create', [FacultiesController::class, 'store']);
            Route::get('faculties/{id}', [FacultiesController::class, 'show']);
            Route::put('faculties/{id}', [FacultiesController::class, 'update']);
            Route::delete('faculties/{id}', [FacultiesController::class, 'destroy']);

            // Departments CRUD
            Route::get('departments', [DepartmentController::class, 'index']);
            Route::post('departments/create', [DepartmentController::class, 'store']);
            Route::get('departments/{id}', [DepartmentController::class, 'show']);
            Route::put('departments/{id}', [DepartmentController::class, 'update']);
            Route::delete('departments/{id}', [DepartmentController::class, 'destroy']);

            // Courses CRUD
            Route::get('courses', [CourseController::class, 'index']);
            Route::post('courses/create', [CourseController::class, 'store']);
            Route::get('courses/{id}', [CourseController::class, 'show']);
            Route::put('courses/{id}', [CourseController::class, 'update']);
            Route::delete('courses/{id}', [CourseController::class, 'destroy']);

            // Rooms and Beds
            Route::get('buildings/{id}/rooms', [RoomController::class, 'getRooms']);
            Route::get('rooms', [RoomController::class, 'index']);
            Route::get('rooms/{id}', [RoomController::class, 'show']);
            Route::post('rooms/create', [RoomController::class, 'store']);
            Route::put('rooms/{id}', [RoomController::class, 'update']);
            Route::delete('rooms/{id}', [RoomController::class, 'destroy']);

            Route::get('beds', [BedController::class, 'index']);
            Route::get('beds/{id}', [BedController::class, 'show']);
            Route::post('beds/create', [BedController::class, 'store']);
            Route::put('beds/update/{id}', [BedController::class, 'update']);
            Route::delete('beds/{id}', [BedController::class, 'destroy']);
            Route::get('rooms/{room_id}/available-beds', [BedController::class, 'getAvailableBeds']);

            // Residents
            Route::get('residents', [ResidentController::class, 'getAllResidents']);
            Route::get('residents/unassigned', [ResidentController::class, 'getUnassignedResidents']);
            Route::get('residents/{id}', [ResidentController::class, 'getResidentById']);
            Route::post('/assign-bed', [ResidentController::class, 'assignBed']);

            // Guests (admin)
            Route::get('/guests/pending', [GuestController::class, 'pendingGuestsForAccountant']);
            Route::get('/guests', [GuestController::class, 'guestsStatus']);
            Route::get('/guests/{guest_id}', [GuestController::class, 'guestDetails']);
            Route::get('/guests/status', [GuestController::class, 'guestsStatus']);
            Route::post('/approve-guest', [AdminController::class, 'guestApproval']);
            Route::get('/guest/{guest}/fee-exception-details', [FeeExceptionController::class, 'getFeeExceptionDetailsForEdit']);
            Route::get('/paid-guests', [GuestController::class, 'getPaidGuests']);

            // Waiver guests
            Route::post('/modify-waiver/payments', [FeeExceptionController::class, 'store']);
            Route::post('/reject-waiver', [FeeExceptionController::class, 'waiverRejected']);

            // Payments
            Route::get('/allPendingPayments', [PaymentController::class, 'getAllPendingPayments']);
            Route::get('/allPendisgPayments', [PaymentController::class, 'getAllPendingPayments']); // legacy name kept

            // Accessories
            Route::get('accessories', [AccessoryController::class, 'getAllAccessories']);
            Route::post('accessories/create-or-update', [AccessoryController::class, 'createOrUpdate']);
            Route::post('assign-accessories', [StudentAccessoryController::class, 'adminSendAccessoryToResident']);
            Route::get('accessories/active', [AccessoryController::class, 'getActiveAccessories']);
            Route::get('accessories/{resident_id}', [CheckoutController::class, 'getAccessoryByResidentId']);

            // Accessory master
            Route::prefix('accessories-master')->group(function () {
                Route::post('/add', [AccessoryHeadController::class, 'store']);
                Route::post('/update/{id}', [AccessoryHeadController::class, 'update']);
                Route::get('/', [AccessoryHeadController::class, 'index']);
            });

            // Staff / admin users
            Route::post('admin/create', [StaffController::class, 'createAdmin']);
            Route::get('admin-list', [StaffController::class, 'getAllAdmin']);
            Route::get('admin/{id}', [StaffController::class, 'getAdminDetails']);
            Route::put('admin/update/{id}', [StaffController::class, 'updateAdmin']);

            Route::post('staff/create', [StaffController::class, 'createStaff']);
            Route::get('staff-list', [StaffController::class, 'getAllStaff']);
            Route::get('staff/{id}', [StaffController::class, 'getStaffDetails']);
            Route::put('staff/update/{id}', [StaffController::class, 'updateStaff']);

            Route::post('hods/create', [StaffController::class, 'createHod']);
            Route::get('hods-list', [StaffController::class, 'getAllHods']);
            Route::get('hods/{id}', [StaffController::class, 'getHodDetails']);
            Route::put('hods/update/{id}', [StaffController::class, 'updateHod']);

            // Room change (admin)
            Route::get('room-change/requests', [RoomChangeController::class, 'getAllRoomChangeRequests']);
            Route::post('room-change/respond/{request_id}', [RoomChangeController::class, 'respondToRequest']);
            Route::post('room-change/message/{request_id}', [RoomChangeMessageController::class, 'sendMessage']);
            Route::get('room-change/all-messages/{request_id}', [RoomChangeMessageController::class, 'getMessages']);
            Route::post('room-change/final-approval/{request_id}', [RoomChangeController::class, 'finalApproval']);
            Route::put('room-change/deny/{request_id}', [RoomChangeController::class, 'denyRoomChangeByAdmin']);

            // Checkout (admin)
            Route::put('accessory/checking/{residentId}', [CheckoutController::class, 'adminAccessoryChecking']);
            Route::put('checkout/admin-approval/{id}', [CheckoutController::class, 'adminApproval']);
            Route::get('resident/all-checkout-requests', [CheckoutController::class, 'getAllCheckoutRequests']);
            Route::get('resident-checkout-logs/{residentId}', [CheckoutController::class, 'adminGetCheckoutLogs']);

            // Leave Requests (admin)
            Route::get('leave-requests', [LeaveRequestController::class, 'allLeaveRequests']);
            Route::patch('leave-requests/{id}/admin-approve', [LeaveRequestController::class, 'adminApprove']);
            Route::patch('leave-requests/{id}/admin-deny', [LeaveRequestController::class, 'adminDeny']);
            Route::get('residents/{residentId}/leave-requests', [LeaveRequestController::class, 'leaveReqById']);

            // Admin create resident
            Route::post('residents/create', [AdminController::class, 'createResident']);

            // Fines
            Route::post('assign/fine', [FineController::class, 'assignFineToResident']);

            // Grievances
            Route::get('/grievances', [GrievanceController::class, 'getAllGrievances']);
            Route::get('grievances/{id}', [GrievanceController::class, 'getGrievanceById']);
            Route::post('grievances/respond', [GrievanceController::class, 'respondToGrievance']);

            // Feedbacks / Notices
            Route::get('/feedbacks', [FeedbackController::class, 'feedbacksForAdmin']);
            Route::post('/notices', [NoticeController::class, 'store']);
            Route::get('/notices/{id}', [NoticeController::class, 'show']);
            Route::put('/notices/{id}', [NoticeController::class, 'update']);
            Route::delete('/notices/{id}', [NoticeController::class, 'destroy']);
            Route::get('notices', [NoticeController::class, 'index']);
        });

        // -----------------------------
        // Accountant (under admin_api_auth)
        // -----------------------------
        Route::prefix('accountant')->group(function () {
            Route::get('checkout-requests', [CheckoutController::class, 'getAllCheckoutRequests']);
            Route::put('checkout/account-approval/{id}', [CheckoutController::class, 'accountApproval']);
            Route::post('update-guest-status', [FeeExceptionController::class, 'updateGuestStatusWithRemark']);
            Route::get('resident-checkout-logs/{residentId}', [CheckoutController::class, 'adminGetCheckoutLogs']);
            Route::get('residents', [ResidentController::class, 'getAllResidents']);
            Route::get('resident/{resident_id}/subscription', [SubscriptionController::class, 'getResidentSubscriptions']);
            Route::get('/pending/{resident_id}/subscription', [SubscriptionController::class, 'getPendingResidentSubscriptions']);
            Route::post('subscribe/pay', [PaymentController::class, 'accountSubscribePay']);

            // accessories/payments
            Route::get('/resident/{resident_id}/accessories', [PaymentController::class, 'getAccessoryPendingPayments']);
            Route::post('/residents/{resident_id}/accessories/{accessory_id}/pay', [StudentAccessoryController::class, 'payAccessory']);
            Route::get('/payments/resident/{id}', [PaymentController::class, 'getPaymentsByResident']);

            // Fee ops
            Route::get('/fees', [FeeController::class, 'getAllFees']);
            Route::get('/activeFees', [FeeController::class, 'getAllActiveFees']);
            Route::post('/admin/addOrUpdateFees', [FeeController::class, 'createOrUpdate']);
            Route::get('/fee-heads', [FeeHeadController::class, 'index']);
            Route::post('/fee-heads', [FeeHeadController::class, 'store']);
            Route::get('/fee-heads/{id}', [FeeHeadController::class, 'show']);
            Route::put('/fee-heads/{id}', [FeeHeadController::class, 'update']);
            Route::delete('/fee-heads/{id}', [FeeHeadController::class, 'destroy']);

            Route::get('/guest/{guest}/fee-exception', [FeeExceptionController::class, 'getFeeExceptionDetailsForEdit']);

            // Fine Routes
            Route::post('set-fine-amount', [FineController::class, 'accountantSetFineAmount']);
            Route::get('view-fine-details', [FineController::class, 'viewAllFineDetails']);
            Route::get('view-fine-details', [FineController::class, 'PendingFines']);
            Route::post('set-fine-amount', [FineController::class, 'updateFineItem']);
            Route::get('accessories/active', [AccessoryController::class, 'getActiveAccessories']);
        });

        // -----------------------------
        // Extra guest invoice preview route (admin)
        // -----------------------------
        Route::post('/guests/{guest}/invoice-preview', [AdminController::class, 'invoicePreview']);

        // -----------------------------
        // Admission group
        // -----------------------------
        Route::prefix('admission')->group(function () {
            Route::put('/verify-guest/{guest_id}', [AdminController::class, 'guestUpdateAndVerification']);
            Route::get('accessories/active', [AccessoryController::class, 'getActiveAccessories']);
            Route::get('/faculties/active', [FacultiesController::class, 'getActiveFaculties']);
            Route::get('/faculties/{faculty_id}/departments', [DepartmentController::class, 'getActiveDepartments']);
            Route::get('/departments/{department_id}/courses', [CourseController::class, 'getActiveCourses']);
        });

        // -----------------------------
        // Resident protected routes (auth:sanctum + role:resident)
        // -----------------------------
        Route::middleware(['auth:sanctum', 'role:resident'])->group(function () {
            Route::prefix('resident')->group(function () {
                // Leave
                Route::post('leave', [LeaveRequestController::class, 'store']);
                Route::get('leave-requests', [LeaveRequestController::class, 'leaveRequestByResident']);

                // Room change
                Route::post('room-change/request', [RoomChangeController::class, 'requestRoomChange']);
                Route::get('room-change/requests', [RoomChangeController::class, 'getRoomChangeRequests']);
                Route::get('room-change/requests/{id}', [RoomChangeController::class, 'getRoomChangeRequestsById']);
                Route::get('{residentId}/room-change-requests', [RoomChangeController::class, 'getRoomChangeRequestsByResidentId']);

                // Messages in room-change
                Route::get('room-change/all-messages/{request_id}', [RoomChangeMessageController::class, 'getMessages']);
                Route::post('room-change/message/{request_id}', [RoomChangeMessageController::class, 'sendMessage']);

                // Feedback
                Route::post('feedbacks', [FeedbackController::class, 'store']);

                // Notices & subscriptions
                Route::get('notices', [NoticeController::class, 'index']);
                Route::get('pending/subscription', [SubscriptionController::class, 'getResidentSubscriptions']);

                // Fines
                Route::post('pending/appliedFines', [FineController::class, 'getResidentFines']);

                // Confirm room-change
                Route::post('room-change/confirm-by-resident/{request_id}', [RoomChangeController::class, 'confirmRoomChange']);

                // Accessories & payments
                Route::get('accessories/active', [AccessoryController::class, 'getActiveAccessories']);
                Route::post('accessories', [StudentAccessoryController::class, 'addAccessory']);
                Route::get('accessories-pending-payments', [PaymentController::class, 'getAccessoryPendingPayments']);
                Route::post('invoices/{invoice_id}/pay', [StudentAccessoryController::class, 'payAccessory']);
                Route::get('invoices/{invoice_id}', [StudentAccessoryController::class, 'getResidentInvoices']);

                // Payments
                Route::get('payment/summary', [PaymentController::class, 'paymentSummary'])->name('resident.payment.summary');
                Route::post('payment/confirm', [PaymentController::class, 'confirmPayment'])->name('resident.payment.confirm');
                Route::post('payment/confirmation/{ref}', [PaymentController::class, 'confirmPay'])->name('resident.payment.confirmation');
                Route::post('payment/initiate', [PaymentController::class, 'initiateResidentPayment']);
                Route::get('payment/status', [PaymentController::class, 'ResidentPaymentStatus']);

                // Checkout
                Route::post('checkout/request', [CheckoutController::class, 'requestCheckout']);
                Route::get('checkout-status', [CheckoutController::class, 'getCheckoutStatus']);
                Route::get('checkout-logs', [CheckoutController::class, 'getCheckoutLogs']);

                // Pending payments
                Route::get('/pending-payments', [PaymentController::class, 'getPendingPayments']);
                Route::post('pending-payments/invoice-items', [PaymentController::class, 'getInvoiceItems']);

                // Grievances nested
                Route::prefix('grievances')->group(function () {
                    Route::post('/submit', [GrievanceController::class, 'submitGrievance']);
                    Route::post('/respond/{id}', [GrievanceController::class, 'residentRespond']);
                    Route::get('/', [GrievanceController::class, 'getGrievancesByResident']);
                    Route::get('/{id}', [GrievanceController::class, 'getGrievanceById']);
                    Route::put('/close/{id}', [GrievanceController::class, 'closeGrievance']);
                });
            });
        });
    });

    // -----------------------------
    // Additional public or semi-public endpoints preserved below
    // -----------------------------
    Route::get('/accountant/guests/pending', [GuestController::class, 'pendingGuestsForAccountant']);

    // Fees & fee-head (some are duplicated in original, kept all)
    Route::put('/fees/{id}', [FeeController::class, 'updateFeeById']);
    Route::post('/admin/add-fees', [FeeController::class, 'addOrUpdateFees']);
    Route::get('accountant/activeFees', [FeeController::class, 'getAllActiveFees']);
    Route::post('/admin/addOrUpdateFees', [FeeController::class, 'createOrUpdate']);
    Route::get('accountant/fee-heads', [FeeHeadController::class, 'index']);
    Route::post('accountant/fee-heads', [FeeHeadController::class, 'store']);
    Route::put('/fee-heads/{id}', [FeeHeadController::class, 'update']);

    // Payment / subscription related endpoints
    Route::post('/resident/pay', [PaymentController::class, 'payAsResident']);
    Route::post('/payment/reject', [AdminController::class, 'rejectPaymentRequest']);
    Route::post('/payments/{payment_id}/pay', [PaymentController::class, 'subscribePay']);
    Route::get('/allPendisgPayments', [PaymentController::class, 'getAllPendingPayments']); // legacy kept
    Route::post('/payments/pay', [PaymentController::class, 'makePayment']);
    Route::get('/payments/pending/{resident_id}', [PaymentController::class, 'getPendingPayments']);
    Route::get('/payments/all/resident/{resident_id}', [PaymentController::class, 'getAllPaymentsByResidentId']);

    Route::post('/residents/subscribe', [SubscriptionController::class, 'subscribeToService']);
    Route::post('/subscription/pay', [PaymentController::class, 'subscribePay']);
    Route::get('/pending/{resident_id}/subscription', [SubscriptionController::class, 'getPendingResidentSubscriptions']);
    Route::get('/combined/pending/subscription', [SubscriptionController::class, 'getCombinedSubscription']);
    Route::post('/admin/subscribe-resident', [SubscriptionController::class, 'adminSubscribeResident']);

    Route::get('/resident/all', [StaffController::class, 'getresidents']);

    // Mess & misc
    Route::get('/messes', [MessController::class, 'index']);

    // -----------------------------
    // Notifications, mail, and sms testing endpoints (closures preserved)
    // -----------------------------
    Route::post('/send-notification', function (Request $request) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'message' => 'required|string'
        ]);

        $user = User::find($request->user_id);
        $user->notify(new CustomAppNotification($request->message));

        return response()->json(['success' => true, 'message' => 'Notification sent.']);
    });

    Route::post('/send-sms', function (Request $request) {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|string',
            'message' => 'required|string|max:160'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $response = SmsService::send($request->phone, $request->message);

        return response()->json([
            'success' => $response['success'] ?? false,
            'message' => $response['success'] ? 'SMS sent successfully' : 'SMS failed to send',
            'data' => $response,
        ]);
    });

    Route::post('/send-mail', function (Request $request) {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'subject' => 'required|string',
            'name' => 'required|string',
            'body' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $response = MailService::send(
            $request->email,
            $request->subject,
            'emails.hostel_welcome',
            [
                'name' => $request->name,
                'body' => $request->body
            ]
        );

        return response()->json([
            'success' => $response['success'] ?? false,
            'message' => $response['message'] ?? 'Mail operation done',
            'data' => $response
        ]);
    });

    // -----------------------------
    // Notification API Routes
    // -----------------------------
    Route::prefix('notifications')->group(function () {
        Route::get('resident/{residentId}', [NotificationController::class, 'getPaginatedResidentNotifications']);
        Route::get('resident/{residentId}/all', [NotificationController::class, 'getAllResidentNotifications']);
        Route::get('resident/{residentId}/unread', [NotificationController::class, 'getUnreadResidentNotifications']);
        Route::post('{id}/read', [NotificationController::class, 'markNotificationAsRead']);
        Route::get('payments', [NotificationController::class, 'getPaymentNotifications']);
    });

    // -----------------------------
    // Auth API (api register/login/profile with sanctum)
    // -----------------------------
    Route::post('/register', [ApiAuthController::class, 'apiRegister'])->name('api.register');
    Route::post('/login', [ApiAuthController::class, 'apiLogin'])->name('api.login');

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::get('/profile', [ApiAuthController::class, 'profile']);
    });

    // Protected web-like views for roles (sanctum + role)
    Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
        Route::prefix('admin')->group(function () {
            Route::view('/dashboard', 'admin.dashboard')->name('admin.dashboard');
            Route::view('/users', 'admin.users')->name('admin.users');
            Route::view('/settings', 'admin.settings')->name('admin.settings');
        });
    });

    Route::middleware(['auth:sanctum', 'role:resident'])->group(function () {
        Route::prefix('resident')->group(function () {
            Route::view('/dashboard', 'resident.dashboard')->name('resident.dashboard');
            Route::view('/leave-request', 'resident.leave_request')->name('resident.leave_request');
        });
    });

    Route::middleware(['auth:sanctum', 'role:hod'])->group(function () {
        Route::prefix('hod')->group(function () {
            Route::view('/dashboard', 'hod.dashboard')->name('hod.dashboard');
            Route::view('/approvals', 'hod.approvals')->name('hod.approvals');
        });
    });

    Route::middleware(['auth:sanctum', 'role:mess_manager'])->group(function () {
        Route::prefix('mess-manager')->group(function () {
            Route::view('/dashboard', 'mess_manager.dashboard')->name('mess_manager.dashboard');
            Route::view('/menu', 'mess_manager.menu')->name('mess_manager.menu');
            Route::view('/orders', 'mess_manager.orders')->name('mess_manager.orders');
        });
    });

    // Fallback for v1
    Route::fallback(function () {
        return response()->json(['message' => 'API v1: Endpoint not found.'], 404);
    });
}); // end v1
