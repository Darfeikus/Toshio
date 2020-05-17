<?php
  
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: PUT, GET, POST");
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    
    $id_student = $_GET['id'];
    $id_assignment = $_GET['idAss'];

    //File Upload
    
    $folderPath = 'uploads/'.$id_student.'/'.$id_assignment.'/';
    $testCases = 'testCases/'.$id_assignment.'/';
    $numberOfTestCases = 10;

    if(!file_exists($folderPath)){
        mkdir($folderPath,0777,true);
    }

    $file_tmp = $_FILES['file']['tmp_name'];
    $file_ext = strtolower(end(explode('.',$_FILES['file']['name'])));
    $file = $folderPath . 'Main' . '.'.$file_ext;
    move_uploaded_file($file_tmp, $file);
  
    //

    //Compile Uploaded file

    system('docker run --rm -v "$PWD":/usr/src/myapp -w /usr/src/myapp openjdk:8-jdk-alpine javac '.$folderPath.'Main.java');

    //Compile Text Comparison

    system('docker run --rm -v "$PWD":/usr/src/myapp -w /usr/src/myapp openjdk:8-jdk-alpine javac -d '.$folderPath.' CompareTextFiles/Java/CompareTextFilesJava.java');

    //Run TestCases

    for ($i=1; $i <= $numberOfTestCases; $i++) { 
        system('docker run -i --rm -v "$PWD":/usr/src/myapp -w /usr/src/myapp openjdk:8-jdk-alpine java -cp '.$folderPath.' Main < '.$testCases.'in'.$i.' > '.$folderPath.'out'.$i);
    }

    //Run Text Comparison
    system('echo '.$id_student.'#'.$id_assignment.'#'.$numberOfTestCases.' > '.$folderPath.'input');
    system('docker run -i --rm -v "$PWD":/usr/src/myapp -w /usr/src/myapp openjdk:8-jdk-alpine java -cp '.$folderPath.' CompareTextFilesJava < '.$folderPath.'input');

    //Clean TestCase
    system('rm '.$folderPath.'out* && rm '.$folderPath.'Main.* && rm '.$folderPath.'CompareTextFilesJava.class');
?>