<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use Inertia\Inertia;

class LegalQuestionPdfController extends Controller
{
    public function index(Request $request) {
        $agent = Role::whereName('internal-agent')->first();

        $agents = User::whereHas('roles', function ($query) use ($agent) {
            $query->where('role_id', $agent->id);
        })
        ->where(function($query) use ($request) {
            if(isset($request->name) && $request->name != '') {
                $query->where('first_name', 'LIKE', '%' . $request->name . '%')
                ->orWhere('last_name', 'LIKE', '%' . $request->name . '%');
            }
        })
        ->where(function($query) use ($request) {
            if(isset($request->email) && $request->email != '') {
                $query->where('email', 'LIKE', '%' . $request->email . '%');
            }
        })
        ->where(function($query) use ($request) {
            if(isset($request->phone) && $request->phone != '') {
                $query->where('phone', 'LIKE', '%' . $request->phone . '%');
            }
        })
        ->where(function ($query) use ($request) {
            if (isset($request->first_six_card_no) && $request->first_six_card_no != '') {
                $query->whereHas('cards', function ($query) use ($request) {
                    $query->whereRaw('SUBSTRING(number, 1, 6) = ?', [$request->first_six_card_no]);
                });
            }
        })
        ->where(function ($query) use ($request) {
            if (isset($request->last_four_card_no) && $request->last_four_card_no != '') {
                $query->whereHas('cards', function ($query) use ($request) {
                    $query->whereRaw('SUBSTRING(number, -4) = ?', [$request->last_four_card_no]);
                });
            }
        })
        ->whereHas('internalAgentContract.legalQuestion')
        ->with(['internalAgentContract.legalQuestion' => function ($query) {
            $query->where('value', 'YES')->whereNotNull('description');
        }])
        ->paginate(10);
        return Inertia::render('Admin/Agent/LegalQuestions', [
            'requestData'=>$request->all(),
            'agents' => $agents,
        ]);
    }
}
