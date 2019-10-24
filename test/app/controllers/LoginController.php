<?php
use Phalcon\Mvc\Controller;

class LoginController extends Controller
{
    /**
     * ログインページアクション
     */
    public function indexAction(){

        // ログインエラー
        if( $this->session->has('login_failure_message') ){
            $this->view->login_failure_message = $this->session->get('login_failure_message');
            $this->session->remove('login_failure_message');
        }


    }

    /**
     * ログイン認証アクション
     */
    public function loginCheckAction(){

        try{

            // csfr
            if (!$this->security->checkToken()) {
                throw new Exception('不正な処理です。再度ログインしてください。');
            }

            $params = $this->request->getPost();
            $employee = Employees::authorization($params['username'], $params['password']);

            // authorization
            if( empty($employee) === true ){
                throw new Exception('認証に失敗しました。再度ログインしてください。');
            }

            return $this->response->redirect('/');

        }catch(Exception $e){
            $this->session->set('login_failure_message', [$e->getMessage()]);
            return $this->response->redirect('/login');
        }
    }
}