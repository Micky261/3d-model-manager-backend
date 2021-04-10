<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelFiles extends Model
{
    use HasFactory;

    protected $table = "model_files";

    protected $fillable = [
        "position",
        "filename",
        "type"
    ];

    protected $hidden = [
        "user_id"
    ];
}
