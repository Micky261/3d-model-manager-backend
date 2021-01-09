<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ThreeDModelController;
use App\Http\Controllers\VerificationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
/*Route::middleware("auth:api")->get("/user", function (Request $request) {
    return $request->user();
});*/

// Endpoints available to logged-in users with verified email addresses
Route::middleware(["auth:sanctum", "verified"])->group(function () {
    // 3D models
    Route::get("models", [ThreeDModelController::class, "getAllModels"]);
    Route::get("models/random/{num}", [ThreeDModelController::class, "getRandomModels"]);
    Route::get("model/{id}", [ThreeDModelController::class, "getModel"]);
    Route::post("model", [ThreeDModelController::class, "createModel"]);
    Route::post("model/import", [ThreeDModelController::class, "importModel"]);
    Route::put("model/{id}", [ThreeDModelController::class, "updateModel"]);
    Route::delete("model/{id}", [ThreeDModelController::class, "deleteModel"]);
});

// Login / Register
Route::post("login", [AuthController::class, "login"]);
Route::post("register", [AuthController::class, "register"]);

// Register -> email verification
Route::get("email/resend", [VerificationController::class, "resend"])->name("verification.resend");
Route::get("email/verify/{id}/{hash}", [VerificationController::class, "verify"])->name("verification.verify");
// TODO: Respond to notification
Route::get("models", [ThreeDModelController::class, "getAllModels"])->name("verification.notification");
