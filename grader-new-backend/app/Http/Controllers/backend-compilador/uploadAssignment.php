<?php

use \App\Http\Controllers\AssignmentController;

function compile()
{
    $id_teacher = $_GET['id'];
    $nombre = $_POST['nombre'];
    $lenguaje = $_POST['lenguaje'];
    $crn = $_POST['materia'];
    $intentos = $_POST['intentos'];
    $fechaApertura = $_POST['fechaApertura'].' '.$horaApertura = $_POST['horaApertura'];;
    $fechaClausura = $_POST['fechaClausura'].' '.$horaClausura = $_POST['horaClausura'];    

    $allowed1 = array('zip');
    $allowed2 = array('pdf');

    $ext1 = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    $ext2 = pathinfo($_FILES['file2']['name'], PATHINFO_EXTENSION);

    if (!in_array($ext1, $allowed1) || !in_array($ext2, $allowed2)) {
        return json_encode(array('error' => true, 'message' => 'Invalid Format'));
    }

    $idAss = AssignmentController::createAssignment($nombre,$crn,$fechaApertura,$fechaClausura,$intentos,$lenguaje);
        
    try{
        $target_dir = 'testCases/'.$crn.'/'.$idAss.'/';
             
        mkdir($target_dir, 0777, true);
    
        $target_file = $target_dir.$nombre;
        move_uploaded_file($_FILES['file']['tmp_name'], $target_file);
        move_uploaded_file($_FILES['file2']['tmp_name'], $target_dir.'rules.pdf');
        
        $zip = new ZipArchive;
        $res = $zip->open($target_file);
        
        if ($res === TRUE) {
            $zip->extractTo($target_dir);
            $zip->close();
        }
        else{
            AssignmentController::delete($idAss);
            return json_encode(array('error' => true, 'message' => 'Extraction Error'));
        }
    }
    catch(Exception $e){
        AssignmentController::delete($idAss);
    }
    return json_encode(array('error' => false, 'message' => 'Success'));
}
?>