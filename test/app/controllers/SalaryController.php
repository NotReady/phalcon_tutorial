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

        // 就業トランザクション
        $reportService = new ReportService($employee_id, $year, $month);
        $this->view->reports = $reportService->getMonthlyReport();
        $this->view->days_worked = $reportService->howDaysWorked();
        $this->view->summary = $summary = $reportService->getSummaryBySiteWorkUnit();

        // 給与モデルを取得します
        $salary_origin = Salaries::getSalaryByEmployeeAndDate($employee_id, "${year}/${month}/01");
        $salary_temporary = clone $salary_origin;

        // 未確定段階ではブランク属性をマスタで補完します
        if( $salary_temporary->fixed === 'temporary' ){

            // アルバイトに時間給をセット
            if( $employee->employee_type !== 'pro' && isset($salary_temporary->base_charge) === false ){
                $salary_temporary->base_charge = $summary['chargeAll'];
            }

            // マスタから補完します
            SalaryHelper::complementTempolarySalary($salary_temporary, $employee);
        }

        $form = new SalaryForm($salary_temporary);

        $this->view->employee = $employee;
        $this->view->salary = $salary_temporary;
        $this->view->salary_origin = $salary_origin;
        $this->view->form = $form;
        $this->view->thismonth = $month ;
        $this->view->thisyear = $year ;

    }

}