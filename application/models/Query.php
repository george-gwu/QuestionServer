<?php

/**
 * Query Model
 * 
 */

class Application_Model_Query extends Zend_Db_Table_Abstract {
    protected $_name   = 'query';
    protected $_primary = 'ID';              
   
    /**
     * Submit Query Log Entry
     * @param type $command
     * @param type $ip
     * @param type $useragent
     * @param type $response
     * @return type
     */
    public function submitEntry($command, $ip, $useragent, $response){
         
        $data = array(
            'command'   => $command,
            'ip'        => $ip,
            'useragent' => $useragent,
            'ts'        => new Zend_Db_Expr('CURRENT_TIMESTAMP'),
            'response'  => $response
        );
 
        return $this->insert($data);
    }
    
    public function getLast($num=10){
         $select  = $this->select()->order('ID DESC')->limit($num);
 
        return $this->fetchAll($select);
    }
            
}
