<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Models\AdditionalFile;
use Illuminate\Support\Facades\Storage;

class AdditionalFilesController extends Controller
{
    public function index(Request $request)
    {
        $additionalFiles = AdditionalFile::whereUserId($request->user()->id)->get();

        return Inertia::render('AdditionalFiles/Index', compact('additionalFiles'));
    }

    public function store(Request $request)
    {
        // Validate the incoming request
        $request->validate([
            'file' => 'required|file|max:1024',
        ]);

        // Retrieve the uploaded file
        $uploadedFile = $request->file('file');

        // Generate a unique filename: timestamp_originalname.extension
        $filename = time() . '_' . $uploadedFile->getClientOriginalName();

        // Determine the directory path based on user ID
        $directory = (string) $request->user()->id;

        // Store the file in the 'additional-files' disk under user's directory
        $path = $uploadedFile->storeAs($directory, $filename, 'additional-files');

        // Create a new record in the database
        AdditionalFile::create([
            'path' => $path,
            'label' => $uploadedFile->getClientOriginalName(),
            'user_id' => $request->user()->id
        ]);

        return redirect()->back()->with('message', 'File uploaded successfully.');
    }

    public function show(Request $request, AdditionalFile $additionalFile)
    {
        if ($additionalFile->user_id !== $request->user()->id) {
            return abort(403);
        }

        $path = Storage::disk('additional-files')->path($additionalFile->path);

        return response()->file($path);
    }
}