<?php

use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;
use Phalcon\Mvc\Model\Transaction\Failed as TransactionFailed;

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
     * 曜日ごとの時間外手当の算出比率
     */
    private const OVERTIME_CHARGE_PAR_MAP =[
        '平日時間外' => 1.25
        ,'土曜日出勤' => 1.35
        ,'日曜日出勤' => 1.5
        ,'祝祭日出勤' => 1.5
    ];

    /**
     * 欠勤控除額を算出します
     * @note 基本給をベースに日給×欠勤日数を欠勤控除額とします
     * @param $baseCharge 基本給
     * @param $daysCountOfBusinessDays 月の営業日
     * @param $daysCountOfAbsenteeismDays 欠勤日数
     * @return int
     */
    private static function getAbsenceDeductionByDay($baseCharge, $daysCountOfBusinessDays, $daysCountOfAbsenteeismDays ){
        $salaryPerDay = floor($baseCharge / $daysCountOfBusinessDays);
        return $salaryPerDay * $daysCountOfAbsenteeismDays;
    }

    /**
     * 勤怠控除額を算出します
     * @note 基本給をベースに時給×欠勤時間を欠勤控除額とします
     * @param $baseCharge 基本給
     * @param $daysCountOfBusinessDays 月の営業日
     * @param $missingTimes 控除時間
     * @return int
     */
    private static function getMissingDeductionByTime($baseCharge, $daysCountOfBusinessDays, $missingTimes){
        $salaryPerSeconds = $baseCharge / $daysCountOfBusinessDays / 8 / 3600;
        $missingBySecounds = TimeUtil::makeFromTimeStr($missingTimes)->getSeconds();
        return floor($salaryPerSeconds * $missingBySecounds);
    }

    /**
     * 基本給を元に残業手当を算出します
     * @param $reportHelper ReportService
     */
    private static function getOvertimeChargeBasedSalary($baseCharge, $daysCountOfBusinessDays, $reportHelper){
        $report = $reportHelper->getSummaryBySiteWorkUnit();
        $timeUnit = $report['timeunits'];
        $salaryPerSeconds = $baseCharge / $daysCountOfBusinessDays / 8 / 3600;
        $charge = 0;
        foreach(['平日時間外', '土曜日出勤' ,'日曜日出勤', '祝祭日出勤'] as $nameUnit ){

            $outtimeStr = $timeUnit[$nameUnit]['time'];
            $outtimeSeconds = TimeUtil::makeFromTimeStr($outtimeStr)->getSeconds();

            $per = self::OVERTIME_CHARGE_PAR_MAP[$nameUnit];
            $charge += ($salaryPerSeconds * $outtimeSeconds * $per);
        }
        return floor($charge);
    }

    /**
     * 給与モデルのブランクをマスタで補完します
     * @param $salary Salaries
     * @param $employee Employees
     * @return array
     */
    public static function complementTempolarySalary($salary, $employee, $reportHelper){

        $bisinessDaysOfThisMonth = $reportHelper->getBusinessDayOfMonth();  // 営業日

        // 基本給を補完します
        if( is_null($salary->base_charge) === true ){

            // 社員固定給
            if( $employee->employee_type === 'pro' ){

                // 基本給
                $salary->base_charge = $employee->monthly_charge;

            }
            // パート時間給
            else
            {
                $salary->base_charge = $reportHelper->getSummaryBySiteWorkUnit();
            }
        }

        // 欠勤控除を補完します
        if( is_null($salary->attendance_deduction1) === true ) {
            if( $employee->employee_type === 'pro' ) {
                $days_Absenteeism = $reportHelper->howDaysAbsenteeism(); // 欠勤日数
                $salary->attendance_deduction1 =
                self::getAbsenceDeductionByDay($salary->base_charge, $bisinessDaysOfThisMonth, $days_Absenteeism);
            }

        }

        // 勤怠控除を補完します
        if( is_null($salary->attendance_deduction2) === true ) {
            if( $employee->employee_type === 'pro' ) {
                $missingTimes = $reportHelper->getMissingTime();  // 勤怠控除時間
                $salary->attendance_deduction2 =
                    self::getMissingDeductionByTime($salary->base_charge, $bisinessDaysOfThisMonth, $missingTimes);
            }
        }
        
        // 時間外手当を補完します
        if( is_null($salary->overtime_charge) === true ){
            // みなし残業を優先に設定
            if( empty($employee->overtime_charge) === false ){
                $salary->overtime_charge = $employee->overtime_charge;
            }else{
                $salary->overtime_charge = self::getOvertimeChargeBasedSalary($salary->base_charge, $bisinessDaysOfThisMonth, $reportHelper);
            }
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
            /*
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
            */

            // 社会保険料を補完します
            if( is_null($salary->insurance_bill) === true ){
                $salary->insurance_bill = $employee->insurance_bill;
            }

            // 厚生年金量を補完します
            if( is_null($salary->pension_bill) === true ){
                $salary->pension_bill = $employee->pension_bill;
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

    /**
     * 給与を更新します
     * @param Salaries $salary 給与モデル
     * @param Employees $employee 社員モデル
     * @param bool $fixed 確定フラグ
     * @throws Exception
     */
    public static function updateWithComplement($salary, $employee, $reportService, $fixed = true){

        $transactionManager = new TransactionManager();
        $transaction = $transactionManager->get();

        try{

            // ブランク属性をマスタから補完して更新します
            self::complementTempolarySalary($salary, $employee, $reportService);
            $salary->fixed = $fixed;

            $salary->setTransaction($transaction);
            if( $salary->save() === false ){
                $transaction->rollback();
            }

            // 貸付返済を登録します
            if( $salary->loan_bill > 0 ){

                $year = date('Y', strtotime($salary->salary_date));
                $month = date('m', strtotime($salary->salary_date));
                $loan = Loans::createLoan($employee->id, date("Y/m/d"), 2, $salary->loan_bill, "${year}年${month}月 給与控除", $salary->salary_id);

                $loan->setTransaction($transaction);
                if( $loan->save() === false ){
                    $transaction->rollback();
                }
            }

            $transaction->commit();

        }catch (TransactionFailed $e){
            throw Exception();
        }
    }

    /**
     * 給与確定を取り消します
     * @param Salaries $salary 給与モデル
     * @param Employees $employee 社員モデル
     */
    public static function cancel($salary, $employee){
        $transactionManager = new TransactionManager();
        $transaction = $transactionManager->get();

        try{

            // 給与を未確定状態に更新します
            $salary->fixed = 'temporary';

            $salary->setTransaction($transaction);
            if( $salary->save() === false ){
                $transaction->rollback();
            }

            // 関連する貸付明細を削除します
            $loan = Loans::getLoanBySalaryId($salary->salary_id);
            if( empty($loan) === false ){
                $loan->setTransaction($transaction);
                if( $loan->delete() === false ){
                    $transaction->rollback();
                }
            }

            $transaction->commit();

        }catch (TransactionFailed $e){
            throw Exception();
        }
    }

}