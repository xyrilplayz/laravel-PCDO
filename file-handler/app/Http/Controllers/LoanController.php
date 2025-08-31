<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\PaymentSchedule;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Program;
use App\Notifications\LoanOverdueNotification;
use Illuminate\Support\Facades\Log;
class LoanController extends Controller
{
    // Show all loans
    public function index()
    {
        $loans = Loan::with('cooperative', 'program', 'paymentSchedules')->get();
        return view('loans.index', compact('loans'));
    }
    // Show a single loan with schedules
    public function show(Loan $loan)
    {
        $loan->load([
            'program',
            'cooperative',
            'paymentSchedules' => fn($q) => $q->orderBy('due_date'),
        ]);

        return view('loan_tracker', compact('loan'));
    }

    // Store a new loan and auto-generate payment schedule

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cooperative_id' => 'required|exists:cooperatives,id',
            'program_id' => 'required|exists:programs,id',
            'amount' => 'required|numeric',
            'start_date' => 'required|date',
        ]);

        // Get program details
        $program = Program::findOrFail($validated['program_id']);

        // Merge program values into loan
        $loan = Loan::create([
            'cooperative_id' => $validated['cooperative_id'],
            'program_id' => $validated['program_id'],
            'amount' => $validated['amount'],
            'start_date' => $validated['start_date'],
            'grace_period' => $program->grace_period,   // auto-fill from program
            'term_months' => $program->term_months,    // auto-fill from program
        ]);

        // Generate payment schedule
        $dueDate = Carbon::parse($loan->start_date)->addMonths($loan->grace_period);
        $monthlyAmount = $loan->amount / $loan->term_months;

        for ($i = 1; $i <= $loan->term_months; $i++) {
            PaymentSchedule::create([
                'loan_id' => $loan->id,
                'due_date' => $dueDate->copy(),
                'amount_due' => $monthlyAmount,
            ]);
            $dueDate->addMonth();
        }

        return redirect()
            ->route('loans.index')
            ->with('success', 'Loan created and payment schedule generated.');
    }

    public function updateAmount(Request $request, Loan $loan)
    {
        $request->validate([
            'amount' => 'nullable|numeric|min:0',
            'preset' => 'nullable|in:min,max',
        ]);

        // Determine new loan amount
        if ($request->preset === 'min') {
            $loan->amount = $loan->program->min_amount;
        } elseif ($request->preset === 'max') {
            $loan->amount = $loan->program->max_amount;
        } elseif ($request->amount) {
            $loan->amount = $request->amount;
        }

        $loan->save();

        // Delete old schedules and regenerate
        $loan->paymentSchedules()->delete();
        $loan->generateSchedule();

        return back()->with('success', 'Loan amount updated and payment schedule regenerated!');
    }

    public function sendOverdueEmail($loanId)
    {
        $loan = Loan::with('cooperative.user')->findOrFail($loanId);

        if ($loan->cooperative && $loan->cooperative->user) {
            $user = $loan->cooperative->user;

            try {
                Log::info("Sending LoanOverdueNotification to: " . $user->email);

                $user->notify(new LoanOverdueNotification($loan));

                return back()->with('success', 'Overdue email sent to ' . $user->email);
            } catch (\Exception $e) {
                Log::error("Notification failed: " . $e->getMessage());
                return back()->with('error', 'Notification failed: ' . $e->getMessage());
            }
        }

        return back()->with('error', 'No user/email found for this loan.');
    }

}
