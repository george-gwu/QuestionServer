<?php

/**
 * Question Model
 * 
 */
class Application_Model_Question extends Zend_Db_Table_Abstract {
    protected $_name   = 'questions';
    protected $_primary = 'ID';       
    
    /**
     * Create a question, answer must be added later
     * @param int $examID
     * @param String $text
     * @return ID
     */
    public function createQuestion($examID, $text){
         
        $data = array(
            'examID' => $examID,
            'text' => $text
        );
 
        return $this->insert($data);
    }
    
    /**
     * Set a valid answer
     * @param int $questionID
     * @param int $answerID
     * @return true/false
     */
    public function setValidAnswer($questionID, $answerID){
        $data = array('validAnswerID' => $answerID);
        $where = $this->getAdapter()->quoteInto('ID = ?', (int)$questionID);
        
        return $this->update($data, $where);
        
    }
    
    
    /**
     * Fetch an Question
     * @param type $questionID
     * @return Question
     */
    public function getQuestion($questionID){
        
        $select  = $this->select()->where('ID = ?', (int)$questionID);
 
        return $this->fetchRow($select);
           
    }
    
    public function getQuestionsForExam($examID){

        $select  = $this->select()->where('examID = ?', (int)$examID);
 
        return $this->fetchAll($select);
    }
    
    
    public function deleteQuestionsForExam($examID){
        $where = $this->getAdapter()->quoteInto('examID = ?', (int)$examID);
 
        return $this->delete($where);
    }

    
}
