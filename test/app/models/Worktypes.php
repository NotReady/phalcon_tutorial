<?php
use Phalcon\Mvc\Model;

class Worktypes extends Model
{
    /**
     * @var サロゲートキー
     */
    public $id;

    /**
     * @var 作業分類名
     */
    public $name;

    /**
     * 社員が現場において出勤可能な作業分類の一覧を取得します
     * @param $employee_id Employees.id
     * @param $site_id Sites.id
     */
    public static function getWorkTypesByEmployeeAtSite($employee_id, $site_id){
        $query = "
                select
                    h.worktype_id,
                    w.name,
                    s.time_from,
                    s.time_to,
                    timediff(s.breaktime_to, s.breaktime_from) as breaktime
                from
                    employees e
                join
                    hourlycharges h on h.skill_id = e.skill_id
                join
                    worktypes w on w.id = h.worktype_id
                join
                	sites s on s.id = h.site_id
                where
                    e.id = :employee_id
                    and h.site_id = :site_id
        ";

        $mo = new Worktypes();

        $workTypes = new \Phalcon\Mvc\Model\Resultset\Simple(null, $mo,
            $mo->getReadConnection()->query($query, [
                'employee_id' => $employee_id,
                'site_id' => $site_id,
            ]));

        return $workTypes->toArray();
    }
}