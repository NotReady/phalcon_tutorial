<?php
use Phalcon\Mvc\Application;

// default some absolute path constants
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// Iniを登録する
$config = include APP_PATH . '/config/config.php';

include APP_PATH . '/config/loader.php';

include APP_PATH . '/config/services.php';

$application = new Application($di);


try{
    // handle the request
    $response = $application->handle();
    $response->send();
}catch (\Exception $e){
    echo 'Exception: ', $e->getMessage();
}

