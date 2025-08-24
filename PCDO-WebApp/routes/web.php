<?php

use Illuminate\Support\Facades\Route;
use App\Jobs\TestJob;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dispatch-test', function() {
    TestJob::dispatch('Hello Redis Queue!');
    return 'Job dispatched!';
});