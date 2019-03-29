<?php

use Phalcon\Loader;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Application;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\Url as UrlProvider;

// default some absolute path constants
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/app');

// Register an autoloader
$loader = new Loader();

$loader->registerDirs(
    [
        APP_PATH . '/controllers/',
        APP_PATH . '/models/',
    ]
);

$loader->register();

// Create DI
$di = new FactoryDefault();

// setup the view component
$di->set(
    'view',
    function(){
        $view = new View();
        $view->setViewsDir(APP_PATH. '/views/');
        return $view;
    }
);

// setup a base URI
$di->set(
    'url',
    function(){
        $url = new UrlProvider();
        $url->setBaseUri('/');
        return $url;
    }
);

$application = new Application($di);

try{
    // handle the request
    $response = $application->handle();
    $response->send();
}catch (\Exception $e){
    echo 'Exception: ', $e->getMessage();
}

