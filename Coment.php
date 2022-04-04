<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coment extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'id',
        'post_id',
        'user_id',
        'comment',
        'parent_id',
    ];
    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(Replyy::class);
    }
    public function likes()
    {
        return $this->hasMany(Post::class);
    }
    public $timestamps = false;
}
