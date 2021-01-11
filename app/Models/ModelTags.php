<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModelTags extends Model
{
    use HasFactory;

    protected $table = "model_tags";

    protected $hidden = [
        "user_id"
    ];

    public $timestamps = false;
}
