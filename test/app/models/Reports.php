<?php
use Phalcon\Mvc\Model;

class Reports extends Model
{
    public $employee_id;
    public $at_day;
    public $time_from;
    public $time_to;
    public $breaktime;
    public $created;
    public $updated;
    public $site_id;
    public $worktype_id;

    public function initialize(){
        $this->belongsTo('employee_id', 'Employees', 'id');
        $this->belongsTo('site_id', 'Sites', 'id');
        $this->belongsTo('worktype_id', 'Worktypes', 'id');
        $this->useDynamicUpdate(true);

        $this->skipAttributes(
            [
                'created',
                'updated',
            ]
        );
    }

    public static function getReportWithDayAll($employee_id, $year, $month){

        $results = [];

        // ブランクのカレンダーリストを作成します
        $lastDay = date('t', mktime(0, 0, 0, $month, 1, $year));
        for($day=1; $day<=$lastDay; $day++){
            $results[sprintf("%02d-%02d", $month, $day)] = '';
        }

        // 記録のある出勤簿を上書きします
        $repofts = self::getReport($employee_id, $year, $month);
        foreach ( $repofts as $report) {
            $results[date('m-d', strtotime($report->at_day))] = $report;
        }

        return $results;

    }

    // ある年月の勤怠表を取得します。
    public function getReport($employee_id, $year, $month){

        // 日数
        $lastDay = date('t', mktime(0, 0, 0, $month, 1, $year));

        // 従業員 and 指定期間でモデル取得
        return $reports = Reports::find(
            [
                'conditions' => 'employee_id = ?0 and at_day between ?1 and ?2',
                "order" => "at_day",
                "bind" => [
                    0 => $employee_id,
                    1 => "{$year}-{$month}-01",
                    2 => "{$year}-{$month}-{$lastDay}",
                ]
            ]);
    }

    public function updateOneReport($employeeId, $day, $report){

        try{

            $mo = Reports::findfirst([
                "conditions" => "employee_id = ?1 and at_day = ?2",
                bind => [
                    1 => $employeeId,
                    2 => $day,
                ]
            ]);

            // new
            if($mo===false) {
                $mo = new Reports();
            }


            $ar = array(
                'employee_id' => $employeeId,
                'at_day' => $day,
                'site_id' => $report['site_id'],
                'worktype_id' => $report['wtype_id'],
                'time_from' => $report['timefrom'],
                'time_to' => $report['timeto'],
                'breaktime' => '1:00',
            );

            $wh = array(
                'employee_id',
                'at_day',
                'site_id',
                'worktype_id',
                'time_from',
                'time_to',
                'breaktime',
            );

            if($mo->save($ar, $wh)==false){
                foreach ($mo->getMessages() as $message) {
                    echo "${day}: $message . <br>";
                }
            }else{
                echo "${day}: save completed' . <br>";
            }

        }catch (Exception $e){
            die($e);
        }
    }
}