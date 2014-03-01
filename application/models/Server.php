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
        
        return array('userID' => 55);
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
        
        return array('sessionID' => "abcde12345", 'role' => 1);
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
