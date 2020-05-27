<?php

use \App\Http\Controllers\MailController;
use \App\Http\Controllers\GroupController;

function RandomString() {
    $characters = '0123456789abcdefghijkmlmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < 6; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function compile()
{
    include "SimpleXLS.php";

    $id_teacher = $_GET['id'];
    $name = $_GET['name'];

    $target_dir = 'groups/';
    $filename = basename($_FILES['file']['name']);
    $target_file = $target_dir.$filename;
    move_uploaded_file($_FILES['file']['tmp_name'], $target_file);
    $termcode = 0;
    $crn = 0;
    $arrayUsers = array();

    if ($xlsx = SimpleXLS::parse($target_file)) {
        $i = 0; //rows
        foreach ($xlsx->rows() as $elt) {
            if($i != 0){
                if($i == 1){ // Termcode and crn
                    $termcode = $elt[0];
                    $crn = $elt[1];
                    GroupController::createGroup($id_teacher,$crn,$name,$termcode);
                }
                $currentId = $elt[4];
                
                GroupController::insertStudent($crn,$currentId);
                
                $password = RandomString();
                $hashed = md5($password);

                // MailController::sendMail($currentId,$password);

                array_push($arrayUsers,array($currentId,$password));
            }
            $i++;
        }
    } else {
        echo SimpleXLS::parseError();
    }
    $i = 0;
    foreach($arrayUsers as $res){
        system('echo '.implode(" ",$res).' > test/out'.$i++);
    }
    return $arrayUsers;
}
?>