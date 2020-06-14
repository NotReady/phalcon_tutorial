<?php

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
     * 月間の出勤日数を取得します
     * @return int
     */
    public function howDaysWorked(){ return count($this->_reports);}

    /**
     * 曜日別の出勤日数を取得します
     * @return int
     */
    public function howDaysWorkedOfDay(){
        $daysWorked = [
            '平日' => 0,
            '土曜日' => 0,
            '日曜日' => 0
        ];
        foreach ( $this->_reports as $report ){
            $week = date('w', strtotime($report->at_day));
            switch ($week){
                case 0:     $daysWorked['日曜日'] += 1; break; // 日曜日
                case 6:     $daysWorked['土曜日'] += 1; break; // 土曜日
                default:    $daysWorked['平日'] += 1; break;   // 平日
            }
        }
        return $daysWorked;
    }

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
     * 月間勤務表の時間集計を取得します
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
    }

    /**
     * 月間勤務表の時間集計を取得します
     * @return array
     */
    public function getSummaryBySiteWorkUnit(){
        $report = new Reports();
        $summary = $report->getSummaryOfGroupBySiteWorkType($this->_employee_id, $this->_year, $this->_month);
        $viewable = [];

        // 合計出勤時間計算オブジェクト
        $timeAll = new TimeUtil();
        // 平日時間内 / 平日時間外 / 土曜日出勤 / 日曜日出勤　計算オブジェクト
        $timeUnitObjects = [
            '平日時間内' => new TimeUtil(),
            '平日時間外' => new TimeUtil(),
            '土曜日出勤' => new TimeUtil(),
            '日曜日出勤' => new TimeUtil(),
        ];
        $timeUnitsValue = [];
        $chargeAll = 0;

        foreach($summary as $row){
            $hms = explode(':', $row->sum_time);
            $row->sum_time = "${hms[0]}:${hms[1]}";
            $timeAll->addTimeStr($row->sum_time);
            $timeUnitObjects[$row->label]->addTimeStr($row->sum_time);
            $chargeAll += $row->sum_charge;
            array_push($viewable, $row);
        }

        // 平日時間内 / 平日時間外 / 土曜日出勤 / 日曜日出勤　の計算
        foreach ( $timeUnitObjects as $unitName => $obj ){
            $timeUnitsValue[$unitName] = $obj->getTimeStr();
        }

        return [
            'timeunits' => $timeUnitsValue,
            'site' => $viewable,
            'timeAll' => $timeAll->getTimeStr(),
            'chargeAll' => $chargeAll,
        ];
    }

}