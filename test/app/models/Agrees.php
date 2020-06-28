<?php
use Phalcon\Mvc\Model;

class Agrees extends Model
{
    /**
     * @var サロゲートキー
     */
    public $id;

    /**
     * @var 現場ID
     */
    public $site_id;

    /**
     * @var 作業分類ID
     */
    public $worktype_id;

    /**
     * @var 時給
     */
    public $price;

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