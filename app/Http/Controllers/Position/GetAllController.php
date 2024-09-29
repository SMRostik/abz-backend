<?php

namespace App\Http\Controllers\Position;

use App\Http\Controllers\Controller;
use App\Http\Resources\Position\PositionResource;
use App\Models\Position;
use Illuminate\Http\Request;

/**
 * @OA\Get(
 *     path="/positions",
 *     summary="Get users positions",
 *     description="Returns a list of all available users positions.",
 *     tags={"Positions"},
 *     @OA\Response(
 *         response=200,
 *         description="A JSON object of positions",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(
 *                 property="success",
 *                 type="boolean",
 *                 example=true
 *             ),
 *             @OA\Property(
 *                 property="positions",
 *                 type="array",
 *                 @OA\Items(type="object",
 *                     @OA\Property(property="id", type="integer", example=1),
 *                     @OA\Property(property="name", type="string", example="Lawyer")
 *                 )
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=404,
 *         description="A JSON object containing errors",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Positions not found")
 *         )
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="A JSON object containing errors",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="success", type="boolean", example=false),
 *             @OA\Property(property="message", type="string", example="Positions not found")
 *         )
 *     )
 * )
 */
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
