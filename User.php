<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'r_key',
        'followers',
        'following',
        'username',
        'pvt_account',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'email_verified_at',

        "created_at",
        "updated_at",
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function post(){
        return $this->hasMany(Post::class);
    }
    public function comment(){
        return $this->hasMany(Comment::class);
    }
    public function follow(User $user) {
        if(!$this->isFollowing($user)) {
            Follow::create([
                'user_id' => auth()->id(),
                'following_id' => $user->id
            ]);
        }
    }

    public function unfollow(User $user) {
        Follow::where('user_id', auth()->id())->where('following_id', $user->id)->delete();
    }

    public function isFollowing(User $user) {
        return $this->following()->where('users.id', $user->id)->exists();
    }

    public function following() {
        return $this->hasManyThrough(User::class, Follow::class, 'user_id', 'id', 'id', 'following_id');
    }

    public function followers() {
        return $this->hasManyThrough(User::class, Follow::class, 'following_id', 'id', 'id', 'user_id');
    }
    public function friends()
    {
    return $this->hasMany(Pndrequest::class);
    }

}
