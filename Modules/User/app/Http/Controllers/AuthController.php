<?php

namespace Modules\User\app\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\User\app\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\User\app\Http\Requests\LogInRequest;

class AuthController extends Controller
{
    public function logIn(LogInRequest $request)
    {
        $user = User::where('username', $request->username)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => trans('messages.incorrectCredentials')
            ], 422);
        }

        $token = $user->createToken($request->device_name)->plainTextToken;

        // Check if this is the first login
        if (!$user->first_login) {
            // Store user agent data
            $user->token = $token;
            $user->first_login = true;
            $user->last_login_device = $request->header('User-Agent') . '&' . $user->username;
            $user->save();
        }


        // Check if user agent matches
        elseif ($user->first_login && $user->last_login_device !== ($request->header('User-Agent') . '&' . $user->username)) {
            // User is trying to login from a different device
            return response()->json([
                'message' => trans('messages.loginWithDifferentDevice'),
            ], 422);
        }

        // Update last login device
        $user->token = $token;
        $user->last_login_device = ($request->header('User-Agent') . '&' . $user->username);
        $user->save();

        // if (Auth::attempt($request->only('email', 'password'))) {
        //     $user = Auth::user();
        //     $token = $user->createToken($request->device_name)->plainTextToken;

        //     return response()->json(['token' => $token], 200);
        // }

        return response()->json(['token' => $token], 200);
    }


    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => trans('messages.loggedOutSuccessfully')], 200);
    }
}
