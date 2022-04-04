<?php

namespace App\Http\Controllers;

use App\Models\Pndrequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Follow;
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

class PndrequestController extends Controller
{
    use Reply;
    public function followUser_request(Request $request,$id){

        $validated =  Validator::make($request->all(), [
            'accepted' => 'required',

        ]);

        if ($validated->fails()) {
            return $validated->errors()->first();
        }



       $pndreq = Pndrequest::find($id);
        //return $pndreq->accepted;
       $userid = $pndreq->request_id;
        if(!$pndreq){
            return $this->failed('bad request');
        }else{
            if($request->accepted==1){
                 $finduser= User::find($userid);
                $Uid =  Auth::id();
                $follow['following_id'] = $pndreq->user_id;
                $follow['user_id'] = $pndreq->request_id;
                $follow['name'] = Auth::user()->name;
               // return $follow;
                // $followuser = Follow::where(['following_id'=>$pndreq->user_id,'user_id'=>$pndreq->request_id]);
                // if($followuser==1){
                //     return $this->failed("already follows");
                // }
                $followuser = Follow::create($follow);
                $exists = Pndrequest::where(['id' => $id])->first();
                //delete pnd req row after data move on follow table

                //user following creation code
                if($followuser){
                  $followid = User::find($followuser->user_id);
                  $following = $followid->following;
                  $follownum = $following+1;
                  $followid->following = $follownum;
                  $followid->update();
                  //user followers creation code
                  $followerid = User::find($followuser->following_id);
                  $followerUser = $followerid->follower;
                  $followusernum = $followerUser+1;
                  $followerid->follower = $followusernum;
                  $followerid->update();
                  $exists->delete();
                  $user = User::find($Uid);
                }

                       return $this->success('your request is accepted');


            }

        }


    }


}
