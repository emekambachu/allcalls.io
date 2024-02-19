<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\User;
use App\Services\Base\BaseService;
use App\Services\User\UserService;
use Exception;
use Faker\Provider\Base;
use Inertia\Inertia;
use App\Models\State;
use Inertia\Response;
use App\Models\CallType;
use App\Models\Timezone;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\UserCallTypeState;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class ProfileController extends Controller
{
    protected UserService $user;
    public function __construct(UserService $user){
        $this->user = $user;
    }
    /**
     * Display the user's profile form.
     */
    public function view(Request $request): Response
    {
        $user = User::with('getAgentLevel')->find($request->user()->id);
        $internalAgent=$user->hasRole('internal-agent');
        // Get user's call types with states
        $userCallTypesWithStates = $user->callTypes()->with('states')->get();

        // For some reason it's returning the correct call types
        // (only the one's associated with the authenticated user)
        // but wrong states event the ones that do not belong to the current user.
        // So, I'm just filtering it down to currently authenticated user, might be a cleaner
        // way to go about it. Might update later. :)

        $userCallTypesWithStates = $userCallTypesWithStates->map(function ($type) use ($user) {
            $type->user_states = $type->states->filter(function ($state) use ($user) {
                return (int) $state->pivot->user_id === (int) $user->id;
            })->toArray();

            return $type;
        });

        $callTypes = CallType::all();
        $states = State::all();

        $callTypes = $callTypes->map(function ($callType) use ($userCallTypesWithStates, $states) {
            // Find the corresponding call type in $userCallTypesWithStates, if it exists
            $userCallType = $userCallTypesWithStates->firstWhere('id', $callType->id);

            // If a corresponding call type was found in $userCallTypesWithStates, set the call type's selected property
            $callType->selected = $userCallType !== null;

            $callType->statesWithSelection = $states->map(function ($state) use ($userCallType) {
                if ($userCallType !== null && in_array($state->id, array_column($userCallType->user_states, 'id'))) {
                    $state['selected'] = true;
                } else {
                    $state['selected'] = false;
                }

                return $state;
            })->toArray();

            return $callType;

        });

        $bids = Bid::whereUserId($request->user()->id)->with('callType')->get();

        return Inertia::render('Profile/View', compact('user', 'callTypes', 'bids', 'internalAgent'));
    }

    /**
     * Edut the user's profile form.
     */
    public function edit(Request $request): Response
    {
        $user = $request->user();
        // $role=
        $internalAgent=$user->hasRole('internal-agent');

        // Get user's call types with states
        $userCallTypesWithStates = $user->callTypes()->with('states')->get();

        // For some reason it's returning the correct call types
        // (only the one's associated with the authenticated user)
        // but wrong states event the ones that do not belong to the current user.
        // So, I'm just filtering it down to currently authenticated user, might be a cleaner
        // way to go about it. Might update later. :)

        $userCallTypesWithStates = $userCallTypesWithStates->map(function ($type) use ($user) {
            $type->user_states = $type->states->filter(function ($state) use ($user) {
                return (int) $state->pivot->user_id === (int) $user->id;
            })->toArray();

            return $type;
        });

        $callTypes = CallType::all();
        $states = State::all();
        $timezone = Timezone::all();
        $callTypes = $callTypes->map(function ($callType) use ($userCallTypesWithStates, $states) {
            // Find the corresponding call type in $userCallTypesWithStates, if it exists
            $userCallType = $userCallTypesWithStates->firstWhere('id', $callType->id);

            // If a corresponding call type was found in $userCallTypesWithStates, set the call type's selected property
            $callType->selected = $userCallType !== null;

            $callType->statesWithSelection = $states->map(function ($state) use ($userCallType) {
                if ($userCallType !== null && in_array($state->id, array_column($userCallType->user_states, 'id'))) {
                    $state['selected'] = true;
                } else {
                    $state['selected'] = false;
                }

                return $state;
            })->toArray();

            return $callType;

        });

        $bids = Bid::whereUserId($request->user()->id)->with('callType')->get();
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'callTypes' => $callTypes,
            'bids' => $bids,
            'internalAgent'=>$internalAgent,
            'timezones' => $timezone
        ]);
    }


    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        dd($request->all());

        $user = $request->user();
        $user->fill($request->validated());
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();

        $incomingData = $this->buildIncomingData($request->selected_states);

        UserCallTypeState::updateUserCallTypeState($user, $incomingData);

        return Redirect::route('profile.edit')->with('message', 'Profile updated successfully.');
    }

//    public function api_update(ProfileUpdateRequest $request): \Illuminate\Http\JsonResponse
//    {
//        try {
//            $data = $this->user->updateUser($request);
//            return response()->json($data);
//
//        }catch (Exception $e){
//            return BaseService::tryCatchException($e);
//        }
//    }

    private function buildIncomingData($selectedStates): array
    {
        // Initialize an empty array to store the call type and state combinations.
        $incomingData = [];

        // For each selected state item in the provided list
        foreach ($selectedStates as $item) {
            // Extract the call type ID
            $typeId = $item['typeId'];
            // Extract the list of selected state IDs
            $stateIds = $item['selectedStateIds'];

            // For each state ID in the list of selected state IDs
            foreach ($stateIds as $stateId) {
                // Add a new entry to the incoming data with the current call type ID and state ID.
                // Each entry represents a combination of a call type and state that the user has selected.
                $incomingData[] = [
                    'call_type_id' => $typeId,
                    'state_id' => $stateId,
                ];
            }
        }

        // Return the populated incoming data array.
        return $incomingData;
    }

    public function uploadProfilePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpg,jpeg,png', // 5MB Max
        ]);

        $user = Auth::user();

        // Get the file extension of the uploaded file
        $extension = $request->file('profile_picture')->getClientOriginalExtension();

        // Create a unique filename
        $uniqueFilename = Str::random(10) . '.' . $extension;

        // Create a directory path using the user's ID
        $directory = 'profile_pictures/' . $user->id;

        // Store the file in the created directory with the unique filename
        $path = $request->file('profile_picture')->storeAs($directory, $uniqueFilename, 'public');

        // Optionally delete the old image if it exists and is different from the new one
        if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        // Update the user's profile picture path in the database
        $profileUrl = Storage::url($path);
        $user->profile_picture = $profileUrl;
        $user->save();

        Log::info("User profile picture request came in for: " . $user->email);
        Log::info("Filename: " . $uniqueFilename);
        Log::info("Path: " . $path);
        Log::info("Profile URL: " . $profileUrl);


        // Return a JSON response
        return response()->json([
            'success' => true,
            'message' => 'Profile picture updated successfully.',
            'profile_picture_url' => $profileUrl,
        ]);
    }


    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
