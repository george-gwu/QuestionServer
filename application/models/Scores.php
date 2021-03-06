<?php

/**
 * Scores Model
 * 
 */

class Application_Model_Scores extends Zend_Db_Table_Abstract {
    protected $_name   = 'scores';
    protected $_primary = 'ID';              
    
    /**
     * Submit Score
     * @param string $user
     * @param int $examID
     * @param float $score
     * @return ID
     */
    public function submitScore($user,$examID,$score){
         
        $data = array(
            'user'      => $user,
            'examID'    => (int)$examID,
            'score'     =>  (float)$score
        );
 
        return $this->insert($data);
    }
    
    /**
     * 
     * @param String $user
     * @param int $examID
     * @return boolean
     */
    public function checkForScore($user,$examID){
        $select  = $this->select()
                            ->from($this->_name, new Zend_Db_Expr('COUNT(*)'))
                            ->where('LOWER(user) = ?', strtolower($user))
                            ->where('examID = ?', $examID);
        
        $count = $this->getAdapter()->fetchOne($select);                
        
        return ($count==1);
    }
    
    /**
     * 
     * @param String $user
     * @param int $examID
     * @return Scores Array
     */
    public function searchScores($user=null, $examID=null){
        $select  = $this->select()->from(array('s' => 'scores'),new Zend_Db_Expr('`s`.*'));        
        $select->setIntegrityCheck(false);
        $select->join(array('e' => 'exam'), 's.examID = e.ID', array('e.title')); 
        
        if(!empty($user)){
            $select->where('LOWER(s.user) = ?', strtolower($user));        
        }
        
        
        if(!is_null($examID)){
            $select->where('s.examID = ?', (int)$examID);
        }
        
        return $this->fetchAll($select);
       
    }
    
        
}
