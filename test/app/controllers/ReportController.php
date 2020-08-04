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

        // 勤務表をフォームにバインドする
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

        // 勤務表
        $this->view->reports = $formarr;
        $this->view->days_worked = $reportService->howDaysWorked(); // 出勤日数
        $this->view->days_Absenteeism = $reportService->howDaysAbsenteeism(); // 欠勤日数
        $this->view->days_holiday = $reportService->howDaysHoliday();// 有給日数
        $this->view->days_business = $reportService->getBusinessDayOfMonth(); // 営業日数
        $this->view->summary = $reportService->getSummaryBySiteWorkUnit();

        // 社員
        $employee = Employees::findfirst($employee_id);
        $this->view->employee = $employee;
        $this->view->thismonth = $month ;
        $this->view->thisyear = $year ;

        // 前後の月へのアンカー
        $currentYmd = "${year}/${month}/1";
        $this->view->previousUrl = "/report/${employee_id}/" . date('Y', strtotime( $currentYmd.' -1 month')) .
            '/' . date('m', strtotime( $currentYmd.' -1 month')) . '/edit';
        $this->view->nextUrl = "/report/${employee_id}/" . date('Y', strtotime( $currentYmd.' +1 month')) .
            '/' . date('m', strtotime( $currentYmd.' +1 month')) . '/edit';

        // 祝日リスト
        $daysInThisMonth = date('t', strtotime("${year}-${month}-01"));
        $this->view->holidays = GoogleCalenderAPIClient::getHoliday("${year}-${month}-01", "${year}-${month}-${daysInThisMonth}");

        // 給与明細
        $salary = Salaries::getSalaryByEmployeeAndDate($employee_id, $year, $month);
        $this->view->salary = $salary;
    }
}