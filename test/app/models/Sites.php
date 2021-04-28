<?php
use Phalcon\Mvc\Model;

class Sites extends Model
{
    /**
     * @var サロゲートキー
     */
    public $id;

    /**
     * @var 顧客ID
     */
    public $customer_id;

    /**
     * @var 現場名
     */
    public $sitename;

    /**
     * @var 契約種別
     */
    public $business_type;

    /**
     * @var 業務開始時間
     */
    public $time_from;

    /**
     * @var 業務終了時間
     */
    public $time_to;

    /**
     * @var 休憩開始時間
     */
    public $breaktime_from;

    /**
     * @var 休憩終了時間
     */
    public $breaktime_to;

    /**
     * @var 月額請負金額
     */
    public $monthly_bill_amount;

    public $created;

    public $updated;

    const BUSINESS_TYPE_MAP = [
        'takeup' => '請負契約',
        'spot' => '派遣契約',
    ];

    public function initialize(){
        $this->skipAttributes(
            [
                'created',
                'updated',
            ]
        );
    }

    public static function getSerializeFormData(){
        $selectable = [];
        foreach ( self::find(['columns' => 'id, sitename']) as $site) {
            $selectable[$site->id] = $site->sitename;
        }
        return $selectable;
    }

    /**
     * サイトとカスタマーの結合エンティティを取得します
     * @return mixed
     */
    public function getSitesWithCustomer(){
        $query = new \Phalcon\Mvc\Model\Query(
            'select
              s.id as site_id,
              s.sitename,
              s.business_type,
              s.time_from,
              s.time_to,
              s.breaktime_from,
              s.breaktime_to,
              s.monthly_bill_amount,
              s.created,
              s.updated,
              c.id as customer_id,
              c.name as customername
             from Sites s
             left join Customers c
             on s.customer_id = c.id',
            $this->getDI()
        );

        $rows =  $query->execute();
        return $rows;
    }
}