<?php
    function compile(){
        $id_student = $_GET['id'];
        $id_assignment = $_GET['idAss'];
        $lang = $_GET['lang'];
        //File Upload
        
        $folderPath = 'uploads/'.$id_student.'/'.$id_assignment.'/';
        $testCases = 'testCases/'.$id_assignment.'/';
        $numberOfTestCases = 10;

        if(!file_exists($folderPath)){
            mkdir($folderPath,0777,true);
        }

        $file_tmp = $_FILES['file']['tmp_name'];
        
        $file = $folderPath.'Main'.'.'.$lang;
        
        move_uploaded_file($file_tmp, $file);
        //
    
        //Compile Uploaded file
    
        if($lang == 'java'){
            system('docker run --rm -v "$PWD":/usr/src/myapp -w /usr/src/myapp openjdk:8-jdk-alpine javac '.$folderPath.'Main.java');
        }
        else if($lang == 'c'){
            system('docker run --rm -v "$PWD":/usr/src/myapp -w /usr/src/myapp gcc:8.2 gcc -o '.$folderPath.'main '.$folderPath.'Main.c');
        }
    
        //Compile Text Comparison
    
        system('docker run --rm -v "$PWD":/usr/src/myapp -w /usr/src/myapp openjdk:8-jdk-alpine javac -d '.$folderPath.' CompareTextFiles/CompareTextFiles.java');
    
        //Run TestCases
    
        for ($i=1; $i <= $numberOfTestCases; $i++) {
            if($lang == 'java'){
                system('docker run -i --rm -v "$PWD":/usr/src/myapp -w /usr/src/myapp openjdk:8-jdk-alpine java -cp '.$folderPath.' Main < '.$testCases.'in'.$i.' > '.$folderPath.'out'.$i);
            }
            else if($lang == 'c'){
                system('./'.$folderPath.'main < '.$testCases.'in'.$i.' > '.$folderPath.'out'.$i);
            }
            else if($lang == 'py'){
                system('docker run -i --rm -v "$PWD":/usr/src/myapp -w /usr/src/myapp python:3 python3 '.$folderPath.'Main.py < '.$testCases.'in'.$i.' > '.$folderPath.'out'.$i);
            }
        }
    
        //Run Text Comparison
        system('echo '.$id_student.'#'.$id_assignment.'#'.$numberOfTestCases.' > '.$folderPath.'input');
        system('docker run -i --rm -v "$PWD":/usr/src/myapp -w /usr/src/myapp openjdk:8-jdk-alpine java -cp '.$folderPath.' CompareTextFiles < '.$folderPath.'input');
    
        //Clean TestCase
        system('rm '.$folderPath.'out*');
        system('rm '.$folderPath.'input');
        
        return(json_encode(array("HOLA" => "MUNDO")));
    }
?>