<?php

namespace App\Http\Controllers\Position;

use App\Http\Controllers\Controller;
use App\Http\Resources\Position\PositionResource;
use App\Models\Position;
use Illuminate\Http\Request;

class GetAllController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $positions = Position::all();

        return response()->json([
            'success' => true,
            'positions' => PositionResource::collection($positions)
        ]);
    }
}
