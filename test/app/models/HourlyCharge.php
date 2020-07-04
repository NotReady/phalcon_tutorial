<?php
use Phalcon\Mvc\Model;

class HourlyCharge extends Model
{
    /**************** properties ****************/

    /**
     * @var 現場ID
     */
    public $site_id;

    /**
     * @var 作業分類ID
     */
    public $worktype_id;

    /**
     * @var 職能ID
     */
    public $skill_id;

    /**
     * @var 時給
     */
    public $value;

    public $created;
    public $updated;

    /**************** methods ****************/

    public function initialize(){
        $this->skipAttributes(
            [
                'created',
                'updated',
            ]
        );
    }
}