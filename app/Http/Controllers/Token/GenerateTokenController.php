<?php

namespace App\Http\Controllers\Token;

use App\Http\Controllers\Controller;
use App\Models\Token;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

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
