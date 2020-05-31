<?php

use \App\Http\Controllers\AssignmentController;

function compile()
{
    $nombre = $_POST['nombre'];
    $lenguaje = $_POST['lenguaje'];
    $crn = $_POST['materia'];
    $intentos = $_POST['intentos'];
    $runtime = $_POST['runtime'];
    $fechaApertura = $_POST['fechaApertura'] . ' ' . $_POST['horaApertura'];
    $fechaClausura = $_POST['fechaClausura'] . ' ' . $_POST['horaClausura'];

    $allowed1 = array('zip');
    $allowed2 = array('pdf');

    $ext1 = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
    $ext2 = pathinfo($_FILES['file2']['name'], PATHINFO_EXTENSION);

    if (!in_array($ext1, $allowed1) || !in_array($ext2, $allowed2)) {
        return json_encode(array('error' => true, 'message' => 'Invalid Format'));
    }

    $idAss = AssignmentController::createAssignment($nombre, $crn, $fechaApertura, $fechaClausura, $intentos, $lenguaje, $runtime);

    try {
        $target_dir = 'testCases/' . $crn . '/' . $idAss . '/';

        mkdir($target_dir, 0777, true);

        $target_file = $target_dir . $nombre;
        move_uploaded_file($_FILES['file']['tmp_name'], $target_file);
        move_uploaded_file($_FILES['file2']['tmp_name'], $target_dir . 'rules.pdf');

        $zip = new ZipArchive;
        $res = $zip->open($target_file);

        if ($res === TRUE) {
            $zip->extractTo($target_dir);
            $zip->close();
        } else {
            AssignmentController::delete($idAss);
            return json_encode(array('error' => true, 'message' => 'Extraction Error'));
        }
    } catch (Exception $e) {
        AssignmentController::delete($idAss);
    }
    return json_encode(array('error' => false, 'message' => 'Success'));
}

function compileUpdate()
{
    $idAss = $_POST['assignment_id'];

    $query = AssignmentController::show($idAss);

    $lenguaje = $query[0]->language_id;

    $crn = $query[0]->crn;


    if (isset($_POST['lenguaje'])) {
        $lenguaje = $_POST['lenguaje'];
    }
    if (isset($_POST['materia'])) {
        $crn = $_POST['materia'];
    }

    $nombre = $_POST['nombre'];
    $intentos = $_POST['intentos'];
    $runtime = $_POST['runtime'];
    $fechaApertura = $_POST['fechaApertura'] . ' ' . $_POST['horaApertura'];
    $fechaClausura = $_POST['fechaClausura'] . ' ' . $_POST['horaClausura'];

    $allowed1 = array('zip');
    $allowed2 = array('pdf');

    $ext1 = "";
    $ext2 = "";

    if (isset($_FILES['file']['name'])) {
        $ext1 = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        if (!in_array($ext1, $allowed1)) {
            return json_encode(array('error' => true, 'message' => 'Invalid Format'));
        }
    }
    if (isset($_FILES['file2']['name'])) {
        $ext2 = pathinfo($_FILES['file2']['name'], PATHINFO_EXTENSION);
        if (!in_array($ext2, $allowed2)) {
            return json_encode(array('error' => true, 'message' => 'Invalid Format'));
        }
    }

    try {
        
        $target_dir = 'testCases/' . $crn . '/' . $idAss . '/';
        $originalPath = 'testCases/' . $query[0]->crn . '/' . $query[0]->assignment_id.'/';
        
        if($target_dir != $originalPath){

            if(!file_exists($target_dir)){
                mkdir($target_dir,0777,true);
            }
            
            if($target_dir != $originalPath){
                system('cp -avr '.$originalPath.' testCases/' . $crn . '/ && rm '.$originalPath.' -R');
            }
        }

        $target_file = $target_dir . $nombre;

        if (isset($_FILES['file']['tmp_name'])) {
            move_uploaded_file($_FILES['file']['tmp_name'], $target_file);
            $zip = new ZipArchive;
            $res = $zip->open($target_file);

            if ($res === TRUE) {
                $zip->extractTo($target_dir);
                $zip->close();
            } else {
                return json_encode(array('error' => true, 'message' => 'Extraction Error'));
            }
        }
        if (isset($_FILES['file2']['tmp_name'])) {
            move_uploaded_file($_FILES['file2']['tmp_name'], $target_dir . 'rules.pdf');
        }

        AssignmentController::update($idAss, $nombre, $crn, $fechaApertura, $fechaClausura, $intentos, $lenguaje, $runtime);
    } catch (Exception $e) {
        return json_encode(array('error' => true, 'message' => $e->getMessage()));
    }
    return json_encode(array('error' => false, 'message' => 'Success'));
}
