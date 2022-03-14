<?php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Post;
use App\Models\Otp;
use App\Models\Like;
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
        $validated =  Validator::make($request->all() , [
            'name' => 'required|unique:users|max:255',
            'email' => 'email|required|unique:users',
            'password' => 'required|same:password_confirmation',
            'password_confirmation' => 'required|min:6'
        ]);

        if($validated->fails())
        {
            return $validated->errors()->first();
        }

        $request['password'] = Hash::make($request['password']);
        //$user = User::create($request->toArray());
      $data= User::create($request->all());
            //return response()->json($data);
            //return $data;
            if(!$data){
                // $token = $data->createToken('api_token')->accessToken;

               return  $this->failed("data empty");
            }
               return $this->success($data);





    }
    //user login api

    public function loginUser(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
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
        if(!auth()->attempt($credials)){
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

             return  $this->success('successfully',$user,$token);
    }

    public function UserHomeGetAllPosts(Request $request){
        //return "hello user";
        //return $request;
        $user =  Auth::user();
        if($user){
           $posts =  Post::all();
           return  $this->success($user,$posts);
        }

    }
    public function createPost(Request $request){
        $validated =  Validator::make($request->all() , [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if($validated->fails())
        {
            return $validated->errors()->first();
        }

        if ( $request->hasFile('image')){
            if ($request->file('image')->isValid()){
                $file = $request->file('image');
                $name = rand().''.$file->getClientOriginalName();
                $file->move('public/images' , $name);
                $user = Auth::user();
                 $request['user_id']=$user->id ;
                $inputs = $request->all();
                $inputs['image'] = $name;
            }
        }


      $Userdata =   Post::create($inputs);
        return  $this->success($Userdata);

}

public function showAllPostsOnlyOneuser($id){
    $user= Post::find($id);
    $useractive = Auth::user();
    if(!$user){
        return $this->failed("Post not found");
    }
    if($user){
        return $this->success($user->post,$useractive);
    }

}
public function updateData(Request $request,$id)
    {
        if($request->caption){
            //return $request;
         $updateid =  Post::find($id);
        if($updateid->user_id ==Auth::id()){
             $updateid->update($request->all());
            return  $this->success($id);
        }

            return  $this->failed('Posts Not Found');

    }

    }
    public function destroy($id)
    {
        $data = Post::find($id);
        //return $data;
        if(!$data){
            return $this->failed('not post found');
        }
        if($data->user_id == Auth::id()){
            $data =  Post::destroy($id);
            return  $this->success($data,'deleted successfully');
        }else{
            return  $this->failed('failed to delete data');
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
            }else {
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
                }else {
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

public function countLikeWithNames(Request $request,$id){
    if(!$request->id){
        return "post not found";
    }else{
       $postid = Post::find($id);
      $like = $postid->likeCount;
      $arr = [];
     foreach($like as $likes) {
      array_push($arr,$likes->name);
     }
     //$arrname = $arr->name;
     return $this->success($arr);

    }
    }
     function createComment(Request $request,$id){
        if($request->comment){

            if(Post::find($id)==true){
            $user =  Auth::id();
            $likes['user_id'] = $user;
            $likes['post_id'] = $id;

        // $usercom = Coment::find($id)->user();
            //return $usercom;

        $socialPost = Coment::firstOrCreate(
                    ['user_id' => $user, 'post_id' => $id],
                    ['user_id' => $user, 'post_id' => $id, 'comment' => $request->comment],
        );
            return $this->success("comment added successfully",$socialPost);
        }else{
        return $this->failed('post not found');
        }
    }
        }

             public function deleteComment(Request $request,$id)
             {
                 if($request->id){

                    $commid = Coment::find($id);
                    if(!$commid){
                        return $this->failed('not post found');
                    }
                    if($commid->user_id== Auth::id()){
                        $data =  DB::table('coments')->where('id', $id)->delete();
                        //return $data;
                        return  $this->success($data,'Comment deleted successfully');
                      }
                      return  $this->failed('failed to delete Comment');
                 }

             }

             public function showComment(Request $request,$id){
                if(!$request->id){
                    return  $this->failed('Comment not found');
                }else{
                      if(Post::find($id)){

                             $data = Coment::all();
                             return  $this->success($data,'Comment deleted successfully');
                      }
                }
                return "id not found";
             }


}




