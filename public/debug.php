<?php


    // Define path to application directory
    defined('APPLICATION_PATH')
        || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

    // Define application environment
    defined('APPLICATION_ENV')
        || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'production'));

    // Ensure library/ is on include_path
    set_include_path(implode(PATH_SEPARATOR, array(
        realpath(APPLICATION_PATH . '/../library'),
        realpath('.'),
    )));

    /** Zend_Application */
    require_once 'Zend/Application.php';

    // Create application, bootstrap, and run
    $application = new Zend_Application(
        APPLICATION_ENV,
        APPLICATION_PATH . '/configs/application.ini'
    );
    $application->bootstrap();       
    Bootstrap::setBootstrap($application->getBootstrap());
    
    $db = $application->getBootstrap()->getResource('db');
    
    
    $queryDb = new Application_Model_Query();
    
    $queries = $queryDb->getLast(50)->toArray();
    
    print "<html><title>Debug Interface</title><body><table border=1>";
    print "<tr><td>Time</td><td>ComputerID</td><td>Request</td><td>Response</td></tr>";
    
    foreach($queries as $query){
        print '<tr><td>'  . $query['ts'] . '</td><td>'. substr(md5($query['ip'] . $query['useragent']),0,6) .'</td><td><pre>' 
            . Zend_Json::prettyPrint($query['command']) . '</pre></td><td><pre>'  . Zend_Json::prettyPrint($query['response']) . '</pre></td></tr>';
    }
    //echo  Zend_Json::prettyPrint($smd);
    
    print '</table>';
    
    