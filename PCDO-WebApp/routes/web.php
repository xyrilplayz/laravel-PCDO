<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CooperativesController;
// use App\Http\Controllers\CoopHistoryController;
use App\Http\Controllers\CoopMemberController;
use App\Http\Controllers\CoopProgramController;
use App\Http\Controllers\CoopDetailsController;
use App\Http\Controllers\CoopProgramChecklistController;
use App\Http\Controllers\SyncController;
use Inertia\Inertia;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    // Cooperatives Routes
    Route::resource('cooperatives', CooperativesController::class);
    Route::get('cooperatives/search', [CooperativesController::class, 'search'])->name('cooperatives.search');
    Route::get('cooperatives/export', [CooperativesController::class, 'export'])->name('cooperatives.export');
    Route::post('cooperatives/import', [CooperativesController::class, 'import'])->name('cooperatives.import');

    // Cooperatives Nested Routes
    // Route::get('cooperatives/{cooperative}/history', [CoopHistoryController::class, 'index'])->name('cooperatives.history');
    Route::get('cooperatives/{cooperative}/members', [CoopMemberController::class, 'index'])->name('cooperatives.members');
    Route::get('cooperatives/{cooperative}/programs', [CoopProgramController::class, 'index'])->name('cooperatives.programs');
    Route::get('cooperatives/{cooperative}/details', [CoopDetailsController::class, 'show'])->name('cooperatives.details');


    // Cooperatives Programs Routes
    Route::resource('coop-programs', CoopProgramController::class);
    Route::get('coop-programs/search', [CoopProgramController::class, 'search'])->name('coop-programs.search');
    Route::get('coop-programs/export', [CoopProgramController::class, 'export'])->name('coop-programs.export');
    Route::post('coop-programs/import', [CoopProgramController::class, 'import'])->name('coop-programs.import');

    // Cooperatives Program Nested Routes
    Route::resource('coop-programs/{cooperative}/checklists', CoopProgramChecklistController::class);

    // Custom Command Routes
    Route::get('/sync', [SyncController::class, 'sync'])->name('sync');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';