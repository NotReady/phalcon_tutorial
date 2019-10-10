<?php

use Phalcon\Mvc\Controller;


class DevController extends Controller{

    // /dev/make
    public function makeAction(){

        $sei = [
            '大橋', '木下', '野々宮', '山田', '藤原'
        ];

        $mei = [
            '正明', '花子', '健太', '太郎', '次郎', 'サンタ'
        ];

        //$fp = fopen('source.sql', 'w');
        $file = new SplFileObject('./source.sql', 'w');

        foreach (range(1,100) as $count){
            $query = 'insert into step3.employees' .
            '(`name_sei`, `name_mei`)' .
            ' values( ';
            $query .= '"' . $sei[rand(1, rand(0, count($sei)-1))] . '"';
            $query .= ', "' . $mei[rand(1, rand(0, count($mei)-1))] . '");';
            $query .= "\n";

            $file->fwrite($query);
            echo $query;
        }

        echo 'completed!';
    }

}