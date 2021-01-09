<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServerMessage extends Model
{
    use HasFactory;

    protected $fillable = ["message", "message_code", "model_id"];
}
