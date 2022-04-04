<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $fillable = [
        'caption',
        'image',
        'user_id',
        'likes',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function likeCount()
    {
        return $this->hasMany(Like::class);
    }
    public function comment()
    {
        return $this->hasMany(Coment::class);
    }
    public function comments()
    {
        return $this->hasMany(Coment::class)->whereNull('parent_id');
    }
    public function follow()
    {
        return $this->belongsTo(Follow::class);
    }

}
