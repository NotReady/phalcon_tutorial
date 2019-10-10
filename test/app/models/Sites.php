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
}