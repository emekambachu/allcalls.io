<?php

namespace App\Http\Controllers\InternalAgent;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TrainingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('InternalAgent/Traning/Index', []);
    }
    public function downloadPdf($fileName)
    {
        // $file = public_path("Agent Training/$request->file_name");
        $file = public_path("Agent Training" . DIRECTORY_SEPARATOR . $fileName);                        
        if (file_exists($file)) {
            $contentType = mime_content_type($file);
            // dd($file, $contentType);
            return response()->download($file, $fileName, ['Content-Type' => $contentType]);
        } else {
            return response()->json(['error' => 'File not found'], 404);
        }
    }
    public function trainingComplete($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->basic_training = true;
            $user->save();
            return response()->json([
                'success' => true,
                'message' => 'Your Training Completed Successfully.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->getMessage(),
            ], 400);
        }
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
