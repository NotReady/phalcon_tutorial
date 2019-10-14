<?php
use Phalcon\Mvc\Controller;

class ReportController extends Controller
{
    /**
     * 勤怠リスト要求アクション
     */
    public function indexAction(){

        // routeで展開されたパラメタはdispatcherが握っている
        $year = $this->dispatcher->getParam('year');
        $month = $this->dispatcher->getParam('month');
        $employee_id = $this->dispatcher->getParam('employee_id');

        $report = Reports::getReportWithDayAll($employee_id, $year, $month);
        $this->view->reports = $report;

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

    }

    /**
     * 勤怠更新アクション
     */
    public function saveAction(){
    }
}