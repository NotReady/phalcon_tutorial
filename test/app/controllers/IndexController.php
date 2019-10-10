<?php

use phalcon\Mvc\Controller;


class IndexController extends Controller{

    public function indexAction(){

        echo 'indexAction Started';

        //$this->view->disable();

        // hasMany
//        $emps = Employees::find();
//        foreach ($emps as $emp){
//            foreach ($emp->reports as $rep)
//            echo $rep->at_day;
//        }

        // belongsTo
//        $repo = Reports::findfirst(1);
//        echo $repo->employees->first_name;

        $report = Reports::getReport(1, 2019, 4);

        $this->view->reports = $report;

        echo '<br>' . 'indexAction Completed';
    }

}