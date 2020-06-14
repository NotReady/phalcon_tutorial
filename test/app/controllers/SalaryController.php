<?php
use Phalcon\Mvc\Controller;

class SalaryController extends Controller
{
    /**
     * 給与閲覧アクション
     */
    public function viewSalaryAction(){
        $year = $this->dispatcher->getParam('year');
        $month = $this->dispatcher->getParam('month');
        $employee_id = $this->dispatcher->getParam('employee_id');

        // 給与モデルを取得します
        $salary = Salaries::getSalaryByEmployeeAndDate($employee_id, $year, $month);

        // 確定前は編集画面にリダイレクトします
        if( $salary->fixed !== 'fixed' ){
            return $this->response->redirect("salary/${employee_id}/${year}/${month}/edit");
        }

        // 社員マスタ
        $employee = null;
        $employee = Employees::findfirst($employee_id);

        // 就業トランザクション
        $reportService = new ReportService($employee_id, $year, $month);
        $this->view->reports = $reportService->getMonthlyReport();
        $this->view->days_worked = $reportService->howDaysWorked();
        $this->view->howDaysWorkedOfDay = $reportService->howDaysWorkedOfDay();
        $this->view->summary = $reportService->getSummaryBySiteWorkUnit();

        // 給与モデルを取得します
        $salary = Salaries::getSalaryByEmployeeAndDate($employee_id, $year, $month);

        $this->view->employee = $employee;
        $this->view->salary = $salary;
        $this->view->total_salary = $salary->getSalary();
        $this->view->thismonth = $month ;
        $this->view->thisyear = $year ;
        $this->view->activeLoan = Loans::getAmount($employee->id);
    }

    /**
     * 給与編集アクション
     */
    public function editSalaryAction(){

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
        $this->view->howDaysWorkedOfDay = $reportService->howDaysWorkedOfDay();
        $this->view->summary = $summary = $reportService->getSummaryBySiteWorkUnit();

        // 給与モデルを取得します
        $salary_origin = Salaries::getSalaryByEmployeeAndDate($employee_id, $year, $month);
        $salary_temporary = clone $salary_origin;

        // 未確定段階ではブランク属性をマスタで補完します
        if( $salary_temporary->fixed === 'temporary' ){

            // アルバイトに時間給をセット
            if( $employee->employee_type !== 'pro' ){
                $salary_temporary->base_charge = $summary['chargeAll'];
            }

            // マスタから補完します
            SalaryHelper::complementTempolarySalary($salary_temporary, $employee);
        }

        $form = new SalaryForm($salary_temporary);

        $this->view->employee = $employee;
        $this->view->salary = $salary_temporary;
        $this->view->salary_origin = $salary_origin;
        $this->view->total_salary = $salary_temporary->getSalary();
        $this->view->form = $form;
        $this->view->thismonth = $month ;
        $this->view->thisyear = $year ;
        $this->view->activeLoan = Loans::getAmount($employee->id) - $salary_temporary->loan_bill;
    }

    /**
     * 給与確定アクション
     */
    public function fixSalaryAction()
    {
        // routeで展開されたパラメタはdispatcherが握っている

        try{
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
            $salary = Salaries::getSalaryByEmployeeAndDate($employee_id, $year, $month);

            // 確定済み
            if( $salary->fixed === 'fixed' ){
                throw new Exception();
            }

            // アルバイトに時間給をセット
            if( $employee->employee_type !== 'pro' ){
                $salary->base_charge = $summary['chargeAll'];
            }

            // ブランク属性をマスタから補完して給与を確定します
            SalaryHelper::updateWithComplement($salary, $employee, true);

            // 閲覧画面にリダイレクトします
            $this->response->redirect("/salary/${employee_id}/${year}/${month}");

        }catch (Exception $e){
            return $this->response->redirect($_SERVER['HTTP_REFERER']);
        }
    }

    /**
     * 給与確定キャンセルアクション
     */
    public function cancelSalaryAction()
    {
        // routeで展開されたパラメタはdispatcherが握っている

        try{
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
            $this->view->summary = $reportService->getSummaryBySiteWorkUnit();

            // 給与モデルを取得します
            $salary = Salaries::getSalaryByEmployeeAndDate($employee_id, $year, $month);

            // 確定前
            if( $salary->fixed === 'temporary' ){
                throw new Exception();
            }

            // ブランク属性をマスタから補完して給与を確定します
            SalaryHelper::cancel($salary, $employee);

            // 閲覧画面にリダイレクトします
            $this->response->redirect("/salary/${employee_id}/${year}/${month}/edit");

        }catch (Exception $e){
            return $this->response->redirect($_SERVER['HTTP_REFERER']);
        }
    }

}