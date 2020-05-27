<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\User;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function register(Request $request) {
        $validator = $this->validator($request->all());
        if($validator->fails()){
            return response(
                json_encode(array('error' => true, 'error_message' => "Your request is not correct"))
                , 400)->header('Content-type', 'application/json');
        }
        return $this->create($request->all());
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
            'role' => ['required', 'string', 'max:20'],
            'student_id' => ['required', 'string', 'min:9', 'max:9'],
            'password' => ['required', 'string', 'min:8'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {   
        $user = new User();
        $user->setStudentId($data['student_id']);
        $user->setPassword($data['password']);
        $user->setRole($data['role']);
        
        if($user->alreadyExist()){
            return response(
                json_encode(array('error' => true, 'error_message' => "Student ID Already used"))
                , 400)->header('Content-type', 'application/json');
        }
        $id = $user->create();
        return response(
            json_encode($user->getId())
            , 201)->header('Content-type', 'application/json');
    }
}
