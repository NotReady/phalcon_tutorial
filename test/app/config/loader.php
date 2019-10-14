<?php
use Phalcon\Loader;

// Register an autoloader
$loader = new Loader();

$loader->registerDirs(
    [
        BASE_PATH . $config->application->controllersDir,
        BASE_PATH . $config->application->modelsDir,
        BASE_PATH . $config->application->formsDir,
        //APP_PATH . '/controllers/',
        //APP_PATH . '/models/',
    ]
);

$loader->register();