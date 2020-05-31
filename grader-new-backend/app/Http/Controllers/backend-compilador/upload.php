<?php
    use App\Http\Controllers\SubmissionController;

    function compile(){
        $id_student = $_GET['id'];
        $crn = $_POST['crn'];
        $runtime = $_POST['runtime'];
        $id_assignment = $_POST['assignment_id'];
        $lang = $_POST['lang'];
        $date = date("Y-m-d H:i:s");
        //File Upload
        
        $folderPath = 'uploads/'.$id_student.'/'.$id_assignment.'/';
        $testCases = 'testCases/'.$crn.'/'.$id_assignment.'/';

        $fi = new FilesystemIterator($testCases, FilesystemIterator::SKIP_DOTS);

        $numberOfTestCases = intdiv(iterator_count($fi),3);
        
        if(!file_exists($folderPath)){
            mkdir($folderPath,0777,true);
        }
        if(!file_exists($folderPath.$date)){
            mkdir($folderPath.$date,0777,true);
        }
        $file_tmp = $_FILES['file']['tmp_name'];
        
        $file = $folderPath.'Main'.'.'.$lang;
        $allowed = array('c','java','py');
        $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        
        if (!in_array($ext, $allowed)) {
            return json_encode(array('error' => true, 'message' => 'Invalid Format'));
        }

        if(!move_uploaded_file($file_tmp, $file)){
            return json_encode(array('error' => true, 'message' => 'Error uploading file'));
        }
        //
    
        //Compile Uploaded file
    
        if($lang == 'java'){
            system('docker run --rm -v "$PWD":/usr/src/myapp -w /usr/src/myapp openjdk:8-jdk-alpine javac '.$folderPath.'Main.java');
            rename($folderPath.'Main.java',$folderPath.'/'.$date.'/Main.java');
            if(!file_exists($folderPath.'Main.class')){
                $query = SubmissionController::uploadSubmission($id_assignment,0,$id_student);
                return json_encode(array('error' => true, 'message' => 'Compilation error'));
            }
        }
        else if($lang == 'c'){
            system('docker run --rm -v "$PWD":/usr/src/myapp -w /usr/src/myapp gcc:8.2 gcc -o '.$folderPath.'main '.$folderPath.'Main.c');
            rename($folderPath.'Main.c',$folderPath.'/'.$date.'/Main.c');
            if(!file_exists($folderPath.'main')){
                $query = SubmissionController::uploadSubmission($id_assignment,0,$id_student);
                return json_encode(array('error' => true, 'message' => 'Compilation error'));
            }
        }
    
        //Compile Text Comparison
    
        system('docker run --rm -v "$PWD":/usr/src/myapp -w /usr/src/myapp openjdk:8-jdk-alpine javac -d '.$folderPath.' CompareTextFiles/CompareTextFiles.java');
    
        //Run TestCases
    
        for ($i=1; $i <= $numberOfTestCases; $i++) {
            if($lang == 'java'){
                system('timeout '.$runtime.'s docker run -i --rm -v "$PWD":/usr/src/myapp -w /usr/src/myapp openjdk:8-jdk-alpine java -cp '.$folderPath.' Main < '.$testCases.'in'.$i.' > '.$folderPath.'out'.$i);
                if($i == $numberOfTestCases){
                    rename($folderPath.'Main.class',$folderPath.'/'.$date.'/Main.class');
                }
            }
            else if($lang == 'c'){
                system('timeout '.$runtime.'s ./'.$folderPath.'main < '.$testCases.'in'.$i.' > '.$folderPath.'out'.$i);
                if($i == $numberOfTestCases){
                    rename($folderPath.'main',$folderPath.'/'.$date.'/main');
                }
            }
            else if($lang == 'py'){
                system('timeout '.$runtime.'s docker run -i --rm -v "$PWD":/usr/src/myapp -w /usr/src/myapp python:3 python3 '.$folderPath.'Main.py < '.$testCases.'in'.$i.' > '.$folderPath.'out'.$i);
                if($i == $numberOfTestCases){
                    rename($folderPath.'Main.py',$folderPath.'/'.$date.'/Main.py');
                }
            }
        }
    
        //Run Text Comparison
        system('echo '.$id_student.'#'.$id_assignment.'#'.$numberOfTestCases.'#'.$crn.' > '.$folderPath.'input');
        
        system('docker run -i --rm -v "$PWD":/usr/src/myapp -w /usr/src/myapp openjdk:8-jdk-alpine java -cp '.$folderPath.' CompareTextFiles < '.$folderPath.'input');
        
        //Read result
        try{
            $result = file_get_contents($folderPath.'ResultsOut');
            $grade = explode("\n", $result);
            //Clean TestCase
            
            system('rm '.$folderPath.'out*');
            system('rm '.$folderPath.'input');
    
            $query = SubmissionController::uploadSubmission($id_assignment,$grade[0],$id_student);
            
            rename($folderPath.'ResultsOut',$folderPath.'/'.$date.'/ResultsOut');

            return json_encode($grade);
        }
        catch(Exception $e){
            // return json_encode(array('error' => true, 'message' => 'Error in grade evaluation, contact administrator'));
            return json_encode(array('error' => true, 'message' => $e->getMessage()));
        }

    }
?>