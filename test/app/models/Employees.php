<?php

use Phalcon\Mvc\Model;

class Employees extends Model{
    public $id;
    // 性
    public $first_name;
    // 名
    public $last_name;
    // 交通費
    public $Transportation_expenses;
    public $created;
    public $updated;

    public function initialize(){
        $this->hasMany('id', 'Reports', 'employee_id');
    }
}