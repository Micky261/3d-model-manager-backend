<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller {
    public function register(Request $request): JsonResponse {
        if (User::where("email", $request->email)->exists()) {
            return response()->json([
                "message" => "User already exists.",
                "message_code" => "USER_ALREADY_EXISTS"
            ], 409);
        }

        $request->validate([
            "name" => " required",
            "email" => "email | required",
            "password" => "required"
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);

        $user->save();

        event(new Registered($user));

        return response()->json([
            "message" => "Success",
            "message_code" => "SUCCESS"
        ]);
    }

    public function login(Request $request): JsonResponse {
        try {
            $request->validate([
                "email" => "email | required",
                "password" => "required"
            ]);

            $credentials = request(["email", "password"]);

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    "message" => "Incorrect user data",
                    "message_code" => "USER_DATA_INCORRECT"
                ], 403);
            }

            $user = User::where("email", $request->email)->first();

            if (!Hash::check($request->password, $user->password, [])) {
                throw new Exception("Error on Login");
            }

            $tokenResult = $user->createToken("authToken");

            return response()->json([
                "access_token" => $tokenResult->plainTextToken,
                "token_type" => "Bearer",
            ]);
        } catch (Exception $error) {
            return response()->json([
                "message" => "Error on login",
                "message_code" => "LOGIN_ERROR"
            ], $error->status);
        }
    }
}
