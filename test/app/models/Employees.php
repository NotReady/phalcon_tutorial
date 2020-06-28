<?php

use Phalcon\Mvc\Model;

class Employees extends Model{

    /**************** properties ****************/

    // サロゲートキー
    public $id;
    // 社員番号
    public $employee_no;
    // 雇用状態
    public $employee_status;
    // 入社日
    public $hire_date;
    // 退社日
    public $leave_date;
    // 性
    public $first_name;
    // 名
    public $last_name;
    // 住所
    public $address;
    // 社員属性
    public $employee_type;
    // 職能外部キー
    public $skill_id;
    // 社会保険加入
    public $insurance_type;

    // 固定給
    public $monthly_charge;
    // 固定残業代
    public $overtime_charge;
    // 役職手当
    public $skill_charge;
    // 課税交通費
    public $transportation_expenses;
    // 日割交通費
    public $transportation_expenses_by_day;
    // 非課税交通費
    public $transportation_expenses_without_tax;
    // 非課税通信費
    public $communication_charge_without_tax;
    // 住宅手当
    public $house_charge;
    // 送迎手当
    public $bus_charge;
    // 事務手当
    public $officework_charge;
    // その他支給
    public $etc_charge;

    // 家賃
    public $rent_bill;
    // 電気代
    public $electric_bill;
    // ガス代
    public $gas_bill;
    // 水道代
    public $water_bill;
    // 弁当代
    public $food_bill;
    // その他控除
    public $etc_bill;

    // 作成日
    public $created;
    // 更新日
    public $updated;
    // ログインユーザ
    public $username;
    // ログインパスワード
    public $password;

    const EMPLOYEE_STATUS_MAP = [
        'active' => '雇用中',
        'dismiss' => '解雇済',
        'suspend' => '休職中',
    ];

    const EMPLOYEE_TYPE_MAP = [
        'pro' => '社員',
        'part' => 'パートアルバイト',
    ];

    const EMPLOYEE_SKILL_MAP = [
        '1' => '研修中',
        '2' => '一般職',
        '3' => '上級職',
        '4' => '管理職',
        '5' => '専門職',
    ];

    /**************** methods ****************/

    public function initialize(){
        $this->hasMany('id', 'Reports', 'employee_id');

        $this->skipAttributes(
            [
                'created',
                'updated',
            ]
        );
    }

    /**
     * 退社日のセッター
     * Date型テーブルなので、ブランクの場合はnullに置き換える
     * @param $leaveDate
     */
    public function setLeaveDate($leaveDate){
        $this->leave_date = empty($leaveDate) ? null : $leaveDate;
    }

    /**
     * 社員番号のセッター
     * int型テーブルなので、0パディングを数字化する
     * @param $employeeNo
     */
    public function setEmployeeNo($employeeNo){
        $this->employee_no = intval($employeeNo);
    }

    /**
     * 社員番号のゲッター
     * int型テーブルなので、0パディングする
     * @return 0埋めした社員番号
     */
    public function getEmployeeNo(){
        return empty($this->employee_no) ? "" :  str_pad($this->employee_no, 5, 0, STR_PAD_LEFT);
    }

    public function authorization($username, $password){
        self::findFirst();
        $employee = Employees::findfirst([
            "conditions" => "username = :username: and password = :password:",
            bind => [
                'username' => $username,
                'password' => $password,
            ]
        ]);

        return $employee;
    }

    public static function getEmployeesReportList($year, $month){

        $lastDay = date('t', mktime(0,0,0,$month, 1, $year));
        $date_from = date('Y-m-d', mktime(0, 0, 0, $month, 1, $year));
        $date_to = date('Y-m-d', mktime(0,0,0,$month, $lastDay, $year));

        $query = '
                select
                    e.id as employee_id,
                    concat(e.first_name, " ", e.last_name) as employee_name,
                    (
                        case when r.days_worked is null then 0
                        else r.days_worked end
                    ) as days_worked,
                    max(r.at_day) as last_worked_day,
                    (
                        case when s.fixed is null then "temporary"
                        else s.fixed end
                    ) as salary_fixed
                from
                    employees e
                left outer join
                (
                    select
                        max(reports.at_day) as at_day,
                        count(reports.at_day) as days_worked,
                        reports.employee_id
                    from
                        reports
                    where
                        reports.at_day between :date_from and :date_to
                    group by employee_id
                ) r on r.employee_id = e.id
                left outer join
                (
                    select
                        salaries.employee_id,
                        salaries.fixed
                    from
                        salaries
                    where
                        salaries.salary_date between :date_from and :date_to
                ) s on s.employee_id = e.id
                group by
                e.id, s.fixed';

        $mo = new Employees();
        $employees = new \Phalcon\Mvc\Model\Resultset\Simple(null, $mo,
            $mo->getReadConnection()->query($query, [
                'date_from' => $date_from,
                'date_to' => $date_to
            ]));

        return $employees;
    }

    public function getEmployeesWithLatestInput(){

        return Employees::find();

        /*
        $query = new \Phalcon\Mvc\Model\Query(
            'select
              e.id as employee_id,
              e.employee_status,
              e.employee_no,
              e.hire_date,
              e.employee_type,
              e.first_name,
              e.last_name,
              e.created,
              e.updated,
              max(r.at_day) as last_input_day
             from Employees e
             left join Reports r
             on e.id = r.employee_id
             group by e.id',
            $this->getDI()
        );


        $rows =  $query->execute();
        return $rows;
        */
    }
}