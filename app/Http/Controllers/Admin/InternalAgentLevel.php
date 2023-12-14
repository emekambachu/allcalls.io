<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InternalAgentLevel as AgentLevel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class InternalAgentLevel extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $levels = AgentLevel::orderBy('order', 'asc')->paginate(100);
        return Inertia::render('Admin/AgentLevel/Index', [
            'levels' => $levels,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:'.AgentLevel::class.',name',
            'order' => 'required|numeric|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 400);
        }
        AgentLevel::create([
            'name' => $request->name,
            'order' => $request->order,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Internal Agent Level Added Successfully.',
        ], 200);
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
    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('internal_agent_levels', 'name')->ignore($request->lavel_id),
            ],
            'order' => [
            'required',
            'numeric',
            'max:255',
            Rule::unique('internal_agent_levels', 'order')->ignore($request->lavel_id),
        ],
        ]);


        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 400);
        }
        $agentLevel = AgentLevel::find($request->lavel_id);

        if (!$agentLevel) {
            return response()->json([
                'success' => false,
                'message' => 'Agent Level not found.',
            ], 404);
        }
        $agentLevel->name = $request->name;
        $agentLevel->order = $request->order;
        $agentLevel->save();

        return response()->json([
            'success' => true,
            'message' => 'Internal Agent Level Added Successfully.',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $exist = AgentLevel::findOrFail($id);

        if(count($exist->getAgentInvites)) {
            return response()->json([
                'success' => false,
                'message' => "Sorry! you can't delete this. Some of the agents pending invites belongs to this level.",
            ], 400);
        }

        if(count($exist->getRegisteredAgentInvites)) {
            return response()->json([
                'success' => false,
                'message' => "Sorry! you can't delete this. Some of the registered agents belongs to this level.",
            ], 400);
        }

        $exist->delete();
        return response()->json([
            'success' => false,
            'message' => "Level deleted successfully.",
        ], 200);
    }
}
