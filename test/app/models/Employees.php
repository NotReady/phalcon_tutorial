<?php

use Phalcon\Mvc\Model;

class Employees extends Model{
    public $id;
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

    public function initialize(){
        $this->hasMany('id', 'Reports', 'employee_id');

        $this->skipAttributes(
            [
                'created',
                'updated',
            ]
        );
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

    public function getEmployeesWithLatestInput(){
        $query = new \Phalcon\Mvc\Model\Query(
            'select
              e.id as employee_id,
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

    }
}