<?php
use Phalcon\Mvc\Controller;

class ReportController extends Controller
{
    /**
     * 勤務表一覧アクション
     */
    public function indexAction()
    {
        // routeで展開されたパラメタはdispatcherが握っている
        $year = $this->dispatcher->getParam('year');
        $month = $this->dispatcher->getParam('month');

        // 従業員一覧
        $employees = Employees::getEmployeesReportList($year, $month);

        // viewパラメタ
        $this->view->year = $year;
        $this->view->month = $month;
        $this->view->employees = $employees;

        $currentYmd = "${year}/${month}/1";
        $this->view->previousUrl = '/report/' . date('Y', strtotime( $currentYmd.' -1 month')) . '/' . date('m', strtotime( $currentYmd.' -1 month'));
        $this->view->nextUrl = '/report/' . date('Y', strtotime( $currentYmd.' +1 month')) . '/' . date('m', strtotime( $currentYmd.' +1 month'));
    }

    /**
     * 勤怠リスト要求アクション
     */
    public function editReportAction(){

        // routeで展開されたパラメタはdispatcherが握っている
        $year = $this->dispatcher->getParam('year');
        $month = $this->dispatcher->getParam('month');
        $employee_id = $this->dispatcher->getParam('employee_id');

        $reportService = new ReportService($employee_id, $year, $month);

        $arr = $reportService->getMonthlyReport();
        $formarr = [];
        foreach ($arr as $key => $item) {
            if( empty($item) === false  ){
                $form = new ReportForm($item);
                $formarr[$key] = $form;
            }else{
                $entity = new Reports();
                $entity->at_day = $key;
                $entity->employee_id = $employee_id;
                $formarr[$key] = new ReportForm($entity);
            }
        }

        $this->view->reports = $formarr;
        $this->view->days_worked = $reportService->howDaysWorked();
        $this->view->howDaysWorkedOfDay = $reportService->howDaysWorkedOfDay();
        $this->view->summary = $reportService->getSummaryBySiteWorkUnit();

        $employee = Employees::findfirst($employee_id);
        $this->view->employee = $employee;
        $this->view->thismonth = $month ;
        $this->view->thisyear = $year ;

        $currentYmd = "${year}/${month}/1";
        $this->view->previousUrl = "/report/${employee_id}/" . date('Y', strtotime( $currentYmd.' -1 month')) .
            '/' . date('m', strtotime( $currentYmd.' -1 month')) . '/edit';
        $this->view->nextUrl = "/report/${employee_id}/" . date('Y', strtotime( $currentYmd.' +1 month')) .
            '/' . date('m', strtotime( $currentYmd.' +1 month')) . '/edit';

        $entity = new Reports();
        $form = new ReportForm();
        $this->view->form = $form;
    }
}