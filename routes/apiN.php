<?php

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
    RoomChangeMessageController
};

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
    Route::prefix('admin')->group(function () {
        // Faculties, Departments, Courses
        Route::apiResource('faculties', FacultiesController::class);
        Route::apiResource('departments', DepartmentController::class);
        Route::apiResource('courses', CourseController::class);

        // Buildings, Rooms, Beds
        Route::apiResource('buildings', BuildingController::class);
        Route::apiResource('rooms', RoomController::class);
        Route::apiResource('beds', BedController::class);
        Route::get('rooms/{room_id}/available-beds', [BedController::class, 'getAvailableBeds']);

        // Residents
        Route::get('residents', [ResidentController::class, 'getAllResidents']);
        Route::get('residents/unassigned', [ResidentController::class, 'getUnassignedResidents']);
        Route::post('/assign-bed', [ResidentController::class, 'assignBed']);

        // Guests
        Route::get('/guests/pending', [GuestController::class, 'pendingGuestsForAccountant']);
        Route::get('/guests', [GuestController::class, 'guestsStatus']);
        Route::post('/approve-guest', [AdminController::class, 'guestApproval']);
        Route::post('/modify-waiver/payments', [FeeExceptionController::class, 'store']);

        // Accessories
        Route::get('accessories', [AccessoryController::class, 'getAllAccessories']);
        Route::post('accessories/create-or-update', [AccessoryController::class, 'createOrUpdate']);
        Route::prefix('accessories-master')->group(function () {
            Route::post('/add', [AccessoryHeadController::class, 'store']);
            Route::post('/update/{id}', [AccessoryHeadController::class, 'update']);
            Route::get('/', [AccessoryHeadController::class, 'index']);
        });

        // Grievances, Feedback, Notices
        Route::apiResource('grievances', GrievanceController::class)->only(['index', 'show']);
        Route::post('grievances/respond', [GrievanceController::class, 'respondToGrievance']);
        Route::get('/feedbacks', [FeedbackController::class, 'feedbacksForAdmin']);
        Route::apiResource('notices', NoticeController::class);
    });

    /*
    |------------------- ACCOUNTANT -------------------
    */
    Route::prefix('accountant')->group(function () {
        Route::get('checkout-requests', [CheckoutController::class, 'getAllCheckoutRequests']);
        Route::put('checkout/account-approval/{id}', [CheckoutController::class, 'accountApproval']);
        Route::post('update-guest-status', [FeeExceptionController::class, 'updateGuestStatusWithRemark']);
        Route::get('/payments/resident/{id}', [PaymentController::class, 'getPaymentsByResident']);
        Route::post('subscribe/pay', [PaymentController::class, 'accountSubscribePay']);
        Route::get('fee-heads', [FeeHeadController::class, 'index']);
    });

    /*
    |------------------- ADMISSION -------------------
    */
    Route::prefix('admission')->group(function () {
        Route::put('/verify-guest/{guest_id}', [AdminController::class, 'guestUpdateAndVerification']);
        Route::get('/faculties/active', [FacultiesController::class, 'getActiveFaculties']);
    });


    /*
    |------------------- ADMISSION -------------------
    */
    Route::get('accessories/active', [AccessoryController::class, 'getActiveAccessories']);
    // Route::get('guests', [GuestController::class, 'guestsStatus']);

    Route::get('/admin/guests', [GuestController::class, 'guestsStatus']);
     Route::get('accessories/active', [AccessoryController::class, 'getActiveAccessories']);

});

/*
    |------------------- RESIDENT -------------------
    */
Route::middleware(['auth:sanctum', 'role:resident'])->prefix('resident')->group(function () {
    Route::post('leave', [LeaveRequestController::class, 'store']);
    Route::post('room-change/request', [RoomChangeController::class, 'requestRoomChange']);
    Route::post('feedbacks', [FeedbackController::class, 'store']);
    Route::get('notices', [NoticeController::class, 'index']);
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
});
