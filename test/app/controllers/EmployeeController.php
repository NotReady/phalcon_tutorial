<?php
use Phalcon\Mvc\Controller;

class EmployeeController extends Controller
{
    /**
     * 従業員一覧アクション
     */
    public function indexAction(){

        // 従業員一覧
        $employees = new Employees();
        $resultset = $employees->getEmployeesWithLatestInput();

        // viewパラメタ
        $this->view->employee_info = $resultset;
    }

    /**
     * 従業員属性編集アクション
     */
    public function editAction()
    {
        $employee = null;
        if( $this->session->has('editable') === true ){
            $employee = $this->session->get('editable');
            $this->session->remove('editable');
            $form = $employee;
        }else{
            $employee_id = $this->dispatcher->getParam('employee_id');
            $employee = Employees::findfirst($employee_id);
            $form = new EmployeesForm($employee);
        }

        $this->view->setVar('employee_id', $employee->id);

        $this->view->form = $form;
        $l = Loans::getBookAssosiatePageer($employee->id);
        $this->view->loans = Loans::getBook($employee->id);
        $this->view->loansAmount = Loans::getAmount($employee->id);
    }

    /**
     * 従業員属性変更要求アクション
     */
    public function editCheckAction()
    {
        $form = new EmployeesForm();
        $employee = new Employees();

        $params = $this->request->getPost();
        $form->bind($params, $employee);

        // バリデーションガード
        if( $form->isValid() === false )
        {
            $this->session->set('editable', $form);
            return $this->dispatcher->forward([
                'controller' => 'Employee',
                'action' => 'edit'
            ]);
        }

        if( $employee->save() === false )
        {
            $this->session->set('editable', $form);
            return $this->dispatcher->forward([
                'controller' => 'Employee',
                'action' => 'edit'
            ]);

        }

        return $this->dispatcher->forward([
            'controller' => 'Employee',
            'action' => 'index'
        ]);

    }

    /**
     * 貸付の登録アクション
     */
    public function addLoanAction(){
        $params = $this->request->getPost();
    }
}