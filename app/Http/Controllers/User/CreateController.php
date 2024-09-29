<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateRequest;
use App\Models\Token;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Laravel\Facades\Image;

class CreateController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(CreateRequest $request)
    {
        $tokenRecord = Token::where('token', $request->input("token"))->first();

        if (!$tokenRecord || ($tokenRecord->is_used || $tokenRecord->expires_at < Carbon::now())) {
            return response()->json([
                "success" => false,
                'message' => 'The token expired.'
            ], 401);
        }

        if($request->phoneOrEmailExists()) {
            return response()->json([
                "success" => false,
                'message' => 'User with this phone or email already exist'
            ], 409);
        }

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->position_id = $request->input('position_id');

        $image = $request->file('photo');
        $imageName = time() . "-" . uniqid() . '.' . $image->getClientOriginalExtension();
        $imagePath = $image->storeAs('images/uploads', $imageName, 'public');

        $img = Image::read(public_path('storage/' . $imagePath));

        // $img->resize(70, 70);
        $width = $img->width();
        $height = $img->height();
        $x = ($width - 70) / 2;
        $y = ($height - 70) / 2;
        $img->crop(70, 70, $x, $y);

        $img->save(public_path('storage/' . $imagePath), 100);

        $optimizedImage = $this->optimizeImageWithTinypng(public_path('storage/' . $imagePath));
        Storage::put($imagePath, $optimizedImage);

        $user->photo = $imagePath;
        $user->save();

        $tokenRecord->is_used = true;
        $tokenRecord->save();

        return response()->json([
            "success" => true,
            "user_id" => $user->id,
            "message" => "New user successfully registered"
        ], 201);
    }

    private function optimizeImageWithTinypng($imagePath)
    {
        $client = new Client();
        $apiKey = env('TINYPNG_API_KEY');

        $response = $client->request('POST', 'https://api.tinify.com/shrink', [
            'auth' => ['api', $apiKey],
            'body' => fopen($imagePath, 'r'),
        ]);

        $optimizedImageUrl = json_decode($response->getBody(), true)['output']['url'];
        $optimizedResponse = $client->request('GET', $optimizedImageUrl);

        return $optimizedResponse->getBody()->getContents();
    }
}
