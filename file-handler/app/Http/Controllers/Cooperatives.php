<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Program;
use App\Models\Cooperative;
use Illuminate\Support\Facades\Auth;

class Cooperatives extends Controller
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
            // Instead of blocking, show a view that lets user decide
            return view('coop_exists', [
                'cooperative' => $existing
            ]);
        }

        // If no existing cooperative, create a new one
        $cooperative = Cooperative::create([
            'name' => $request->name,
            'program_id' => $request->program_id,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('checklist.show', $cooperative->id)
            ->with('success', 'Cooperative created successfully!');
    }

}
