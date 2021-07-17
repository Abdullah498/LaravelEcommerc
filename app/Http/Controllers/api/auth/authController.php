<?php

namespace App\Http\Controllers\api\auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Traits\generalTrait;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;
class authController extends Controller
{
    use generalTrait;
    public function register(Request $request)
    {
        $rules = [
            'name'=>'required|string|min:2',
            'email'=>'required|unique:users',
            'phone'=>'required|numeric|unique:users|digits:11',
            'password'=>'required|min:8'
        ];
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return $this->returnValidationError($validator);
        }

        $data = $request->except('password');
        $data['password'] = bcrypt($request->password);
        $id = User::insertGetId($data);
        $user = User::find($id);

        //generate and return token with user data
        $credentials = $request->only('email', 'password');

        if (! $token = auth('api')->attempt($credentials)) {
            return $this->returnError(401 , 'Unauthorized');
        }
        $user->token ='bearer '.$token;
        return $this->returnData('user',$user);
    }

    public function checkCode(Request $request)
    {
        $rules = [
            'code'=>'required|exists:users|integer|digits:5'
        ];
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return $this->returnValidationError($validator);
        }

        $id = auth('api')->user()->id;
        $check = User::where('id','=',$id)->where('code','=',$request->code)->first();
        if($check){
            $user = User::find($id);
            $user->status = 1;
            $user->save();
            $user->token = $request->header('authorization');
            return $this->returnData('user',$user);
        }
        return $this->returnError(403 , 'Wrong Code');
    }

    public function login(Request $request){
        $rules = [
            'email'=>'required',
            'password'=>'required|min:8'
        ];
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return $this->returnValidationError($validator);
        }

        $credentials = $request->all();

        if (! $token = auth('api')->attempt($credentials)) {
            return $this->returnError(401 , 'Unauthorized');
        }

        $user = auth('api')->user();
        if($user->status == 2){
            return $this->returnError(401 , 'Your account is not verified');
        }
        $user->token ='bearer '.$token;
        return $this->returnData('user',$user);
    } 
    public function sendCode(Request $request)
    {
        $user = auth('api')->user();
        $email = $user->email;

        $updatedUser = User::find($user->id);
        $code = rand(11111,99999);
        $updatedUser->code = $code;
        $updatedUser->save();

        //sending mail
        Mail::to($email)->send(new SendMail($code));

        $updatedUser->token = $request->header('authorization');
        return $this->returnData('user',$updatedUser);

    }
    public function profile()
    {
        return $this->returnData('user' , auth('api')->user());
    }

    public function updateProfile(Request $request)
    {
        $rules = [
            'name'=>'string|min:2',
            'phone'=>'numeric|digits:11'
        ];
        $validator = Validator::make($request->all(),$rules);
        if($validator->fails()){
            return $this->returnValidationError($validator);
        }

        $user = auth('api')->user();
        try{
            $check = User::where('id','=',$user->id)->update($request->all());
            if($check){
                if($request->has('name')){
                    $user->name = $request->name;
                }
                if($request->has('phone')){
                    $user->phone = $request->phone;
                }
                $user->token = $request->header('authorization');
                return $this->returnData('user',$user);
            }
        }catch(QueryException $e){ //identify error to send suitable error response
            $message = $e->getMessage();
            if(strpos($message , 'users_phone_unique') !== false){
                return $this->returnError(403 , "Phone is already exist");
            }else{
                return $this->returnError(403 , "Mail is already exist");
            }
        }
    }
    //logout destroy token
    public function logout()
    {
        auth('api')->logout();
        return $this->returnSuccessMessage('Successfully logged out');
    }
}
