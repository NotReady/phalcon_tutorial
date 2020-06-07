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
                // todo 非正規社員は時間給合計を補完します
            }
        }
        // みなし残業代を補完します
        if( is_null($salary->overtime_charge) === true ){
            $salary->overtime_charge = $employee->overtime_charge;
        }
        // 交通費を補完します
        if( is_null($salary->transportation_expenses) === true ){
            $salary->transportation_expenses = $employee->transportation_expenses;
        }
        // 役職手当を補完します
        if( is_null($salary->skill_charge) === true ){
            $salary->skill_charge = $employee->skill_charge;
        }
    }

}