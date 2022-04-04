<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PndrequestController;
use App\Http\Requests\FollowUnfollowRequest;
use App\Models\User;
use App\Models\follow;
use App\Models\Post;
use App\Models\Pndrequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;
use App\Traits\Reply;
use DB;

class FollowController extends Controller {
    use Reply;
    public function follow(Request $request) {
       //  return fail();
       $userToFollow = User::findOrFail(request('user_id'));
       //return $userToFollow->id;
      //return Auth::user();
      if ($userToFollow->id == Auth::id()) {
        return $this->failed('you cant follow yourself buddy');
    }

        if($userToFollow->pvt_account===1){
           //auth()->user()->follow($userToFollow);
       $exists = Pndrequest::where(['user_id' => $userToFollow->id])->first();
       if ($exists) {
          return $this->failed('You Already sent following request');
      }
           //return $userToFollow;
              $user_id = Auth::user();
           //return    $user_id->id;
           //$friend = Pndrequest::find($userToFollow);
           $following['user_id'] = $userToFollow->id;
           $following['request_id'] = $user_id->id;
           $following['name'] = $userToFollow->name;
           Pndrequest::create($following);
           return $this->success('request sent successfully');
        }
         //return $userToFollow->id;
    //    $existserr = Follow::where(['following_id' => $userToFollow->id])->first();
    //     if ($existserr) {
    //        return $this->failed('You Already sent following request');
    //    }

      auth()->user()->follow($userToFollow);
      //return $userToFollow;
       //return $userToFollow;
       $userid = Auth::user();

        $followid = User::find($userid->id);
        $following = $followid->following;
        $follownum = $following+1;
        $followid->following = $follownum;
        $followid->update();
        //return "djsfhdskj";
        //user followers creation code
        // return $userToFollow->id;
          $followuser =   Follow::where(['following_id' => $userToFollow->id])->first();
        //return $followuser;
         $followerid = User::find($followuser->following_id);
         $followerUser = $followerid->follower;
         $followusernum = $followerUser+1;
         $followerid->follower = $followusernum;
        $followerid->update();
        return $this->success($followerid,"following successfully");
        //$user = User::find($Uid);

        }


    public function unfollow(Request $request) {
        $userToUnfollow = User::findOrFail(request('user_id'));
        auth()->user()->unfollow($userToUnfollow);
        return $this->success(['unfollow successfully'=>$userToUnfollow]);
    }

    public function follower_user_list(Request $request,$id){
        if (!$request->id) {
            return "User not found";
        }
           // $useractive = Auth::user();
            $userid = User::find($id);
            $follow = $userid->following;
            return $this->success(['your account'=>$userid]);

    }
    public function user_account(Request $request){
        $id =Auth::user();
            $post = $id->post;
            return $this->success(['your posts'=>$post]);

    }
    public function followingpost()
    {
     //return $post_id;
        //return Auth::user();

       $follows = Follow::where('following_id', Auth::id())->pluck('user_id');

        $posts = Post::whereIn('user_id', $follows)->latest()->get();
        return $this->success("Following user all Posts", $posts->follow);
    }




}
