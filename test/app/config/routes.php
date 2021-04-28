<?php

use Phalcon\Mvc\Router;

$router = new Router();
$router->removeExtraSlashes(true);

// インデックス
$router->add('/',                               ['controller' => "root", 'action'=>'index',]);

// ログイン
$router->add('/login',                          ['controller' => "login", 'action'=>'index',]);
$router->add('/login/check',                    ['controller' => "login", 'action'=>'loginCheck',]);

// 勤務表一覧
$router->add('/report/{year:[0-9]{4}}/{month:[0-9]{2}}', ['controller' => "report", 'action'=>'index']);
// 勤務表編集
$router->add('/report/{employee_id}/{year:[0-9]{4}}/{month:[0-9]{2}}/edit', ['controller' => "report", 'action'=>'editReport']);
// 勤務表保存
$router->add('/report/update',                  ['controller' => "api", 'action'=>'saveReport']);
// 勤務表削除
$router->add('/report/delete',                  ['controller' => "api", 'action'=>'deleteReport']);
// 作業分類の取得
$router->add('/report/list/worktype',           ['controller' => "api", 'action'=>'getWorkTypeList']);

// 給与閲覧
$router->add('/salary/{employee_id}/{year:[0-9]{4}}/{month:[0-9]{2}}', ['controller' => "salary", 'action'=>'viewSalary']);
// 給与明細
$router->add('/salary/{employee_id}/{year:[0-9]{4}}/{month:[0-9]{2}}/report', ['controller' => "salary", 'action'=>'reportSalary']);
// 給与編集
$router->add('/salary/{employee_id}/{year:[0-9]{4}}/{month:[0-9]{2}}/edit',        ['controller' => "salary", 'action'=>'editSalary']);
// 給与確定
$router->add('/salary/{employee_id}/{year:[0-9]{4}}/{month:[0-9]{2}}/fix', ['controller' => "salary", 'action'=>'fixSalary']);
// 給与確定取消
$router->add('/salary/{employee_id}/{year:[0-9]{4}}/{month:[0-9]{2}}/cancel', ['controller' => "salary", 'action'=>'cancelSalary']);
// 給与保存
$router->add('/salary/{employee_id}/{year:[0-9]{4}}/{month:[0-9]{2}}/update', ['controller' => "api", 'action'=>'updateSalary']);
// 給与Undo
$router->add('/salary/{employee_id}/{year:[0-9]{4}}/{month:[0-9]{2}}/undo', ['controller' => "api", 'action'=>'undoSalary']);

// 従業員
$router->add('/employees',                      ['controller' => "employee", 'action'=>'index']);
$router->add('/employees/create',               ['controller' => "api",      'action'=>'createEmployee']);
$router->add('/employees/edit/{employee_id}',   ['controller' => "employee", 'action'=>'edit']);
$router->add('/employees/edit/check',           ['controller' => "employee", 'action'=>'editCheck']);
$router->add('/employees/loan/create',          ['controller' => "api",      'action'=>'createLoan']);
$router->add('/employees/loan/update',          ['controller' => "api",      'action'=>'updateLoan']);
$router->add('/employees/loan/delete',          ['controller' => "api",      'action'=>'deleteLoan']);
$router->add('/employees/loan/get/member',      ['controller' => "api",      'action'=>'getLoanWithMember']);
$router->add('/employees/loan/get/id',          ['controller' => "api",      'action'=>'getLoanWithId']);
$router->add('/employees/holiday/get/member',   ['controller' => "api",      'action'=>'getPaidHolidayOfEmployee']);
$router->add('/employees/holiday/get/id',       ['controller' => "api",      'action'=>'getPaidHolidayOfUnit']);
$router->add('/employees/holiday/create',       ['controller' => "api",      'action'=>'createHoliday']);
$router->add('/employees/holiday/update',       ['controller' => "api",      'action'=>'updateHoliday']);
$router->add('/employees/holiday/delete',       ['controller' => "api",      'action'=>'deleteHoliday']);

// 現場
$router->add('/sites',                          ['controller' => "site",     'action'=>'index']);
$router->add('/sites/edit/{site_id}',           ['controller' => "site",     'action'=>'edit']);
$router->add('/sites/edit/check',               ['controller' => "site",     'action'=>'editCheck']);
$router->add('/sites/create',                   ['controller' => "api",      'action'=>'createSite']);
$router->add('/sites/associate',                ['controller' => "api",      'action'=>'associateWork']);

// 顧客
$router->add('/customers',                      ['controller' => "customer", 'action'=>'index']);
$router->add('/customers/update',               ['controller' => "api",      'action'=>'updateCustomer']);

// 時給と請求単価
$router->add('/hourlycharges/get',              ['controller' => "api",      'action'=>'getHourlyCharge']);
$router->add('/hourlycharges/update',           ['controller' => "api",      'action'=>'updateHourlyCharge']);
$router->add('/hourlycharges/delete',           ['controller' => "api",      'action'=>'deleteHourlyCharge']);
$router->add('/hourlybill/update',              ['controller' => "api",      'action'=>'updateHourlyBill']);
$router->add('/hourlybill/delete',              ['controller' => "api",      'action'=>'deleteHourlyBill']);

$router->handle();