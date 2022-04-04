<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Events\NewFollowCreated;

class Follow extends Model
{

    use HasFactory;
    protected $fillable = [
        'user_id',
        'following_id',


    ];

    protected $dispatchesEvents = [
        'created' => NewFollowCreated::class
    ];



}
