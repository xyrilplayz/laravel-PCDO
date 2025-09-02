<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SyncController
{
    public function sync(Request $request)
    {
        Artisan::call('sync:local-database');
        return redirect()->back();
    }
}
