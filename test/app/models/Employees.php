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

    public function getEmployeesWithLatestInput(){
        $query = new \Phalcon\Mvc\Model\Query(
            'select
              e.id as employee_id,
              e.first_name,
              e.last_name,
              e.created,
              max(r.at_day) as last_input_day
             from Employees e
             left join Reports r
             on e.id = r.employee_id
             group by e.id',
            $this->getDI()
        );

        $rows =  $query->execute();
        return $rows;

    }
}