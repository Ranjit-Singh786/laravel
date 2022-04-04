<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    use HasFactory;
    protected $fillable = [
        'post_id',
        'user_id',
        'name',
    ];
    public function user()
    {
        return $this->belongsTo(Post::class);
    }
    public function likes()
    {
        return $this->belongsTo(Post::class);
    }
    public function userdat()
{
    return $this->hasOne(User::class, 'id', 'user_id');
}




}
