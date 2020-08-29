<?php

use Phalcon\Logger\Adapter\Stream as StreamAdapter;
use Phalcon\Logger\Formatter\Line as LineFormatter;
use Phalcon\DI\FactoryDefault;

class Logger
{
    public static function put($caption){

        $session_id = \Phalcon\DI::getDefault()->getSession()->getId();
        $session_id = substr($session_id, 0, 5);

        self::getAdapter()->log("[{$session_id}] {$caption}");
    }

    private static function getAdapter(){
        $formatter = new LineFormatter("[%date%] %message%");
        $formatter->setDateFormat('Y/m/d H:i:s');

        $logger = new StreamAdapter("php://stdout");
        $logger->setFormatter($formatter);

        return $logger;
    }
}