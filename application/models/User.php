<?php
/**
 * User Model maps to user table and session cache
 */
class Application_Model_User extends Zend_Db_Table_Abstract {
    protected $_name   = 'user';
    protected $_primary = 'ID';

    private static $salt = "dsaf!#@ad122332d1edsf0-";
    
    
    
    /**
     * 
     * @param type $user
     * @param type $password
     * @param type $role
     * @return int $userID (primary key)
     */
    public function createUser($user, $password, $role){
         
        $data = array(
            'user' => $user,
            'password' => sha1($password . self::$salt),
            'role' => $role
        );
 
        return $this->insert($data);
    }
    
    /**
     * Checks whether a user exists by running a count(*)
     * @param type $user
     * @return bool 
     */
    public function userExists($user){
        $select  = $this->select()
                            ->from($this->_name, new Zend_Db_Expr('COUNT(*)'))
                            ->where('user = ?', $user);
        
        $count = $this->getAdapter()->fetchOne($select);                
        
        return ($count==1);
    }
    
    /**
     * Tests a password against a user
     * @param type $user
     * @param type $password
     * @return boolean
     */
    public function isUserPasswordValid($user, $password){
        
        $select  = $this->select()->where('user = ?', $user);
 
        $row = $this->fetchRow($select);
        
        if($row['password'] == sha1($password . self::$salt)){
            return true;
        } else {
            return false;
        }        
    }
    
    /**
     * This assumes a valid login and returns a session ID
     * @param type $user
     * @return type
     */
    public function createSession($user){        
        $sessionID = sha1(microtime() . rand(0, 99999));
        $cacheID = 'session_' . $sessionID;
        
        $select  = $this->select()->where('user = ?', $user); 
        $row = $this->fetchRow($select);
        
        $cache = $this->getCache();        
        $cache->save($row->toArray(), $cacheID);
        
        return $sessionID;      

    }
    
    /**
     * Clear a session if it exists
     * @param type $sessionID
     * @return boolean true/false on success
     */
    public function deleteSession($sessionID){
        $cache = $this->getCache();
        
        $cacheID = 'session_' . $sessionID;
        
        if($cache->test($cacheID)){
            $cache->remove($cacheID);
            return true;
        }
        return false;
    }
    
    /**
     * This pulls the user db row out of the cache (see createSession)
     * @param type $sessionID
     * @return type
     * @throws Exception
     */
    public function getUserDataFromSessionID($sessionID){
        $cacheID = 'session_' . $sessionID;
        
        $cache = $this->getCache();        

        
        if( ($result = $cache->load($cacheID)) !== false ) {
            $cache->touch($cacheID,3600); // extend life by an hour
            return $result;  
        }  else {
            throw new Exception("Invalid Session: "+$sessionID);
        }
        
    }
    
    /**
     * This accesses the Zend Cache (1 hour long)
     * @return Zend_Cache_Core
     */
    protected function getCache(){
        $bootstrap = Bootstrap::getBootStrap();        
        $cacheManager = $bootstrap->getPluginResource('cachemanager')->getCacheManager();        
        $cache = $cacheManager->getCache('main');        
        return $cache;
    }

    
}
