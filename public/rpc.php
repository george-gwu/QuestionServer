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
    
    $serverModel = new Application_Model_Server();

    $server = new Zend_Json_Server();
    $server->setClass($serverModel);

    if ('GET' == filter_input(INPUT_SERVER, "REQUEST_METHOD", FILTER_SANITIZE_STRING)){
        // Indicate the URL endpoint, and the JSON-RPC version used:
        $server->setTarget('/rpc.php')
               ->setEnvelope(Zend_Json_Server_Smd::ENV_JSONRPC_2);

        // Grab the SMD
        $smd = $server->getServiceMap();

        // Return the SMD to the client
        header('Content-Type: application/json');
        if(APPLICATION_ENV=='development'){
            echo  Zend_Json::prettyPrint($smd);
        } else {
            echo $smd;
        }
        
        return;
    } else {
    
        $server->setAutoEmitResponse(false);

        $rawJSON = $server->handle();

        $queryDb = new Application_Model_Query();
        $queryDb->submitEntry(
                                $server->getRequest(), 
                                filter_input(INPUT_SERVER, "REMOTE_ADDR", FILTER_SANITIZE_STRING), 
                                filter_input(INPUT_SERVER, "HTTP_USER_AGENT", FILTER_SANITIZE_STRING), 
                                $rawJSON
                );
        
        echo $rawJSON;
    }
            
/*
    if(APPLICATION_ENV=='development'){
        echo  Zend_Json::prettyPrint($rawJSON);
    } else {
        echo $rawJSON;
    }
 * */