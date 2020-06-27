<?php

/**
 * Class KVSExtendedException
 * @note 配列拡張の例外クラス
 */
class KVSExtendedException extends Exception
{
    private $_arraiableMessage;

    public function __construct($arraiableMessage){
        $this->_arraiableMessage = $arraiableMessage;
    }

    public function getKVSStore(){
        return $this->_arraiableMessage;
    }
}