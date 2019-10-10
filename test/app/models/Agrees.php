<?php
use Phalcon\Mvc\Model;

class Agrees extends Model
{
    public $id;
    public $site_id;
    public $worktype_id;
    public $price;
    public $created;
    public $updated;
}