<?php

use Phalcon\Mvc\Router;

$router = new Router();
$router->removeExtraSlashes(true);

// インデックス
$router->add('/',                               ['controller' => "Root", 'action'=>'index',]);

// ログイン
$router->add('/login',                          ['controller' => "Login", 'action'=>'index',]);
$router->add('/login/check',                    ['controller' => "Login", 'action'=>'loginCheck',]);

// 勤務表
$router->add('/report/{employee_id}/{year:[0-9]{4}}/{month:[0-9]{2}}', ['controller' => "Report", 'action'=>'index']);
$router->add('/report/save',                    ['controller' => "Report", 'action'=>'save']);

// 従業員
$router->add('/employees',                      ['controller' => "Employee",  'action'=>'index']);
$router->add('/employees/edit/{employee_id}',   ['controller' => "Employee", 'action'=>'edit']);
$router->add('/employees/edit/check',           ['controller' => "Employee", 'action'=>'editCheck']);
$router->add('/employees/loan/add',             ['controller' => "Api", 'action'=>'addLoan']);
$router->add('/employees/loan/get',             ['controller' => "Api", 'action'=>'getLoan']);

// 現場
$router->add('/sites',                          ['controller' => "Site", 'action'=>'index']);

$router->handle();