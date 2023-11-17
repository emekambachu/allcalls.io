<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NotificationGroup;
use App\Models\NotificationGroupMember;

class NotificationGroupController extends Controller
{
    // List all groups
    public function index() {
        $groups = NotificationGroup::with('members')->get();
        return response()->json($groups);
    }

    // Create a new group
    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string',
            'user_ids' => 'required|array'
        ]);

        $group = NotificationGroup::create(['name' => $request->name]);

        foreach ($request->user_ids as $userId) {
            NotificationGroupMember::create([
                'notification_group_id' => $group->id,
                'user_id' => $userId
            ]);
        }

        // After creating group members
        $group->load('members');
        return response()->json($group);

    }

    // Delete a group
    public function destroy($id) {
        $group = NotificationGroup::find($id);
        if ($group) {
            $group->members()->delete();
            $group->delete();
            return response()->json(['message' => 'Group deleted successfully!']);
        }

        return response()->json(['message' => 'Group not found'], 404);
    }

    // Add a user to a group
    public function addUser(Request $request, $groupId) {
        $request->validate([
            'user_id' => 'required|exists:users,id'
        ]);

        $member = NotificationGroupMember::create([
            'notification_group_id' => $groupId,
            'user_id' => $request->user_id
        ]);

        return response()->json(['message' => 'User added to group successfully!', 'member' => $member]);
    }

    // Remove a user from a group
    public function removeUser($groupId, $userId) {
        $member = NotificationGroupMember::where('notification_group_id', $groupId)
            ->where('user_id', $userId)
            ->first();

        if ($member) {
            $member->delete();
            return response()->json(['message' => 'User removed from group successfully!']);
        }

        return response()->json(['message' => 'Member not found'], 404);
    }
}
