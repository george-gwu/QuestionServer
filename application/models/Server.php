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
     * @param  int $role    Role Code (1-User/0-Admin)
     * @return int $userID  A user ID is returned on successful registration
     */
    public function userRegister($user, $password, $role=1) {
        
        $validator = new Zend_Validate_Alnum();
        if(!$validator->isValid($user)){
            throw new Exception("Invalid Username: Must be Alphanumeric");
        }
               
        if(!$validator->isValid($password)){
            throw new Exception("Invalid Password: Must be Alphanumeric");
        }  
        
        if($role!=1 && $role !=0){ $role=1; }
        
        $userDb = new Application_Model_User();  
        
        if($userDb->userExists($user)){
            throw new Exception("User Exists");
        }
        
        $userID = $userDb->createUser($user, $password, $role);
        
        return array('userID' => $userID);
    }

    
    
    /**
     * Login a user
     *
     * @param  string $user 
     * @param  string $password
     * @return string $sessionID Hex String of Session ID
     * @return int    $role  Role Code (1-User/0-Admin)
     */
    public function userLogin($user, $password) {
        
        $userDb = new Application_Model_User();
        $valid = $userDb->isUserPasswordValid($user, $password);
        
        if($valid){             
            $sessionID = $userDb->createSession($user);            
            $userData = $userDb->getUserDataFromSessionID($sessionID);
            
            return array('sessionID' => $sessionID, 'role' => $userData['role']);
            
        } else { 
            throw new Exception("Failed Login");
        }
    }
    
       
    
    
    /**
     * Start a new exam
     *
     * @param  string $sessionID  A required login session is required
     * @param  int $examID  A required login session is required
     * @return Array of Questions & possible Answers
     */
    public function questionStart($sessionID, $examID) {
        
        return array(
                    0 => array(  'question'=>"2 + 2 =",
                            'answers'=> array(0=>"0",1=>"3",2=>"4",3=>"8")                                
                        ),
                    1 => array(  'question'=>"The sky is blue.",
                            'answers'=> array(0=>"False",1=>"True")                                
                        ),            
            );
            

    }
        
   

}
