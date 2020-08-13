<?php
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Session\Adapter\Files as Session;
use Phalcon\Mvc\View\Engine\Volt;

// Create DI
$di = new FactoryDefault();

// setup a base URI
$di->setShared(
    'url',
    function(){
        $url = new UrlProvider();
        $url->setBaseUri('/');
        return $url;
    }
);

// Register Volt as a service
$di->setShared(
    'voltService',
    function ($view, $di) use ( $config ){

        /* create instance */
        $volt = new Volt($view, $di);

        /* set options */
        $volt->setOptions(
            [
                // absolute path
                'compiledPath'      => BASE_PATH . $config->application->cacheDir,
                'compiledExtension' => '.compiled',
                'compiledSeparator' => '_',
                'stat' => true,
                'compileAlways' => true
            ]
        );

        /* set filter */
        $volt->getCompiler()->addFilter('strtotime', 'strtotime');
        $volt->getCompiler()->addFilter('number_format', 'number_format');
        $volt->getCompiler()->addFilter('is_null', 'is_null');

        return $volt;
    }
);

// setup the view component
$di->setShared('view', function() use ($config) {

    $view = new View();
    $view->setViewsDir('../app/views');

    $registerOptions = [
        // voltエンジン
        '.volt' => 'voltService',
        '.phtml' => 'Phalcon\Mvc\View\Engine\Php'
    ];

    // 登録
    $view->registerEngines($registerOptions);
    return $view;
});

// db
$di->setShared(
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
$di->setShared(
    'router', function(){
    require APP_PATH . '/config/routes.php';
    return $router;
});

$di->setShared(
    'session',
    function(){
        $session = new Session();
        $session->start();
        return $session;
    }
);

