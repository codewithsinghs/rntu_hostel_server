<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\ApiAuthController;
use App\Http\Controllers\FacultiesController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FeeController;
use App\Http\Controllers\FeeHeadController;
use App\Http\Controllers\AccessoryController;
use App\Http\Controllers\StudentAccessoryController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ResidentController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\UniversityController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\BedController;
use App\Http\Controllers\LeaveRequestController;
use App\Http\Controllers\RoomChangeController;
use App\Http\Controllers\RoomChangeMessageController;
use App\Http\Controllers\GrievanceController;
use App\Http\Controllers\FineController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\NoticeController;
use App\Http\Controllers\FeeExceptionController;
use App\Http\Controllers\GuestPaymentController;
use App\Http\Controllers\AccessoryHeadController;
use App\Http\Controllers\MessController;

/*
|--------------------------------------------------------------------------
| API Routes - v1
|--------------------------------------------------------------------------
*/

Route::prefix('v1')->group(function () {

    // ----------------------------
    // Public Routes (No Auth)
    // ----------------------------
    Route::post('register', [ApiAuthController::class, 'apiRegister'])->name('v1.auth.register');
    Route::post('login', [ApiAuthController::class, 'apiLogin'])->name('v1.auth.login');

    Route::post('otp/send', [OtpController::class, 'send'])->name('v1.auth.otp.send');
    Route::post('otp/verify', [OtpController::class, 'verify'])->name('v1.auth.otp.verify');

    Route::get('faculties/active', [FacultiesController::class, 'getActiveFaculties'])->name('v1.faculties.active');
    Route::get('faculties/{faculty}/departments', [DepartmentController::class, 'getActiveDepartments'])->name('v1.faculties.departments.active');
    Route::get('departments/{department}/courses', [CourseController::class, 'getActiveCourses'])->name('v1.departments.courses.active');

    Route::post('guests', [GuestController::class, 'register'])->name('v1.guests.register');
    Route::post('guest/login', [LoginController::class, 'guestLogin'])->name('v1.guests.login');
    Route::get('accessories/active/{faculty}', [AccessoryController::class, 'getPublicActiveAccessories'])->name('v1.accessories.active');

    // ----------------------------
    // Protected Routes (Auth Required)
    // ----------------------------
    Route::middleware('auth:sanctum')->group(function () {

        // Authenticated user profile
        Route::get('profile', [ApiAuthController::class, 'profile'])->name('v1.auth.profile');

        // ----------------------------
        // SuperAdmin Routes
        // ----------------------------
        Route::prefix('superadmin')->name('v1.superadmin.')->group(function () {
            Route::get('profile', [SuperAdminController::class, 'profile'])->name('profile');

            Route::apiResource('universities', UniversityController::class)->names('universities');
            Route::post('create-admin', [SuperAdminController::class, 'createAdmin'])->name('admins.create');
            Route::get('admins', [SuperAdminController::class, 'getAdmins'])->name('admins.index');
            Route::get('admins/{id}', [SuperAdminController::class, 'getAdmin'])->name('admins.show');
            Route::put('admins/{id}', [SuperAdminController::class, 'updateAdmin'])->name('admins.update');
        });

        // ----------------------------
        // Admin Routes
        // ----------------------------
        Route::prefix('admin')->name('v1.admin.')->group(function () {

            // Faculties / Departments / Courses
            Route::apiResource('faculties', FacultiesController::class)->names('faculties');
            Route::apiResource('departments', DepartmentController::class)->names('departments');
            Route::apiResource('courses', CourseController::class)->names('courses');

            // Buildings / Rooms / Beds
            Route::apiResource('buildings', BuildingController::class)->names('buildings');
            Route::get('buildings/{id}/rooms', [RoomController::class, 'getRooms'])->name('buildings.rooms');
            Route::apiResource('rooms', RoomController::class)->names('rooms');
            Route::apiResource('beds', BedController::class)->names('beds');
            Route::get('rooms/{room}/available-beds', [BedController::class, 'getAvailableBeds'])->name('rooms.available-beds');

            // Residents
            Route::get('residents', [ResidentController::class, 'getAllResidents'])->name('residents.index');
            Route::get('residents/unassigned', [ResidentController::class, 'getUnassignedResidents'])->name('residents.unassigned');
            Route::get('residents/{id}', [ResidentController::class, 'getResidentById'])->name('residents.show');
            Route::post('assign-bed', [ResidentController::class, 'assignBed'])->name('residents.assign-bed');

            // Staff / HOD / Admin Management
            Route::post('staff/create', [StaffController::class, 'createStaff'])->name('staff.create');
            Route::get('staff', [StaffController::class, 'getAllStaff'])->name('staff.index');
            Route::get('staff/{id}', [StaffController::class, 'getStaffDetails'])->name('staff.show');
            Route::put('staff/update/{id}', [StaffController::class, 'updateStaff'])->name('staff.update');

            Route::post('hods/create', [StaffController::class, 'createHod'])->name('hod.create');
            Route::get('hods', [StaffController::class, 'getAllHods'])->name('hod.index');
            Route::get('hods/{id}', [StaffController::class, 'getHodDetails'])->name('hod.show');
            Route::put('hods/update/{id}', [StaffController::class, 'updateHod'])->name('hod.update');

            Route::post('admin/create', [StaffController::class, 'createAdmin'])->name('admin.create');
            Route::get('admins', [StaffController::class, 'getAllAdmin'])->name('admin.index');
            Route::get('admins/{id}', [StaffController::class, 'getAdminDetails'])->name('admin.show');
            Route::put('admins/update/{id}', [StaffController::class, 'updateAdmin'])->name('admin.update');

            // Accessories & Accessory Heads
            Route::get('accessories', [AccessoryController::class, 'getAllAccessories'])->name('accessories.index');
            Route::post('accessories/create-or-update', [AccessoryController::class, 'createOrUpdate'])->name('accessories.store');
            Route::post('assign-accessories', [StudentAccessoryController::class, 'adminSendAccessoryToResident'])->name('accessories.assign');
            Route::get('accessories/active', [AccessoryController::class, 'getActiveAccessories'])->name('accessories.active');
            Route::get('accessories/{resident}', [CheckoutController::class, 'getAccessoryByResidentId'])->name('accessories.resident');

            Route::prefix('accessories-master')->name('accessories-master.')->group(function () {
                Route::post('add', [AccessoryHeadController::class, 'store'])->name('add');
                Route::post('update/{id}', [AccessoryHeadController::class, 'update'])->name('update');
                Route::get('/', [AccessoryHeadController::class, 'index'])->name('index');
            });

            // Leave Requests
            Route::get('leave-requests', [LeaveRequestController::class, 'allLeaveRequests'])->name('leave-requests.index');
            Route::patch('leave-requests/{id}/admin-approve', [LeaveRequestController::class, 'adminApprove'])->name('leave-requests.admin-approve');
            Route::patch('leave-requests/{id}/admin-deny', [LeaveRequestController::class, 'adminDeny'])->name('leave-requests.admin-deny');

            // Room Change
            Route::get('room-change/requests', [RoomChangeController::class, 'getAllRoomChangeRequests'])->name('room-change.requests');
            Route::post('room-change/respond/{request}', [RoomChangeController::class, 'respondToRequest'])->name('room-change.respond');
            Route::post('room-change/message/{request}', [RoomChangeMessageController::class, 'sendMessage'])->name('room-change.message.send');
            Route::get('room-change/all-messages/{request}', [RoomChangeMessageController::class, 'getMessages'])->name('room-change.messages.index');
            Route::post('room-change/final-approval/{request}', [RoomChangeController::class, 'finalApproval'])->name('room-change.final-approval');
            Route::put('room-change/deny/{request}', [RoomChangeController::class, 'denyRequest'])->name('room-change.deny');

            // Grievances
            Route::get('grievances', [GrievanceController::class, 'allGrievances'])->name('grievances.index');
            Route::post('grievances/respond/{grievance}', [GrievanceController::class, 'respond'])->name('grievances.respond');

            // Fines
            Route::get('fines', [FineController::class, 'allFines'])->name('fines.index');
            Route::post('fines/assign', [FineController::class, 'assignFine'])->name('fines.assign');
            Route::post('fines/pay', [FineController::class, 'payFine'])->name('fines.pay');

            // Subscriptions
            Route::get('subscriptions', [SubscriptionController::class, 'allSubscriptions'])->name('subscriptions.index');
            Route::post('subscriptions/add', [SubscriptionController::class, 'addSubscription'])->name('subscriptions.add');
            Route::patch('subscriptions/update/{id}', [SubscriptionController::class, 'updateSubscription'])->name('subscriptions.update');
            Route::delete('subscriptions/delete/{id}', [SubscriptionController::class, 'deleteSubscription'])->name('subscriptions.delete');

            // Notifications / Feedback / Notices
            Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
            Route::post('notifications/send', [NotificationController::class, 'send'])->name('notifications.send');
            Route::get('feedbacks', [FeedbackController::class, 'allFeedback'])->name('feedbacks.index');
            Route::post('feedbacks/respond/{id}', [FeedbackController::class, 'respond'])->name('feedbacks.respond');
            Route::get('notices', [NoticeController::class, 'allNotices'])->name('notices.index');
            Route::post('notices/create', [NoticeController::class, 'store'])->name('notices.create');
            Route::put('notices/update/{id}', [NoticeController::class, 'update'])->name('notices.update');

            // Fees / Fee Heads / Fee Exceptions
            Route::apiResource('fees', FeeController::class)->names('fees');
            Route::apiResource('fee-heads', FeeHeadController::class)->names('fee-heads');
            Route::post('fee-exception', [FeeExceptionController::class, 'store'])->name('fee-exceptions.store');
            Route::get('fee-exception/{id}', [FeeExceptionController::class, 'show'])->name('fee-exceptions.show');

            // Mess
            Route::apiResource('mess', MessController::class)->names('mess');
        });

        // ----------------------------
        // HOD Routes
        // ----------------------------
        Route::prefix('hod')->name('v1.hod.')->group(function () {
            Route::get('profile', [StaffController::class, 'hodProfile'])->name('profile');
            Route::get('students', [StaffController::class, 'hodStudents'])->name('students.index');
            Route::get('leave-requests', [LeaveRequestController::class, 'hodLeaveRequests'])->name('leave-requests.index');
            Route::patch('leave-requests/{id}/approve', [LeaveRequestController::class, 'hodApprove'])->name('leave-requests.approve');
            Route::patch('leave-requests/{id}/deny', [LeaveRequestController::class, 'hodDeny'])->name('leave-requests.deny');
        });

        // ----------------------------
        // Accountant Routes
        // ----------------------------
        Route::prefix('accountant')->name('v1.accountant.')->group(function () {
            Route::get('profile', [StaffController::class, 'accountantProfile'])->name('profile');
            Route::get('pending-guests', [GuestController::class, 'pendingGuestsForAccountant'])->name('guests.pending');
            Route::post('payments/process', [GuestPaymentController::class, 'processPayment'])->name('payments.process');
        });

        // ----------------------------
        // Resident Routes
        // ----------------------------
        Route::prefix('resident')->name('v1.resident.')->group(function () {
            Route::get('profile', [ResidentController::class, 'residentProfile'])->name('profile');
            Route::get('assigned-bed', [ResidentController::class, 'assignedBed'])->name('assigned-bed.show');
            Route::get('accessories', [ResidentController::class, 'residentAccessories'])->name('accessories.index');
            Route::post('submit-feedback', [FeedbackController::class, 'submitFeedback'])->name('feedback.submit');
            Route::get('grievances', [GrievanceController::class, 'residentGrievances'])->name('grievances.index');
            Route::post('grievances/submit', [GrievanceController::class, 'submitGrievance'])->name('grievances.submit');
        });

    });

});
