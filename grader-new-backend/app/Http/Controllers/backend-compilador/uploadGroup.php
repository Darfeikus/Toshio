<?php

function RandomString() {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%';
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
                if($i != 0){ // Termcode and crn
                    $termcode = $elt[0];
                    $crn = $elt[1];
                    /*
                        Create group in psql with crn, termcode, teacher id
                        Once created, get id from the group
                    */
                }
                $currentId = $elt[4];
                /*
                    Select * from Students where id = $currentId;
                    Assign the students to their group.
                */
                $password = RandomString();
                $hashed = md5($password);
                
                array_push($arrayUsers,array($currentId,$password));
            }
            $i++;
        }
    } else {
        echo SimpleXLS::parseError();
    }

    return $arrayUsers;
}
?>