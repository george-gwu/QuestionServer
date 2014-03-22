<?php

/**
 * Exam Model
 * 
 */
class Application_Model_Exam extends Zend_Db_Table_Abstract {
    protected $_name   = 'exam';
    protected $_primary = 'ID';
    
    public static $UNPUBLISHED = 0;
    public static $ONGOING = 1;
    public static $TERMINATED = 2;
    
    /** 
     * Create an Exam
     * @param String $title
     * @param String $owner
     * @param int $status
     * @param String $password
     * @param int $timeLimit in Minutes
     * @return ExamID
     */
    public function createExam($title, $owner, $status=0, $password=null, $timeLimit=null){
         
        $data = array(
            'title' => $title,
            'owner' => $owner,
            'status' => $status,
            'password' => $password,
            'timeLimit' => $timeLimit
        );
 
        return $this->insert($data);
    }
    
    /**
     * Update an Exam
     * @param type $examID
     * @param type $title
     * @param type $status
     * @param type $password
     * @param type $timeLimit
     * @return type
     */
    public function updateExam($examID, $title=null, $status=null, $password=null, $timeLimit=null){
         
        $data = array();
        
        if($title!=null){ $data['title']=$title; }
        if($status!=null){ $data['status']=$status; }
        if($password!=null){ $data['password']=$password; }
        if($timeLimit!=null){ $data['timeLimit']=$timeLimit; }        
           
        $where = $this->getAdapter()->quoteInto('ID = ?', (int)$examID);
        
        return $this->update($data, $where);
 
    }
    
    public function updateExamStatus($examID,$status){
        $data = array();
        $data['status']=$status;
        
        $where = $this->getAdapter()->quoteInto('ID = ?', (int)$examID);
        
        return $this->update($data, $where);
    }

    
    /**
     * Fetch an Exam
     * @param type $examID
     * @return type
     */
    public function getExam($examID){
        
        $select  = $this->select()->where('ID = ?', (int)$examID);
 
        return $this->fetchRow($select);
           
    }
    
    
    public function searchExams($query, $userName=null, $status=array()){
        $select  = $this->select();
        
        if(!empty($query)){
            $select->where('LOWER(title) LIKE ?', strtolower("%{$query}%"));
        }
        
        if(!is_null($userName) and !empty($userName)){
            $select->where('LOWER(owner) = ?', strtolower($userName));
        }
       
        if(count($status)>0){
            $select->where('status IN(?)', $status);
        }
        
        return $this->fetchAll($select);
    }
    
    
    public function deleteExam($examID){
        
        $questionDb = new Application_Model_Question();
        $answerDb = new Application_Model_Answer();
        
        $questions = $questionDb->getQuestionsForExam($examID)->toArray();
        
        foreach($questions as $question){
            $answerDb->deleteAnswers($question['ID']);
        }
        
        $questionDb->deleteQuestionsForExam($examID);
                
        $where = $this->getAdapter()->quoteInto('ID = ?', (int)$examID);
        
        return $this->delete($where);
        
    }
    

    
}
