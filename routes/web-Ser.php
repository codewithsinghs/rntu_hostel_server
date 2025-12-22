<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    GuestController,
    HostelController,
    BuildingController,
    BedController,
    AccessoryController,
    StaffController,
    ResidentController,
    RoomController,
    NoticeController,
    LeaveRequestController,
    RoomChangeController,
    MessController,
    StudentAccessoryController,
    FeeController,
    FineController,
    PaymentController,
    Auth\LoginController,
    CheckoutController
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Change password / profile (generic)
Route::get('{role}/change-password', fn() => view('auth.change-password'));
Route::get('{role}/profile', fn() => view('auth.profile'));

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => view('frontend.index'))->name('home');
Route::get('/login', fn() => view('frontend.pages.auth.login'))->name('login');

/*
|--------------------------------------------------------------------------
| Resident Routes
|--------------------------------------------------------------------------
*/

// Route::middleware(['api.auth', 'role:resident'])->group(function () {api.auth:admin
//  Route::middleware(['api.auth:resident'])->group(function () {
// Route::middleware(['auth:sanctum', 'role:resident'])->prefix('resident')->group(function() {
// Route::middleware(['auth:sanctum', 'role:resident'])->group(function () {
Route::prefix('resident')->group(function () {
    Route::view('/dashboard', 'resident.dashboard')->name('resident.dashboard');
    Route::view('/leave-request', 'resident.leave_request')->name('resident.leave_request');
    Route::view('/leave_request_status', 'resident.leave_request_status')->name('resident.leave_request_status');
    Route::get('/room-change', fn() => view('resident.room_change_request'))->name('resident.room_change_request');
    Route::view('/room_change_status', 'resident.room_change_status')->name('resident.room_change_status');
    Route::view('/grievances/submit', 'resident.submit_grievance')->name('resident.submit_grievance');
    Route::view('/grievance_status', 'resident.grievance_status')->name('grievance_status');
    Route::view('/accessories', 'resident.accessories')->name('resident.accessories');
    Route::view('/payment', 'resident.payment')->name('resident.payment');
    Route::view('/payment/{id}', 'resident.make_payment')->name('resident.make_payment');
    Route::view('/feedback', 'resident.feedback')->name('resident.feedback');
    Route::view('/notices', 'resident.notices')->name('resident.notices');
    Route::view('/subscription_type', 'resident.subscription_type')->name('resident.subscription.show');
    Route::view('/subscription_payment', 'resident.subscription_payment')->name('resident.subscription.payment');
    Route::view('/checkout', 'resident.checkout')->name('resident.checkout');
    Route::get('/checkout_status', fn() => view('resident.checkout_status'))->name('resident.checkout.status');

    // Payment confirmations
    Route::view('/pay/confirm', 'resident.payment_confirm')->name('resident.payment.confirm');
    Route::post('/payment/callback', [PaymentController::class, 'resPayCallback'])->name('resident.payment.callback');
    Route::view('/payment/pay/status', 'resident.payment_reciept')->name('resident.payment.reciept');
    Route::get('/fine', fn() => view('resident.fine'));

    Route::view('/grievances', 'resident.grievances')->name('resident.grievances');
});
// });
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->group(function () {
    Route::view('/dashboard', 'admin.admin')->name('admin.dashboard');
    Route::view('/faculties', 'admin.faculties')->name('admin.faculties');
    Route::view('/faculties/create', 'admin.create_faculties')->name('admin.create_faculties');
    Route::view('/faculties/edit/{id}', 'admin.edit_faculties')->name('admin.edit_faculties');

    Route::view('/departments', 'admin.departments')->name('admin.departments');
    Route::view('/departments/create', 'admin.create_departments')->name('admin.create_departments');
    Route::view('/departments/edit/{id}', 'admin.edit_departments')->name('admin.edit_departments');

    Route::view('/courses', 'admin.courses')->name('admin.courses');
    Route::view('/courses/create', 'admin.create_courses')->name('admin.create_courses');
    Route::view('/courses/edit/{id}', 'admin.edit_courses')->name('admin.edit_courses');

    Route::view('/buildings', 'admin.building')->name('admin.building');
    Route::view('/buildings/create', 'admin.create_building')->name('admin.create_building');
    Route::view('/buildings/edit/{id}', 'admin.edit_building')->name('edit.edit_building');

    Route::view('/rooms', 'admin.rooms')->name('admin.rooms');
    Route::view('/rooms/create', 'admin.create_rooms')->name('admin.create_rooms');
    Route::view('/rooms/edit/{id}', 'admin.edit_rooms')->name('admin.edit_rooms');

    Route::view('/beds', 'admin.beds')->name('admin.beds');
    Route::view('/beds/create', 'admin.create_bed')->name('admin.create_bed');
    Route::view('/assignbed', 'admin.assignbed')->name('admin.assignbed');

    Route::view('/residents', 'admin.residents')->name('admin.residents');
    Route::view('/leave-requests', 'admin.leave_requests')->name('admin.leave_requests');
    Route::view('/grievances', 'admin.grievances')->name('admin.grievances');
    Route::view('/room-change-requests', 'admin.room_change')->name('admin.room_change');
    Route::view('/feedbacks', 'admin.feedback')->name('admin.feedbacks');
    Route::view('/pending-guests', 'admin.pending_guest')->name('guest.pending');
    Route::view('/guests/paid', 'admin.paidguest')->name('admin.paid.guests');

    Route::view('/pendingpayments', 'admin.pendingpayments')->name('admin.pendingpayments');
    Route::view('/checkout', 'admin.checkout')->name('admin.checkout');

    Route::view('/accessories', 'admin.accessory')->name('admin.accessories');
    Route::view('/accessories/create', 'admin.create_accessory')->name('admin.create_accessory');

    Route::view('/staff', 'admin.staff')->name('admin.staff');
    Route::view('/staff/create', 'admin.create_staff')->name('admin.create_staff');
    Route::view('/staff/edit/{id}', 'admin.edit_staff')->name('admin.edit_staff');

    Route::view('/admin', 'admin.admin_list')->name('admin.admin_list');
    Route::view('/admin/create', 'admin.create_admin')->name('admin.create_admin');
    Route::view('/admin/edit/{id}', 'admin.edit_admin')->name('admin.edit_admin');

    Route::view('/hods', 'admin.hods')->name('admin.hods');
    Route::view('/hods/create', 'admin.create_hod')->name('admin.create_hod');
    Route::view('/hods/edit/{id}', 'admin.edit_hod')->name('admin.edit_hod');

    Route::view('/notices', 'admin.notices')->name('admin.notices');
    Route::view('/notices/create', 'admin.create_notice')->name('admin.create_notice');

    Route::view('/subscribe-resident', 'admin.subscribe_resident')->name('admin.subscribe_resident');
    Route::view('/add-accessory', 'admin.addaccessories')->name('admin.adda_ccessories');

    Route::get('/fine', [FineController::class, 'showFineAssignmentForm'])->name('admin.fine.form');
});

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
Route::prefix('guest')->group(function () {
    Route::get('/registration', fn() => view('frontend.pages.guest.register'))->name('guest.register');
    Route::view('/registration-status', 'frontend.pages.guest.registration_status')->name('guest.registration_status');
    // Route::view('/dashboard', 'guest.guest')->name('guest');
    Route::view('/dashboard', 'guest.dashboard')->name('guest.dashboard');
    Route::view('/status', 'guest.status')->name('guest.status');
    Route::view('/payment', 'guest.payment')->name('guest.payment');
    Route::view('/makepayment', 'guest.makepayment')->name('guest.makepayment');
    Route::view('/payment/reciept', 'guest.payment_reciept')->name('guest.payment.reciept');
    Route::post('/payment/callback', [PaymentController::class, 'guestPayCallback'])->name('guest.payment.callback');
});

/*
|--------------------------------------------------------------------------
| Accountant Routes
|--------------------------------------------------------------------------
*/
Route::prefix('accountant')->group(function () {
    Route::view('/dashboard', 'accountant.dashboard')->name('accountant.dashboard');
    Route::view('/account', 'accountant.account')->name('accountant.account');
    Route::view('/fee_heads', 'accountant.fee_heads')->name('accountant.fee_heads');
    Route::view('/create_fee_head', 'accountant.create_fee_head')->name('accountant.create_fee_head');
    Route::view('/fee-heads/edit/{id}', 'accountant.edit_fee_head')->name('accountant.edit_fee_head');
    Route::view('/fees', 'accountant.fee')->name('accountant.fees');
    Route::view('/feemaster', 'accountant.feemaster')->name('accountant.feemaster');
    Route::view('/fines', 'accountant.fines')->name('accountant.fines');
    Route::view('/resident/accessory-pay', 'accountant.accessory_pay')->name('accountant.resident.accessory-pay');
    Route::view('/resident/pay', 'accountant.payaccount')->name('resident.pay.form');
    Route::view('/payments', 'accountant.payments')->name('accountant.payments');
    Route::view('/resident-payments', 'accountant.residentpayment')->name('accountant.resident-payments');
    Route::get('/guests', [App\Http\Controllers\FeeExceptionController::class, 'showGuestManagement'])->name('accountant.guests');
    Route::get('/resident/accessory-pay', [PaymentController::class, 'showAccessoryPaymentForm'])->name('accountant.resident.accessory-pay');
    Route::view('/residents', 'accountant.residents')->name('accountant.residents');
    Route::view('/pendingpayments', 'accountant.pendingpayments')->name('accountant.pendingpayments');
});

/*
|--------------------------------------------------------------------------
| Super Admin Routes
|--------------------------------------------------------------------------
*/
Route::prefix('superadmin')->group(function () {
    Route::view('/dashboard', 'superadmin.superadmin')->name('superadmin.dashboard');
    Route::view('/universities', 'superadmin.universities')->name('superadmin.universities');
    Route::view('/universities/create', 'superadmin.create_university')->name('superadmin.create_university');
    Route::view('/admins', 'superadmin.admin')->name('superadmin.admins');
    Route::view('/admins/create', 'superadmin.create_admin')->name('superadmin.create_admin');
});

/*
|--------------------------------------------------------------------------
| HOD / Admission / Mess
|--------------------------------------------------------------------------
*/
Route::view('/hod/dashboard', 'hod.dashboard')->name('hod.dashboard');
Route::view('/hod/leave-requests', 'hod.leave_requests')->name('hod.leave_requests');
Route::view('/admission/dashboard', 'admission.dashboard')->name('admission.dashboard');
Route::view('/admission/guest/forms', 'admission.guest_forms')->name('admission.guest_forms');
Route::view('/admission/form_verify/{id}', 'admission.form_verify')->name('admission.form_verify');
Route::get('/mess', fn() => view('mess.dashboard'))->name('mess.dashboard');
Route::get('/mess/records', [MessController::class, 'indexBlade'])->name('messes.index');

/*
|--------------------------------------------------------------------------
| Payment / Miscellaneous
|--------------------------------------------------------------------------
*/
Route::get('/paytm-payment', [PaymentController::class, 'initiate'])->name('paytm.payment');
Route::post('/paytm-callback', [PaymentController::class, 'callback'])->name('paytm.callback');
Route::view('/payment/status', 'guest.payment_status')->name('guest.payment_status');
Route::view('/payment/reciept', 'guest.payment.reciept')->name('payment.reciept');

Route::view('backend/index', 'backend/index')->name('backend.index');
Route::view('backend/pages', 'backend/pages')->name('backend.pages');



// Admin pages
// Route::middleware(['api.auth:admin', 'role:admin'])->group(function () {
//     Route::view('/admin/beds', 'admin.beds')->name('admin.beds');
// });

// // Resident pages
// Route::middleware(['api.auth', 'role:resident'])->group(function () {
//     Route::view('/resident/dashboard', 'resident.resident_dashboard')->name('resident.dashboard');
// });

// Usage in routes/web.php // - require valid token + auth-id and role admin 
// Route::middleware(['api.auth:admin'])->group(function () { Route::view('/admin/beds', 'admin.beds')->name('admin.beds'); }); 
// // - require valid token + auth-id but any role 
// Route::middleware(['api.auth'])->group(function () { Route::view('/profile', 'auth.profile')->name('profile.view'); }); 
// // You can pass multiple roles by comma or multiple params: // 
// Route::middleware(['api.auth:admin,hod'])->group(...)


// Route::middleware(['api.auth:resident'])->group(function() {
//     Route::prefix('resident')->group(function() {
//         Route::view('/dashboard', 'resident.resident_dashboard')->name('resident.dashboard');
//         // other resident blades
//     });
// });

// Route::middleware(['api.auth:admin'])->group(function() {
//     Route::prefix('admin')->group(function() {
//         Route::view('/dashboard', 'admin.admin_dashboard')->name('admin.dashboard');
//     });
// });

// Public routes (no auth)
// Route::get('/login', function () {
//     return view('login');
// })->name('login');