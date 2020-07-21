<?php

use Phalcon\Mvc\Model;

class Salaries extends Model{

    /**
     * @var サロゲートID
     */
    public $salary_id;

    /**
     * @var 対象年月
     */
    public $salary_date;

    /**
     * @var 社員ID
     */
    public $employee_id;

    /**
     * @var 基本給(固定給か時間給)
     */
    public $base_charge;

    /**
     * @var 賞与
     */
    public $bonus_charge;

    /**
     * @var みなし残業代
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
     * @var 貸付返済
     */
    public $loan_bill;

    /**
     * @var 社会保険料
     */
    public $insurance_bill;

    /**
     * @var 厚生年金料
     */
    public $pension_bill;

    /**
     * @var 雇用保険料 従業員負担
     */
    public $employment_insurance_bill;

    /**
     * @var 雇用保険料 事業主負担
     */
    public $employment_insurance_owner;

    /**
     * @var 所得税
     */
    public $income_tax;

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
     * @var 欠勤控除
     */
    public $attendance_deduction1;

    /**
     * @var 勤怠控除
     */
    public $attendance_deduction2;

    /**
     * @var 確定フラグ
     */
    public $fixed;

    public $created;
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
    public static function getSalaryByEmployeeAndDate($employee_id, $year, $month){

        $salatyDate = "${year}/${month}/01";

        $salary = Salaries::findfirst([
            'conditions' => 'employee_id = :employee_id: and salary_date = :salary_date:',
            bind => [
                'employee_id' => $employee_id,
                'salary_date' => $salatyDate,
            ]
        ]);

        // 未作成の場合は作成して返却する
        if( $salary === false ){
            $data = array(
                'employee_id' => $employee_id,
                'salary_date' => $salatyDate,
                'fixed' => 'temporary'
            );
            $cols = array(
                'employee_id',
                'salary_date',
                'fixed'
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
                + $this->bonus_charge
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
                + $this->etc_bill
                + $this->attendance_deduction1
                + $this->attendance_deduction2
                + $this->loan_bill;
    }

    /**
     * 税引対象となる支給額の合計を取得します
     */
    public function getSubjectToTaxSummary(){
        return $this->base_charge
                + $this->bonus_charge
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

    /**
     * 総支給額を取得します
     */
    public function getSalary(){
        return $this->getChargiesSummary() - $this->getBillsSummary() - $this->getInsuranciesSummary() - $this->getTaxSummary();
    }
}