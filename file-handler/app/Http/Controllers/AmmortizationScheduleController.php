<?php

namespace App\Http\Controllers;

use App\Models\AmmortizationSchedule;
use Illuminate\Http\Request;

class AmmortizationScheduleController extends Controller
{
    // Show all schedules for a loan
    public function index($loanId)
    {
        $schedules = AmmortizationSchedule::where('loan_id', $loanId)->get();
        return view('schedules.index', compact('schedules', 'loanId'));
    }
}
