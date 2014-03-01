<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    
        
    protected static $_bs;
    
    /**
     * 
     * @param Bootstrap $bs
     */
    public static function setBootstrap($bs){
        self::$_bs = $bs;
    }
    
    /**
     * 
     * @return Bootstrap
     */
    public static function getBootStrap(){
        return self::$_bs;
    }

    
    
}

