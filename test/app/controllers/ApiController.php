<?php
use Phalcon\Mvc\Controller;

class ApiController extends Controller
{
    /**
     * JSONレスポンスヘッダのラッパ
     */
    private function jsonResponse($callble){
        header("Content-type: text/json; charset=UTF-8");
        $callble();
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
}