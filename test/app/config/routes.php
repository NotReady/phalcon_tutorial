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

$router->add('/employees',[
    'controller' => "Employee",
    'action'=>'index',
]);

$router->add('/employees/edit/{employee_id}',[
    'controller' => "Employee",
    'action'=>'edit',
]);

$router->add('/employees/edit/check',[
    'controller' => "Employee",
    'action'=>'editCheck',
]);


$router->handle();