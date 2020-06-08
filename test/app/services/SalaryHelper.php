<?php
/**
 * Created by PhpStorm.
 * User: notready
 * Date: 2019-10-21
 * Time: 23:57
 */

class SalaryHelper
{
    /**
     * 給与モデルのブランクをマスタで補完します
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

    }

}