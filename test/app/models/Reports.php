<?php
use Phalcon\Mvc\Model;
use Phalcon\Mvc\Model\Query as phQuery;

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

    /**
     * 給与を算出します。
     * @params $employee_id integer 社員Id
     * @params $year integer 指定年
     * @params $month integer 指定月
     * @return Simple collections
     * days_worked      integer 出勤日数
     * sitename         string  現場名
     * worktype_name    string  現場作業名
     * in_time          time    定時内就業時間
     * out_time         time    定時外就業時間
     */
    public function getSummaryReport($employee_id, $year, $month)
    {
        // 日数
        $lastDay = date('t', mktime(0,0,0,$month, 1, $year));
        $date_from = date('Y-m-d', mktime(0, 0, 0, $month, 1, $year));
        $date_to = date('Y-m-d', mktime(0,0,0,$month, $lastDay, $year));

        $query = "
        select
            count(*) as days_worked,
            r.sitename,
            r.worktype_name,
            sec_to_time(sum(time_to_sec(timediff( r.worktime , timediff(r.worktime, r.overtime))))) as in_time,
            sec_to_time(sum(time_to_sec(timediff(r.worktime, r.overtime)))) as out_time
        from
            (
                select
                    timediff(timediff(rp.time_to, rp.time_from), rp.breaktime) as worktime,
                    timediff(timediff(s.time_to, s.time_from), timediff(s.breaktime_to, s.breaktime_from)) as overtime,
                    rp.site_id,
                    s.sitename,
                    w.id as worktype_id,
                    w.name as worktype_name
                from
                 reports rp
                join 
                 sites s on s.id = rp.site_id
                join
                 worktypes w on w.id = rp.worktype_id
                where
                 rp.employee_id = :employee_id and
                 rp.at_day between :date_from and :date_to
             ) r
        group by r.site_id, r.worktype_id
        ";

        $mo = new Reports();

        $summaryReports = new \Phalcon\Mvc\Model\Resultset\Simple(null, $mo,
            $mo->getReadConnection()->query($query, [
            'employee_id' => $employee_id,
            'date_from' => $date_from,
            'date_to' => $date_to
        ]));

        return $summaryReports;
    }

    /**
     * 指定年月で現場作業∩時間内∪時間外作業時間の合計を取得します
     * @params $employee_id integer 社員Id
     * @params $year integer 指定年
     * @params $month integer 指定月
     * @return Simple collections
     * sitename         string  現場名
     * worktype_name    string  現場作業名
     * label            string  時間内|時間外
     * sum_time         time    就業時間
     */
    public function getSummaryOfGroupBySiteWorkType($employee_id, $year, $month)
    {
        // 日数
        $lastDay = date('t', mktime(0,0,0,$month, 1, $year));
        $date_from = date('Y-m-d', mktime(0, 0, 0, $month, 1, $year));
        $date_to = date('Y-m-d', mktime(0,0,0,$month, $lastDay, $year));

        $query = "

        select
            in_time.sitename,
            in_time.worktype_name,
            '時間内' as label,
            sec_to_time(sum(time_to_sec(timediff( in_time.worktime , timediff(in_time.worktime, in_time.overtime))))) as sum_time
        from
            (
                select
                    timediff(timediff(rp.time_to, rp.time_from), rp.breaktime) as worktime,
                    timediff(timediff(s.time_to, s.time_from), timediff(s.breaktime_to, s.breaktime_from)) as overtime,
                    rp.site_id,
                    s.sitename,
                    w.id as worktype_id,
                    w.name as worktype_name
                from
                    reports rp
                join 
                    sites s on s.id = rp.site_id
                join
                    worktypes w on w.id = rp.worktype_id
                where
                    rp.employee_id = :employee_id and
                    rp.at_day between :date_from and :date_to
             ) in_time
        group by in_time.site_id, in_time.worktype_id

		union 
		
        select
            out_time.sitename,
            out_time.worktype_name,
            '時間外' as label,
            sec_to_time(sum(time_to_sec(timediff(out_time.worktime, out_time.overtime)))) as sum_time
        from
            (
                select
                    timediff(timediff(rp.time_to, rp.time_from), rp.breaktime) as worktime,
                    timediff(timediff(s.time_to, s.time_from), timediff(s.breaktime_to, s.breaktime_from)) as overtime,
                    rp.site_id,
                    s.sitename,
                    w.id as worktype_id,
                    w.name as worktype_name
                from
                    reports rp
                join 
                    sites s on s.id = rp.site_id
                join
                    worktypes w on w.id = rp.worktype_id
                where
                    rp.employee_id = :employee_id and
                    rp.at_day between :date_from and :date_to
             ) out_time
        group by out_time.site_id, out_time.worktype_id
        having sum_time > 0
        order by sitename, worktype_name, label asc
        ";

        $mo = new Reports();

        $summaryReports = new \Phalcon\Mvc\Model\Resultset\Simple(null, $mo,
            $mo->getReadConnection()->query($query, [
                'employee_id' => $employee_id,
                'date_from' => $date_from,
                'date_to' => $date_to
            ]));

        return $summaryReports;
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