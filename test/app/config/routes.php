<?php

use Phalcon\Mvc\Router;

$router = new Router();
$router->removeExtraSlashes(true);

// インデックス
$router->add('/',                               ['controller' => "Root", 'action'=>'index',]);

// ログイン
$router->add('/login',                          ['controller' => "Login", 'action'=>'index',]);
$router->add('/login/check',                    ['controller' => "Login", 'action'=>'loginCheck',]);

// 勤務表一覧
$router->add('/report/{year:[0-9]{4}}/{month:[0-9]{2}}', ['controller' => "Report", 'action'=>'index']);
// 勤務表編集
$router->add('/report/{employee_id}/{year:[0-9]{4}}/{month:[0-9]{2}}/edit', ['controller' => "Report", 'action'=>'editReport']);
// 勤務表保存
$router->add('/report/save',                    ['controller' => "Api", 'action'=>'saveReport']);

// 給与閲覧
$router->add('/salary/{employee_id}/{year:[0-9]{4}}/{month:[0-9]{2}}', ['controller' => "Salary", 'action'=>'viewSalary']);
// 給与編集
$router->add('/salary/{employee_id}/{year:[0-9]{4}}/{month:[0-9]{2}}/edit',        ['controller' => "Salary", 'action'=>'editSalary']);
// 給与確定
$router->add('/salary/{employee_id}/{year:[0-9]{4}}/{month:[0-9]{2}}/fix', ['controller' => "Salary", 'action'=>'fixSalary']);
// 給与確定取消
$router->add('/salary/{employee_id}/{year:[0-9]{4}}/{month:[0-9]{2}}/cancel', ['controller' => "Salary", 'action'=>'cancelSalary']);
// 給与保存
$router->add('/salary/{employee_id}/{year:[0-9]{4}}/{month:[0-9]{2}}/update', ['controller' => "Api", 'action'=>'updateSalary']);
// 給与Undo
$router->add('/salary/{employee_id}/{year:[0-9]{4}}/{month:[0-9]{2}}/undo', ['controller' => "Api", 'action'=>'undoSalary']);

// 従業員
$router->add('/employees',                      ['controller' => "Employee",  'action'=>'index']);
$router->add('/employees/edit/{employee_id}',   ['controller' => "Employee", 'action'=>'edit']);
$router->add('/employees/edit/check',           ['controller' => "Employee", 'action'=>'editCheck']);
$router->add('/employees/loan/create',          ['controller' => "Api", 'action'=>'createLoan']);
$router->add('/employees/loan/update',          ['controller' => "Api", 'action'=>'updateLoan']);
$router->add('/employees/loan/delete',          ['controller' => "Api", 'action'=>'deleteLoan']);
$router->add('/employees/loan/get/member',      ['controller' => "Api", 'action'=>'getLoanWithMember']);
$router->add('/employees/loan/get/id',          ['controller' => "Api", 'action'=>'getLoanWithId']);

// 現場
$router->add('/sites',                          ['controller' => "Site", 'action'=>'index']);

$router->handle();