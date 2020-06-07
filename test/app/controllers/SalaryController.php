<?php
use Phalcon\Mvc\Controller;

class SalaryController extends Controller
{
    /**
     * 給与明細リクエスト
     */
    public function indexAction(){

        // routeで展開されたパラメタはdispatcherが握っている
        $year = $this->dispatcher->getParam('year');
        $month = $this->dispatcher->getParam('month');
        $employee_id = $this->dispatcher->getParam('employee_id');

        // 社員マスタ
        $employee = null;
        $employee = Employees::findfirst($employee_id);

        // 給与モデルを取得します
        $sarary = Salaries::getSalaryByEmployeeAndDate($employee_id, "${year}/${month}/01");

        // 未確定段階ではブランク属性をマスタで上書きします
        if( $sarary->fixed === 'temporary' ){
            SalaryHelper::complementTempolarySalary($sarary, $employee);
        }

        $form = new SalaryForm($sarary);

        $this->view->employee = $employee;
        $this->view->salary = $sarary;
        $this->view->form = $form;
        $this->view->thismonth = $month ;
        $this->view->thisyear = $year ;

    }

}