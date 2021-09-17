<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response as FacadesResponse;
use Laravel\Sanctum\PersonalAccessToken;

class AuthTokensController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'username'    => ['required'],
            'password'    => ['required'],
            'device_name' => ['required'],
            'abilities'   => ['nullable'],
        ]);

        $user = User::where('email',$request->username)->first();

        if(!$user || !Hash::check($request->password, $user->password)){
            return Response::json([
                'message' => 'Invalid Username or Password combination',
            ],401);
        }

        $abilities = $request->input('abilities', ['*']);

        if ($abilities && is_string($abilities)) {
            $abilities = explode(',', $abilities);
        }
        // $token = $user->createToken($request->device_name, $abilities);
        $token = $user->createToken($request->device_name, $request->ip());

        // one solution
        // $accesstoken = PersonalAccessToken::findToken($token->plainTextToken);
        // $accesstoken->forceFill([
        //     'ip' => $request->ip(),
        // ])->save();

        // two solution
        // $accesstoken = $user->tokens()->latest()->first();
        // $accesstoken->forceFill([
        //     'ip' => $request->ip(),
        // ])->save();

        return Response::json([
            'token' => $token->plainTextToken,
            'user' => $user,
        ]);
    }

    public function destroy()
    {
        $user = Auth::guard('sanctum')->user();

        // Revoke (delete) all tokens...
        // $user->tokens()->delete();

        // Revoke the token that was used to authenticate the current request...
        $user->currentAccessToken()->delete();

        // Revoke a specific token...
        // $user->tokens()->where('id', $tokenId)->delete();
    }
}
