<?php
use Phalcon\Mvc\Model;

class Customers extends Model
{
    /**
     * @var サロゲートキー
     */
    public $id;

    /**
     * @var 顧客名
     */
    public $name;

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