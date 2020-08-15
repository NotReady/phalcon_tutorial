<?php
use Phalcon\Mvc\Controller;

class LoginController extends ControllerBase
{
    /**
     * ログインページアクション
     */
    public function indexAction(){

        LoginManager::logout();

        $form = new LoginForm();

        if( $this->session->has('login_form') ){

            $form = $this->session->get('login_form');
            $params = $this->session->get('login_params');
            $this->session->remove('login_form');
            $this->session->remove('login_params');

            $params['password'] = '';
            $form->bind($params, $form);
        }

        $this->view->form = $form;

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

        $params = $this->request->getPost();
        $form = new LoginForm();

        try{

            // csfr
            if ( $this->security->checkToken() === false ) {
                throw new Exception('不正な処理です。再度ログインしてください。');
            }

            $form->bind($params, new Employees());

            if( $form->isValid() === false ){
                throw new Exception();
            }

            LoginManager::login($params['username'], $params['password']);

            $this->session->remove('login_form');

            // 一般ユーザは勤務表入力へリダイレクト
            $role = LoginManager::getRole();
            if( $role === 'user' ){
                return $this->response->redirect('/');
            }

            // 管理者はルートへリダイレクト
            return $this->response->redirect('/');

        }catch(Exception $e){
            $this->session->set('login_form', $form);
            $this->session->set('login_params', $params);
            $this->session->set('login_failure_message', $e->getMessage());
            return $this->response->redirect('/login');
        }
    }
}