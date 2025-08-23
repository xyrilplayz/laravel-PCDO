<?php

use App\Http\Controllers\Programs;
use App\Http\Controllers\xy;
use App\Http\Controllers\Cooperatives;
use App\Http\Controllers\CooperativeUpload;
use App\Models\Program;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Checklist;


Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/login', [xy::class, 'login'])->name('login');
Route::post('/login', [xy::class, 'loginPost'])->name('login.post');

Route::get('/registration', [xy::class, 'registration'])->name('registration');
Route::post('/registration', [xy::class, 'registrationPost'])->name('registration.post');

Route::get('/logout', [xy::class, 'logout'])->name('logout');

Route::group(['middleware' => 'auth'], function () {
    // Cooperatives routes
    Route::get('/createcooperative', [Cooperatives::class, 'coop'])->name('cooperatives.create');
    Route::post('/createcooperative_details', [Cooperatives::class, 'creatcoopPost'])->name('cooperatives.post');
    Route::get('/checklist/{cooperative}', [Checklist::class, 'show'])->name('checklist.show');
    Route::post('/checklist/{cooperative}/upload', [Checklist::class, 'upload'])->name('checklist.upload');;
    Route::get('/checklist/download/{id}', [Checklist::class, 'download'])->name('checklist.download');
    Route::get('/uploads/search', [Checklist::class, 'searchUploads'])->name('uploads.search');
    Route::delete('/uploads/delete/{id}', [Checklist::class, 'delete'])->name('checklist.delete');


});
