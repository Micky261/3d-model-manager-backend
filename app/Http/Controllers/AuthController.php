<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    public function login(Request $request): JsonResponse {
        try {
            $request->validate([
                "email" => "email | required",
                "password" => "required"
            ]);

            $credentials = request(["email", "password"]);

            if (!Auth::attempt($credentials)) {
                return response()->json(                    [
                        "msg" => "Incorrect user data",
                        "msg_code" => "USER_DATA_INCORRECT"
                    ],                     403);
            }

            $user = User::where("email", $request->email)->first();

            if (!Hash::check($request->password, $user->password, [])) {
                throw new Exception("Error on Login");
            }

            $tokenResult = $user->createToken("authToken")->plainTextToken;

            return response()->json([
                "access_token" => $tokenResult,
                "token_type" => "Bearer",
            ]);
        } catch (Exception $error) {
            return response()->json([
                "msg" => "Error on login",
                "msg_code" => "LOGIN_ERROR",
                "error" => $error,
            ], $error->status);
        }
    }
}
