<?php

namespace App\Http\Controllers\Token;

use App\Http\Controllers\Controller;
use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @OA\Get(
 *     path="/token",
 *     tags={"Token"},
 *     summary="Get a new token",
 *     description="Method returns a token that is required to register a new user. 
 *                  The token is valid for 40 minutes and can be used for only one request. 
 *                  For the next registration, you will need to get a new one.",
 *     @OA\Response(
 *         response=200,
 *         description="A JSON object that contains token",
 *         @OA\JsonContent(
 *             @OA\Property(property="success", type="boolean", example=true),
 *             @OA\Property(property="token", type="string", 
 *                 example="eyJpdiI6Im9mV1NTMFZQT...YWNmYjAwZTI0YjY0NTkyNWIqfQ==")
 *         )
 *     )
 * )
 */
class GenerateTokenController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $token = Str::random(100);

        Token::create([
            "token" => $token,
            "expires_at" => Carbon::now()->addMinutes(40),
        ]);

        return response()->json([
            "success" => true,
            "token" => $token
        ]);
    }
}
