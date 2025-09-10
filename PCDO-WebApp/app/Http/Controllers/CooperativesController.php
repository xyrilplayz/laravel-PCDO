<?php

namespace App\Http\Controllers;
use App\Models\Cooperative;
use Illuminate\Http\Request;

class CooperativesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cooperatives = Cooperative::withCount('members')
            ->with(['programs' => function ($q) {
                $q->where('program_status', 'ongoing');
            }])
            ->with('details')
            ->get()
            ->map(function ($coop) {
                return [
                    'id' => $coop->id,
                    'name' => $coop->name,
                    'type' => $coop->type,
                    'holder' => $coop->holder,
                    'member_count' => $coop->details->members_count,
                    'has_ongoing_program' => $coop->programs->isNotEmpty(),
                ];
            });

        return inertia('cooperatives/index', [
            'cooperatives' => $cooperatives,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('cooperatives/create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:cooperatives,name',
            'holder' => 'nullable|string|max:255',
            'type' => 'required|in:primary,secondary,tertiary',
            ''
        ]);

        $cooperative = Cooperative::create($data);

        return redirect()
            ->route('cooperatives.details', $cooperative)
            ->with('success', 'Cooperative created successfully!'); 
    }

    /**
     * Display the specified resource.
     */
    public function show(Cooperative $cooperative)
    {
        $cooperative->load('details');
        return inertia('cooperatives/show', [
            'cooperative' => $cooperative,
            'details' => $cooperative->details,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cooperative $cooperative)
    {
        return inertia('cooperatives/edit', [
            'cooperative' => $cooperative,
            'details' => $cooperative->details,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cooperative $cooperative)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:cooperatives,name,' . $cooperative->id,
            'holder' => 'nullable|string|max:255',
            'type' => 'required|in:primary,secondary,tertiary',
        ]);

        $cooperative->update($data);

        return redirect()
            ->route('cooperatives.index')
            ->with('success', 'Cooperative updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cooperative $cooperative)
    {
        $cooperative->delete();

        return redirect()
            ->route('cooperatives.index')
            ->with('success', 'Cooperative deleted successfully!');
    }

    public function import()
    {
        return inertia('cooperatives/import');
    }

    public function export()
    {
        return inertia('cooperatives/export');
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('query');

        $cooperatives = Cooperative::where('name', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('id', 'LIKE', '%' . $searchTerm . '%')
            ->orWhere('holder' , 'LIKE', '%' . $searchTerm . '%')
            ->get();

        return inertia('cooperatives/index', [
            'cooperatives' => $cooperatives,
            'searchTerm' => $searchTerm,
        ]);
    }
}
