<?php
use Phalcon\Mvc\Controller;

class ApiController extends Controller
{
    /**
     * JSONレスポンスヘッダのラッパ
     */
    private function jsonResponse($callble){
        try{
            $callble();
            header("Content-type: text/json; charset=UTF-8");
        }catch (Exception $e){
            header("HTTP/1.1 503 Service Unavailable");
        }
    }

    /**
     * 貸付の登録アクション
     * @todo 過去日付については給与明細の変更が発生するかもしれない
     */
    public function addLoanAction(){
        $this->jsonResponse(function (){
            $params = $this->request->getPost();
            Loans::createLoan($params['employee_id'], $params['date'], $params['type'], $params['amount'], $params['comment']);
            echo json_encode(['result' => 'success']);
        });
    }

    /**
     * 明細の取得アクション
     */
    public function getLoanAction(){
        $this->jsonResponse(function (){
            $params = $this->request->getPost();
            $eid = $params['employee_id'];
            $page = $params['page'];
            $assosiagePageRows = Loans::getBookAssosiatePageer($eid, $page);
            $json = json_encode($assosiagePageRows);
            echo json_encode([
                'result' => 'success',
                'loans' => $json,
            ]);
        });
    }

    public function updateSalaryAction(){
        $this->jsonResponse(function (){
            // パラメタ収集
            $year = $this->dispatcher->getParam('year');
            $month = $this->dispatcher->getParam('month');
            $employee_id = $this->dispatcher->getParam('employee_id');
            $params = $this->request->getPost();

            $sarary = Salaries::getSalaryByEmployeeAndDate($employee_id, "${year}/${month}/01");
            $sarary->{$params['name']} = $params['value'];

            if( $sarary->save() === false ){
                throw new Exception();
            }

        });
    }

    public function undoSalaryAction(){
        $this->jsonResponse(function (){
            // パラメタ収集
            $year = $this->dispatcher->getParam('year');
            $month = $this->dispatcher->getParam('month');
            $employee_id = $this->dispatcher->getParam('employee_id');
            $params = $this->request->getPost();

            $sarary = Salaries::getSalaryByEmployeeAndDate($employee_id, "${year}/${month}/01");
            $sarary->{$params['name']} = null;

            if( $sarary->save() === false ){
                throw new Exception();
            }

        });
    }
}