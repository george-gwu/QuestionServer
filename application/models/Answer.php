<?php

/**
 * Answer Model
 * 
 */
class Application_Model_Answer extends Zend_Db_Table_Abstract {
    protected $_name   = 'answers';
    protected $_primary = 'ID';              
    
    /**
     * Create an answer
     * @param int $questionID
     * @param String $text
     * @return ID
     */
    public function createAnswer($questionID, $text){
         
        $data = array(
            'questionID' => $questionID,
            'text' => $text
        );
 
        return $this->insert($data);
    }
    
    
    /**
     * Fetch all Answers for a question
     * @param type $questionID
     * @return Question
     */
    public function getAnswers($questionID){
        
        $select  = $this->select()->where('ID = ?', $questionID);
 
        return $this->fetchAll($select);
           
    }
    

    
}
