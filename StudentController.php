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
use DB;

class StudentController extends Controller
{

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
                $token = $data->createToken('api_token')->accessToken;
                return response()->json(['token'=>"data  insered successfully",'status'=>200]);
            }else{
                return response()->json(['data' => "data  failed",'status'=>201]);
            }




    }
    //user login api
    public function loginUser(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }
        $user = User::where('email', $request->email)->first();
        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('Laravel Password Grant Client')->accessToken;
                $response = ["msg"=>"successfully login",'token' => $token];
                return response($response, 200);
            } else {
                $response = ["message" => "Password mismatch"];
                return response($response, 422);
            }
        } else {
            $response = ["message" =>'User does not exist'];
            return response($response, 422);
        }
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
            'email' => 'required|unique:students',
            'country' => 'required',
        ]);
        if($validated->fails())
        {
            return $validated->errors()->first();
        }
        $user=Student::find($id);
         if($user){
            $user->update($request->all());
            return response()->json(['data' => "data successfully updated",'status'=>200]);
        }else{
            return response()->json(['data' =>"data updated failed",'status'=>201]);
        }


    }
//delete data api
    public function destroy($id)
    {
        if(Student::find($id)){
             Student::destroy($id);
            return response()->json(['data' => "user successfully deleted",'status'=>200]);
        }else{
            return response()->json(['data' =>"user deleted failed",'status'=>201]);
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

        $user = User::firstWhere('email', $request->email)->first();
        $name = $user->name;
        //return $name;die;

        $token = rand(111111,999999);
        $CurrTime = Carbon::now();
        $otp_expires = $CurrTime->subMinute(2);
        //return $emailotp;die;
        $mail= Otp::firstWhere('email', $email);
        if($mail!=""){
            return response()->json(['data' => "otp already generated",'status'=>201]);
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
        return response()->json(['data' => "otp does not matched",'status'=>201]);
    } else{

        return response()->json(['data' => "Otp verified",'status'=>200]);
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
        return response()->json(['data' => "password successfully changed",'status'=>200]);

    }else{
        return response()->json(['data' => "something wrong",'status'=>201]);
    }









}
}
