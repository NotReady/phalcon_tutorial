<?php
/**
 * Created by PhpStorm.
 * User: notready
 * Date: 2019-04-30
 * Time: 19:20
 */
use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\TextArea;

class ReportForm extends Form
{
    public function initialize(){

        $time_from = new Text('time_from', array());
        $this->add($time_from);

        $time_to = new Text('time_to', array());
        $this->add($time_to);

        $breaktime = new Text('breaktime', array());
        $this->add($breaktime);

    }
}