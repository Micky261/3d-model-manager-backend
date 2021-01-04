<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ThreeDModel extends Model {
    use HasFactory;

    protected $table = "models";

    protected $fillable = [
        "name",
        "imported_name",
        "links",
    ];

    protected $casts = [
        "links" => "array"
    ];
}
