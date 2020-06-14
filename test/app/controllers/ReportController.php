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
        $this->view->reports = $reportService->getMonthlyReport();
        $this->view->days_worked = $reportService->howDaysWorked();
        $this->view->howDaysWorkedOfDay = $reportService->howDaysWorkedOfDay();
        $this->view->summary = $reportService->getSummaryBySiteWorkUnit();

        $employee = Employees::findfirst($employee_id);
        $this->view->employee = $employee;
        $this->view->thismonth = $month ;
        $this->view->thisyear = $year ;

        $sites = Sites::find();
        $siteinfo = [''=>''];
        foreach ($sites as $site) {
            $siteinfo += [$site->id=>$site->sitename];
        }
        $this->view->sites = $siteinfo;

        $wtypes = Worktypes::find();
        $wtypeinfo = [''=>''];
        foreach ($wtypes as $wtype) {
            $wtypeinfo += [$wtype->id=>$wtype->name];
        }
        $this->view->wtypes = $wtypeinfo;

        $currentYmd = "${year}/${month}/1";
        $this->view->previousUrl = "/report/${employee_id}/" . date('Y', strtotime( $currentYmd.' -1 month')) .
            '/' . date('m', strtotime( $currentYmd.' -1 month')) . '/edit';
        $this->view->nextUrl = "/report/${employee_id}/" . date('Y', strtotime( $currentYmd.' +1 month')) .
            '/' . date('m', strtotime( $currentYmd.' +1 month')) . '/edit';
    }

    /**
     * 勤怠更新アクション
     */
    public function saveAction(){

        $params = $this->request->getPost();
        $report = new Reports();
        $report->employee_id = $params['nm_employee_id'];
        $report->at_day = $params['nm_date'];
        $report->site_id = $params['nm_site_id'];
        $report->worktype_id = $params['nm_wtype_id'];
        $report->time_from = $params['nm_timefrom'];
        $report->time_to = $params['nm_timeto'];
        $report->breaktime = $params['nm_breaktime'];

        if( $report->save() === false ){
            header("HTTP/1.1 503 Service Unavailable");
        }

        header("Content-type: text/plain; charset=UTF-8");
    }
}