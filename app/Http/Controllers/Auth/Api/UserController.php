<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Validator;
use Input;
use Auth;
use Cookie;
use Session;
use DB;
use Image;
use File;
use Exception;
use Mail;
use App\Models\User;
use App\Helpers\AdminHelper;
use Carbon;

class UserController extends Controller
{
    //=================================================================

    public function usersList(Request $request)
    {
        $data = User::select('id','user_type','status','email','name')
                        ->where('user_type','user')
                        ->where('status','1')
                        ->get();

        if(count($data)>0){
            return response()->json($data,200);
        }else{
            return response()->json(array(
                        'status' => 400,
                        'message'=> 'Error',
                        'error_message'=>'No data found!'
                    ),200);
        }
    }

    //=================================================================
    
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                                        'status' => 400,
                                        'message'=> 'Error',
                                        'error_message'=>$validator->errors()
                                    ),200);
        }else{
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password]))
            {
                if(Auth::user()->status == 0) // status 0 is for disabled users
                {
                    return response()->json(array(
                                                'status' => 400,
                                                'message'=> 'Error',
                                                'error_message'=>'Your account is inactive!'
                                            ),200);
                }

                $user = User::find(Auth::user()->id);
                $user->device_id = $request->device_id;
                $user->fcm_token = $request->fcm_token;
                $user->last_login = date("Y-m-d H:i:s");
                $accessToken = $user->createToken('auth_token')->plainTextToken;
                if ($user->save()) {
                    return response()->json(array(
                            'status' => 200,
                            'message'=> 'Success',
                            'success_message'=>'User logged in successfully.',
                            'data' => $user,
                            'access_token' => $accessToken,
                            'token_type' => 'Bearer',
                        ),200);
                }else{
                    return response()->json(array(
                            'status' => 400,
                            'message'=> 'Error',
                            'error_message'=>'Something went wrong!'
                        ),200);
                }
            }else{
                return response()->json(array(
                            'status' => 400,
                            'message'=> 'Error',
                            'error_message'=>'Incorrect email or password!'
                        ),200);
            }
        }
    }
}
