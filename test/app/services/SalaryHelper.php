<?php

class SalaryHelper
{
    /**
     * @note 社会保険料算出のAPIエンドポイント
     */
    private const INSURANCE_CALC_API_ENDPOINT_V202003 = 'https://asia-northeast1-tsunagi-all.cloudfunctions.net/getSocialInsurance202003';
    /**
     * @note 源泉所得徴収額のAPIエンドポイント
     */
    private const TAX_CALC_API_ENDPOINT_V2020 = 'https://asia-northeast1-tsunagi-all.cloudfunctions.net/getIncomeTax2020';
    /**
     * @note 雇用保険料率 従業員負担
     */
    private const EMPLOYMENT_INSURANCE_PER_EMPLOYEE_V2020 = 0.003;
    /**
     * @note 雇用保険料率 事業主負担
     */
    private const EMPLOYMENT_INSURANCE_PER_OWNER_V2020 = 0.006;
    /**
     * @note 雇用保険料率 小数オフセット
     */
    private const EMPLOYMENT_INSURANCE_ROUND_OFFSET = 0.41;
    /**
     * @note 貸付返済額のデフォルト値
     */
    private const REPAY_LOAN_VALUE = 20000;

    /**
     * 給与モデルのブランクをマスタで補完します
     * @param $salary Salaries
     * @param $employee Employees
     * @return array
     */
    public static function complementTempolarySalary($salary, $employee){

        // 基本給を補完します
        if( is_null($salary->base_charge) === true ){
            // 社員は固定給を補完します
            if( $employee->employee_type === 'pro' ){
                $salary->base_charge = $employee->monthly_charge;
            }else{
                $salary->base_charge = 0;
            }
        }

        // 賞与を補完します
        if( is_null($salary->bonus_charge) === true ){
            $salary->bonus_charge = 0;
        }

        // みなし残業代を補完します
        if( is_null($salary->overtime_charge) === true ){
            $salary->overtime_charge = $employee->overtime_charge;
        }

        // 役職手当を補完します
        if( is_null($salary->skill_charge) === true ){
            $salary->skill_charge = $employee->skill_charge;
        }

        // 課税交通費を補完します
        if( is_null($salary->transportation_expenses) === true ){
            $salary->transportation_expenses = $employee->transportation_expenses;
        }

        // 日割交通費を補完します
        if( is_null($salary->transportation_expenses_by_day) === true ){
            $salary->transportation_expenses_by_day = $employee->transportation_expenses_by_day;
        }

        // 非課税交通費を補完します
        if( is_null($salary->transportation_expenses_without_tax) === true ){
            $salary->transportation_expenses_without_tax = $employee->transportation_expenses_without_tax;
        }

        // 非課税通信費を補完します
        if( is_null($salary->communication_charge_without_tax) === true ){
            $salary->communication_charge_without_tax = $employee->communication_charge_without_tax;
        }

        // 住宅手当を補完します
        if( is_null($salary->house_charge) === true ){
            $salary->house_charge = $employee->house_charge;
        }

        // 送迎手当を補完します
        if( is_null($salary->bus_charge) === true ){
            $salary->bus_charge = $employee->bus_charge;
        }

        // 事務手当を補完します
        if( is_null($salary->officework_charge) === true ){
            $salary->officework_charge = $employee->officework_charge;
        }

        // その他支給を補完します
        if( is_null($salary->etc_charge) === true ){
            $salary->etc_charge = $employee->etc_charge;
        }

        // 家賃を補完します
        if( is_null($salary->rent_bill) === true ){
            $salary->rent_bill = $employee->rent_bill;
        }

        // 電気代を補完します
        if( is_null($salary->electric_bill) === true ){
            $salary->electric_bill = $employee->electric_bill;
        }

        // ガス代を補完します
        if( is_null($salary->gas_bill) === true ){
            $salary->gas_bill = $employee->gas_bill;
        }

        // 水道代を補完します
        if( is_null($salary->water_bill) === true ){
            $salary->water_bill = $employee->water_bill;
        }

        // 弁当代を補完します
        if( is_null($salary->food_bill) === true ){
            $salary->food_bill = $employee->food_bill;
        }

        // その他控除を補完します
        if( is_null($salary->etc_bill) === true ){
            $salary->etc_bill = $employee->etc_bill;
        }

        // 貸付返済額を補完します
        if( is_null($salary->loan_bill) === true ){
            $active_loan_value = Loans::getAmount($employee->id);
            $active_loan_value = empty($active_loan_value) ? 0 : $active_loan_value;
            $salary->loan_bill = $active_loan_value < self::REPAY_LOAN_VALUE ? $active_loan_value : self::REPAY_LOAN_VALUE;
        }

        if( $employee->insurance_type === 'enable' ){
            $insurancies = ApiHelper::get(self::INSURANCE_CALC_API_ENDPOINT_V202003, [
                'salary' => $salary->getChargiesSummary(),
                'kaigo' => 0
            ]);
            // 社会保険料を補完します
            if( is_null($salary->insurance_bill) === true ){
                $salary->insurance_bill = $insurancies->insurance->payment;
            }
            // 厚生年金量を補完します
            if( is_null($salary->pension_bill) === true ){
                $salary->pension_bill = $insurancies->pension->payment;
            }
        }else{
            $salary->insurance_bill = null;
            $salary->pension_bill = null;
        }

        // 雇用保険を補完します
        if( is_null($salary->employment_insurance_bill) === true ){
            $salary->employment_insurance_bill = self::getEmploymentInsuranceForEmployee($salary->getChargiesSummary());
            $salary->employment_insurance_owner = self::getEmploymentInsuranceForOwnerIfInputed($salary->employment_insurance_bill);
        }else{
            // 所得税が上書き保存されている場合は、入力金額に応じた事業主負担を出す
            $salary->employment_insurance_owner = self::getEmploymentInsuranceForOwnerIfInputed($salary->employment_insurance_bill);
        }

        // 所得税を補完します
        if( is_null($salary->income_tax) === true ){
            $tax = ApiHelper::get(self::TAX_CALC_API_ENDPOINT_V2020, [
                'salary' => $salary->getSubjectToTaxSummary(),
                'numberOfDependents' => 0,
                'type' => 'a'
            ]);
            $salary->income_tax = $tax->total;
        }

    }

    /**
     * 従業員の雇用保険負担額を取得します
     * @note https://www.reloclub.jp/relotimes/article/10320
     * @note 小数は0.50以下切り捨て
     */
    private static function getEmploymentInsuranceForEmployee($value){
        return floor($value * self::EMPLOYMENT_INSURANCE_PER_EMPLOYEE_V2020 + self::EMPLOYMENT_INSURANCE_ROUND_OFFSET);
    }

    /**
     * 事業主の雇用保険負担額を取得します
     * @note https://www.reloclub.jp/relotimes/article/10320
     * @note 小数は0.50以下切り捨て
     */
    private static function getEmploymentInsuranceForOwner($value){
        return floor($value * self::EMPLOYMENT_INSURANCE_PER_OWNER_V2020 + self::EMPLOYMENT_INSURANCE_ROUND_OFFSET);
    }

    /**
     * 雇用保険を手動で補正された場合の事業主の雇用保険負担額を取得します
     * @note 利率で
     */
    private static function getEmploymentInsuranceForOwnerIfInputed($value){
        return $value * (self::EMPLOYMENT_INSURANCE_PER_OWNER_V2020 /  self::EMPLOYMENT_INSURANCE_PER_EMPLOYEE_V2020);
    }

}