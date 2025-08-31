<?php
namespace App\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
class Loan extends Model
{
    public function paymentSchedules()
    {
        return $this->hasMany(PaymentSchedule::class);
    }

    /**
     * Generate schedules once the loan starts
     */
    public function generateSchedule()
    {
        $amountPerMonth = $this->amount / $this->term_months;
        $startDate = Carbon::parse($this->start_date)->addMonths($this->grace_period);

        for ($i = 0; $i < $this->term_months; $i++) {
            $this->paymentSchedules()->create([   // <- correct relation
                'due_date' => $startDate->copy()->addMonths($i),
                'amount_due' => $amountPerMonth,
            ]);
        }
    }
    protected $fillable = [
        'cooperative_id',
        'program_id',
        'amount',
        'start_date',
        'grace_period',
        'term_months',
    ];
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function cooperative()
    {
        return $this->belongsTo(Cooperative::class,'cooperative_id');
    }
    
}


?>