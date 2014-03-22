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
     * @return int    $role  Role Code (1-User/0-Admin)
     * 
     * curl -i -X POST -d '{ "jsonrpc": "2.0", "method": "LogIn",  "params": { "userName": "test",   "UPWD": "test"  }, "id": 1}' http://107.170.68.145/rpc.php
     */
    public function LogIn($userName, $UPWD) {
        
        $userDb = new Application_Model_User();
        $valid = $userDb->isUserPasswordValid($userName, $UPWD);
        
        if($valid){             
            $sessionID = $userDb->createSession($userName);            
            $userData = $userDb->getUserDataFromSessionID($sessionID);
            
            return array('sessionID' => $sessionID, 'role' => $userData['role']);
            
        } else { 
            throw new Exception("Wrong username or password!",-32001);
        }
    }
    
       
    /**
     * Log out a session
     * @param type $sessionID
     * @return type
     * @throws Exception
     */
    public function LogOut($sessionID){
        $userDb = new Application_Model_User();
        if($userDb->deleteSession($sessionID)){
            return array('success'=>true);            
        } else {
            throw new Exception("Invalid Session ID", -32010);
        }
    }
   
    /**
     * Search for an exam where the teacher owns it
     * @param type $userName
     * @param type $content is keyword
     */
    public function TeacherSearch($userName, $content){
        $examDb = new Application_Model_Exam();
        $examResults = $examDb->searchExams($content, $userName);
        
        $arrayResults = $examResults->toArray();
        
        $results = array();
        
        foreach($arrayResults as $arr){
            $results[] = array('examID'=>$arr['ID'], 'subject'=>$arr['title'], 'status'=>$arr['status']);
        }
        
        return $results;
        
    }
    
    /**
     * Search for a published exams
     * @param type $content is keyword
     */
    public function StudentSearch($content){
        $examDb = new Application_Model_Exam();
        $examResults = $examDb->searchExams($content,null,array(Application_Model_Exam::$ONGOING));
        
        $arrayResults = $examResults->toArray();
        
        $results = array();
        
        foreach($arrayResults as $arr){
            $results[] = array('examID'=>$arr['ID'], 'subject'=>$arr['title'], 'status'=>$arr['status']);
        }
        
        return $results;
        
    }
    

}
