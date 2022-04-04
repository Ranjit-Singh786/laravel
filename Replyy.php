<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Replyy extends Model
{
    use HasFactory;
    protected $fillable = [
    	'coment_id',
        'name',
    	'reply',
    	'user_id',
    	'post_id',
    ];
    public $timestamps = false;

    public function coment()
    {
        return $this->hasMany(Coment::class);
    }

}
