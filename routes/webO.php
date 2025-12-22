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
    PaymentController
};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CheckoutController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/




// Show login form
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// Handle login POST request
Route::post('/login', [LoginController::class, 'login']);

// Logout route (optional)
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Route::get('/change-password', function () {
//     return view('auth.change-password');
// })->name('change.password.view');


// ✅ Default Home Route
Route::get('/', function () {
    return view('index');
})->name('home');


Route::get('/resident/room-change-requests', function () {
    return view('resident.room_change_status');
});

Route::view('/resident/dashboard', 'resident.resident_dashboard')->name('resident.dashboard');
Route::view('/resident/leave-request', 'resident.leave_request')->name('resident.leave_request');
Route::view('/resident/leave_request_status', 'resident.leave_request_status')->name('resident.leave_request_status');
Route::get('/resident/room-change', fn() => view('resident.room_change_request'))->name('resident.room_change_request');
Route::view('/resident/room_change_status', 'resident.room_change_status')->name('resident.room_change_status');
Route::view('/resident/grievances/submit', 'resident.submit_grievance')->name('resident.submit_grievance');
Route::view('/resident/grievance_status', 'resident.grievance_status')->name('grievance_status');
Route::view('/resident/accessories', 'resident.accessories')->name('resident.accessories');
Route::view('/resident/payment', 'resident.payment')->name('resident.payment');
Route::view('/resident/payment/{id}', 'resident.make_payment')->name('resident.make_payment');
Route::view('/resident/feedback', 'resident.feedback')->name('resident.feedback');
Route::view('/resident/notices', 'resident.notices')->name('resident.notices');
Route::view('/resident/subscription_type', 'resident.subscription_type')->name('resident.subscription.show');
Route::view('/resident/subscription_payment', 'resident.subscription_payment')->name('resident.subscription.payment');
Route::view('/resident/checkout', 'resident.checkout')->name('resident.checkout');
Route::get('/resident/checkout_status', fn() => view('resident.checkout_status'))->name('resident.checkout.status');
// ✅ Resident Routes
Route::middleware(['auth:sanctum', 'role:resident'])->group(function () {



    // Route::view('/resident/subscription', 'resident.subscription')->name('resident.subscription');

    // Route::get('/resident/checkout-status', fn() => view('resident.checkout_status'));
    // Route::get('/resident/checkout_status', fn() => view('resident.checkout_status'))->name('resident.checkout.status');
    // Route::post('/resident/payment/process', [StudentAccessoryController::class, 'processPayment'])->name('resident.payment.process');
});



// Route::post('/checkout/request', [CheckoutController::class, 'requestCheckout'])->middleware('auth');

// ✅ Admin Routes
// Route::middleware(['admin_api_auth'])->group(function(){
Route::view('/admin/dashboard', 'admin.admin')->name('admin.dashboard');
Route::get('/admin-dashboard', fn() => view('dashboard'))->name('admin.dashboard');

Route::view('/admin/faculties', 'admin.faculties')->name('admin.faculties');
Route::view('/admin/faculties/create', 'admin.create_faculties')->name('admin.create_faculties');
Route::view('/admin/faculties/edit/{id}', 'admin.edit_faculties')->name('admin.edit_faculties');

Route::view('/admin/departments', 'admin.departments')->name('admin.departments');
Route::view('/admin/departments/create', 'admin.create_departments')->name('admin.create_departments');
Route::view('/admin/departments/edit/{id}', 'admin.edit_departments')->name('admin.edit_departments');

Route::view('/admin/courses', 'admin.courses')->name('admin.courses');
Route::view('/admin/courses/create', 'admin.create_courses')->name('admin.create_courses');
Route::view('/admin/courses/edit/{id}', 'admin.edit_courses')->name('admin.edit_courses');

Route::view('/admin/buildings', 'admin.building')->name('admin.building');
Route::view('/admin/buildings/create', 'admin.create_building')->name('admin.create_building');
Route::view('/admin/buildings/edit/{id}', 'admin.edit_building')->name('edit.edit_building');
// Route::post('/admin/buildings', [BuildingController::class, 'store'])->name('admin.store_building');
// Route::delete('/admin/buildings/{id}', [BuildingController::class, 'destroy'])->name('admin.delete_building');

Route::view('/admin/rooms', 'admin.rooms')->name('admin.rooms');
// Route::get('/admin/rooms/list', [RoomController::class, 'index'])->name('admin.list_rooms');
Route::view('/admin/rooms/create', 'admin.create_rooms')->name('admin.create_rooms');
Route::view('/admin/rooms/edit/{id}', 'admin.edit_rooms')->name('admin.edit_rooms');
// Route::post('/admin/rooms', [RoomController::class, 'store'])->name('admin.store_room');
// Route::delete('/admin/rooms/{id}', [RoomController::class, 'destroy'])->name('admin.delete_room');

Route::view('/admin/beds', 'admin.beds')->name('admin.beds');
Route::view('/admin/beds/create', 'admin.create_bed')->name('admin.create_bed');
// Route::get('/admin/api/beds', [BedController::class, 'index'])->name('admin.list_beds');
// Route::post('/admin/api/beds', [BedController::class, 'store'])->name('admin.store_bed');

Route::view('/admin/assignbed', 'admin.assignbed')->name('admin.assignbed');

Route::view('/admin/residents', 'admin.residents')->name('admin.residents');

Route::view('/admin/leave-requests', 'admin.leave_requests')->name('admin.leave_requests');

Route::view('/hod/leave-requests', 'hod.leave_requests')->name('hod.leave_requests');

Route::view('/admin/grievances', 'admin.grievances')->name('admin.grievances');
Route::view('/admin/room-change-requests', 'admin.room_change')->name('admin.room_change');
Route::view('/admin/feedbacks', 'admin.feedback')->name('admin.feedbacks');

Route::view('/admin/pending-guests', 'admin.pending_guest')->name('guest.pending');
Route::view('/admin/guests/paid', 'admin.paidguest')->name('admin.paid.guests');

Route::view('/admin/pendingpayments', 'admin.pendingpayments')->name('admin.pendingpayments');
Route::view('/admin/checkout', 'admin.checkout')->name('admin.checkout');

Route::view('/admin/accessories', 'admin.accessory')->name('admin.accessories');
Route::view('/admin/accessories/create', 'admin.create_accessory')->name('admin.create_accessory');

Route::view('/admin/staff', 'admin.staff')->name('admin.staff');
Route::view('/admin/staff/create', 'admin.create_staff')->name('admin.create_staff');
Route::view('/admin/staff/edit/{id}', 'admin.edit_staff')->name('admin.edit_staff');

Route::view('/admin/admin', 'admin.admin_list')->name('admin.admin_list');
Route::view('/admin/admin/create', 'admin.create_admin')->name('admin.create_admin');
Route::view('/admin/admin/edit/{id}', 'admin.edit_admin')->name('admin.edit_admin');

Route::view('/admin/hods', 'admin.hods')->name('admin.hods');
Route::view('/admin/hods/create', 'admin.create_hod')->name('admin.create_hod');
Route::view('/admin/hods/edit/{id}', 'admin.edit_hod')->name('admin.edit_hod');

Route::view('/admin/notices', 'admin.notices')->name('admin.notices');
Route::view('/admin/notices/create', 'admin.create_notice')->name('admin.create_notice');

Route::view('/admin/subscribe-resident', 'admin.subscribe_resident')->name('admin.subscribe_resident');

Route::view('/admin/add-accessory', 'admin.addaccessories')->name('admin.adda_ccessories');

// });


Route::get('/paytm-payment', [PaymentController::class, 'initiate'])->name('paytm.payment');
Route::post('/paytm-callback', [PaymentController::class, 'callback'])->name('paytm.callback');

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    // Route::view('/admin/buildings', 'admin.building')->name('admin.building');
    // Route::view('/admin/buildings/create', 'admin.create_building')->name('admin.create_building');
    // Route::post('/admin/buildings', [BuildingController::class, 'store'])->name('admin.store_building');
    // Route::delete('/admin/buildings/{id}', [BuildingController::class, 'destroy'])->name('admin.delete_building');

    // Route::view('/admin/beds', 'admin.beds')->name('admin.beds');
    // Route::view('/admin/beds/create', 'admin.create_bed')->name('admin.create_bed');
    // Route::view('/admin/assignbed', 'admin.assignbed')->name('admin.assignbed');
    // Route::get('/admin/api/beds', [BedController::class, 'index'])->name('admin.list_beds');
    // Route::post('/admin/api/beds', [BedController::class, 'store'])->name('admin.store_bed');





    // Route::view('/admin/accessories', 'admin.accessory')->name('admin.accessories');
    // Route::view('/admin/accessories/create', 'admin.create_accessory')->name('admin.create_accessory');
    // Route::post('/admin/accessories', [AccessoryController::class, 'store'])->name('admin.store_accessory');

    // Route::view('/admin/staff', 'admin.staff')->name('admin.staff');
    // Route::view('/admin/staff/create', 'admin.create_staff')->name('admin.create_staff');
    // Route::post('/admin/staff', [StaffController::class, 'createStaff'])->name('admin.store_staff');
    // Route::delete('/admin/staff/{id}', [StaffController::class, 'destroy'])->name('admin.delete_staff');

    // Route::view('/admin/rooms', 'admin.rooms')->name('admin.rooms');
    // Route::get('/admin/rooms/list', [RoomController::class, 'index'])->name('admin.list_rooms');
    // Route::view('/admin/rooms/create', 'admin.create_rooms')->name('admin.create_rooms');
    // Route::post('/admin/rooms', [RoomController::class, 'store'])->name('admin.store_room');
    // Route::delete('/admin/rooms/{id}', [RoomController::class, 'destroy'])->name('admin.delete_room');

    // Route::view('/admin/residents', 'admin.residents')->name('admin.residents');
    // Route::get('/admin/residents/list', [ResidentController::class, 'index'])->name('admin.list_residents');
    // Route::post('/admin/residents', [ResidentController::class, 'store'])->name('admin.store_resident');

    // Route::view('/admin/leave-requests', 'admin.leave_requests')->name('admin.leave_requests');
    // Route::view('/admin/grievances', 'admin.grievances')->name('admin.grievances');
    // Route::view('/admin/room-change-requests', 'admin.room_change')->name('admin.room_change');
    // Route::view('/admin/feedbacks', 'admin.feedback')->name('admin.feedbacks');

    // Route::view('/admin/fee_list', 'admin.fee_list')->name('admin.fee_list');
    // Route::get('/admin/fees', [FeeController::class, 'getAllFees']);
    // Route::post('/admin/add-fees', [FeeController::class, 'addOrUpdateFees']);

    // Route::view('/admin/notices', 'admin.notices')->name('admin.notices');
    // Route::view('/admin/notices/create', 'admin.create_notice')->name('admin.create_notice');
    // Route::post('/admin/notices', [NoticeController::class, 'store'])->name('admin.store_notice');
    // Route::delete('/admin/notices/{id}', [NoticeController::class, 'destroy'])->name('admin.delete_notice');

    // Route::view('/admin/pendingpayments', 'admin.pendingpayments')->name('admin.pendingpayments');
    // Route::view('/admin/checkout', 'admin.checkout')->name('admin.checkout');

    // Route::view('/admin/pending-guests', 'admin.pending_guest')->name('guest.pending');
    // Route::view('/admin/guests/paid', 'admin.paidguest')->name('admin.paid.guests');



});

Route::view('/super-admin/dashboard', 'superadmin.superadmin')->name('superadmin.dashboard');
Route::view('/superadmin/universities', 'superadmin.universities')->name('superadmin.universities');
Route::view('/superadmin/universities/create', 'superadmin.create_university')->name('superadmin.create_university');
Route::view('/superadmin/admins', 'superadmin.admin')->name('superadmin.admins');
Route::view('/superadmin/admins/create', 'superadmin.create_admin')->name('superadmin.create_admin');


// ✅ Superadmin Routes
// Route::middleware(['auth:sanctum', 'role:super_admin'])->group(function () {
//     Route::view('/superadmin', 'superadmin.superadmin')->name('superadmin.dashboard');
//     Route::view('/superadmin/universities', 'superadmin.universities')->name('superadmin.universities');
//     Route::view('/superadmin/universities/create', 'superadmin.create_university')->name('superadmin.create_university');
//     Route::view('/superadmin/admins', 'superadmin.admin')->name('superadmin.admins');
//     Route::view('/superadmin/admins/create', 'superadmin.create_admin')->name('superadmin.create_admin');
// });

// ✅ Guest Routes
// Route::get('/guest/register', fn() => view('guest.register'));
Route::view('/guest/registration-status', 'guest.registration_status')->name('guest.registration_status');
// Handle login POST request
// Route::post('/guest/regs_status', [LoginController::class, 'guestLogin'])->name('guest.regs_status');
Route::get('/guest/registration', fn() => view('guest.register'))->name('guest.register');

// Route::middleware(['auth:sanctum'])->group(function () {
Route::view('/guest/dashboard', 'guest.guest')->name('guest');
Route::view('/guest/status', 'guest.status')->name('guest.status');
Route::view('/guest/payment', 'guest.payment')->name('guest.payment');
Route::view('/guest/makepayment', 'guest.makepayment')->name('guest.makepayment');
// Route::view('/guest/application-status', 'guest_status')->name('guest_status');
// });
// Route::view('guest/payment/confirm', 'guest.payment_confirm')->name('guest.payment.confirm');
Route::post('guest/payment/callback', [PaymentController::class, 'guestPayCallback'])->name('guest.payment.callback');


// Route::middleware(['auth:sanctum', 'role:accountant'])->group(function () {
// });
// ✅ Mess & Accountant
// Route::get('/accountant/dashboard', fn() => view('accountant.account'))->name('accountant.account');
Route::get('/mess', fn() => view('mess.dashboard'))->name('mess.dashboard');
Route::get('/mess/records', [MessController::class, 'indexBlade'])->name('messes.index');

// ✅ APIs
// Room Change Request Routes
Route::get('/room-change-requests', [RoomChangeController::class, 'getAllRequests']);
Route::put('/room-change-requests/{id}/respond', [RoomChangeController::class, 'respondToRequest']);
Route::put('/room-change-requests/{id}/confirm', [RoomChangeController::class, 'confirmRoomChange']);

// Leave Request Routes
Route::post('/leave-requests', [LeaveRequestController::class, 'store']);
Route::patch('/leave-requests/{id}/hod-approve', [LeaveRequestController::class, 'hodApprove']);
Route::patch('/leave-requests/{id}/hod-deny', [LeaveRequestController::class, 'hodDeny']);
Route::patch('/leave-requests/{id}/admin-approve', [LeaveRequestController::class, 'adminApprove']);
Route::patch('/leave-requests/{id}/admin-deny', [LeaveRequestController::class, 'adminDeny']);


Route::post('/api/leave-request', [LeaveRequestController::class, 'store']);

// ✅ Accessory Payment
Route::post('/residents/{resident_id}/accessories/{accessory_id}/pay', [StudentAccessoryController::class, 'payAccessory']);
Route::get('/residents/{resident_id}/accessories/{accessory_id}/pay', [StudentAccessoryController::class, 'payAccessory']);

// ✅ Subscription Payment Redirect
Route::post('/payments/{id}/process', fn($id) => redirect('/resident/subscription_payment/' . $id)->with('success', 'Payment Successful'));
Route::get('/resident/subscription_payment/{id}', fn($id) => view('resident.subscription_payment', ['subscription_id' => $id]));

// ✅ Fallback / Miscellaneous
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout');


// // Admin Password Change View
// Route::prefix('admin')->middleware(['auth', 'role:admin'])->group(function () {
//     Route::view('/change-password', 'auth.change-password')->name('admin.change-password');
// });

// // Student Password Change View
// Route::prefix('student')->middleware(['auth', 'role:student'])->group(function () {
//     Route::view('/change-password', 'auth.change-password')->name('student.change-password');
// });

// // Accountant Password Change View
// Route::prefix('accountant')->middleware(['auth', 'role:accountant'])->group(function () {
//     Route::view('/change-password', 'auth.change-password')->name('accountant.change-password.view');
// });

// Route::middleware(['auth'])->group(function () {
//     Route::get('/{role}/change-password', function ($role) {
//         // Optional: validate role if needed
//         return view('auth.change-password', compact('role'));
//     })->name('change-password.view');
// });

// routes/web.php
Route::get('{role}/change-password', function () {
    return view('auth.change-password'); // Only serves the static page
});
Route::get('{role}/profile', function () {
    return view('auth.profile'); // Only serves the static page
});

Route::get('/head', function () {
    return view('admin.Head');
})->name('head');

Route::view('/hod/dashboard', 'hod.dashboard')->name('hod.dashboard');
Route::view('/admission/dashboard', 'admission.dashboard')->name('admission.dashboard');
Route::view('/admission/guest/forms', 'admission.guest_forms')->name('admission.guest_forms');
Route::view('/admission/form_verify/{id}', 'admission.form_verify')->name('admission.form_verify');


Route::view('/accountant/resident/pay', 'accountant.payaccount')->name('resident.pay.form');
Route::view('/accountant/payments', 'accountant.payments')->name('accountant.payments');
Route::view('/accountant/dashboard', 'accountant.dashboard')->name('accountant.dashboard');
Route::view('/accountant/account', 'accountant.account')->name('accountant.account');
Route::view('/accountant/fee_heads', 'accountant.fee_heads')->name('accountant.fee_heads');
Route::view('/accountant/create_fee_head', 'accountant.create_fee_head')->name('accountant.create_fee_head');
Route::view('/accountant/fee-heads/edit/{id}', 'accountant.edit_fee_head')->name('accountant.edit_fee_head');
Route::view('/accountant/fees', 'accountant.fee')->name('accountant.fees');
Route::view('/accountant/feemaster', 'accountant.feemaster')->name('accountant.feemaster');
Route::view('/accountant/fines', 'accountant.fines')->name('accountant.fines');
Route::view('/accountant/resident/accessory-pay', 'accountant.accessory_pay')->name('accountant.resident.accessory-pay');

Route::view('/accountant/resident-payments', 'accountant.residentpayment')->name('accountant.resident-payments');



Route::get('/accountant/guests', [App\Http\Controllers\FeeExceptionController::class, 'showGuestManagement'])->name('accountant.guests');
Route::get('/accountant/resident/accessory-pay', [PaymentController::class, 'showAccessoryPaymentForm'])->name('accountant.resident.accessory-pay');




Route::get('/admin/fine', function () {
    return view('admin.fine');
})->name('admin.fine');

Route::get('/resident/fine', function () {
    return view('resident.fine');
});


Route::get('/admin/fine', [FineController::class, 'showFineAssignmentForm'])->name('admin.fine.form');

// 11092025
// Route::view('/payment-successstatus', 'guest.payment_status')->name('guest.payment_status');
Route::view('/payment/status', 'guest.payment_status')->name('guest.payment_status');
Route::view('/payment/reciept', 'guest.payment.reciept')->name('payment.reciept');
Route::view('/guest/payment/reciept', 'guest.payment_reciept')->name('guest.payment.reciept');


// Route::get('/', function () {return view('frontend.app');})->name('home');
// Route::get('/guest/registration', fn() => view('frontend.vue.guest.register'))->name('guest.register');
// Route::get('/register', function () {return view('frontend.vue.auth.register');})->name('register');

Route::get('/', function () {
    return view('frontend.index');
})->name('home');
Route::get('/login', fn() => view('frontend.pages.auth.login'))->name('login');

Route::get('/guest/registration', fn() => view('frontend.pages.guest.register'))->name('guest.register');
Route::view('/guest/registration-status', 'frontend.pages.guest.registration_status')->name('guest.registration_status');

Route::view('resident/pay/confirm', 'resident.payment_confirm')->name('resident.payment.confirm');
Route::post('resident/payment/callback', [PaymentController::class, 'resPayCallback'])->name('resident.payment.callback');
Route::view('resident/payment/pay/status', 'resident.payment_reciept')->name('resident.payment.reciept');


Route::view('backend/index', 'backend/index')->name('backend.index');
Route::view('backend/pages', 'backend/pages')->name('backend.pages');

// Route::view('api/login', 'frontend.apilayout.auth.login')->name('login');
// Route::view('api/register', 'frontend.apilayout.auth.register')->name('register');
// Route::view('api/forgot-password', 'frontend.apilayout.auth.forgot')->name('forgot.password');
// Route::view('api/reset-password/{token}', 'frontend.apilayout.auth.reset')->name('reset');