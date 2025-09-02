<?php

use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Programs;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\CooperativesController;
use App\Http\Controllers\SyncController;
use App\Http\Controllers\CoopDetailsController;
use App\Http\Controllers\CoopProgramController;
use App\Models\Program;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChecklistController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\AmmortizationScheduleController;



Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/login', [AuthenticationController::class, 'login'])->name('login');
Route::post('/login', [AuthenticationController::class, 'loginPost'])->name('login.post');

Route::get('/registration', [AuthenticationController::class, 'registration'])->name('registration');
Route::post('/registration', [AuthenticationController::class, 'registrationPost'])->name('registration.post');

Route::get('/logout', [AuthenticationController::class, 'logout'])->name('logout');

Route::group(['middleware' => 'auth'], function () {
    // Cooperatives routes
    Route::post('/sync', [SyncController::class, 'sync']);
    Route::get('/createcooperative', [CooperativesController::class, 'coop'])->name('cooperatives.create');
    Route::post('/createcooperative_details', [CoopDetailsController::class, 'creatcoopPost'])->name('cooperatives.post');
    Route::get('/ChecklistController/{cooperative}', [ChecklistController::class, 'show'])->name('ChecklistController.show');
    Route::post('/ChecklistController/{cooperative}/upload', [ChecklistController::class, 'upload'])->name('ChecklistController.upload');
    Route::get('/ChecklistController/download/{id}', [ChecklistController::class, 'download'])->name('ChecklistController.download');
    Route::get('/uploads/search', [ChecklistController::class, 'searchUploads'])->name('uploads.search');
    Route::delete('/uploads/delete/{id}', [ChecklistController::class, 'delete'])->name('ChecklistController.delete');
    Route::resource('loans', LoanController::class);
    Route::get('loans/{loan}/schedules', [AmmortizationScheduleController::class, 'index'])->name('schedules.show');
    Route::put('/loans/{loan}/update-amount', [LoanController::class, 'updateAmount'])->name('loans.updateAmount');
    Route::post('/schedules/{schedule}/note-payment', [PaymentController::class, 'notePayment'])->name('schedules.post');
    Route::post('/schedules/{schedule}/mark-paid', [PaymentController::class, 'PaymentController'])->name('schedules.PaymentController');
    Route::post('/loans/{loan}/send-overdue-email', [LoanController::class, 'sendOverdueEmail'])
        ->name('loans.sendOverdueEmail');
    Route::post('/loans/schedules/{schedule}/penalty', [LoanController::class, 'penalty'])
        ->name('schedules.penalty');


});
