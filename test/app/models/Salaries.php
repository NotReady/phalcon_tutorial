<?php

use Phalcon\Mvc\Model;

class Salaries extends Model{

    // 対象年月
    public $salary_date;
    // 社員Id
    public $employee_id;
    // 基本給
    public $base_charge;
    // 残業代
    public $overtime_charge;
    // 役職手当
    public $skill_charge;
    // 交通費
    public $transportation_expenses;
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