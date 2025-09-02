<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\Cooperative;
use Illuminate\Support\Facades\Auth;

class CooperativesController extends Controller
{
    // Show create form
    public function coop()
    {
        return view('createcooperative');
    }

    // Handle post request
    public function creatcoopPost(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'program_id' => 'required|exists:programs,id',
        ]);

        // Check if cooperative already exists under this program
        $existing = Cooperative::where('program_id', $request->program_id)
            ->where('name', $request->name)
            ->first();

        if ($existing) {
            return view('coop_exists', [
                'cooperative' => $existing
            ]);
        }

        // Create cooperative
        //dd($request->all());

        $cooperative = Cooperative::create([
            'name' => $request->name,
        ]);

        return redirect()->route('checklist.show', $cooperative->id)
            ->with('success', 'Cooperative created successfully!');
    }

}
