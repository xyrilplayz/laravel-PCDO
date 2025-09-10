<?php

namespace App\Http\Controllers;

use App\Models\CoopProgram;
use App\Models\Cooperative;
use App\Models\Programs;
use Inertia\Inertia;
use Illuminate\Http\Request;

class CoopProgramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $coopPrograms = CoopProgram::with(['program', 'cooperative', 'checklist'])
            ->where('program_status', 'Ongoing')
            ->get();

        return inertia('coop-program/index', [
            'coopPrograms' => $coopPrograms,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('coop-program/create', [
            'cooperatives' => Cooperative::all(),
            'programs' => Programs::all(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'coop_id' => 'required|exists:cooperatives,id',
            'program_id' => 'required|exists:programs,id',
        ]);

        $coopId = $data['coop_id'];
        $programId = $data['program_id'];

        $program = Programs::findOrFail($programId);

        $ongoingPrograms = CoopProgram::where('coop_id', $coopId)
                            ->where('program_status', 'Ongoing')
                            ->with('program')
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

        $coopProgram = CoopProgram::create([
            'coop_id' => $coopId,
            'program_id' => $programId,
            'start_date' => now(),
            'end_date' => now()->addMonths($program->term_months),
            'program_status' => 'Ongoing',
            'email' => Cooperative::find($coopId)->name . '@example.com',
            'loan_ammount' => rand($program->min_amount, $program->max_amount),
        ]);

        return redirect()->route('coop-programs.documents', $coopProgram->id)
            ->with('success', 'Program enrolled successfully. Please upload required documents.');
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

    public function schedule(CoopProgram $coopProgram)
    {
        return inertia('coop-program/schedule', [
            'coopProgram' => $coopProgram
        ]);
    }

    public function documents(CoopProgram $coopProgram)
    {
        $checklistItems = $coopProgram->program->checklists->map(function ($item) use ($coopProgram) {
            $upload = $coopProgram->checklists()
                ->where('program_checklist_id', $item->pivot->id)
                ->first();

            $item->upload = $upload;
            return $item;
        });

        return Inertia::render('coop-program/document', [
            'coopProgram' => $coopProgram->load('program'),
            'checklistItems' => $checklistItems,
        ]);
    }

    public function upload(Request $request, CoopProgram $coopProgram)
    {
        $request->validate([
            'program_checklist_id' => 'required|exists:program_checklists,id',
            'file' => 'required|file|max:5120',
        ]);

        $file = $request->file('file');

        $coopProgram->checklists()->updateExistingPivot(
            $request->program_checklist_id,
            [
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'file_content' => base64_encode(file_get_contents($file)),
                'updated_at' => now(),
            ]
        );

        return back()->with('success', 'File uploaded successfully!');
    }

    public function download(CoopProgram $coopProgram, $programChecklistId)
    {
        $pivot = $coopProgram->checklists()->where('program_checklist_id', $programChecklistId)->firstOrFail()->pivot;

        return response(base64_decode($pivot->file_content))
            ->header('Content-Type', $pivot->mime_type)
            ->header('Content-Disposition', 'attachment; filename="' . $pivot->file_name . '"');
    }

    public function destroyUpload(CoopProgram $coopProgram, $programChecklistId)
    {
        $coopProgram->checklists()->updateExistingPivot($programChecklistId, [
            'file_name' => null,
            'mime_type' => null,
            'file_content' => null,
        ]);

        return back()->with('success', 'File deleted successfully!');
    }

}
