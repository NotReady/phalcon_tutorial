<?php

use Phalcon\Mvc\Model;

class Employees extends Model{

    /**************** properties ****************/

    /**
     * @var サロゲートキー
     */
    public $id;

    /**
     * @var 社員番号
     */
    public $employee_no;

    /**
     * @var 雇用状態
     */
    public $employee_status;

    /**
     * @var 入社日
     */
    public $hire_date;

    /**
     * @var 退社日
     */
    public $leave_date;

    /**
     * @var 名字
     */
    public $first_name;

    /**
     * @var 名前
     */
    public $last_name;

    /**
     * @var 住所
     */
    public $address;

    /**
     * @var 社員属性
     */
    public $employee_type;

    /**
     * @var 職能外部キー
     */
    public $skill_id;

    /**
     * @var 社会保険加入
     */
    public $insurance_type;

    /**
     * @var 操作権限
     */
    public $service_role;

    /**
     * @var 固定給
     */
    public $monthly_charge;

    /**
     * @var 固定残業代
     */
    public $overtime_charge;

    /**
     * @var 役職手当
     */
    public $skill_charge;

    /**
     * @var 課税交通費
     */
    public $transportation_expenses;

    /**
     * @var 日割交通費
     */
    public $transportation_expenses_by_day;

    /**
     * @var 非課税交通費
     */
    public $transportation_expenses_without_tax;

    /**
     * @var 非課税通信費
     */
    public $communication_charge_without_tax;

    /**
     * @var 住宅手当
     */
    public $house_charge;

    /**
     * @var 送迎手当
     */
    public $bus_charge;

    /**
     * @var 事務手当
     */
    public $officework_charge;

    /**
     * @var その他支給
     */
    public $etc_charge;

    /**
     * @var 家賃
     */
    public $rent_bill;

    /**
     * @var 電気代
     */
    public $electric_bill;

    /**
     * @var ガス代
     */
    public $gas_bill;

    /**
     * @var 水道代
     */
    public $water_bill;

    /**
     * @var 弁当代
     */
    public $food_bill;

    /**
     * @var その他控除
     */
    public $etc_bill;

    /**
     * @var 社会保険料
     */
    public $insurance_bill;

    /**
     * @var 厚生年金料
     */
    public $pension_bill;

    /**
     * @var ログインユーザ
     */
    public $username;

    /**
     * @var ログインパスワード
     */
    public $password;

    public $created;
    public $updated;

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

    const SERVICE_ROLE_MAP = [
        'none' => 'ログイン不可',
        'user' => '従業員',
        'admin' => '管理者',
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
     * 永続化前のコールバック
     * @note 文字列以外のプリミティブ型へのブランク、型違いをnullに置き換える
     */
    public function beforeSave(){

        // 無効値のnull埋め
        $this->leave_date       = empty($this->leave_date) === false ? $this->leave_date : null;
        $this->overtime_charge  = is_numeric($this->overtime_charge ) ? $this->overtime_charge  : null;
        $this->skill_charge     = is_numeric($this->skill_charge) ? $this->skill_charge : null;
        $this->transportation_expenses              = is_numeric($this->transportation_expenses) ? $this->transportation_expenses : null;
        $this->transportation_expenses_by_day       = is_numeric($this->transportation_expenses_by_day) ? $this->transportation_expenses_by_day : null;
        $this->transportation_expenses_without_tax  = is_numeric($this->transportation_expenses_without_tax) ? $this->transportation_expenses_without_tax : null;
        $this->communication_charge_without_tax = is_numeric($this->communication_charge_without_tax) ? $this->communication_charge_without_tax : null;
        $this->house_charge     = is_numeric($this->house_charge) ? $this->house_charge : null;
        $this->bus_charge       = is_numeric($this->bus_charge) ? $this->bus_charge : null;
        $this->officework_charge = is_numeric($this->officework_charge) ? $this->officework_charge : null;
        $this->etc_charge       = is_numeric($this->etc_charge) ? $this->etc_charge : null;
        $this->rent_bill        = is_numeric($this->rent_bill) ? $this->rent_bill : null;
        $this->electric_bill    = is_numeric($this->electric_bill) ? $this->electric_bill : null;
        $this->gas_bill         = is_numeric($this->gas_bill) ? $this->gas_bill : null;
        $this->water_bill       = is_numeric($this->water_bill) ? $this->water_bill : null;
        $this->food_bill        = is_numeric($this->food_bill) ? $this->food_bill : null;
        $this->etc_bill         = is_numeric($this->etc_bill) ? $this->etc_bill : null;
        $this->insurance_bill   = is_numeric($this->insurance_bill) ? $this->insurance_bill : null;
        $this->pension_bill     = is_numeric($this->pension_bill) ? $this->pension_bill : null;
        $this->insurance_bill   = is_numeric($this->insurance_bill) ? $this->insurance_bill : null;
        $this->pension_bill     = is_numeric($this->pension_bill) ? $this->pension_bill : null;
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

    public function authentication($username, $password){
        $employee = Employees::findfirst([
            "conditions" => "username = :username: and password = :password:",
            bind => [
                'username' => $username,
                'password' => $password,
            ]
        ]);

        return $employee;
    }

    /**
     * サロゲートキーをもとに従業員を取得します
     * @param $employee_id サロゲートキー
     * @return Employees
     */
    public static function getEmployeeById($id){
        $employee = Employees::findfirst([
            "conditions" => "id = :employee_id:",
            bind => [
                'employee_id' => $id,
            ]
        ]);
        return $employee;
    }

    /**
     * ログインIDをもとに従業員を取得します
     * @param $login_id ログインID
     * @return Employees
     */
    public static function getEmployeeByLoginId($login_id){
        $employee = Employees::findfirst([
            "conditions" => "username = :login_id:",
            bind => [
                'login_id' => $login_id,
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
                    r.at_day as last_worked_day,
                    (
                        case when s.fixed is null then "temporary"
                        else s.fixed end
                    ) as salary_fixed
                -- 社員
                from
                    employees e
                -- 期間内の勤務表 社員IDグルーピング
                left outer join
                (
                    select
                        max(reports.at_day) as at_day, -- 最終出勤日
                        count(reports.at_day) as days_worked, -- 出勤日数
                        reports.employee_id -- 社員ID
                    from
                        reports
                    where
                        reports.at_day between :date_from and :date_to
                        and reports.attendance not in ("absenteeism","holidays") -- 欠勤,有給以外
                    group by employee_id
                ) r on r.employee_id = e.id
                -- 給与
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