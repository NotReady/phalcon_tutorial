<?php
use Phalcon\Mvc\Model;

class Loans extends Model
{
    public $loan_id;
    public $employee_id;
    public $ammount;
    public $io_type;
    public $comment;
    public $created;

    public function initialize(){
        $this->skipAttributes(
            [
                'created',
            ]
        );
    }

    /**
     * 貸付明細を取得します
     * @param $employee_id
     * @return mixed
     */
    public function getBook($employee_id){
        return $reports = Loans::find(
            [
                'conditions' => 'employee_id = :employee_id:',
                "order" => "created",
                "bind" => [
                    'employee_id' => $employee_id,
                ]
            ]);
    }

    /**
     * 貸付残高を取得します
     * @param $employee_id
     */
    public function getSummary($employee_id){
        $query = "select
                    sum(case when lo.io_type = 1 then lo.ammount else 0 end) - sum(case when lo.io_type = 2 then lo.ammount else 0 end) as ammount
                  from
                    loans lo
                  where lo.employee_id = :employee_id
                  ";

        $mo = new Loans();
        $result = new \Phalcon\Mvc\Model\Resultset\Simple(null, $mo,
            $mo->getReadConnection()->query($query, [
                'employee_id' => $employee_id,
            ]));

        return count($result) == 0 ? false: $result[0];

    }
}