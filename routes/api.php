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
    /**
     * All endpoints handle data for the given user, not for all users
     */

    /**
     * Models
     */
    // Get list of all models
    Route::get("models", [ThreeDModelController::class, "getAllModels"]);
    // Get a list of {num} random models
    Route::get("models/random/{num}", [ThreeDModelController::class, "getRandomModels"]);
    // Get data for the specified model
    Route::get("model/data/{id}", [ThreeDModelController::class, "getModel"]);
    // Create a model (data via POST)
    Route::post("model/data", [ThreeDModelController::class, "createModel"]);
    // Start import of a model from a 3d file vendor
    Route::post("model/import", [ThreeDModelController::class, "importModel"]);
    // Update the specified model
    Route::put("model/data/{id}", [ThreeDModelController::class, "updateModel"]);
    // Delete specified model
    Route::delete("model/{id}", [ThreeDModelController::class, "deleteModel"]);

    /**
     * Tags for Models
     */
    // Get all tags
    Route::get("tags/all", [ModelTagsController::class,"getAllTags"]);
    // Get tags assigned to the specified model
    Route::get("model/tags/{id}", [ModelTagsController::class, "getTags"]);
    // Add a tag for the specified model
    Route::post("model/tag/{id}/{tag}", [ModelTagsController::class, "setTag"]);
    // Remove a tag from the specified model
    Route::delete("model/tag/{id}/{tag}", [ModelTagsController::class, "removeTag"]);

    /**
     * Files for Models
     */
    // Get a list of files attached to the specified model
    Route::get("model/files/{modelId}", [ModelFilesController::class, "getFiles"]);
    // Get a list of files of the given file-type attached to the specified model
    Route::get("model/files/{modelId}/{type}", [ModelFilesController::class, "getFilesWithType"]);
    // Download a zip file including all files of the specified file-type for the given model
    Route::get("model/zip/{modelId}/{type}", [ModelFilesController::class, "downloadZipFile"]);
    // Download a single file
    Route::get("model/file/{modelId}/{filename}/{type}", [ModelFilesController::class, "getFileWithType"]);
    // Update files for a model
    Route::post("model/files/{modelId}", [ModelFilesController::class, "updateFiles"]);
    // Save a file for a model
    Route::post("model/file/{modelId}", [ModelFilesController::class, "saveFile"]);
});

// Login / Register
Route::post("login", [AuthController::class, "login"]);
Route::post("register", [AuthController::class, "register"]);

// Register -> email verification
Route::get("email/resend", [VerificationController::class, "resend"])->name("verification.resend");
Route::get("email/verify/{id}/{hash}", [VerificationController::class, "verify"])->name("verification.verify");
