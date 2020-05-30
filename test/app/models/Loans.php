<?php
use Phalcon\Mvc\Model;

class Loans extends Model
{
    public $loan_id;
    public $employee_id;
    public $ammount;
    public $io_type;
    public $comment;
    public $regist_date;
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
     * @param $employee_id  社員Id
     * @return mixed Loansモデル
     */
    public function getBook($employee_id){
        return $reports = Loans::find(
            [
                'conditions' => 'employee_id = :employee_id:',
                "order" => "regist_date desc",
                "bind" => [
                    'employee_id' => $employee_id,
                ]
            ]);
    }

    /**
     * 貸付明細を取得します
     * @param $employee_id
     * @return mixed
     */
    public function getBookAssosiatePageer($employee_id, $offset=1, $limit=10){
        $offset -= 1;
        return $reports = Loans::find(
            [
                'conditions' => 'employee_id = :employee_id:',
                'order' => 'regist_date desc',
                'limit' => $limit,
                'offset' => $offset * $limit,
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

    /**
     * 新規に貸付明細を作成します
     * @param $employee_id  社員id
     * @param $date         明細日付
     * @param $type         1:貸付/2:返済
     * @param $amount       金額
     * @param $comment      明細コメント
     */
    public static function createLoan($employee_id, $regist_date, $type, $amount, $comment){
        $loan = new Loans();
        $loan->employee_id = $employee_id;
        $loan->ammount = $amount;
        $loan->io_type = $type;
        $loan->comment = $comment;
        $loan->regist_date = $regist_date;
        $loan->save();
    }
}