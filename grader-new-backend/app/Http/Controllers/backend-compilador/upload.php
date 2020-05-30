<?php
    use App\Http\Controllers\SubmissionController;

    function compile(){
        $id_student = $_GET['id'];
        $crn = $_GET['crn'];
        $id_assignment = $_GET['idAss'];
        $lang = SubmissionController::getLanguage($_GET['lang']);
        
        //File Upload
        
        $folderPath = 'uploads/'.$id_student.'/'.$id_assignment.'/';
        $testCases = 'testCases/'.$crn.'/'.$id_assignment.'/';

        $fi = new FilesystemIterator($testCases, FilesystemIterator::SKIP_DOTS);

        $numberOfTestCases = intdiv(iterator_count($fi),3);
        
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
        system('echo '.$id_student.'#'.$id_assignment.'#'.$numberOfTestCases.'#'.$crn.' > '.$folderPath.'input');
        system('docker run -i --rm -v "$PWD":/usr/src/myapp -w /usr/src/myapp openjdk:8-jdk-alpine java -cp '.$folderPath.' CompareTextFiles < '.$folderPath.'input');
        
        //Read result
        
        $result = file_get_contents($folderPath.'ResultsOut');

        $grade = explode("\n", $result);
        //Clean TestCase
        
        system('rm '.$folderPath.'out*');
        system('rm '.$folderPath.'input');
        $query = SubmissionController::uploadSubmission($id_assignment,$grade[0],$id_student);
        return $grade[0] == $query[0] ? $result:$query;
    }
?>