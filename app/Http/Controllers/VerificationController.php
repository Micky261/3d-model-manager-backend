<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VerificationController extends Controller {
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct() {
        //$this->middleware("auth:api")->only("resend");
        $this->middleware("signed")->only("verify");
        $this->middleware("throttle:5,1")->only("verify", "resend");
    }

    /**
     * Resend the email verification notification.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function resend(Request $request): JsonResponse {
        $user = User::where("email", $request->email)->first();

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                "message" => "Already verified",
                "message_code" => "ALREADY_VERIFIED"
            ]);
        } else {
            $user->sendEmailVerificationNotification();

            return response()->json([
                "message" => "Success",
                "message_code" => "SUCCESS"
            ]);
        }
    }


    /**
     * Mark the authenticated user's email address as verified.
     *T
     * @param Request $request
     * @return JsonResponse
     */
    public function verify(Request $request, $id, $hash): JsonResponse {
        $user = User::where("id", $id)->first();

        if ($user->hasVerifiedEmail()) {
            return response()->json([
                "message" => "Already verified",
                "message_code" => "ALREADY_VERIFIED"
            ]);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return response()->json([
            "message" => "Success",
            "message_code" => "SUCCESS"
        ]);
    }
}
