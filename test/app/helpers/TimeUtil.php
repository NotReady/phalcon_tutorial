<?php
/**
 * Created by PhpStorm.
 * User: notready
 * Date: 2019-10-22
 * Time: 01:36
 */

class TimeUtil
{
    private $_hour;
    private $_minute;

    public static function makeFromTimeStr($timestr){
        $hm = explode(':', $timestr);
        return new TimeUtil($hm[0], $hm[1]);
    }

    public function __construct($hour=0, $_minute=0)
    {
        $this->_hour = $hour;
        $this->addMinuteToHour($_minute);
    }

    /**
     * 時分の文字列で取得します
     * @return string
     */
    public function getTimeStr(){
        return sprintf("%02d:%02d", $this->_hour, $this->_minute);
    }

    /**
     * 秒数を取得します
     */
    public function getSeconds(){
        return ( $this->_hour * 60 * 60 ) + ( $this->_minute * 60 );
    }

    public function addTimeStr($timeStr){
        $hm = explode(':', $timeStr);
        $this->addHour($hm[0]);
        $this->addMinuteToHour($hm[1]);
        return $this->getTimeStr();
    }

    public function getHour(){return $this->_hour;}
    public function addHour($hour){
        $this->_hour += $hour;
        return $this->_hour;
    }

    public function getMinute(){return $this->_minute;}
    public function addMinute($minute){
        return $this->addMinuteToHour($minute);
    }

    private function addMinuteToHour($minute){
        $this->_hour += floor( ( $this->_minute + $minute ) / 60);
        $this->_minute = ( $this->_minute + $minute ) % 60;
        return $this->_minute;
    }
}