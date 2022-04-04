<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Otp;
use App\Models\Like;
use App\Models\Replyy;
use App\Models\Coment;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Validator;
use App\Traits\Reply;
use DB;

class AuthController extends Controller
{
    use Reply;
    public function create(Request $request)
    {
        $validated =  Validator::make($request->all(),[
            'name' => 'required|max:255',
            'email' => 'email|required|unique:users',
            'password' => 'required|same:password_confirmation',
            'password_confirmation' => 'required|min:6',
            'username' => 'required|unique:users'




        ]);

        if ($validated->fails()) {
            return $validated->errors()->first();
        }
         //return $request;
        //$request['password'] = Hash::make($request['password']);
        $key  = mt_rand(1000000000, 9999999999);
        //return $request['r_key'];
        //$request['r_key'] = $key;
        //$request['pvt_account'] = $request->pvt_account;
        //$user = User::create($request->toArray());
         $request->pvt_account;
         $request->r_key;
         $userid = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'username'=> $request['username'],
             'r_key' =>$key
]);
        return $this->success('successfully registered',$userid);
    }
    //user login api

    public function loginUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response(['errors' => $validator->errors()->all()], 422);
        }

        $user = User::firstWhere('email', $request->email);
        // return $user;

        $token =  $user->createToken('apitoken')->accessToken;
        //    return $token;
        // return $request->password;
        // return $request->email;
        $credials = [
            'email' =>  $request->email,
            'password' => $request->password,
        ];


        //return $validator
        if (!auth()->attempt($credials)) {
            return  $this->failed('login failed');
        }
        $user = Auth::user();
        $id = $user->id;
        $user1 = User::find($id);
        $user1->tokens()->limit(PHP_INT_MAX)->offset(1)->get()->map(function ($token1) {
            $token1->delete();
        });
        //  Auth::user()->tokens->each(function($token, $key) {
        //     $token->delete();
        // });

        return  $this->success('successfully', $user, $token);
    }


    public function UserHomeGetAllPosts(Request $request)
    {
        //return "hello user";
        //return $request;
        $user =  Auth::user();
        if ($user) {
            $posts =  Post::all();
            return  $this->success($user, $posts);
        }
    }

    ###################Follow user by another user#######################
}
