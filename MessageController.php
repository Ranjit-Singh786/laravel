<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PndrequestController;
use App\Http\Requests\FollowUnfollowRequest;
use App\Models\User;
use App\Models\follow;
use App\Models\Post;
use App\Events\Sendmessage;
use App\Models\Message;
use App\Models\Pndrequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Validator;
use App\Traits\Reply;
use DB;

class MessageController extends Controller
{
        use Reply;
    function send(Request $request,$id){
        // return $request;
        $validated =  Validator::make($request->all(), [
            'message' => 'required',
        ]);

        if ($validated->fails()) {
            return $validated->errors()->first();
        }
        //return $request;

        $auth_id = Auth::id();
        if($auth_id == $request->id){
            return $this->failed("saabdhaan");
        }

        //return $auth;
        //return $id;
        $user = User::find($id);
        //return $user;
        $user_id = $user->id;
        $reciever_name = $user->name;
        //return $reciever_name;
        $reciever_id = $user->id;
        $auth = Auth::user();
        $s_key = $auth->r_key;
        //return $auth;
        $sender_name = $auth->name;
        $sender_id = $auth->id;
        $message=$request['message'];
        //return $message;

        event(new Sendmessage($reciever_name,$message,$sender_name,$s_key,$auth_id,$user_id));
        $input['sender_id'] = $sender_id;
        $input['sender_name'] = $sender_name;
        $input['reciever_id'] = $reciever_id;
        $input['reciever_name'] = $reciever_name;
        $input['message'] = $message;
          //return $input;
        $select =  Message::create($input);

            return $this->success("successfully inserted",$input);


    }
    public function showdata($id){
        $mupdate =  Message::where(['sender_id' => $id, "reciever_id" => Auth::id()])->update(['seen'=>'seen']);
        $auth_id = Auth::id();
        $user = User::find($id);
        //return $id;
        $s_key= $user->r_key;
       $user_id = $user->id;

        $sender_name = Auth::user()->name;


        $usermessage = Message::where(['sender_id' => $auth_id, "reciever_id" => $user->id])->get();
        $reciever_name = $user->name;
        $array1 = json_decode(json_encode($usermessage), true);
        $idmessages = Message::where(['sender_id' => $user->id, "reciever_id" => $auth_id])->get();
        //return $idmessages;
        $array2 = json_decode(json_encode($idmessages), true);
        $data = array_merge($array1, $array2);
        // return $data;
        $data1 = array_column($data, 'created_at');
        array_multisort($data1, SORT_ASC, $data);
        //   return $data;
        return  view('index', compact('data','reciever_name','sender_name','s_key','user_id'));
    }

    public function login_data(Request $request){

        $validate = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);
        if ($validate->fails()) {
            return ['error' => $validate->errors()->first()];
        }
        $credentials= $request->only('email','password');
        if (Auth::attempt($credentials)) {

            Auth::user()->tokens()->delete();

            $user = User::where('email', $request->email)->first();
            //  return $user;

            // $r_key= $user->r_key;
            $logged= Auth::user();
            $logged_name=$logged->name;
            $logged['status']='online';
            $token = $user->createToken('loginToken')->accessToken;
            $logged_id= Auth::user()->id;
            $users= User::where('id','!=',Auth::id())->get();
            $logged->update();
            $status = $logged->status;

            return view('dashboard',compact('token','users','logged_name','logged_id','status'));
        }
        return $this->failed("Kindly Check Account Credentials");
}

function get_data(Request $request,$id){
    $mupdate =  Message::where(['sender_id' => $id, "reciever_id" => Auth::id()])->update(['seen'=>'seen']);
    $auth_id = Auth::id();
    $user = User::find($id);
    //return $id;
    $s_key= $user->r_key;
   $user_id = $user->id;

    $sender_name = Auth::user()->name;


    $usermessage = Message::where(['sender_id' => $auth_id, "reciever_id" => $user->id])->get();
    $reciever_name = $user->name;
    $array1 = json_decode(json_encode($usermessage), true);
    $idmessages = Message::where(['sender_id' => $user->id, "reciever_id" => $auth_id])->get();
    //return $idmessages;
    $array2 = json_decode(json_encode($idmessages), true);
    $data = array_merge($array1, $array2);
    // return $data;
    $data1 = array_column($data, 'created_at');
    array_multisort($data1, SORT_ASC, $data);
    return $data;

}

public function UserLogOut(Request $request)
{
        $auth  =Auth::user();
        $auth['status'] ='offline';
        $auth->update();
        Auth::user()->tokens->each(function($token, $key) {
            $token->delete();
        });
        return view('login');


}


}
