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
        $cooperatives = Cooperative::with('details')
            ->withCount(['programs as ongoing_program_count' => function ($q) {
                $q->where('program_status', 'ongoing');
            }])
            ->orderByDesc('ongoing_program_count')
            ->orderBy('id')
            ->get()
            ->map(function ($coop) {
                return [
                    'id' => $coop->id,
                    'name' => $coop->name,
                    'type' => $coop->type,
                    'holder' => $coop->holder,
                    'member_count' => $coop->details->members_count ?? 0,
                    'has_ongoing_program' => $coop->ongoing_program_count > 0,
                ];
            });

        return inertia('cooperatives/index', [
            'cooperatives' => $cooperatives,
            'breadcrumbs' => [
                ['title' => 'Cooperatives', 'href' => route('cooperatives.index')],
            ],
            'urls' => [
                'create' => route('cooperatives.create'),
                'import' => route('cooperatives.import'),
                'export' => route('cooperatives.export'),
                'search' => route('cooperatives.search'),
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('cooperatives/create', [
            'breadcrumbs' => [
                ['title' => 'Cooperatives', 'href' => route('cooperatives.index')],
                ['title' => 'Create Cooperative', 'href' => route('cooperatives.create')],
            ],
        ]);
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
            'breadcrumbs' => [
                ['title' => 'Cooperatives', 'href' => route('cooperatives.index')],
                ['title' => $cooperative->name, 'href' => route('cooperatives.show', $cooperative)],
            ],
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
            'breadcrumbs' => [
                ['title' => 'Cooperatives', 'href' => route('cooperatives.index')],
                ['title' => $cooperative->name, 'href' => route('cooperatives.show', $cooperative)],
                ['title' => 'Edit Cooperative', 'href' => route('cooperatives.edit', $cooperative)],
            ],
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
