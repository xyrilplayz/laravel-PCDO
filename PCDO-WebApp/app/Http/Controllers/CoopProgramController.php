<?php

namespace App\Http\Controllers;

use App\Models\CoopProgram;
use App\Models\Cooperative;
use App\Models\ProgramChecklists;
use App\Models\Program;
use Illuminate\Http\Request;

class CoopProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cooperatives = Cooperative::with('programs');
        $coopPrograms = CoopProgram::with('checklists');
        

        $checklistCount = ProgramChecklists::count();

        // Add a computed status for each cooperative
        $cooperatives->transform(function ($coop) use ($checklistCount) {
            if ($coop->uploaded_files_count >= $checklistCount && $checklistCount > 0) {
                $coop->status = 'Complete';
            } elseif ($coop->uploaded_files_count > 0) {
                $coop->status = 'Pending';
            } else {
                $coop->status = 'Incomplete';
            }
            return $coop;
        });

        return inertia('cooperative/index', [
            'cooperatives' => $cooperatives,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $coopId = $request->input('coop_id');
        $programId = $request->input('program_id');
        
        $program = Program::findOrFail($programId);
        $ongoingPrograms = CoopProgram::where('coop_id', $coopId)
                            ->where('program_status', 'Ongoing')
                            ->get();

        foreach ($ongoingPrograms as $ongoing) {
            if ($ongoing->program_id === $programId) {
                return back()->withErrors(['program_id' => 'This program is already ongoing.']);
            }
            if ($program->name === 'LICAP' && $ongoing->program->name === 'LICAP') {
                return back()->withErrors(['program_id' => 'LICAP program already ongoing.']);
            }
            if ($program->name !== 'LICAP' && $ongoing->program->name !== 'LICAP') {
                return back()->withErrors(['program_id' => 'Cannot enroll in another non-LICAP program while one is ongoing.']);
            }
        }

        // Only create if validation passed
        CoopProgram::create([
            'coop_id' => $coopId,
            'program_id' => $programId,
            'start_date' => now(),
            'end_date' => now()->addMonths($program->term_months),
            'program_status' => 'Ongoing',
            'email' => Cooperative::find($coopId)->name . '@example.com',
            'loan_ammount' => rand($program->min_amount, $program->max_amount),
        ]);

        return redirect()->back()->with('success', 'Program enrolled successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(CoopProgram $coopProgram)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CoopProgram $coopProgram)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CoopProgram $coopProgram)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CoopProgram $coopProgram)
    {
        //
    }
}
