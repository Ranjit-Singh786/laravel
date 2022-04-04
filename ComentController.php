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

class ComentController extends Controller
{
    use Reply;
    function createComment(Request $request, $id)
    {
        if ($request->comment) {

            if (Post::find($id) == true) {
                $user =  Auth::id();
                $likes['user_id'] = $user;
                $likes['post_id'] = $id;

                // $usercom = Coment::find($id)->user();
                //return $usercom;

                $socialPost = Coment::firstOrCreate(
                    ['user_id' => $user, 'post_id' => $id],
                    ['user_id' => $user, 'post_id' => $id, 'comment' => $request->comment],
                );
                return $this->success("comment added successfully", $socialPost);
            } else {
                return $this->failed('post not found');
            }
        }
    }

    public function deleteComment(Request $request, $id)
    {
        if ($request->id) {

            $commid = Coment::find($id);
            if (!$commid) {
                return $this->failed('not post found');
            }
            if ($commid->user_id == Auth::id()) {
                $data =  DB::table('coments')->where('id', $id)->delete();
                //return $data;
                return  $this->success($data, 'Comment deleted successfully');
            }
            return  $this->failed('You cant delete others comment');
        }
    }

    public function showComment(Request $request, $id)
    {
        $post = Post::find($id);
        //return $post;
        $abc = $post->comment;  //relation has many coment with posts
        //return $post;
        $store = [];
        foreach ($abc as $name) {
            array_push($store, $name['comment']);
        }
        return $store;
    }
    public function commentReply(Request $request, $pid, $cid)
    {
        $pid =  Post::find($pid);
        if ($pid) {
            $cid = Coment::find($cid);
            if ($cid) {
                $data =    Replyy::create([
                    'coment_id' => $request->cid,
                    'name' => Auth::user()->name,
                    'reply' => $request->input('reply'),
                    'user_id' => Auth::user()->id,
                    'post_id' => $request->pid,
                ]);
                return $this->success($data, $data['reply']);
            }
            return "coment id not valid";
        }
        return 'post id not valid';
    }
}
