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

    public $created;
    public $updated;

    public function initialize(){
        $this->skipAttributes(
            [
                'created',
                'updated',
            ]
        );
    }

    public function getSitesWithCustomer(){
        $query = new \Phalcon\Mvc\Model\Query(
            'select
              s.id as site_id,
              s.sitename,
              s.time_from,
              s.time_to,
              s.breaktime_from,
              s.breaktime_to,
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