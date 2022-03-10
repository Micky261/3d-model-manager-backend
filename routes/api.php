<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ModelFilesController;
use App\Http\Controllers\ThreeDModelController;
use App\Http\Controllers\ModelTagsController;
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
    Route::get("model/data/{id}", [ThreeDModelController::class, "getModel"]);
    Route::post("model/data", [ThreeDModelController::class, "createModel"]);
    Route::post("model/import", [ThreeDModelController::class, "importModel"]);
    Route::put("model/data/{id}", [ThreeDModelController::class, "updateModel"]);
    Route::delete("model/{id}", [ThreeDModelController::class, "deleteModel"]);

    Route::get("model/tags/{id}", [ModelTagsController::class, "getTags"]);
    Route::post("model/tag/{id}/{tag}", [ModelTagsController::class, "setTag"]);
    Route::delete("model/tag/{id}/{tag}", [ModelTagsController::class, "removeTag"]);

    Route::get("tags/all", [ModelTagsController::class,"getAllTags"]);

    Route::get("model/files/{modelId}", [ModelFilesController::class, "getFiles"]);
    Route::get("model/files/{modelId}/{type}", [ModelFilesController::class, "getFilesWithType"]);
    Route::get("model/zip/{modelId}/{type}", [ModelFilesController::class, "downloadZipFile"]);
    Route::get("model/file/{modelId}/{filename}/{type}", [ModelFilesController::class, "getFileWithType"]);
    Route::post("model/files/{modelId}", [ModelFilesController::class, "updateFiles"]);
    Route::post("model/file/{modelId}", [ModelFilesController::class, "saveFile"]);
});

// Login / Register
Route::post("login", [AuthController::class, "login"]);
Route::post("register", [AuthController::class, "register"]);

// Register -> email verification
Route::get("email/resend", [VerificationController::class, "resend"])->name("verification.resend");
Route::get("email/verify/{id}/{hash}", [VerificationController::class, "verify"])->name("verification.verify");
