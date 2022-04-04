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

class ReplyyController extends Controller
{
    use Reply;
    public function showPostReply(Request $request, $id)
    {
        $comments = Coment::find($id);
        $comments->replies;
        $postid = $comments->post_id;
        $user = Post::firstWhere('id', $postid);
        $userlike =  $user->likes;
        $userscom = $user->comment;
        return $this->success(['likes' => $userlike, 'Comment' => $userscom, 'comments' => $comments]);
    }

    public function showusersActivity(Request $request)
    {
        $users = User::with('post.comment')->get();
        return $this->success($users);
    }

    public function destroyCommentReply(Request $request,$pid, $id)
    {
        //return Auth::user();
        //return $request->id;
     $pid = Post::find($pid);
     $cid = Replyy::find($id);
     if($pid &&  $cid){
        if ( Auth::user()->id == $cid->user_id) {
         $reply = Replyy::firstWhere('id', $cid->id);
        if ($reply->count() > 0) {
            $reply->delete();
            return $this->success("successfully deleted");
        }else{
            return $this->failed("delete failed");
        }
    }
    return "Bad request";

     }
     return $this->failed("Unauthorized");

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
