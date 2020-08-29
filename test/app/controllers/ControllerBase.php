<?php
use Phalcon\Mvc\Controller;

class ControllerBase extends Controller
{
    /**
     * あらゆるアクションの前に実行されます
     * @param $dispatcher
     * @return bool
     */
    public function beforeExecuteRoute($dispatcher)
    {
        Logger::put($this->request->getURI());

        // ログイン状態をチェック
        $accessible = LoginManager::isAccessible();
        if( $accessible === false ){
            return $this->response->redirect('/login');
        }
    }
}