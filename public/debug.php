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
    
    print "<html><title>Debug Interface</title><body>";
    print "<table border=1>";
    print "<tr class='head'><td class='time'><center>Time</center></td><td class='comp'>ComputerID</td><td class='req'><center>Request</center></td><td class='resp'><center>Response</center></td></tr>";
    
    foreach($queries as $query){
        print '<tr><td class="time">'  . $query['ts'] . '</td><td class="comp"><center>' 
            . substr(md5($query['ip'] . $query['useragent']),0,6) .'</center></td><td valign=top class="req"><pre>' 
            . Zend_Json::prettyPrint($query['command']) . '</pre></td><td valign=top class="resp"><pre>'  
            . Zend_Json::prettyPrint($query['response']) . '</pre></td></tr>';
    }
    
    print '</table>';
    ?>
<style>
table {
  table-layout: fixed;
  border-collapse: collapse;
  width: 100%;
}

tr.head {
    text-align: center;
}

td.req {
  width: 300px;
  word-wrap:break-word;
}

td.resp {
       word-wrap:break-word;
       width:auto;
}

td.time, td.comp{
    width:100px;
}

td {
  width: auto;
  
}
</style>
    