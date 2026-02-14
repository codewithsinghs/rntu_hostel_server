<?php
// API
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    AuthController,
    Api\ApiAuthController,
    Api\OtpController,
    FacultiesController,
    DepartmentController,
    CourseController,
    GuestController,
    GuestPaymentController,
    AdminController,
    SuperAdminController,
    StaffController,
    ResidentController,
    BuildingController,
    RoomController,
    BedController,
    UniversityController,
    AccessoryController,
    AccessoryHeadController,
    StudentAccessoryController,
    PaymentController,
    FeeController,
    FeeHeadController,
    FeeExceptionController,
    CheckoutController,
    FineController,
    LeaveRequestController,
    GrievanceController,
    FeedbackController,
    NoticeController,
    SubscriptionController,
    RoomChangeController,
    RoomChangeMessageController,
    // 19122025

};
use App\Http\Controllers\ApiV1\FacultyController;
use App\Http\Controllers\ApiV1\DepartmentResController;
use App\Http\Controllers\ApiV1\CourseResController;
use App\Http\Controllers\ApiV1\ResidentResController;
use App\Http\Controllers\ApiV1\HostelController;
use App\Http\Controllers\ApiV1\RoomResController;
use App\Http\Controllers\ApiV1\BedResController;

use App\Http\Controllers\ApiV1\LeaveController;
// use App\Http\Controllers\ApiV1\LeaveController as AdminLeaveController;
use App\Http\Controllers\ApiV1\Resident\LeaveController as ResidentLeaveController;
use App\Http\Controllers\ApiV1\CheckoutsController;

use App\Http\Controllers\ApiV1\AttachmentController;
use App\Http\Controllers\CroneJobsController;
/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/faculties/active', [FacultiesController::class, 'getActiveFaculties']);
Route::get('/faculties/{faculty_id}/departments', [DepartmentController::class, 'getActiveDepartments']);
Route::get('/departments/{department_id}/courses', [CourseController::class, 'getActiveCourses']);

Route::post('/login', [AuthController::class, 'login']);
Route::post('otp/send', [OtpController::class, 'send'])->name('otp.send');
Route::post('otp/verify', [OtpController::class, 'verify'])->name('otp.verify');
Route::post('/send-otp', [AuthController::class, 'sendOtp']);

// Guest Public
Route::post('/guests', [GuestController::class, 'register']);
Route::post('/guest/login', [ApiAuthController::class, 'apiLogin'])->name('guest.guest_login');
Route::get('accessories/active/{facultyId}', [AccessoryController::class, 'getPublicActiveAccessories']);
Route::post('/guests/invoice-preview', [AdminController::class, 'guestInvoicePreview']);

/*
|--------------------------------------------------------------------------
| Role-Specific Authentication Prefix
|--------------------------------------------------------------------------
*/
Route::prefix('{role}')->middleware(['admin_api_auth'])->group(function () {
    Route::post('/send-otp', [AuthController::class, 'sendOtp']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
});

/*
|--------------------------------------------------------------------------
| Guest Authenticated Routes
|--------------------------------------------------------------------------
*/
// Route::middleware(['guest_api_auth'])->group(function () {
Route::get('/guest/profile', [GuestController::class, 'getGuestProfile']);
Route::post('guest-authentication', [ApiAuthController::class, 'AuthenticateGuests']);
Route::get('/guest/approved-rejected-guest', [GuestController::class, 'getApprovedOrRejectedGuests']);
Route::get('/guest/total-amount', [GuestController::class, 'getGuestTotalAmount']);
Route::get('/guests/paid', [GuestController::class, 'getPaidGuests']);
Route::get('/guests/pending', [GuestController::class, 'pendingGuests']);
Route::get('guests/accessories/active', [AccessoryController::class, 'getGuestActiveAccessories']);
Route::post('guest/initiate-transaction', [PaymentController::class, 'initiateGuestTransaction']);
Route::post('guests/guest-payments', [PaymentController::class, 'guestPayment']);
Route::post('guest/payment/confirm', [PaymentController::class, 'confirmGuestPayment'])->name('guest.payment.confirm');
Route::post('guest/payment/initiate', [GuestPaymentController::class, 'initiateGuestTransaction']);
Route::get('/guest/payment/status', [GuestPaymentController::class, 'guestPaymentStatus']);
// });

/*
|--------------------------------------------------------------------------
| Admin Auth Routes
|--------------------------------------------------------------------------
*/
Route::post('admin/login', [ApiAuthController::class, 'apiLogin']);
// Route::post('logout', [ApiAuthController::class, 'logout']);
Route::middleware('auth:sanctum')->post('/logout', [ApiAuthController::class, 'logout']);


// Protected API routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/profile', [ApiAuthController::class, 'profile']);
});

/*
|--------------------------------------------------------------------------
| Protected Admin Routes (admin_api_auth)
|--------------------------------------------------------------------------
*/
// Route::middleware(['admin_api_auth'])->group(function () {

Route::post('authenticate-users', [ApiAuthController::class, 'AuthenticateUsers']);

// Authenticated API Routes
// Route::middleware('auth:sanctum')->group(function () {
// Route::middleware(['dual_auth'])->group(function () {
// Route::middleware(['auth:sanctum', 'dual_auth'])->group(function () {
Route::middleware(['dual_auth'])->group(function () {
    /*
    |------------------- SUPER ADMIN -------------------
    */
    Route::prefix('superadmin')->group(function () {
        Route::get('profile', [AdminController::class, 'getAdminProfile']);
        Route::apiResource('universities', UniversityController::class)->except(['destroy']);
        //Super Admin Routes    
        // Route::post('universities/create', [UniversityController::class, 'store']);
        // Route::get('universities', [UniversityController::class, 'index']);
        // Route::put('universities/{id}', [UniversityController::class, 'update']);

        Route::post('create-admin', [SuperAdminController::class, 'createAdmin']);
        Route::get('admins', [SuperAdminController::class, 'getAdmins']);
        Route::get('admins/{id}', [SuperAdminController::class, 'getAdmin']);
        Route::put('admins/{id}', [SuperAdminController::class, 'updateAdmin']);
    });

    /*
    |------------------- HOD -------------------
    */
    Route::prefix('hod')->group(function () {
        Route::get('leave-requests', [LeaveRequestController::class, 'allLeaveRequests']);
        Route::patch('leave-requests/{id}/hod-approve', [LeaveRequestController::class, 'hodApprove']);
        Route::patch('leave-requests/{id}/hod-deny', [LeaveRequestController::class, 'hodDeny']);
    });

    /*
    |------------------- ADMIN -------------------
    */
    // Admin Routes
    // Route::middleware(['auth:sanctum'])
    //     ->prefix('admin')
    //     ->name('admin.')
    //     ->group(function () {
    //         Route::apiResource('faculties', FacultiesResController::class);
    //     });

    Route::prefix('admin')->group(function () {
        Route::get('roles', [AdminController::class, 'getRoles']); // Get all buildings
        Route::get('staff-roles', [AdminController::class, 'getStaffRoles']); // Get all buildings

        //Faculties Apis 
        // Route::get('faculties', [FacultiesController::class, 'index']); // Get all buildings
        // Route::post('faculties/create', [FacultiesController::class, 'store']);
        // Route::get('faculties/{id}', [FacultiesController::class, 'show']); // Get single building
        // Route::put('faculties/{id}', [FacultiesController::class, 'update']); // Update building
        // Route::delete('faculties/{id}', [FacultiesController::class, 'destroy']); // Delete building
        Route::apiResource('faculties', FacultyController::class);
    
        // Departments Apis
        Route::get('departments', [DepartmentController::class, 'index']);
        Route::post('departments/create', [DepartmentController::class, 'store']);
        Route::get('departments/{id}', [DepartmentController::class, 'show']);
        Route::put('departments/{id}', [DepartmentController::class, 'update']);
        Route::delete('departments/{id}', [DepartmentController::class, 'destroy']); // Delete department
        Route::apiResource('manage/departments', DepartmentResController::class);

        // Courses Apis
        // Route::get('courses', [CourseController::class, 'index']);
        // Route::post('courses/create', [CourseController::class, 'store']);
        // Route::get('courses/{id}', [CourseController::class, 'show']);
        // Route::put('courses/{id}', [CourseController::class, 'update']);
        // Route::delete('courses/{id}', [CourseController::class, 'destroy']); // Delete department
        Route::apiResource('courses', CourseResController::class);

        Route::get('buildings', [BuildingController::class, 'index']); // Get all buildings
        Route::post('buildings/create', [BuildingController::class, 'store']);
        Route::get('buildings/{id}', [BuildingController::class, 'show']); // Get single building
        Route::put('buildings/{id}', [BuildingController::class, 'update']); // Update building
        Route::delete('buildings/{id}', [BuildingController::class, 'destroy']); // Delete building
        Route::get('buildings/{id}/rooms', [RoomController::class, 'getRooms']); //Total Rooms status of a building
        Route::apiResource('hostels', HostelController::class);

        // Route::get('rooms', [RoomController::class, 'index']); // Get all rooms from all buildings
        // Route::get('rooms/{id}', [RoomController::class, 'show']); // Get single room
        // Route::post('rooms/create', [RoomController::class, 'store']); // Create room
        // Route::put('rooms/{id}', [RoomController::class, 'update']); // Update room
        // Route::delete('rooms/{id}', [RoomController::class, 'destroy']); // Delete room
        // Route::apiResource('rooms', RoomController::class);

        Route::apiResource('rooms', RoomResController::class);
        // Route::get('/check-rooms', [AdminController::class, 'checkAvailableRooms']); // Check available rooms

        // Route::apiResource('beds', BedController::class);
        // Route::get('beds', [BedController::class, 'index']); // Get all beds
        // Route::get('beds/{id}', [BedController::class, 'show']); // Get single bed
        // Route::post('beds/create', [BedController::class, 'store']); // Create bed
        // Route::put('beds/update/{id}', [BedController::class, 'update']); // Update bed
        // Route::delete('beds/{id}', [BedController::class, 'destroy']); // Delete bed
        // Route::get('rooms/{room_id}/available-beds', [BedController::class, 'getAvailableBeds']);

        Route::apiResource('beds', BedResController::class);
        //FETCH RESIDENTS
        Route::get('residents', [ResidentController::class, 'getAllResidents']);
        Route::get('residentswarden', [ResidentController::class, 'getAllResidents']);
        Route::apiResource('manage/residents', ResidentResController::class);
        Route::put('manage/residents/{resident}/check-in', [ResidentResController::class, 'updateCheckInDate']);
        Route::put('residents/{resident}/check-in', [ResidentResController::class, 'updateCheckInDate']);

        // Route::get('residents', [ResidentController::class, 'getAllResidents']);

        Route::get('residents/unassigned', [ResidentController::class, 'getUnassignedResidents']);
        Route::get('residents/{id}', [ResidentController::class, 'getResidentById']);
        Route::post('/assign-bed', [ResidentController::class, 'assignBed']); // Assign bed to resident

        //For Guests Apis
        Route::get('/guests/pending', [GuestController::class, 'pendingGuestsForAccountant']);
        Route::get('/guests', [GuestController::class, 'guestsStatus']);
        Route::get('/guests/{guest_id}', [GuestController::class, 'guestDetails']);
        Route::get('/guests/status', [GuestController::class, 'guestsStatus']);
        Route::post('/approve-guest', [AdminController::class, 'guestApproval']); // Send payment request
        Route::get('/guest/{guest}/fee-exception-details', [FeeExceptionController::class, 'getFeeExceptionDetailsForEdit']);
        Route::get('/paid-guests', [GuestController::class, 'getPaidGuests']);

        //For Waiver Guests
        // Route::post('/approved-waiver', [FeeExceptionController::class, 'adminWaiverApproved']); // Send payment request
        Route::post('/modify-waiver/payments', [FeeExceptionController::class, 'store']); // Send payment request
        Route::post('/reject-waiver', [FeeExceptionController::class, 'waiverRejected']); // Send payment request

        Route::get('/allPendingPayments', [PaymentController::class, 'getAllPendingPayments']);

        // admin Acccessories
        Route::get('accessories', [AccessoryController::class, 'getAllAccessories']);
        Route::post('accessories/create-or-update', [AccessoryController::class, 'createOrUpdate']);
        // Route::put('accessories/{id}', [AccessoryController::class, 'update']);
        Route::post('assign-accessories', [StudentAccessoryController::class, 'adminSendAccessoryToResident']);
        Route::get('accessories/active', [AccessoryController::class, 'getActiveAccessories']);
        Route::get('accessories/{resident_id}', [CheckoutController::class, 'getAccessoryByResidentId']);
        // Route::get('default/accessories/{resident_id}', [CheckoutController::class, 'getDefaultAccessoryByResidentId']);



        //Accessory head 
        Route::prefix('accessories-master')->group(function () {
            Route::post('/add', [AccessoryHeadController::class, 'store']);
            Route::post('/update/{id}', [AccessoryHeadController::class, 'update']); // No PUT, use POST
            Route::get('/', [AccessoryHeadController::class, 'index']);
        });

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

        // Admin fetches all room change requests
        Route::get('room-change/requests', [RoomChangeController::class, 'getAllRoomChangeRequests']);
        // Route::get('room-change/requests', [RoomChangeController::class, 'getAllRequests']);

        // Admin responds with available/not available + optional remark
        Route::post('room-change/respond/{request_id}', [RoomChangeController::class, 'respondToRequest']);
        // Send a message in the conversation
        Route::post('room-change/message/{request_id}', [RoomChangeMessageController::class, 'sendMessage']);
        // Fetch all messages in a conversation
        Route::get('room-change/all-messages/{request_id}', [RoomChangeMessageController::class, 'getMessages']);

        // ➡️ Final Approval by Admin when resident has agreed
        Route::post('room-change/final-approval/{request_id}', [RoomChangeController::class, 'finalApproval']);
        Route::put('room-change/deny/{request_id}', [RoomChangeController::class, 'denyRoomChangeByAdmin']);

        //Checkout Process
        Route::put('accessory/checking/{residentId}', [CheckoutController::class, 'adminAccessoryChecking']);
        Route::put('checkout/admin-approval/{id}', [CheckoutController::class, 'adminApproval']); // Admin final approval
        Route::get('resident/all-checkout-requests', [CheckoutController::class, 'getAllCheckoutRequests']);
        Route::get('resident-checkout-logs/{residentId}', [CheckoutController::class, 'adminGetCheckoutLogs']);

        // leave request APIs
        Route::get('leave-requests', [LeaveRequestController::class, 'allLeaveRequests']);
        Route::patch('leave-requests/{id}/admin-approve', [LeaveRequestController::class, 'adminApprove']);
        Route::patch('leave-requests/{id}/admin-deny', [LeaveRequestController::class, 'adminDeny']);
        Route::get('residents/{residentId}/leave-requests', [LeaveRequestController::class, 'leaveReqById']);

        // Route::apiResource('manage/leaves', LeaveController::class);

        // Admin Created Resident
        Route::post('residents/create', [AdminController::class, 'createResident']);
        // Route::get('profile', [AdminController::class, 'getAdminProfile']);

        // Route::post('fine', [FineController::class, 'adminSetFine']);

        Route::post('assign/fine', [FineController::class, 'assignFineToResident']);

        // Fetch all grievances for the admin
        Route::get('/grievances', [GrievanceController::class, 'getAllGrievances']);
        // Fetch grievance by ID for admin/resident
        Route::get('grievances/{id}', [GrievanceController::class, 'getGrievanceById']);
        // Admin can respond to a grievance
        Route::post('grievances/respond', [GrievanceController::class, 'respondToGrievance']);

        Route::get('/feedbacks', [FeedbackController::class, 'feedbacksForAdmin']);   // View All Feedbacks

        Route::post('/notices', [NoticeController::class, 'store']); // Create Notice
        Route::get('/notices/{id}', [NoticeController::class, 'show']); // Get Single Notice
        Route::put('/notices/{id}', [NoticeController::class, 'update']); // Update Notice
        Route::delete('/notices/{id}', [NoticeController::class, 'destroy']); // Delete Notice
        Route::get('notices', [NoticeController::class, 'index']); // Get All Notices


        Route::post('/subscribe-resident', [SubscriptionController::class, 'adminSubscribeResident']);
    });

    /*
    |------------------- ACCOUNTANT -------------------
    */
    Route::prefix('accountant')->group(function () {
        // Accountant Routes
        Route::get('checkout-requests', [CheckoutController::class, 'getAllCheckoutRequests']);
        Route::put('checkout/account-approval/{id}', [CheckoutController::class, 'accountApproval']); // Accounts approval
        Route::post('update-guest-status', [FeeExceptionController::class, 'updateGuestStatusWithRemark']);
        Route::get('resident-checkout-logs/{residentId}', [CheckoutController::class, 'adminGetCheckoutLogs']);
        Route::get('residents', [ResidentController::class, 'getAllResidents']);
        Route::get('resident/{resident_id}/subscription', [SubscriptionController::class, 'getResidentSubscriptions']);
        Route::get('/pending/{resident_id}/subscription', [SubscriptionController::class, 'getPendingResidentSubscriptions']);
        Route::post('subscribe/pay', [PaymentController::class, 'accountSubscribePay']);
        // Acccessories
        Route::get('/resident/{resident_id}/accessories', [PaymentController::class, 'getAccessoryPendingPayments']);
        Route::post('/residents/{resident_id}/accessories/{accessory_id}/pay', [StudentAccessoryController::class, 'payAccessory']);
        Route::get('/payments/resident/{id}', [PaymentController::class, 'getPaymentsByResident']);

        Route::get('/resident/{resident_id}/invoices', [PaymentController::class, 'getResidentInvoices']);
        Route::get('/resident/{resident_id}/invoice/{invoice_id}/transactions', [PaymentController::class, 'getResidentInvoicesTransactions']);

        //Fee
        Route::get('/fees', [FeeController::class, 'getAllFees']);
        Route::get('/activeFees', [FeeController::class, 'getAllActiveFees']);
        Route::post('/admin/addOrUpdateFees', [FeeController::class, 'createOrUpdate']);
        Route::get('/fee-heads', [FeeHeadController::class, 'index']);
        //Fee head
        // Route::post('/fee-heads/create', [FeeHeadController::class, 'create']);
        Route::post('/fee-heads', [FeeHeadController::class, 'store']);
        Route::get('/fee-heads/{id}', [FeeHeadController::class, 'show']);
        Route::put('/fee-heads/{id}', [FeeHeadController::class, 'update']);
        Route::delete('/fee-heads/{id}', [FeeHeadController::class, 'destroy']);

        Route::get('/guest/{guest}/fee-exception', [FeeExceptionController::class, 'getFeeExceptionDetailsForEdit']);

        // Fine accountant Routes
        Route::post('set-fine-amount', [FineController::class, 'accountantSetFineAmount']);
        Route::get('view-fine-details', [FineController::class, 'viewAllFineDetails']);  // created New Below
        Route::get('view-fine-details', [FineController::class, 'PendingFines']);
        Route::post('set-fine-amount', [FineController::class, 'updateFineItem']);
        Route::get('accessories/active', [AccessoryController::class, 'getActiveAccessories']);

        Route::post('submitPayment', [PaymentController::class, 'submitPayment']);
    });

    /*
    |------------------- ADMISSION -------------------
    */
    Route::prefix('admission')->group(function () {
        Route::put('/verify-guest/{guest_id}', [AdminController::class, 'guestUpdateAndVerification']); // Send payment request
        Route::get('accessories/active', [AccessoryController::class, 'getActiveAccessories']);

        // Route::post('/invoice-preview', [AdminController::class, 'invoicePreview']);




        Route::get('/faculties/active', [FacultiesController::class, 'getActiveFaculties']);
        Route::get('/faculties/{faculty_id}/departments', [DepartmentController::class, 'getActiveDepartments']);
        Route::get('/departments/{department_id}/courses', [CourseController::class, 'getActiveCourses']);
    });

    /*
    |------------------- RESIDENTS -------------------
    */

    Route::prefix('resident')->group(function () {
        //Resident Routes
        //Leave Request
        Route::post('leaves', [LeaveRequestController::class, 'store']);
        Route::get('leave-requests', [LeaveRequestController::class, 'leaveRequestByResident']);

        Route::resource('leaves', ResidentLeaveController::class)
            ->only(['index', 'store', 'show', 'destroy']);

        // Resident requests room change
        Route::post('room-change/request', [RoomChangeController::class, 'requestRoomChange']);
        // Resident fetches their room change requests
        Route::get('room-change/requests', [RoomChangeController::class, 'getRoomChangeRequests']);
        // Route::get('room-change/requests/{id}', [RoomChangeController::class, 'getRoomChangeRequestsById']);
        Route::get('{residentId}/room-change-requests', [RoomChangeController::class, 'getRoomChangeRequestsByResidentId']);

        //Messages in Room Change Requests
        Route::get('room-change/all-messages/{request_id}', [RoomChangeMessageController::class, 'getMessages']);
        Route::post('room-change/message/{request_id}', [RoomChangeMessageController::class, 'sendMessage']);

        Route::get('feedbacks', [FeedbackController::class, 'indexs']); // Fetch feedback of logged-in resident
        Route::post('feedbacks', [FeedbackController::class, 'store']);  // Submit Feedback

        // Route::middleware('auth:sanctum')->group(function () {
        //     Route::get('/feedbacks', [FeedbackController::class, 'indexs']);
        //     Route::post('/feedbacks', [FeedbackController::class, 'store']);
        // });

        Route::get('notices', [NoticeController::class, 'index']); // Get All Notices
        Route::get('pending/subscription', [SubscriptionController::class, 'getResidentSubscriptions']);

        Route::post('pending/appliedFines', [FineController::class, 'getResidentFines']);

        // // Resident respond 
        // Route::post('resident/room-change/respond-to-admin/{request_id}', [RoomChangeController::class, 'respondToAdmin']);

        // Resident sends agree/deny
        Route::post('room-change/confirm-by-resident/{request_id}', [RoomChangeController::class, 'confirmRoomChange']);

        Route::get('accessories/active', [AccessoryController::class, 'getActiveAccessories']);

        //Accessory Routes
        // Route::get('accessories', [AccessoryController::class, 'ResidentAccessories']);
        Route::post('accessories', [StudentAccessoryController::class, 'addAccessory']); // Resident Add Accessory

        Route::get('/recent-accessory', [StudentAccessoryController::class, 'getRecentAccessoryRequest']);

        Route::get('accessories-pending-payments', [PaymentController::class, 'getAccessoryPendingPayments']);
        Route::post('invoices/{invoice_id}/pay', [StudentAccessoryController::class, 'payAccessory']);
        Route::get('invoices/{invoice_id}', [StudentAccessoryController::class, 'getResidentInvoices']);

        Route::get('payment/summary', [PaymentController::class, 'paymentSummary'])->name('resident.payment.summary');
        Route::post('payment/confirm', [PaymentController::class, 'confirmPayment'])->name('resident.payment.confirm');
        Route::post('payment/confirmation/{ref}', [PaymentController::class, 'confirmPay'])->name('resident.payment.confirmation');
        Route::post('payment/initiate', [PaymentController::class, 'initiateResidentPayment']);
        Route::get('payment/status', [PaymentController::class, 'ResidentPaymentStatus']);

        Route::post('checkout/request', [CheckoutController::class, 'requestCheckout']);

        Route::get('checkout-status', [CheckoutController::class, 'getCheckoutStatus']);
        Route::get('checkout-logs', [CheckoutController::class, 'getCheckoutLogs']);
        Route::get('profile', [ResidentController::class, 'getResidentProfile']);

        Route::get('/pending-payments', [PaymentController::class, 'getPendingPayments']);
        Route::post('pending-payments/invoice-items', [PaymentController::class, 'getInvoiceItems']);

        Route::get('/recent-transactions', [PaymentController::class, 'getRecentTransactions']);

        Route::prefix('grievances')->group(function () {
            // Submit a grievance
            Route::post('/submit', [GrievanceController::class, 'submitGrievance']);

            // Resident can respond with a message and can agree/disagree with the response
            Route::post('/respond/{id}', [GrievanceController::class, 'residentRespond']);

            // Fetch all grievances for the admin
            Route::get('/', [GrievanceController::class, 'getGrievancesByResident']);

            // Fetch grievance by ID for admin/resident
            Route::get('/{id}', [GrievanceController::class, 'getGrievanceById']);

            // Close grievance by resident (final resolution)
            Route::put('/close/{id}', [GrievanceController::class, 'closeGrievance']);
        });
    });


    /*
    |------------------- ADMISSION -------------------
    */
    Route::get('accessories/active', [AccessoryController::class, 'getActiveAccessories']);
    // Route::get('guests', [GuestController::class, 'guestsStatus']);

    Route::get('/admin/guests', [GuestController::class, 'guestsStatus']);
    Route::get('accessories/active', [AccessoryController::class, 'getActiveAccessories']);


    Route::prefix('manage')->group(function () {
        // Route::apiResource('leaves', LeaveController::class);
        Route::apiResource('leaves', LeaveController::class)->names('manage.leaves');
    });
});

//------------------- RESIDENT -------------------

Route::middleware(['auth:sanctum', 'role:resident'])->prefix('resident')->group(function () {
    // Route::post('leave', [LeaveRequestController::class, 'store']);
    // Route::post('room-change/request', [RoomChangeController::class, 'requestRoomChange']);
    // Route::post('feedbacks', [FeedbackController::class, 'store']);
    // Route::get('notices', [NoticeController::class, 'index']);
    Route::get('payment/status', [PaymentController::class, 'ResidentPaymentStatus']);
});
// });

// Route::get('accessories/active', [AccessoryController::class, 'getActiveAccessories']);

// Route::middleware(['auth:sanctum'])->group(function () {
//     Route::get('/admin/guests', [GuestController::class, 'guestsStatus']);
// });

Route::middleware(['auth:sanctum', 'dual_auth'])->group(function () {
    Route::get('/admin/guests', [GuestController::class, 'guestsStatus']);
    Route::get('accessories/active', [AccessoryController::class, 'getActiveAccessories']);
    Route::get('/faculties/active', [FacultiesController::class, 'getActiveFaculties']);
    Route::get('admin/guests/{guest_id}', [GuestController::class, 'guestDetails']);
    Route::post('/guests/{guest}/invoice-preview', [AdminController::class, 'invoicePreview']);
});

Route::get('accessories/active/{facultyId}', [AccessoryController::class, 'getPublicActiveAccessories']);
// Route::get('accessories/active', [AccessoryController::class, 'getActiveAccessories']);
Route::get('/faculties/active', [FacultiesController::class, 'getActiveFaculties']);
Route::get('/fees', [FeeController::class, 'getAllFees']);

// Protected API routes
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/profile', [ApiAuthController::class, 'show']);

    // Update profile
    Route::put('/profile', [ApiAuthController::class, 'update']);
    Route::post('/profile/image', [ApiAuthController::class, 'updateProfileImage'])->name('profile_image.update');


    Route::get('sync-resident-profiles', [ResidentController::class, 'syncAllProfiles']);
    Route::get('/resident/transactions', [PaymentController::class, 'index']);
});
Route::post('/crone-job/generate-invoices-on-subscriptions-expiry', [CroneJobsController::class, 'generateInvoiceOnSubscriptionsExpiry']);

Route::middleware('auth:sanctum')->get('resident/dashboard', [ResidentController::class, 'dashboard']);

// routes/api.php
Route::get('/files/{file}', [AttachmentController::class, 'download'])
    ->name('api.download.attachment');



// $roles = ['admin' => 'role:admin', 'warden' => 'role:warden', 'hod' => 'role:hod', 'resident' => 'role:resident',];
// Route::middleware(['auth:sanctum'])->group(function () use ($roles) {
//     foreach ($roles as $prefix => $middleware) {
//         Route::middleware([$middleware])->prefix($prefix)->group(function () use ($prefix) {
//             Route::apiResource('leaves', LeaveController::class)->names("$prefix.leaves");
//         });
//     }
// });

// Route::prefix('manage')->middleware(['auth:sanctum', 'role:admin|warden|hod'])->group(function () {
//     Route::apiResource('leaves', LeaveController::class)->names('manage.leaves');
// });



// API Routes for Resident Leaves
Route::middleware(['auth:sanctum'])->prefix('resident')->group(function () {
    Route::apiResource('leaves', ResidentLeaveController::class)->names('resident.leaves');
    
    Route::post('leaves/{leave}/cancel', [ResidentLeaveController::class, 'cancel'])->name('resident.leaves.cancel');
    Route::get('leaves/{leave}/gate-pass', [ResidentLeaveController::class, 'gatePass']);
    Route::get('leaves-summary', [ResidentLeaveController::class, 'summary']);
});

Route::middleware(['auth:sanctum'])->prefix('checkouts')->group(function () {

    Route::get('/', [CheckoutsController::class, 'index'])->name('checkouts.index');
    Route::post('/initiate', [CheckoutsController::class, 'initiate'])->name('checkout.initiate');
    // Show single checkout
    Route::get('/{checkout}', [CheckoutsController::class, 'show'])->name('checkout.show');
    Route::put('/{checkout}/update', [CheckoutsController::class, 'update'])->name('checkout.update');

    Route::get('/{checkout}/clearance-data', [CheckoutsController::class, 'clearanceData'])->name('checkout.clearance');
    Route::post('clearance-submit',   [CheckoutsController::class, 'submitClearance'])->name('checkout.clearance.submit');

    // Route::post('/task/{id}/approve', [CheckoutsController::class, 'approve'])->name('checkout.approvals');
    Route::post('/approval-tasks/{task}/approve', [CheckoutsController::class, 'approve'])->name('checkout.approvals');

    Route::post('/accounts/{task}/finalize', [CheckoutsController::class, 'settleFinance'])->name('checkout.finalize');

    Route::post('/{checkout}/complete', [CheckoutController::class, 'finalExit'])->name('checkout.finalexit');
});

// routes/api.php

// Route::middleware('auth:sanctum')->group(function () {
//     Route::get('/checkouts', [CheckoutController::class, 'index'])->name('checkouts.index');
//     Route::post('/checkouts', [CheckoutController::class, 'store'])->name('checkouts.store');
//     Route::get('/checkouts/{checkout}', [CheckoutController::class, 'show'])->name('checkouts.show');
//     Route::put('/checkouts/{checkout}', [CheckoutController::class, 'update'])->name('checkouts.update');
//     Route::delete('/checkouts/{checkout}', [CheckoutController::class, 'destroy'])->name('checkouts.destroy');
//     Route::post('/checkouts/{checkout}/final-exit', [CheckoutController::class, 'finalExit'])->name('checkouts.finalExit');
// });

