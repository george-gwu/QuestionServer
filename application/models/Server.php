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
    public function signUp($userName, $UPWD, $role=2) {
        
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
     */
    public function logIn($userName, $UPWD) {
        
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
    
       
    public function logOut($sessionID){
        $userDb = new Application_Model_User();
        if($userDb->deleteSession($sessionID)){
            return array('success'=>true);            
        } else {
            throw new Exception("Invalid Session ID", -32010);
        }
    }
   

}
