<?php

use Phalcon\Mvc\Controller;

class SignupController extends Controller
{
    public function indexAction()
    {
    }

    public function registerAction()
    {
        $user = new Users();

        $success = $user->save(
            $this->request->getPost(),
            [
                "name",
                "email",
            ]
        );

        if($success){
            //echo 'successed';
            // ルートにリダイレクトする
            $this->response->redirect();
        }else{
            echo 'failed';

            $messsges = $usre->getMessage();

            foreach($messages as $message){
                echo $message->getMessage(), '<br/>';
            }
        }

        $this->view->disable();

    }
}