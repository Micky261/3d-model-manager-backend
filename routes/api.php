<?php

use App\Http\Controllers\ThreeDModelController;
use Illuminate\Http\Request;
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

Route::middleware("auth:api")->get("/user", function (Request $request) {
    return $request->user();
});

Route::get("models", [ThreeDModelController::class, "getAllModels"]);
Route::get("models/random/{num}", [ThreeDModelController::class, "getRandomModels"]);
Route::get("model/{id}", [ThreeDModelController::class, "getModel"]);
Route::post("model", [ThreeDModelController::class, "createModel"]);
Route::put("model/{id}", [ThreeDModelController::class, "updateModel"]);
Route::delete("model/{id}", [ThreeDModelController::class, "deleteModel"]);

