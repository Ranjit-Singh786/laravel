<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Otp;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Validator;
use App\Traits\Reply;
use DB;

class StudentController extends Controller
{
    use Reply;

//create data api
    public function create(Request $request)
    {
        $validated =  Validator::make($request->all() , [
            'name' => 'required|unique:students|max:255',
            'email' => 'required|unique:students',
            'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
            'password_confirmation' => 'min:6'
        ]);

        if($validated->fails())
        {
            return $validated->errors()->first();
        }

        $request['password'] = Hash::make($request['password']);
        //$user = User::create($request->toArray());
      $data= User::create($request->all());
            //return response()->json($data);
            if($data){
                // $token = $data->createToken('api_token')->accessToken;

               return  $this->success($data);
            }else{
               return $this->failed($data);
            }




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
    //get data api
    public function getData(Request $request)
    {
         return  Student::all();

}

//update data api
public function updateData(Request $request,$id)
    {
        $validated =  Validator::make($request->all() , [
            'name' => 'required|unique:students|max:255',
            'email' => 'required|unique:students'

        ]);
        if($validated->fails())
        {
            return $validated->errors()->first();
        }
        $data=User::find($id);
         if($data){
            $data->update($request->all());
            return  $this->success($data);
        }else{
            return  $this->failed('failed');
        }


    }
//delete data api
    public function destroy($id)
    {
        $data = User::find($id);
        if($data){
            $data =  User::destroy($id);
            return  $this->success($data,'deleted successfully');
        }else{
            return  $this->failed('failed to delete data');
        }
    }


    public  function forgotPassword(Request $request){
        $email = $request->email;
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users',

        ]);
        if($validator->fails())
        {
            return $validator->errors()->first();
        }

        $user = User::firstWhere('email', $request->email);
        $name = $user->name;
        //return $name;die;

        $token = rand(111111,999999);
        $CurrTime = Carbon::now();
        $otp_expires = $CurrTime->subMinute(1);
        //return $emailotp;die;
        $mail= Otp::firstWhere('email', $email);
        if($mail!=""){
            Otp::where('created_at', '<=',$otp_expires)->delete();
            return  $this->failed('otp already generated');
        }

        $data = Otp::where('created_at', '<=',$otp_expires)->delete();

          $otpobj = new Otp;
          //return $otpobj->email;die;
          $otpobj->email =$email;

            $otpobj->otp = $token;
            //return $token;die;

            $otpobj->save();
          //return $otp;

          $post_data = [
        "Messages"=>[
                [
                        "From"=> [
                                "Email"=> "ramandeep@5tb.in",
                                "Name"=> "TESTING MAIL"
                        ],
                        "To"=> [
                                [
                                        "Email"=> $email,
                                        "Name"=> $name
                                ]
                        ],
                        "Subject"=> "Your OTP is ".$token,
                        "TextPart"=> "Your OTP is ".$token,
                        "HTMLPart"=> "<h3>Your Otp is ".$token."</h3>"
                ]
        ]
            ];


    // CALL API

    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.mailjet.com/v3.1/send',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => json_encode($post_data),
      CURLOPT_HTTPHEADER => array(
        'Authorization: Basic Mzc4NjQyYjlhZjhiZjE0NjQ3ZWJmZWEzOTU3MjZlMTg6NzkwMmRlMDE1N2ZlYzRjZjg0MDQ2NzcyZDI3MjQxYTQ=',
        'Content-Type: application/json'
      ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);

    return json_decode($response,true);




    }

   public function verifyotp(Request $request){

    $validator = Validator::make($request->all(), [
        'email' => 'required|email|exists:otps',
        'otp'=>  'required|exists:otps'

    ]);
    if($validator->fails())
    {
        return $validator->errors()->first();
    }
    $email = $request->email;
    $otp = $request->otp;

    $user = Otp::where('email', $email)->first();
    if($user->otp != $otp){
        return  $this->failed('Otp does not match');
    } else{

        return  $this->success('otp verified');
    }

}

public function changePassword(Request $request){
    $validator = Validator::make($request->all(), [
        'email' => 'required|email|exists:otps',
        'otp'=>  'required|exists:otps',
        'password' => 'min:6|required_with:password_confirmation|same:password_confirmation',
        'password_confirmation' => 'min:6'

    ]);
    if($validator->fails())
    {
        return $validator->errors()->first();
    }
    $email = $request->email;
    $password = Hash::make($request->password);
    $c_password = Hash::make($request->password_confirmation);
    //return $c_password;
    $OtpModel = Otp::where('email', $email)->first();
    $otp_verify =  $OtpModel->otp;

    $userModel = User::where('email', $email)->first();
    $userModel->email = $email;
    $userModel->password = $password;
    $UpdateData = $userModel->update();

    if($UpdateData){
        $OtpModel->delete();
        return  $this->success('update data successfully');

    }else{
        return  $this->failed('failed to update');
    }
}
//logout api
public function UserLogOut(Request $request)
    {
        if (Auth::check()) {
            Auth::user()->token()->delete();
            return  $this->success('user logged out successfully');
          }else{
            return  $this->failed('user logged out failed');
          }


    }

    public function UserHome(Request $request)
    {

         Auth::user();
         return  $this->success('welcome to homepage');
    }
}
