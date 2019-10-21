<?php
/**
 * Created by PhpStorm.
 * User: notready
 * Date: 2019-10-21
 * Time: 23:57
 */

class ReportService
{
    private $_employee_id;
    private $_year;
    private $_month;
    private $_reports;

    /**
     * ReportService constructor.
     * @param $employee_id
     * @param $year
     * @param $month
     */
    public function __construct($employee_id, $year, $month){
        $this->_employee_id = $employee_id;
        $this->_year = $year;
        $this->_month = $month;
        $this->_reports = Reports::getReport($employee_id, $year, $month);
    }

    /**
     * 月間の作業日数を取得します
     * @return int
     */
    public function howDaysWorked(){ return count($this->_reports);}

    /**
     * 月間の勤務表を取得します
     * @return array
     */
    public function getMonthlyReport(){

        $monthlyReport = [];

        // ブランクのカレンダーリストを作成します
        $lastDay = date('t', mktime(0, 0, 0, $this->_month, 1, $this->_year));
        for($day=1; $day<=$lastDay; $day++){
            $monthlyReport[sprintf("%02d-%02d", $this->_month, $day)] = '';
        }

        // 記録のある出勤簿を上書きします
        foreach ( $this->_reports as $report) {
            $monthlyReport[date('m-d', strtotime($report->at_day))] = $report;
        }

        return $monthlyReport;
    }

    public function getSummaryTotal(){
    }

    /**
     * 月間勤務表の時間集計結果を取得します
     * @return array
     */
    public function getSummary(){
        $report = new Reports();
        $summary = $report->getSummaryReport($this->_employee_id, $this->_year, $this->_month);
        $viewable = [];

        $inTimeCalc = new TimeUtil();
        $outTimeCalc = new TimeUtil();

        foreach($summary as $row){
            $hms = explode(':', $row->in_time);
            $row->in_time = "${hms[0]}:${hms[1]}";
            $inTimeCalc->addTimeStr($row->in_time);

            $hms = explode(':', $row->out_time);
            $row->out_time = "${hms[0]}:${hms[1]}";
            $outTimeCalc->addTimeStr($row->out_time);
            array_push($viewable, $row);
        }

        return [
            'site' => $viewable,
            'inTimeAll' => $inTimeCalc->getTimeStr(),
            'outTimeAll' => $outTimeCalc->getTimeStr(),
        ];
//        return $viewable;
    }
}