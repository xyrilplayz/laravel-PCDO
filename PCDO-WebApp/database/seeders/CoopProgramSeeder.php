<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Cooperative;
use App\Models\CoopProgram;
use App\Models\Program;

class CoopProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allPrograms = Program::all();

        foreach (Cooperative::all() as $coop) {
            foreach ($allPrograms as $program) {
                CoopProgram::create([
                    'coop_id' => (string)$coop->id,
                    'program_id' => $program->id,
                    'start_date' => now(),
                    'end_date' => now()->addMonths($program->term_months),
                    'program_status' => 'Ongoing',
                    'number' => null,
                    'email' => $coop->name . '@example.com',
                    'loan_ammount' => rand($program->min_amount, $program->max_amount),
                ]);
            }
        }
    }
}
