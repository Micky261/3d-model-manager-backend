<?php

namespace App\Http\Controllers;

use App\Models\ThreeDModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ThreeDModelController extends Controller {
    public function getAllModels(Request $request): JsonResponse {
        $userId = auth()->id();
        return response()->json(ThreeDModel::where("user_id", $userId)->get());
    }

    public function createModel(Request $request): JsonResponse {
        $userId = auth()->id();

        $threeDModel = new ThreeDModel();
        $threeDModel->user_id = $userId;
        $threeDModel->name = $request->name;
        $threeDModel->links = $request->links;
        $threeDModel->description = $request->description;
        $threeDModel->notes = $request->notes;
        $threeDModel->favorite = $request->favorite;
        $threeDModel->author = $request->author;
        $threeDModel->licence = $request->licence;
        $threeDModel->save();

        return response()->json($threeDModel);
    }

    public function importModel(Request $request): JsonResponse {
        echo $request->url;
        echo auth()->id();

        return response()->json();
    }

    public function getModel(int $id): JsonResponse|Response {
        $userId = auth()->id();

        if (ThreeDModel::where("id", $id)->where("user_id", $userId)->exists()) {
            $threeDModel = ThreeDModel::where("id", $id)->get()[0];

            return response()->json($threeDModel);
        } else {
            return response(status: 404);
        }
    }

    public function updateModel(Request $request, int $id): JsonResponse|Response {
        $userId = auth()->id();

        if (ThreeDModel::where("id", $id)->where("user_id", $userId)->exists()) {
            $threeDModel = ThreeDModel::find($id);
            $threeDModel->name = is_null($request->name) ? $threeDModel->name : $request->name;
            $threeDModel->links = is_null($request->links) ? $threeDModel->links : $request->links;
            $threeDModel->description = is_null($request->description) ? $threeDModel->description : $request->description;
            $threeDModel->notes = is_null($request->notes) ? $threeDModel->notes : $request->notes;
            $threeDModel->favorite = is_null($request->favorite) ? $threeDModel->favorite : $request->favorite;
            $threeDModel->author = is_null($request->author) ? $threeDModel->author : $request->author;
            $threeDModel->licence = is_null($request->licence) ? $threeDModel->licence : $request->licence;
            $threeDModel->save();

            return response()->json($threeDModel);
        } else {
            return response(status: 404);

        }
    }

    public function deleteModel(int $id): JsonResponse|Response {
        $userId = auth()->id();

        if (ThreeDModel::where("id", $id)->where("user_id", $userId)->exists()) {
            $threeDModel = ThreeDModel::find($id);
            $threeDModel->delete();

            return response()->json($threeDModel);
        } else {
            return response(status: 404);
        }
    }

    public function getRandomModels(int $num): JsonResponse|Response {
        $userId = auth()->id();

        if ($num > 0) {
            $threeDModels = ThreeDModel::where("user_id", $userId)->inRandomOrder()->limit($num)->get();

            return response()->json($threeDModels);
        } else {
            return response(status: 400);
        }
    }
}
