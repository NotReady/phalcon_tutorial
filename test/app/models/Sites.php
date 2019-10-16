<?php
use Phalcon\Mvc\Model;

class Sites extends Model
{
    public $id;
    public $customer_id;
    public $sitename;
    public $time_from;
    public $time_to;
    public $breaktime_from;
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