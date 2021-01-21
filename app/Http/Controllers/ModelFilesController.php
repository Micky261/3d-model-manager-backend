<?php

namespace App\Http\Controllers;

use App\Models\ModelFiles;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ModelFilesController extends Controller {
    public function saveFile(Request $request, int $modelId): JsonResponse {
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
            Storage::disk("local")->deleteDirectory($uploadFilePath);

            $modelFile = new ModelFiles();
            $modelFile->user_id = $userId;
            $modelFile->model_id = $modelId;
            $modelFile->type = $type;
            $modelFile->filename = $filename;
            $modelFile->position = 999;
            $modelFile->save();
        }

        return response()->json();
    }

    /**
     * DB::table("model_files")
     * ->select("filename")
     * ->where("user_id", $userId)
     * ->where("model_id", $modelId)
     * ->orderBy("updated")
     * ->get()
     */
}
