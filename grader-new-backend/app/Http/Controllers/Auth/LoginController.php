<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use Firebase\JWT\JWT;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    public function login(Request $request){
        $validator = $this->validator($request->all());
        if($validator->fails()){
            return response(
                json_encode(array('error' => true, 'error_message' => "Your request is not correct"))
                , 400)->header('Content-type', 'application/json');
        }
        return $this->validateLogin($request->all());
    }

    public function validateLogin(array $data){
        $user = new User();
        $user->setStudentId($data['student_id']);
        if(!$user->alreadyExist()){
            return response(
                json_encode(array('error' => true, 'error_message' => "Non existant Student ID"))
                , 400)->header('Content-type', 'application/json');
        }
    
        if(!$user->passwordMatch($data['password'])){
            return response(
                json_encode(array('error' => true, 'error_message' => "Incorrect Password"))
                , 400)->header('Content-type', 'application/json');
        }
        $key = "mxfx*C#QJ*aRfeoKTRPUC%&P5vgk^fbY";
        $payload = array(
            "iss" => "http://example.org",
            "aud" => "http://example.com",
            "iat" => 1356999524,
            "nbf" => 1357000000,
            "user" => array(
                'student_id' => $user->getStudentId(),
                'role' => $user->getRole()
            )
        );
        $jwt = JWT::encode($payload, $key);
        return response(
            json_encode(array('message' => 'SuccessfulLogin', 'token' => $jwt))
            , 200)->header('Content-type', 'application/json');
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'student_id' => ['required', 'string', 'min:9', 'max:9'],
            'password' => ['required', 'string', 'min:8'],
        ]);
    }

    
    

    public function denyAccess(){
        return("Unauthorized");
    }
}
