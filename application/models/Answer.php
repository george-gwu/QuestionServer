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
     * @param int $isValid
     * @return ID
     */
    public function createAnswer($questionID, $text, $isValid){
         
        $data = array(
            'questionID' => (int)$questionID,
            'text' => $text,
            'isValid'   => (int)$isValid
        );
 
        return $this->insert($data);
    }
    
    
    /**
     * Fetch all Answers for a question
     * @param type $questionID
     * @return Question
     */
    public function getAnswers($questionID){
        
        $select  = $this->select()->where('questionID = ?', (int)$questionID)->order('ID ASC');
 
        return $this->fetchAll($select);
           
    }
    
    public function deleteAnswers($questionID){
        $where = $this->getAdapter()->quoteInto('questionID = ?', (int)$questionID);
 
        return $this->delete($where);
        
    }
    

    
}
