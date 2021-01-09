<?php

namespace App\Http\Controllers;

use App\Models\ModelTags;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class ModelTagsController extends Controller {
    public function getTags(Request $request, int $id): JsonResponse {
        $userId = auth()->id();
        return response()->json(
            DB::table("model_tags")->where("user_id", $userId)->where("id", $id)->get()
        );
    }

    public function setTag(Request $request, int $id, string $tag): JsonResponse|Response {
        $userId = auth()->id();

        if (DB::table("model_tags")->where("user_id", $userId)->where("id", $id)->exists()) {
            $modelTag = new ModelTags();
            $modelTag->user_id = $userId;
            $modelTag->model_id = $id;
            $modelTag->tag = $tag;
            $modelTag->save();

            return response()->json($modelTag);
        } else {
            return response(status: 404);
        }
    }

    public function removeTag(Request $request, int $id, string $tag): Response {
        $userId = auth()->id();
        $modelTag = DB::table("model_tags")
            ->where("user_id", $userId)
            ->where("id", $id)
            ->where("tag", $tag);

        if ($modelTag->exists()) {
            $modelTag->delete();

            return response();
        } else {
            return response(status: 404);
        }
    }
}
