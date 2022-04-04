<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $fillable = [
        'sender_id',
        'sender_name',
        'message',
        'reciever_id',
        'reciever_name',


    ];
    protected $hidden = [

        'updated_at',
        // 'created_at',
    ];
}
