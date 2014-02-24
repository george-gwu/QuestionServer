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
    
    $calculator = new Application_Model_Calculator();

    $server = new Zend_Json_Server();
    $server->setClass("Application_Model_Calculator");

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
    }
    
    $server->setAutoEmitResponse(false);

    $rawJSON = $server->handle();

    if(APPLICATION_ENV=='development'){
        echo  Zend_Json::prettyPrint($rawJSON);
    } else {
        echo $rawJSON;
    }