@extends('layout')

@section('title', 'Loan Tracker')

@section('content')
    <div class="container mt-4">
        <h2>Loan Tracker for {{ $loan->program->name }}</h2>
        <h3>Loan Tracker for {{ $loan->cooperative->name }}</h3>
        <p><strong>Amount:</strong> ₱{{ number_format($loan->amount, 2) }}</p>
        <p><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($loan->start_date)->toFormattedDateString() }}</p>
        <p><strong>Grace Period:</strong> {{ $loan->program->grace_period }} months</p>
        <p><strong>Term:</strong> {{ $loan->term_months }} months</p>

        @php
            $nextDueId = $loan->paymentSchedules->firstWhere('is_paid', false)?->id;
        @endphp

        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Due Date</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($loan->paymentSchedules as $schedule)
                    @php
                        $isOverdue = !$schedule->is_paid && $schedule->due_date->isPast();
                        $isNextDue = !$schedule->is_paid && !$isOverdue && $schedule->id == $nextDueId;
                    @endphp
                    <tr @if($schedule->is_paid) class="table-success" @elseif($isOverdue) class="table-danger"
                    @elseif($isNextDue) class="table-warning" @endif>
                        <td>{{ $schedule->due_date->toFormattedDateString() }}</td>
                        <td>₱{{ number_format($schedule->amount_due, 2) }}
                            <form action="{{ route('schedules.post', $schedule->id) }}" method="POST" class="mt-1">
                                @csrf
                                <input type="number" step="0.01" name="amount_paid" value="{{ $schedule->amount_paid }}"
                                    class="form-control form-control-sm" placeholder="Amount paid">
                                <button type="submit" class="btn btn-sm btn-warning mt-1">Update Payment</button>
                            </form>
                        </td>
                        <td>
                            @if($schedule->is_paid)
                                ✅ Paid on {{ $schedule->paid_at->toFormattedDateString() }}
                            @elseif($isOverdue)
                                ❌ Overdue
                            @elseif($isNextDue)
                                🔜 Next Due
                            @else
                                Pending
                            @endif
                        </td>
                        <td>
                            @if(!$schedule->is_paid)
                                <form action="{{ route('schedules.markPaid', $schedule->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary">Mark Paid</button>
                                </form>
                                <form action="{{ route('loans.sendOverdueEmail', $loan->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-danger">
                                        Send Overdue Email
                                    </button>
                                </form>
                            @else
                                <span class="text-muted">Already Paid</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection