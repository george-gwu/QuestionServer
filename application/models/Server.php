<?php

/**
 * Server - Main Class exposed via JSON-RPC
 */
class Application_Model_Server {
   

    /**
     * Register a user
     *
     * @param  string $user 
     * @param  string $password
     * @param  int $role    Role Code (1-Teacher/2-Student) Required but Client Sets
     * @return int $userID  A user ID is returned on successful registration
     */
    public function SignUp($userName, $UPWD, $role=2) {
        
        $validator = new Zend_Validate_Alnum();
        if(!$validator->isValid($userName)){
            throw new Exception("Invalid Username: Must be Alphanumeric");
        }
               
        if(!$validator->isValid($UPWD)){
            throw new Exception("Invalid Password: Must be Alphanumeric");
        }  
        
        if($role!=1 && $role !=2){ $role=2; }
        
        $userDb = new Application_Model_User();  
        
        if($userDb->userExists($userName)){
            throw new Exception("User Exists", -3200);
        }
        
        $userID = $userDb->createUser($userName, $UPWD, $role);
        
        return array('userID' => $userID);
    }

    
    
    /**
     * Login a user
     *
     * @param  string $user 
     * @param  string $password
     * @return string $sessionID Hex String of Session ID
     * @return int    $role  Role Code (1-Teacher/2-Student)
     * 
     * curl -i -X POST -d '{ "jsonrpc": "2.0", "method": "LogIn",  "params": { "userName": "test",   "UPWD": "test"  }, "id": 1}' http://107.170.68.145/rpc.php
     */
    public function LogIn($userName, $UPWD, $role) {
        
        $userDb = new Application_Model_User();
        $valid = $userDb->isUserPasswordValid($userName, $UPWD);
        
        if($valid){             
            $sessionID = $userDb->createSession($userName);            
            $userData = $userDb->getUserDataFromSessionID($sessionID);
            
            if($role != $userData['role']){
                $niceRole = ($userData['role']==1 ? 'Teacher' : 'Student');
                throw new Exception("Wrong client! As a $niceRole, you need to use the $niceRole client.",-32022);
            }
            
            return array('sessionID' => $sessionID, 'role' => $userData['role']);
            
        } else { 
            throw new Exception("Wrong username or password!",-32001);
        }
    }
    
       
    /**
     * Log out a session
     * @param String $sessionID
     * @return Success/Exception
     * @throws Exception
     */
    public function LogOut($userName){
        return array('success'=>true);            //faked
    }
   
    /**
     * Search for an exam where the teacher owns it
     * @param String $userName
     * @param String $content is keyword
     */
    public function TeacherSearch($userName, $content){
        $examDb = new Application_Model_Exam();
        $examResults = $examDb->searchExams($content, $userName);
        
        $arrayResults = $examResults->toArray();
        
        if(count($arrayResults)>0){            
            $results = array();
            foreach($arrayResults as $arr){
                $results[] = array('examID'=>(int)$arr['ID'], 'subject'=>$arr['title'], 'status'=>$arr['status']);
            }
        } else {
            throw new Exception("No Results!", -32002);
        
            
        }
        return Application_Model_Utility::convertArrayToJavaLinkedList($results,'nextExamItem');
        
    }
    
    /**
     * Search for all exams, client limits
     * @param String $content is keyword
     */
    public function StudentSearch($content){
        $examDb = new Application_Model_Exam();
        $examResults = $examDb->searchExams($content,null); //,array(Application_Model_Exam::$ONGOING)
        
        $arrayResults = $examResults->toArray();
        
        
        if(count($arrayResults)>0){            
            $results = array();
            foreach($arrayResults as $arr){
                $results[] = array('examID'=>(int)$arr['ID'], 'subject'=>$arr['title'], 'status'=>$arr['status']);
            }
        } else {
            throw new Exception("No Results!", -32002);
        }
        
        return Application_Model_Utility::convertArrayToJavaLinkedList($results,'nextExamItem');
        
    }
    
    /**
     * Teacher get Exam
     * @param int $content examID
     */
    public function GetExam($content){
        $examDb = new Application_Model_Exam();
        return $examDb->getExamination($content);            
    }
    

    /**
     * 
     * @param type $Examination
     * @return Success
     */
    public function NewExam($Examination){
        // Due to a bug in the Java clients they have to double encode
        // This is a hack fix to pull out the double encoding per the original protocol
        $raw = Zend_Json::decode($Examination);       
        
        $userName = $raw['userName'];
        $subject = $raw['subject'];
        $EPWD = $raw['EPWD'];
        $timeLimit = $raw['timeLimit'];
        $status = $raw['status'];
        $firstQuestion = $raw['firstQuestion'];
        // End of hack fix
        
        $examDb = new Application_Model_Exam();
        $questionDb = new Application_Model_Question();
        $answerDb = new Application_Model_Answer();
        $examID = $examDb->createExam($subject, $userName, $status, $EPWD, $timeLimit);
                
        $questions = Application_Model_Utility::convertJavaLinkedListToArray($firstQuestion, 'nextQuestion');        
        foreach($questions as $question){
            $answers = Application_Model_Utility::convertJavaLinkedListToArray($question['firstAnswer'], 'nextAnswer');        
            $questionID = $questionDb->createQuestion($examID, $question['qText']);            
            foreach($answers as $answer){
                $answerDb->createAnswer($questionID, $answer['aText'], $answer['ifCorrect']);
            }            
        }
        
        return array('success'=>true, 'examID'=>$examID); 
        
    }
    
    /**
     * Student get Exam
     * @param int $content examID
     */
    public function TakeExam($userName, $content){
        $scoresDb = new Application_Model_Scores();
        
        if(!$scoresDb->checkForScore($userName,$content)){
            $examDb = new Application_Model_Exam();
            return $examDb->getExamination($content);            
        } else {
            throw new Exception("Taken before!", -32004);
        }
        
    }    
    
    /**
     * Delete Exam
     * @param int $content examID
     */
    public function DeleteExam($content){
        $examDb = new Application_Model_Exam();
        $deleted = $examDb->deleteExam($content);
        
        if($deleted==0){
            throw new Exception("Invalid Exam", -32002);
        } else {
            return array('success'=>true);            
        }
    }
    
    /**
     * Publish Exam
     * @param int $content ExamID
     * @return success status
     */
    public function PublishExam($content){        
        $examDb = new Application_Model_Exam();
        $updated = $examDb->updateExamStatus($content, Application_Model_Exam::$ONGOING);
        
        if($updated==0){
            return array('success'=>false); 
        } else {
            return array('success'=>true);            
        }        
    }
    
    /**
     * Unpublish Exam
     * @param int $content ExamID
     * @return success status
     */
    public function UnpublishExam($content){        
        $examDb = new Application_Model_Exam();
        $updated = $examDb->updateExamStatus($content, Application_Model_Exam::$UNPUBLISHED);
        
        if($updated==0){
            return array('success'=>false); 
        } else {
            return array('success'=>true);            
        }        
    }
    
    /**
     * Terminate Exam
     * @param int $content ExamID
     * @return success status
     */
    public function TerminateExam($content){        
        $examDb = new Application_Model_Exam();
        
        $updated = $examDb->updateExamStatus($content, Application_Model_Exam::$TERMINATED);
        if($updated==0){
            return array('success'=>false); 
        } else {
            return array('success'=>true);            
        }        
        
    }
    
    /**
     * Submit an Exam Score
     * @param String $userName
     * @param int $examID
     * @param int $score
     * @return Success/Error
     * @throws Exception
     */
    public function SubmitExam($userName, $examID, $score){
        $scoresDb = new Application_Model_Scores();
        if($scoresDb->submitScore($userName, $examID, $score)>1){
            return array('success'=>true);            
        } else {
            throw new Exception("Taken before!", -32004);
        }
    }
    
    /**
     * Student Score Search
     * @param String $userName
     * @param int $content ExamID
     * @return Scores
     * @throws Exception
     */
    public function SearchScore($userName, $content=null){
        
        if(empty($content)){$content=null;}
        
        if(empty($userName)){ throw new Exception("User Name is Required."); }
        
        $scoresDb = new Application_Model_Scores();
        $scores = $scoresDb->searchScores($userName, $content)->toArray();
        
        if(count($scores)>0){
            $results = array();
            foreach($scores as $score){
                $results[] = array('userName'=>$score['user'],  'score' => $score['score'], 'subject' => $score['title']);            //'examID'=> $score['examID'],
            }
            return Application_Model_Utility::convertArrayToJavaLinkedList($results,'nextScoreItem');
        } else {
            throw new Exception("No Score!", -32005);
        }
    }
    
    /**
     * Teacher Search Scores
     * @param String $userName unused
     * @param int $content examID
     * @return Scores
     * @throws Exception
     */
    public function ScoreStatistics($userName, $content){
               
        $scoresDb = new Application_Model_Scores();
        $scores = $scoresDb->searchScores(null, $content)->toArray();
        
        if(count($scores)>0){
            $results = array();
            foreach($scores as $score){
                $results[] = array('userName'=>$score['user'],  'score' => $score['score'], 'subject' => $score['title']);            //'examID'=> $score['examID'],
            }
            return Application_Model_Utility::convertArrayToJavaLinkedList($results,'nextScoreItem');
        } else {
            throw new Exception("No Score!", -32005);
        }
    }    
    

}
