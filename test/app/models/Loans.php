<?php
use Phalcon\Mvc\Model;

class Loans extends Model
{
    /**
     * @var サロゲートID
     */
    public $loan_id;
    /**
     * @var Employees.id
     */
    public $employee_id;
    /**
     * @var 金額
     */
    public $amount;
    /**
     * @var 1:貸付 / 2:返済
     */
    public $io_type;
    /**
     * @var コメント
     */
    public $comment;
    /**
     * @var Salaries.salary_id
     */
    public $salary_id;
    /**
     * @var 登録日
     */
    public $regist_date;
    /**
     * @var 更新日
     */
    public $created;

    public function initialize(){
        $this->skipAttributes(
            [
                'created',
            ]
        );
    }

    /**
     * サロゲートキーから貸付明細を取得します
     * @param integer $loan_id サロゲートキー
     * @return Loans 貸付モデル
     */
    public static function getLoanByLoanId($loan_id){
        return $reports = Loans::findFirst(
            [
                'conditions' => 'loan_id = :loan_id:',
                "bind" => [
                    'loan_id' => $loan_id,
                ]
            ]);
    }

    /**
     * 貸付明細を取得します
     * @param $employee_id  社員Id
     * @return mixed Loansモデル
     */
    public static function getBook($employee_id){
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
     * 給与控除に含まれる貸付明細を取得します
     * @param $salary_id  給与Id
     * @return Loans 貸付モデル
     */
    public static function getLoanBySalaryId($salary_id){
        return $reports = Loans::findFirst(
            [
                'conditions' => 'salary_id = :salary_id:',
                "bind" => [
                    'salary_id' => $salary_id,
                ]
            ]);
    }

    /**
     * 貸付明細を取得します
     * @param $employee_id
     * @return mixed
     */
    public static function getBookAssosiatePageer($employee_id, $offset=1, $limit=10){
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
    public static function getAmount($employee_id){
        $query = "select
                    0 + sum(case when lo.io_type = 1 then lo.amount else 0 end) - sum(case when lo.io_type = 2 then lo.amount else 0 end) as amount
                  from
                    loans lo
                  where lo.employee_id = :employee_id
                  ";

        $mo = new Loans();
        $result = new \Phalcon\Mvc\Model\Resultset\Simple(null, $mo,
            $mo->getReadConnection()->query($query, [
                'employee_id' => $employee_id,
            ]));

        return count($result) == 0 ? false: $result[0]->amount;
    }

    /**
     * 新規に貸付明細を作成します
     * @param $employee_id  社員id
     * @param $date         明細日付
     * @param $type         1:貸付/2:返済
     * @param $amount       金額
     * @param $comment      明細コメント
     * @return Loans 貸付モデル
     */
    public static function createLoan($employee_id, $regist_date, $type, $amount, $comment, $salary_id=null){

        $loan = new Loans();
        $loan->employee_id = $employee_id;
        $loan->amount = $amount;
        $loan->io_type = $type;
        $loan->comment = $comment;
        $loan->regist_date = $regist_date;
        $loan->salary_id = $salary_id;
        return $loan;
    }

    /**
     * 新規に貸付明細を更新します
     * @param integer   $loan_id      貸付モデルサロゲートキー
     * @param integer   $employee_id  社員id
     * @param date      $date         明細日付
     * @param integer   $type         1:貸付/2:返済
     * @param integer   $amount       金額
     * @param string    $comment      明細コメント
     * @return Loans 貸付モデル
     */
    public static function updateLoan($loan_id, $employee_id, $regist_date, $type, $amount, $comment){

        $loan = Loans::getLoanByLoanId($loan_id);

        if( empty($loan) === true ){
            throw new Exception('貸付が存在しません');
        }

        if( empty($loan->salary_id) === false ){
            throw new Exception('確定済みの給与に登録されているため削除できません');
        }

        $loan->employee_id = $employee_id;
        $loan->amount = $amount;
        $loan->io_type = $type;
        $loan->comment = $comment;
        $loan->regist_date = $regist_date;

        return $loan;
    }

    /**
     * 新規に貸付明細を削除します
     * @param integer   $loan_id      貸付モデルサロゲートキー
     * @return none
     */
    public static function deleteLoan($loan_id){

        $loan = Loans::getLoanByLoanId($loan_id);
        if( empty($loan) === true ){
            throw new Exception('貸付が存在しません');
        }

        if( empty($loan->salary_id) === false ){
            throw new Exception('確定済みの給与に登録されているため削除できません');
        }

        if( $loan->delete() === false ){
            throw new Exception('削除に失敗しました');
        }

    }
}