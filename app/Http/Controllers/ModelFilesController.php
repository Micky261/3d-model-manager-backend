<?php

namespace App\Http\Controllers;

use App\Models\ModelFiles;
use App\Models\ThreeDModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use STS\ZipStream\ZipStream;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Zip;

class ModelFilesController extends Controller {
    public function getFiles(Request $request, int $modelId): JsonResponse {
        $userId = auth()->id();

        return response()->json(DB::table("model_files")->where([
            ["user_id", "=", $userId],
            ["model_id", "=", $modelId],
        ])->get());
    }

    public function getFilesWithType(Request $request, int $modelId, string $type): JsonResponse {
        $userId = auth()->id();


        return response()->json(DB::table("model_files")->where([
            ["user_id", "=", $userId],
            ["model_id", "=", $modelId],
            ["type", "=", $type],
        ])->get());
    }

    public function getFileWithType(Request $request, int $modelId, string $filename, string $type): BinaryFileResponse {
        $userId = auth()->id();
        $file = "{$userId}/{$modelId}/{$type}/{$filename}";

        if (!Storage::disk("local")->exists($file)) {
            abort("404");
        }
        return response()->file(storage_path("app" . DIRECTORY_SEPARATOR . $file));
    }

    public function updateFiles(Request $request, int $modelId): JsonResponse|Response {
        $userId = auth()->id();

        if (ThreeDModel::where("id", $modelId)->where("user_id", $userId)->exists()) {
            foreach ($request->input() as $file) {
                $modelFile = ModelFiles::find($file["id"]);
                $modelFile->position = is_null($file["position"]) ? $modelFile->position : $file["position"];
                $modelFile->type = is_null($file["type"]) ? $modelFile->type : $file["type"];
                $modelFile->save();
            }

            return response()->json(DB::table("model_files")->where([
                ["user_id", "=", $userId],
                ["model_id", "=", $modelId],
            ])->get());
        } else {
            return response(status: 404);
        }
    }

    public function downloadZipFile(Request $request, int $modelId, string $type): JsonResponse|BinaryFileResponse|Response|ZipStream {
        $userId = auth()->id();
        $baseDir = "{$userId}/{$modelId}/" . (($type == "all") ? "" : "{$type}/");

        if (Storage::disk("local")->exists($baseDir)) {
            $filesForZip = array();

            $files = Storage::disk("local")->allFiles($baseDir);
            foreach ($files as $file) {
                $name = basename($file);
                info(Storage::disk('local')->path($file));
                $filesForZip[Storage::disk('local')->path($file)] = $name;
            }

            info($filesForZip);

            return Zip::create("zip.zip", $filesForZip);
        } else {
            return response(status: 404);
        }
    }

    public function saveFile(Request $request, int $modelId): Response {
        $userId = auth()->id();
        $chunk = $request->chunk;
        $total = $request->totalChunks;
        $time = $request->timestamp;
        $filename = $request->filename;
        $type = $request->type;
        $file = $request->file;

        $uploadFilePath = "{$userId}/upload/{$time}";
        $file->storeAs($uploadFilePath, "{$chunk}__{$filename}");

        if (($chunk + 1) == $total) {
            $fileContent = "";

            for ($i = 0; $i < $total; $i++) {
                $chunkPathName = "{$uploadFilePath}/{$i}__{$filename}";
                $fileContent .= Storage::disk("local")->get($chunkPathName);
            }

            $filePath = "{$userId}/{$modelId}/{$type}/{$filename}";
            Storage::disk("local")->put($filePath, $fileContent);
            $filesize = Storage::disk("local")->size($filePath);

            Storage::disk("local")->deleteDirectory($uploadFilePath);

            $dbRow = DB::table("model_files")->where([
                ["user_id", "=", $userId],
                ["model_id", "=", $modelId],
                ["type", "=", $type],
                ["filename", "=", $filename]
            ]);

            if (!$dbRow->exists()) {
                $modelFile = new ModelFiles();
                $modelFile->user_id = $userId;
                $modelFile->model_id = $modelId;
                $modelFile->type = $type;
                $modelFile->filename = $filename;
                $modelFile->position = 999;
                $modelFile->size = $filesize;
                $modelFile->save();
            } else {
                $dbRow->update([
                    "updated_at" => DB::raw("NOW()"),
                    "size" => $filesize
                ]);
            }
        }

        return response(status: 200);
    }
}
