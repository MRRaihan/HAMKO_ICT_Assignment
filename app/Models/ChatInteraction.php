<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatInteraction extends Model
{
    protected $fillable = [
        'user_id',
        'user_message',
        'chatgpt_response',
    ];
}
