<?php
/**
 * Created by PhpStorm.
 * User: notready
 * Date: 2019-10-22
 * Time: 01:36
 */

/**
 * Class ExtendedTimeForm
 * @note PhalconのDateフォームコンポーネントをTimeフォームにオーバーライドしたクラス
 */
class ExtendedTimeForm extends \Phalcon\Forms\Element\Date  {

    public function render($attributes = null) {

        $html = \Phalcon\Tag::timeField($this->prepareAttributes($attributes));

        return $html;
    }
}