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
    // 雇用保険料 従業員負担
    public $employment_insurance_bill;
    // 雇用保険料 事業主負担
    public $employment_insurance_owner;

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

    /**
     * 支給額の合計を取得します
     */
    public function getChargiesSummary(){
        return $this->base_charge
                + $this->overtime_charge
                + $this->skill_charge
                + $this->transportation_expenses
                + $this->transportation_expenses_by_day
                + $this->transportation_expenses_without_tax
                + $this->communication_charge_without_tax
                + $this->house_charge
                + $this->bus_charge
                + $this->officework_charge
                + $this->etc_charge;
    }

    /**
     * 社会保険料の合計を取得します
     * @note https://support.yayoi-kk.co.jp/faq_Subcontents.html?page_id=19591
     */
    public function getInsuranciesSummary(){
        return $this->insurance_bill
                + $this->pension_bill
                + $this->employment_insurance_bill;
    }

    /**
     * 控除の合計を取得します
     */
    public function getBillsSummary(){
        return $this->rent_bill
                + $this->electric_bill
                + $this->gas_bill
                + $this->water_bill
                + $this->food_bill
                + $this->etc_bill;
    }

    /**
     * 税引対象となる支給額の合計を取得します
     */
    public function getSubjectToTaxSummary(){
        return $this->base_charge
                + $this->overtime_charge
                + $this->skill_charge
                + $this->transportation_expenses
                + $this->transportation_expenses_by_day
                + $this->house_charge
                + $this->bus_charge
                + $this->officework_charge
                + $this->etc_charge
                - $this->getInsuranciesSummary();
    }

    /**
     * 税引の合計を取得します
     */
    public function getTaxSummary(){
        return $this->income_tax;
    }
}