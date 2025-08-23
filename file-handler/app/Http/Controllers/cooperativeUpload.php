<?php

namespace App\Http\Controllers;

use App\Models\CooperativeUploads;
use Illuminate\Http\Request;

class CooperativeUpload extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'cooperative_id' => 'required|exists:cooperatives,id',
            'checklist_item_id' => 'required|exists:checklist_items,id',
            'file' => 'required|file|max:5120', // limit 5MB
        ]);

        $file = $request->file('file');

        CooperativeUploads::create([
            'cooperative_id' => $request->cooperative_id,
            'checklist_item_id' => $request->checklist_item_id,
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getClientMimeType(),
            'file_content' => file_get_contents($file->getRealPath()),

        ]);

        return back()->with('success', 'File uploaded successfully!');
    }

    public function download($id)
    {
        $upload = CooperativeUploads::findOrFail($id);

        return response(base64_decode($upload->file_content))
            ->header('Content-Type', $upload->mime_type)
            ->header('Content-Disposition', 'attachment; filename="' . $upload->file_name . '"');
    }
}
