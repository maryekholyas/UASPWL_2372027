<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CommitteeController;
use App\Http\Controllers\FinanceController;
use App\Http\Controllers\MemberController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::prefix('events')->group(function () {
    Route::get('/', [EventController::class, 'index'])->name('events.index');
    Route::get('/{id}', [EventController::class, 'show'])->name('events.show');
});

Route::prefix('dashboard')->group(function () {
    Route::get('/admin', [DashboardController::class, 'admin'])->name('dashboard.admin');

});

Route::prefix('admin/staff')->group(function () {
    Route::post('/', [DashboardController::class, 'addStaff'])->name('admin.staff.add');
    Route::get('/', [DashboardController::class, 'allStaff'])->name('admin.staff.all');
    Route::get('/{id}/edit', [DashboardController::class, 'editStaff'])->name('admin.staff.edit');
    Route::put('/{id}', [DashboardController::class, 'updateStaff'])->name('admin.staff.update');
    Route::delete('/{id}', [DashboardController::class, 'deleteStaff'])->name('admin.staff.delete');
});

Route::prefix('committee')->group(function () {
    Route::get('/dashboard', [CommitteeController::class, 'committee'])
        ->name('committee.dashboard');
    Route::get('/event', [CommitteeController::class, 'indexEvent'])
        ->name('committee.event.index');
    Route::post('/event', [CommitteeController::class, 'storeEvent'])
        ->name('committee.event.store');
    Route::delete('/event/{id}', [CommitteeController::class, 'deleteEvent'])
        ->name('committee.event.delete');
    Route::post('/event/certificate', [CommitteeController::class, 'uploadCertificate'])->name('committee.event.uploadCertificate');
    Route::post('/event/scan', [CommitteeController::class, 'scanAttendance'])->name('committee.event.scanAttendance');
   
Route::get('/committee/event/scan', function() {
    return view('committee.dashboard.scan');
})->name('committee.event.scan');
});

Route::post('/member/register/{eventId}', [EventController::class, 'registerEvent'])->name('member.register');
Route::get('/member', [MemberController::class, 'index'])->name('member.dashboard');
Route::post('/member/upload-bukti/{id}', [MemberController::class, 'uploadBukti'])->name('member.uploadBukti');

Route::get('/finance', [FinanceController::class, 'dashboard'])->name('finance.dashboard');
Route::put('/dashboard/finance/payment-status', [FinanceController::class, 'updatePaymentStatus'])->name('finance.updatePaymentStatus');

