<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Otp;
use App\Models\Like;
use App\Models\Replyy;
use App\Models\Coment;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Validator;
use App\Traits\Reply;
use DB;

class LikeController extends Controller
{
    use Reply;
    public function countLikeWithNames(Request $request, $id)
    {
        if (!$request->id) {
            return "post not found";
        } else {
            $postid = Post::find($id);
            $like = $postid->likeCount;
            $arr = [];
            foreach ($like as $likes) {
                array_push($arr, $likes->name);
            }
            //$arrname = $arr->name;
            return $this->success($arr);
        }
    }
    public function likeCount(Request $request, $id)
    {

        $Uid =  Auth::id();
        //return $Uid;
        $likes['user_id'] = $Uid;
        $likes['post_id'] = $id;
        $likes['name'] = Auth::user()->name;
        $exists = Like::where(['user_id' => $Uid, 'post_id' => $id])->first();
        // return $exists;
        if ($request->like == true) {
            if ($exists) {
                return $this->failed('You Already Liked This Post');
            } else {
                //return "jsdkfj";
                $post = Post::find($id);
                //return $post;
                if ($post) {
                    // return "kdsfj";
                    $like = $post->likes;
                    $likenum = $like + 1;
                    $post->likes = $likenum;
                    // return $post->likes;
                    Like::create($likes);
                    $post->update();

                    return $this->success('You Liked This Post');
                } else {
                    return $this->failed('Post Does Not Exist');
                }
            }
        } else {
            $post = Post::find($id);
            if (!$post) {
                return $this->failed('No Post Exists');
            }
            if ($exists) {
                if ($post) {
                    $like = $post->likes;
                    $likes = $like - 1;
                    $post->likes = $likes;
                    $post->update();
                    $exists->delete();

                    return $this->success('unliked the post');
                }
            } else {
                return $this->failed('Already Unliked');
            }
        }
    }
    public function showuserLikes(Request $request)
    {

        $user_id = Auth::id();
        // return $user_id;
        $posts = Post::with(['likeCount' => function ($like) use ($user_id) {
            return $like->whereHas('userdat', function ($user) use ($user_id) {
                $user->where('id', $user_id);
            })->get();
        }])->get();

        return  $this->success('your likes data', $posts);
    }

}
