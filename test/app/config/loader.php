<?php
use Phalcon\Loader;

// Register an autoloader
$loader = new Loader();

$loader->registerDirs(
    [
        BASE_PATH . $config->application->controllersDir,
        BASE_PATH . $config->application->modelsDir,
        BASE_PATH . $config->application->formsDir,
        BASE_PATH . $config->application->servicesDir,
        BASE_PATH . $config->application->helpersDir,
    ]
);

$loader->register();