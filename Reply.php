<?php
namespace App\Traits;
use App\Models\User;
use Illuminate\Http\Request;
trait Reply {
    public function success($msg , $data =null,$token=null) {
        $result = [
            'status_code' => "1",
            "status_text" => "success",
            'message' => $msg,
    ];
            if($data!=null){

                 $result['data'] = $data;
            }
            if($token!=null){

                 $result['token'] = $token;
            }

            return $result;
    }
    public function failed($msg) {
        return   response([
            'status_code'=>"0",
            "status_text"=>"fail",
            'data'=>$msg]);
    }
    }



?>


