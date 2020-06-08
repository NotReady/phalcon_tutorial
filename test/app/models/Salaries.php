<?php

use Phalcon\Mvc\Model;

class Salaries extends Model{

    // 対象年月
    public $salary_date;
    // 社員Id
    public $employee_id;

    // 基本給(固定給か時間給)
    public $base_charge;
    // みなし残業代
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

    // 社会保険料
    public $insurance_bill;
    // 厚生年金料
    public $pension_bill;
    // 雇用保険料
    public $employment_insurance_bill;
    // 所得税
    public $income_tax;

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


    // 確定フラグ
    public $fixed;
    // 作成日
    public $created;
    // 更新日
    public $updated;

    public function initialize(){
        $this->skipAttributes(
            [
                'created',
                'updated',
            ]
        );
    }

    /**
     * 社員の指定月の給与明細を取得します。
     */
    public static function getSalaryByEmployeeAndDate($employee_id, $dateBy){
        $salary = Salaries::findfirst([
            'conditions' => 'employee_id = :employee_id: and salary_date = :salary_date:',
            bind => [
                'employee_id' => $employee_id,
                'salary_date' => $dateBy,
            ]
        ]);

        // 未作成の場合は作成して返却する
        if( $salary === false ){

            $data = array(
                'employee_id' => $employee_id,
                'salary_date' => $dateBy,
            );

            $cols = array(
                'employee_id',
                'salary_date',
            );

            $salary = new Salaries();
            if( $salary->save($data, $cols) === false){
                $messages = '';
                foreach ( $salary->getMessages() as $message ) {
                    $messages .= $message;
                }
                throw new Exception($messages);
            }
        }

        return $salary;
    }
}