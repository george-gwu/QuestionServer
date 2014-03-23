<?php

/**
 * Scores Model
 * 
 */

class Application_Model_Query extends Zend_Db_Table_Abstract {
    protected $_name   = 'query';
    protected $_primary = 'ID';              
    
    /**
     * CREATE TABLE  `question`.`query` (
  `ID` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `command` text NOT NULL,
  `ts` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `ip` varchar(16) NOT NULL,
  `useragent` varchar(255) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8
     */
    
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
            
}
