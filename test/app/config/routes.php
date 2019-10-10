<?php

use Phalcon\Mvc\Router;

$router = new Router();
$router->removeExtraSlashes(true);


$router->add('/report/{employee_id}/{year:[0-9]{4}}/{month:[0-9]{2}}',[
    'controller' => "Report",
    'action'=>'index',
]);

$router->add('/report/save',[
    'controller' => "Report",
    'action'=>'save',
]);


$router->handle();