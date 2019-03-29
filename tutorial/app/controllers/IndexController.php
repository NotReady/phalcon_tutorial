<?php

use Phalcon\Mvc\Controller;

// viewの関連付けはindex(IndexController)/index(indexAction).phtml
class IndexController extends Controller
{
    public function indexAction()
    {
        echo '<h1>Hello!</h1>';
    }
}