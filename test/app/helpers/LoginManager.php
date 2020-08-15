<?php
/**
 * Created by PhpStorm.
 * User: notready
 * Date: 2020-08-14
 * Time: 08:17
 */

class LoginManager
{
    /**
     * ログインします
     * @param $loginId
     * @param $password
     * @throws Exception ログイン失敗
     */
    public static function login($login_id, $password){

        $employee = Employees::getEmployeeByLoginId($login_id);
        if( empty($employee) ){
            throw new Exception('認証に失敗しました。');
        }

        $hash = $employee->password;

        $matched = password_verify($password, $hash);
        if( $matched === false ){
            throw new Exception('認証に失敗しました。');
        }

        $di = \Phalcon\DI::getDefault();
        $session = $di->getSession();

        $session->set('login_status', 'logged_in');
        $session->set('login_user', $employee->username);
        $session->set('service_role', $employee->service_role);
    }

    /**
     * ログアウトします
     */
    public static function logout(){
        $di = \Phalcon\DI::getDefault();
        $session = $di->getSession();
        $session->remove('login_status');
        $session->remove('login_user');
        $session->remove('service_role');
    }

    /**
     * リクエスト先がアクセス可能か判定します
     */
    public static function isAccessible(){


        $di = \Phalcon\DI::getDefault();
        $request = $di->getRequest();
        $request_url = $request->getURI();

        //--- test
        $session = $di->getSession();
        //$dispatcher = $di->getDispatcher();
        $session->set('service_role', 'admin');
        //$test = $session->get('test');
        //---

        // 未ログインはログインアクションのみ許容
        $logged_in = self::isLoggedIn();
        if( $logged_in === false ){
            $matched = preg_match('/^\/login/', $request_url);
            return empty($matched) ? false : true ;
        }

        $session = $di->getSession();
        $role = $session->get('service_role');

        // 管理者はすべてのアクションを許容
        if( $role === 'admin' ){
            return true;
        }

        // 一般アカウントは勤務表アクションのみ許容
        if( $role === 'user' ){
            $matched = preg_match('/^report\/user/', $request_url);
            return $matched;
        }

        return false;
    }

    /**
     * ログイン状態を判定します
     * @return bool
     */
    public static function isLoggedIn(){
        $di = \Phalcon\DI::getDefault();
        $session = $di->getSession();

        $loginStatus = $session->get('login_status');
        return $loginStatus === 'logged_in' ? true : false;
    }

    /**
     * ログインロールを取得します
     * @return mixed
     */
    public static function getRole(){
        $di = \Phalcon\DI::getDefault();
        $session = $di->getSession();

        $role = $session->get('service_role');
        return $role;
    }

    /**
     * ログインロールを取得します
     * @return mixed
     */
    public static function getUsername(){
        $di = \Phalcon\DI::getDefault();
        $session = $di->getSession();

        $username = $session->get('login_user');
        return $username;
    }

}