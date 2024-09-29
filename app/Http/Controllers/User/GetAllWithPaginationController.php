<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\GetAllWithPaginationRequest;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class GetAllWithPaginationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(GetAllWithPaginationRequest $request)
    {
        $page = $request->input('page', 1);
        $count = $request->input('count', 10);

        $users = User::paginate($count, ['*'], 'page', $page);

        if ($users->currentPage() > $users->lastPage()) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found'
            ], 404);
        }

        $nextUrl = $users->nextPageUrl();
        $prevUrl = $users->previousPageUrl();

        $response = [
            'success' => true,
            'page' => $users->currentPage(),
            'total_pages' => $users->lastPage(),
            'total_users' => $users->total(),
            'count' => $users->perPage(),
            'links' => [
                'next_url' => $nextUrl,
                'prev_url' => $prevUrl,
            ],
            'users' => UserResource::collection($users->items())
        ];

        return response()->json($response);
    }
}
