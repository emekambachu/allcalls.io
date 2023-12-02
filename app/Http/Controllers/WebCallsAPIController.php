<?php

namespace App\Http\Controllers;

use App\Models\Call;
use Illuminate\Http\Request;

class WebCallsAPIController extends Controller
{
    protected $supportedSortColumns = ['id'];
    protected $supportedFilters = ['id'];

    public function index(Request $request)
    {
        $query = Call::whereUserId($request->user()->id);

        $sortColumn = $request->input('sort_column', 'id');
        $sortDirection = $request->input('sort_direction', 'desc');

        if (in_array($sortColumn, $this->supportedSortColumns)) {
            $sortDirection = $sortDirection === 'asc' ? 'asc' : 'desc';
            $query->orderBy($sortColumn, $sortDirection);
        }

        foreach ($this->supportedFilters as $filterKey) {
            if ($request->has($filterKey)) {
                $query->where($filterKey, $request->input($filterKey));
            }
        }

        $calls = $query->paginate();

        return ['calls' => $calls];
    }

}
