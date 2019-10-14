<?php
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;


// Create DI
$di = new FactoryDefault();

// setup the view component
$di->set(
    'view',
    function(){
        $view = new View();
        $view->setViewsDir(APP_PATH. '/views/');
        $view->registerEngines(
            array(
                ".phtml" => 'Phalcon\Mvc\View\Engine\Volt',
            )
        );

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

// db
$di->set(
    'db',
    function(){
        return new DbAdapter(
            [
                'host' => $_ENV['DATABASE_HOST'],
                'username' => $_ENV['MYSQL_USER'],
                'password' => $_ENV['MYSQL_PASSWORD'],
                'dbname' => $_ENV['MYSQL_DATABASE'],
            ]
        );
    }
);

// router
$di->set(
    'router', function(){
    require APP_PATH . '/config/routes.php';
    return $router;
});

