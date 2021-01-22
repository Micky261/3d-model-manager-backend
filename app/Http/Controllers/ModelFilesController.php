<?php

namespace App\Http\Controllers;

use App\Models\ModelFiles;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ModelFilesController extends Controller {
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
                ["model_Id", "=", $modelId],
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
