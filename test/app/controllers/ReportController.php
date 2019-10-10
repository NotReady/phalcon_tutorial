<?php
use Phalcon\Mvc\Controller;

class ReportController extends Controller
{
    public function indexAction(){

        echo "ReportController::indexAction entried";

        $year = $this->dispatcher->getParam('year');
        $month = $this->dispatcher->getParam('month');
        $employee_id = $this->dispatcher->getParam('employee_id');

        $report = Reports::getReport($employee_id, $year, $month);
        $this->view->reports = $report;

        $employee = Employees::findfirst($employee_id);
        $this->view->employee = $employee;//"{$employee->first_name} {$employee->last_name}" ;
        $this->view->thismonth = $month ;
        $this->view->thisyear = $year ;

        $sites = Sites::find();
        $siteinfo = [""=>""];
        foreach ($sites as $site) {
            $siteinfo += [$site->id=>$site->sitename];
        }
        $this->view->sites = $siteinfo;

        $wtypes = Worktypes::find();
        $wtypeinfo = [""=>""];
        foreach ($wtypes as $wtype) {
            $wtypeinfo += [$wtype->id=>$wtype->name];
        }
        $this->view->wtypes = $wtypeinfo;

        echo "ReportController::indexAction completed";
    }

    public function saveAction(){
        echo "ReportController::saveAction started";
        foreach ($_REQUEST['report'] as $asEmployeeId => $asEmployee){
            foreach ($asEmployee as $asDate => $rowReport){
                    echo '-----------------------------------------------' . '<br>';
                    echo var_dump($rowReport) . '<br>';
                    echo '-----------------------------------------------' . '<br>';
                //Reports::updateOneReport($asEmployeeId, $asDate, $rowReport);
            }
        }
        echo "ReportController::saveAction completed";
    }
}