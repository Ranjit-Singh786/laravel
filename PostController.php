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

class PostController extends Controller
{
    use Reply;
    public function createPost(Request $request)
    {
        $validated =  Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'caption' => 'required',
        ]);

        if ($validated->fails()) {
            return $validated->errors()->first();
        }


        if ($request->hasFile('image')) {
            if ($request->file('image')->isValid()) {
                $file = $request->file('image');
                $name = rand() . '' . $file->getClientOriginalName();
                $file->move('public/images', $name);
                $user = Auth::user();
                $request['user_id'] = $user->id;
                $inputs = $request->all();
                $inputs['image'] = $name;
            }
        }


        $Userdata =   Post::create($inputs);
        return  $this->success($Userdata);
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

    public function showAllPostsOnlyOneuser($id)
    {
        //return "dskjhf";
        $user = Post::find($id);
        $useractive = Auth::user();
        //return $useractive;
        if (!$user) {
            return $this->failed("Post not found");
        }
        if ($user) {
            return $this->success($useractive, $user->all());
        }
    }
    public function updateData(Request $request, $id)
    {

        if ($request->caption) {
            //return $request;
            $updateid =  Post::find($id);
            if ($updateid) {
                if ($updateid->user_id == Auth::id()) {
                    $updateid->update($request->all());
                    return  $this->success($id);
                }
                return  $this->failed('cant edit anthors post');
            }
            return $this->failed('unauthrized');
        } else {
            return "post not found";
        }
    }
    public function destroy($id)
    {
        $data = Post::find($id);
        //return $data;
        if (!$data) {
            return $this->failed('not post found');
        }
        if ($data->user_id == Auth::id()) {
            $data =  Post::destroy($id);
            return  $this->success($data, 'deleted successfully');
        } else {
            return  $this->failed('failed to delete data');
        }
    }
}
