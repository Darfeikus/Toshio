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
    
    system('echo nombre:'.$nombre.' crn:'.$crn.' intentos:'.$intentos.' lenguaje: '.$lenguaje.' '.$fechaApertura.' '.$fechaClausura.' > out');

    $target_dir = 'testCases/'.$crn.'/';

    if (!file_exists($target_dir)) {        
        mkdir($target_dir, 0777, true);
    }

    $filename = basename($_FILES['file']['name']);
    $target_file = $target_dir.$filename;
    move_uploaded_file($_FILES['file']['tmp_name'], $target_file);
    
    $zip = new ZipArchive;
    $res = $zip->open($target_file);
    
    if ($res === TRUE) {
        $zip->extractTo($target_dir.$nombre);
        $zip->close();
    }
    else{
        echo 'extraction error';
    }

    AssignmentController::createAssignment($nombre,$crn,$fechaApertura,$fechaClausura,$intentos,$lenguaje);
}
?>