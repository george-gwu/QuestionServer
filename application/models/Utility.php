<?php

/**
 * Utility Class
 * 
 */
class Application_Model_Utility {
  
    public static function convertArrayToJavaLinkedList($array, $joinName){       
        $node=null;$root=null;
        
        do {
            $element = array_shift($array);
            
            if(is_null($node)){
                $root = (object)$element;
                $node = $root;
            } else {
                $node->$joinName = (object)$element;
                $node = $node->$joinName;
            }            
            
        } while(count($array)>0);
       
        return $root;
    }
    
}
