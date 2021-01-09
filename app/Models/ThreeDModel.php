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
        "description",
        "imported_description",
        "notes",
        "links",
        "favorite",
        "author",
        "imported_author",
        "licence",
        "imported_licence",
        "import_source"
    ];

    protected $casts = [
        "links" => "array",
        "favorite" => "boolean"
    ];

    protected $hidden = [
        "user_id"
    ];
}
