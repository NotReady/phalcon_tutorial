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
        $employeesCreateForm = new EmployeesCreateForm($employees);
        $this->view->form = $employeesCreateForm;

        $resultset = $employees->getEmployeesWithLatestInput();
        $this->view->employee_info = $resultset;
    }

    /**
     * 従業員属性編集アクション
     */
    public function editAction()
    {
        $employee_id = $this->dispatcher->getParam('employee_id');
        $this->view->setVar('employee_id', $employee_id);

        $employee = null;

        if( $this->session->has('employee_edit_form') === true ){
            $form = $this->session->get('employee_edit_form');
            $this->session->remove('employee_edit_form');
        }else{
            $employee = Employees::findfirst($employee_id);
            $form = new EmployeesForm($employee);
        }

        $this->view->form = $form;
        $this->view->loans = Loans::getBook($employee_id);
        $this->view->loansAmount = Loans::getAmount($employee_id);
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

        try{
            // バリデーションガード
            if( $form->isValid() === false )
            {
                throw new Exception();
            }

            if( $employee->save() === false )
            {
                throw new Exception();
            }

        }catch (Exception $e){
            $this->session->set('employee_edit_form', $form);
        }

        return $this->response->redirect("/employees/edit/{$employee->id}");
    }

    /**
     * 貸付の登録アクション
     */
    public function addLoanAction(){
        $params = $this->request->getPost();
    }
}