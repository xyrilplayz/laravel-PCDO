<?php

use App\Http\Controllers\xyril;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/login', [xyril::class, 'login'])->name('login');
Route::post('/login', [xyril::class, 'loginPost'])->name('login.post');

Route::get('/registration', [xyril::class, 'registration'])->name('registration');
Route::post('/registration', [xyril::class, 'registrationPost'])->name('registration.post');

Route::get('/logout', [xyril::class, 'logout'])->name('logout');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/profile', function () {
        return "hi";
    })-> name('profile');
});
