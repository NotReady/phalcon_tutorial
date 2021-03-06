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
     * 営業稼働日を取得します
     */
    public function getBusinessDayOfMonth(){

        // 月日数
        $daysInThisMonth = date('t', strtotime("{$this->_year}-{$this->_month}"));
        // 営業日数
        $beginWeekOfThisMonth = date('w', strtotime("{$this->_year}-{$this->_month}"));
        // 非営業日とする曜日(土日)
        $daysOfHolidayweek = [0,6];

        $daysOfHolidays = 0;
        foreach ($daysOfHolidayweek as $notBisinessDay){

            // ( 月日数 - 非営業曜日のオフセット ) / 7(週) = 曜日登場数
            // 7進数の2の補数で曜日オフセットを出す

            $offset = ( $notBisinessDay + ( 7 - $beginWeekOfThisMonth ) ) % 7;
            $daysOfHolidays += ceil(( $daysInThisMonth - $offset ) / 7);
        }

        // 祝日
        $holidays = GoogleCalenderAPIClient::getHoliday("{$this->_year}-{$this->_month}-01", "{$this->_year}-{$this->_month}-$daysInThisMonth");
        foreach ($holidays as $day => $caption){
            // 平日を営業日カウントから差し引く
            $w = date('w', strtotime($day));
            if( $w >= 1 && $w <= 5 ) $daysOfHolidays += 1;
        }

        return $daysInThisMonth - $daysOfHolidays;
    }

    /**
     * 月間の出勤した日数を取得します
     * @return int
     */
    public function howDaysWorked(){
        $arrayObjct = $this->_reports->toArray();
        $attendanced = array_filter($arrayObjct, function ($r){
            switch ($r['attendance']){
                case 'attendance': // 出勤
                case 'be_late': // 遅刻
                case 'Leave_early': // 早退
                case 'holidays-half': // 半有給
                    return true;
                default: return false;
            }
        });
        return count($attendanced);
    }

    /**
     * 月間の欠勤した日数を取得します
     * @return int
     */
    public function howDaysAbsenteeism(){
        $arrayObjct = $this->_reports->toArray();
        $attendanced = array_filter($arrayObjct, function ($r){
            return $r['attendance'] === 'absenteeism';
        });
        return count($attendanced);
    }

    /**
     * 月間の遅刻した日数を取得します
     * @return int
     */
    public function getCountOfBeLateDays(){
        $arrayObjct = $this->_reports->toArray();
        $filteredBeLateArray = array_filter($arrayObjct, function ($r){
            return $r['attendance'] === 'be_late';
        });
        return count($filteredBeLateArray);
    }

    /**
     * 月間の早退した日数を取得します
     * @return int
     */
    public function getCountOfLeaveEarlyDays(){
        $arrayObjct = $this->_reports->toArray();
        $filteredLeaveEarlyArray = array_filter($arrayObjct, function ($r){
            return $r['attendance'] === 'Leave_early';
        });
        return count($filteredLeaveEarlyArray);
    }


    /**
     * 月間の有給消化日数を取得します
     * @return int
     */
    public function howDaysHoliday(){
        $arrayObjct = $this->_reports->toArray();
        $holidayCount = 0;
        foreach ($arrayObjct as $report) {
            switch ($report['attendance']){
                case 'holidays':        $holidayCount += 1; break;
                case 'holidays-half':   $holidayCount += 0.5; break;
            }
        }
        return $holidayCount;
    }

    /**
     * 曜日別の出勤日数を取得します
     * @return mixed
     */
    public function howDaysWorkedOfDay(){
        $daysWorked = [
            '平日' => 0,
            '土曜日' => 0,
            '日曜日' => 0,
            '祝祭日' => 0
        ];

        // 出勤曜日を集計
        foreach ( $this->_reports as $report ){

            // 欠勤除外
            if( $report->attendance === 'absenteeism' ) continue;
            // 有給除外
            if( $report->attendance === 'holiday' ) continue;
            if( $report->attendance === 'half-holiday' ) continue;

            // 集計
            switch ($report->weekday){
                case 'weekday' : $daysWorked['平日'] += 1; break;
                case 'saturday': $daysWorked['土曜日'] += 1; break;
                case 'sunday'  : $daysWorked['日曜日'] += 1; break;
                case 'holiday' : $daysWorked['祝祭日'] += 1; break;
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
            $monthlyReport[date('Y-m-d', strtotime("{$this->_year}-{$this->_month}-{$day}"))] = '';
        }

        // 記録のある出勤簿を上書きします
        foreach ( $this->_reports as $report) {
            $monthlyReport[date('Y-m-d', strtotime($report->at_day))] = $report;
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
     * 月間勤務表の集計を取得します
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
            '祝祭日出勤' => new TimeUtil(),
        ];
        $daysWorkedCount = [
            '平日時間内' => 0,
            '平日時間外' => 0,
            '土曜日出勤' => 0,
            '日曜日出勤' => 0,
            '祝祭日出勤' => 0,
        ];

        $timeUnitsValue = [];
        $chargeAll = 0;
        foreach($summary as $row){
            $hms = explode(':', $row->sum_time);
            $row->sum_time = "${hms[0]}:${hms[1]}";
            // 総出勤時間のカウント
            $timeAll->addTimeStr($row->sum_time);
            // 出勤時間のカウント
            $timeUnitObjects[$row->label]->addTimeStr($row->sum_time);
            // 出勤日数のカウント
            $daysWorkedCount[$row->label] += $row->days_worked;
            // 時間給のカウント
            $chargeAll += $row->sum_charge;
            array_push($viewable, $row);
        }

        // 平日時間内 / 平日時間外 / 土曜日出勤 / 日曜日出勤　軸の統計
        foreach ( $timeUnitObjects as $unitName => $obj ){
            $timeUnitsValue[$unitName]['time'] = $obj->getTimeStr();
            $timeUnitsValue[$unitName]['days'] = $daysWorkedCount[$unitName];
        }

        // 時間軸の統計 - 時間内
        $inTimeAll = new TimeUtil();
        foreach (['平日時間内'] as $intime){
            $inTimeAll->addTimeStr($timeUnitObjects[$intime]->getTimeStr());
        }

        // 時間軸の統計 -　時間外
        $outTimeAll = new TimeUtil();
        foreach (['平日時間外', '土曜日出勤', '日曜日出勤', '祝祭日出勤'] as $outtime){
            $outTimeAll->addTimeStr($timeUnitObjects[$outtime]->getTimeStr());
        }

        return [
            'timeunits' => $timeUnitsValue,
            'site' => $viewable,
            'timeAll' => $timeAll->getTimeStr(),
            'intimeAll' => $inTimeAll->getTimeStr(),
            'outtimeAll' => $outTimeAll->getTimeStr(),
            'chargeAll' => $chargeAll,
        ];
    }

    /**
     * 遅刻か早退のうち、規定就業時間に満たない勤怠控除時間合計を取得します
     * @return string
     */
    public function getMissingTime(){
        $report = new Reports();
        $missing = $report->getMissingCharge($this->_employee_id, $this->_year, $this->_month);

        $missingTimeSummary = new TimeUtil();
        foreach ($missing as $statement){
            $missingTimeSummary->addTimeStr($statement->time_missing);
        }

        return $missingTimeSummary->getTimeStr();

    }

    /**
     * 遅刻か早退の日数と時間の内訳を取得します
     * @return mixed
     */
    public function getMissingTimeDetail(){
        $report = new Reports();
        $missing = $report->getMissingCharge($this->_employee_id, $this->_year, $this->_month);

        $missing_unit = [
            'be_late' => ['days' => 0, 'time' => new TimeUtil()],
            'Leave_early' => ['days' => 0, 'time' => new TimeUtil()]
        ];

        foreach ($missing as $statement){
            $missing_unit[$statement->attendance]['days']++;
            $missing_unit[$statement->attendance]['time']->addTimeStr($statement->time_missing);
        }

        foreach ($missing_unit as $label => $statement){
            $missing_unit[$label]['time'] = $statement['time']->getTimeStr();
        }

        return $missing_unit;

    }
}