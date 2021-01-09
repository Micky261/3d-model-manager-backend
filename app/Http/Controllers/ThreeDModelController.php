<?php

namespace App\Http\Controllers;

use App\Models\ThreeDModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ThreeDModelController extends Controller {
    public function getAllModels(): JsonResponse {
        return response()->json(ThreeDModel::get());
    }

    public function createModel(Request $request): JsonResponse {
        $threeDModel = new ThreeDModel();
        $threeDModel->name = $request->name;
        $threeDModel->imported_name = $request->imported_name;
        $threeDModel->links = $request->links;
        $threeDModel->save();

        return response()->json($threeDModel);
    }

    public function importModel(Request $request): JsonResponse {
        echo $request->url;

        return response()->json();
    }

    public function getModel(int $id): JsonResponse|Response {
        if (ThreeDModel::where("id", $id)->exists()) {
            $threeDModel = ThreeDModel::where("id", $id)->get()[0];
            return response()->json($threeDModel);
        } else {
            return response(status: 404);
        }
    }

    public function updateModel(Request $request, int $id): JsonResponse|Response {
        if (ThreeDModel::where("id", $id)->exists()) {
            $threeDModel = ThreeDModel::find($id);
            $threeDModel->name = is_null($request->name) ? $threeDModel->name : $request->name;
            $threeDModel->links = is_null($request->links) ? $threeDModel->links : $request->links;
            $threeDModel->save();

            return response()->json($threeDModel);
        } else {
            return response(status: 404);

        }
    }

    public function deleteModel(int $id): JsonResponse|Response {
        if (ThreeDModel::where("id", $id)->exists()) {
            $threeDModel = ThreeDModel::find($id);
            $threeDModel->delete();

            return response()->json($threeDModel);
        } else {
            return response(status: 404);
        }
    }

    public function getRandomModels(int $num): JsonResponse|Response {
        if ($num > 0) {
            $threeDModels = ThreeDModel::select()->inRandomOrder()->limit($num)->get();

            return response()->json($threeDModels);
        } else {
            return response(status: 400);
        }
    }
}
