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
    }

    // ある年月の勤怠表を取得します。
    public function getReport($employee_id, $year, $month){

        // 日数
        $lastDay = date('t', mktime(0, 0, 0, $month, 1, $year));

        // 従業員 and 指定期間でモデル取得
        $repos = Reports::find(
            [
                'conditions' => 'employee_id = ?0 and at_day between ?1 and ?2',
                "order" => "at_day",
                "bind" => [
                    0 => $employee_id,
                    1 => "{$year}-{$month}-01",
                    2 => "{$year}-{$month}-{$lastDay}",
                ]
            ]);

        $reports = array();
        // 存在する勤怠を日付のハッシュリストに登録
        for($d=1; $d<=$lastDay; $d++){

            $asReport = $repos->filter(function ($r) use ($year, $month, $d){
                if( $r->at_day == sprintf("%04d-%02d-%02d", $year, $month, $d)) {
                    return $r;
                }
            });

            // 1件のレポート
            $r = current($asReport);
//            $bfrom = new DateTime($r->breaktime_from);
//            $bto = new DateTime($r->breaktime_to);
//            $interval = $bfrom->diff($bto);

            $timefrom = new DateTime($r->time_from);
            $timeto = new DateTime($r->time_to);
            $breaktime = new DateTime($r->breaktime);

            $week = ['日','月','火','水','木','金','土',];
            $reports[sprintf("%02d-%02d", $month, $d)]['week'] = $week[date('w', mktime(0, 0, 0, $month, $d, $year))];;

            if($r){
                $reports[sprintf("%02d-%02d", $month, $d)]['report'] = [
                    "site" => $r->sites,
                    "wtype" => $r->worktypes,
                    "time_from" => $timefrom->format("H:i"),
                    "time_to" => $timeto->format("H:i"),
                    // intervalはformat式が異なる。。
                    "breaktime" => $breaktime->format("H:i"),//$interval->format("%H:%I"),
                ];
            }
        }

        return $reports;

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

//            $mo->employee_id = $employeeId;
//            $mo->at_day = $day;
//            $mo->site_id = $report['site_id'];
//            $mo->worktype_id = $report['wtype_id'];
//            $mo->time_from = $report['timefrom'];
//            $mo->time_to = $report['timeto'];
//            $mo->breaktime = '1:00';
//
//
//            if($mo->save()===false){
//                foreach ($mo->getMessages() as $message) {
//                    echo "${day}: $message . <br>";
//                }
//            }else{
//                echo "${day}: save completed' . <br>";
//            }


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