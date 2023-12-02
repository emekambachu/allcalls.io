<?php

namespace App\Http\Controllers;

use App\Models\Call;
use Illuminate\Http\Request;

class WebCallsAPIController extends Controller
{
    protected $supportedSortColumns = ['id'];

    public function index(Request $request)
    {
        $query = Call::whereUserId($request->user()->id);

        // Retrieve sort column and direction from request
        $sortColumn = $request->input('sort_column', 'id');
        $sortDirection = $request->input('sort_direction', 'desc');

        // Validate sort column
        if (in_array($sortColumn, $this->supportedSortColumns)) {
            // Validate sort direction
            $sortDirection = $sortDirection === 'asc' ? 'asc' : 'desc';
            $query->orderBy($sortColumn, $sortDirection);
        }

        $calls = $query->paginate();

        return ['calls' => $calls];
    }

}
