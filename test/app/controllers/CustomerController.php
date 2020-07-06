<?php
use Phalcon\Mvc\Controller;

class CustomerController extends Controller
{
    /**
     * 顧客一覧アクション
     */
    public function indexAction()
    {
        // 顧客一覧
        $customers = Customers::getCustomers();
        $this->view->customers = $customers;

        // 現場登録フォーム
        $customers = new Customers();
        $form = new CustomerForm($customers);
        $this->view->form = $form;
    }
}