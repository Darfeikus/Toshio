<?php

namespace App;

use Illuminate\Support\Facades\Hash;
use MongoDB\Client;
use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;

class User
{
    private $db;
    private $studentId;
    private $email;
    private $plainPassword;
    private $hashedPassword;
    private $role;
    private $id;
    const KEY = "mxfx*C#QJ*aRfeoKTRPUC%&P5vgk^fbY";

    function __construct() {
        $client = new Client(
            'mongodb+srv://onlineGrader:grader2020@cluster2-apjah.azure.mongodb.net/test?retryWrites=true&w=majority');
        
        $this->db = $client->onlineGrader;
    }

    //GETTERS
    public function getStudentId(){
        return $this->studentId;
    }

    public function getEmail(){
        return $this->email;
    }

    public function getRole(){
        return $this->role;
    }

    public function getId(){
        return $this->id;
    } 

    //SETTERS
    public function setStudentId($studentId){
        $this->studentId = $studentId;
    }

    public function setPassword($password){
        $this->plainPassword = $password;
    }

    public function setEmail($email){
        $this->email = $email;
    }

    public function setRole($role){
        $this->role = $role;
    }

    public function create()
    {   
        if($this->alreadyExist())return false;
        $document = array(
            'student_id' => $this->studentId,
            'password' => Hash::make($this->plainPassword),
            'role' => $this->role,
        );
        $collection = $this->db->users;
        $result = $collection->insertOne($document);
        $this->id = $result->getInsertedId();
        return true;
    }

    public function alreadyExist(){
        $collection = $this->db->users;
        $existent = $collection->findOne(['student_id' => $this->studentId]);
        if($existent == NULL) return false;
        $this->studentId = $existent['student_id'];
        $this->hashedPassword = $existent['password'];
        $this->role = $existent['role'];
        return true;
    }

    public function passwordMatch($password){
        return Hash::check($password, $this->hashedPassword);
    }

    public function tokenize(){
        $payload = array(
            "iss" => "http://example.org",
            "aud" => "http://example.com",
            "nbf" => mktime(0, 0, 0, date("m"),   date("d"),   date("Y")),
            "exp" => mktime(2, 0, 0, date("m")+1,   1,   date("Y")),
            "user" => array(
                'student_id' => $this->studentId,
                'role' => $this->role
            )
        );
        return JWT::encode($payload, self::KEY);
    }

    public static function getFromToken($token){
        JWT::$leeway = 60; // $leeway in seconds
        $decoded = JWT::decode($token, self::KEY, array('HS256'));

        $user = new User();
        $user->setStudentId($decoded->user->student_id);
        $user->setRole($decoded->user->role);
        return $user;
    }
}
