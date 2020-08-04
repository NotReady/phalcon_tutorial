<?php
use Phalcon\Mvc\Model;

class PaidHolidays extends Model
{
    /**
     * @var サロゲートID
     */
    public $paid_holiday_id;
    /**
     * @var Employees.id
     */
    public $employee_id;
    /**
     * @var 金額
     */
    public $amount;
    /**
     * @var 1:付与 / 2:使用
     */
    public $io_type;
    /**
     * @var コメント
     */
    public $comment;
    /**
     * @var 登録日
     */
    public $regist_date;
    /**
     * @var 更新日
     */
    public $created;
    /**
     * @var 更新日
     */
    public $updated;

    public function initialize(){
        $this->skipAttributes(
            [
                'created',
            ]
        );
    }

    public function getSource(){
        return 'paid_holidays';
    }

    /**
     * サロゲートキーから有給明細モデルを取得します
     * @param integer $holiday_id サロゲートキー
     * @return PaidHolidays 有給明細モデル
     */
    public static function getStatementById($holiday_id){
        return $reports = self::findFirst(
            [
                'conditions' => 'paid_holiday_id = :paid_holiday_id:',
                "bind" => [
                    'paid_holiday_id' => $holiday_id,
                ]
            ]);
    }

    /**
     * 従業員の全有給管理明細を取得します
     * @param $employee_id 従業員Id employees.id
     * @return mixed
     */
    public static function getStatementOfEmplyoee($employee_id){
        return $reports = self::find(
            [
                'conditions' => 'employee_id = :employee_id:',
                "order" => "regist_date desc",
                "bind" => [
                    'employee_id' => $employee_id,
                ]
            ]);
    }

    /**
     * ページング単位で従業員の有給管理明細を取得します
     * @param $employee_id 従業員Id employees.id
     * @param int $offset ページオフセット
     * @param int $limit ステートメント数
     * @return mixed
     */
    public static function getStatementOfEmplyoeePagingUnit($employee_id, $offset=1, $limit=10){
        $offset -= 1;
        return self::find(
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
     * 従業員の残り有給日数を取得します
     * @param $employee_id 従業員Id
     * @return mix
     */
    public static function getCountOfRemainHolidays($employee_id){
        $query = "select
                    0 + sum(case when ph.io_type = 1 then ph.amount else 0 end) - sum(case when ph.io_type = 2 then ph.amount else 0 end) as amount
                  from
                    paid_holidays ph
                  where ph.employee_id = :employee_id
                  ";

        $mo = new PaidHolidays();
        $result = new \Phalcon\Mvc\Model\Resultset\Simple(null, $mo,
            $mo->getReadConnection()->query($query, [
                'employee_id' => $employee_id,
            ]));

        return count($result) == 0 ? false: $result[0]->amount;
    }

    /**
     * 新規に有給明細を作成します
     * @param $employee_id  社員id
     * @param $regist_date  登録日付
     * @param $type         1:付与/2:消化
     * @param $amount       日数
     * @param $comment      明細コメント
     * @throws Exception
     * @return PaidHolidays 貸付モデル
     */
    public static function createHoliday($employee_id, $regist_date, $type, $amount, $comment){

        $remainHolidaysCount = self::getCountOfRemainHolidays($employee_id);
        if( $type == 2 ){
            if( $remainHolidaysCount < $amount ){
                throw new Exception('余剰有給が不足しています。');
            }
        }

        $holiday = new PaidHolidays();
        $holiday->employee_id = $employee_id;
        $holiday->amount = $amount;
        $holiday->io_type = $type;
        $holiday->comment = $comment;
        $holiday->regist_date = $regist_date;
        return $holiday;
    }

    /**
     * 有給明細を更新します
     * @param $holiday_id   サロゲートキー
     * @param $employee_id  社員id
     * @param $regist_date  登録日付
     * @param $type         1:付与/2:消化
     * @param $amount       日数
     * @param $comment      コメント
     * @return PaidHolidays 有給モデル
     * @throws Exception
     */
    public static function updateHoliday($holiday_id, $employee_id, $regist_date, $type, $amount, $comment){

        $holiday = self::getStatementById($holiday_id);

        if( empty($holiday) === true ){
            throw new Exception('データが存在しません');
        }

        if( empty(Reports::getStatementByHolidayId($holiday_id)) === false ){
            throw new Exception('勤務表に登録されているため削除できません');
        }

        $srcIoType = $holiday->io_type;
        $srcAmount = $holiday->amount;

        // 余剰有給のチェック
        $remainHolidaysCount = self::getCountOfRemainHolidays($employee_id) + ( ( $srcIoType == 1 ) ? -$srcAmount : $srcAmount );
        $remainHolidaysCount += $type == 1 ? $amount : -$amount;
        if( $remainHolidaysCount < 0 ){
            throw new Exception('余剰有給が不足するため更新できません。');
        }

        $holiday->employee_id = $employee_id;
        $holiday->amount = $amount;
        $holiday->io_type = $type;
        $holiday->comment = $comment;
        $holiday->regist_date = $regist_date;

        return $holiday;
    }

    /**
     * 有給明細を削除します
     * @param $holiday_id サロゲートキー
     * @throws Exception
     */
    public static function deleteHoliday($holiday_id){

        $holiday = self::getStatementById($holiday_id);

        if( empty($holiday) === true ){
            throw new Exception('データが存在しません');
        }

        if( empty(Reports::getStatementByHolidayId($holiday_id)) === false ){
            throw new Exception('勤務表に登録されているため削除できません');
        }

        // 余剰有給のチェック
        $remainHolidaysCount =
            self::getCountOfRemainHolidays($holiday->employee_id) + ( ( $holiday->io_type == 1 ) ? -$holiday->amount : $holiday->amount );
        if( $remainHolidaysCount < 0 ){
            throw new Exception('余剰有給が不足するため削除できません。');
        }

        if( $holiday->delete() === false ){
            throw new Exception('削除に失敗しました');
        }
    }
}