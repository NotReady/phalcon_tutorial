<?php
use Phalcon\Mvc\Controller;

class EmployeeController extends Controller
{
    /**
     * 従業員一覧アクション
     */
    public function indexAction(){

        // 従業員一覧
        $employees = new Employees();
        $resultset = $employees->getEmployeesWithLatestInput();

        // viewパラメタ
        $this->view->employee_info = $resultset;
    }
}