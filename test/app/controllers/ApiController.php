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
     * プレーンテキストレスポンスヘッダのラッパ
     */
    private function textResponse($callble){
        try{
            $callble();
            header("Content-type: text/plain; charset=UTF-8");
        }catch (Exception $e){
            header("HTTP/1.1 503 Service Unavailable");
        }
    }

    /**
     * 社員の登録アクション
     */
    public function createEmployeeAction(){
        $this->jsonResponse(function (){
            try{
                //$employee = Employees::createEmployee($params['employee_no'], $params['first_name'], $params['last_name']);

                $params = $this->request->getPost();
                $employee = new Employees();

                $form = new EmployeesCreateForm();
                $form->bind($params, $employee);

                 // バリデーション
                if( $form->isValid() === false )
                {
                    $invalidMessages = $form->getMessages();
                    $raiseMessages = [];
                    foreach ($invalidMessages as $message) {
                        $raiseMessages[] = $message->getMessage();
                    }
                    throw new KVSExtendedException($raiseMessages);
                }

                if( $employee->save() === false ){ throw new Exception("登録に失敗しました"); }
                echo json_encode(['result' => 'success']);

            }catch (KVSExtendedException $e){
                echo json_encode([
                    'result' => 'failure',
                    'messages' => $e->getKVSStore()
                ]);
            }catch (Exception $e){
                echo json_encode([
                    'result' => 'failure',
                    'message' => $e->getMessage()
                ]);
            }
        });
    }

    /**
     * 現場の登録アクション
     */
    public function createSiteAction(){
        $this->jsonResponse(function (){
            try{

                $params = $this->request->getPost();
                $site = new Sites();

                $form = new SitesCreateForm();
                $form->bind($params, $site);

                // バリデーション
                if( $form->isValid() === false )
                {
                    $invalidMessages = $form->getMessages();
                    $raiseMessages = [];
                    foreach ($invalidMessages as $message) {
                        $raiseMessages[] = $message->getMessage();
                    }
                    throw new KVSExtendedException($raiseMessages);
                }

                if( $site->save() === false ){ throw new Exception("登録に失敗しました"); }
                echo json_encode(['result' => 'success']);

            }catch (KVSExtendedException $e){
                echo json_encode([
                    'result' => 'failure',
                    'messages' => $e->getKVSStore()
                ]);
            }catch (Exception $e){
                echo json_encode([
                    'result' => 'failure',
                    'message' => $e->getMessage()
                ]);
            }
        });
    }

    /**
     * 貸付の登録アクション
     * @todo 過去日付については給与明細の変更が発生するかもしれない
     */
    public function createLoanAction(){
        $this->jsonResponse(function (){
            try{
                $params = $this->request->getPost();
                $loan = Loans::createLoan($params['employee_id'], $params['date'], $params['type'], $params['amount'], $params['comment']);
                if( $loan->save() === false ){
                    throw new Exception("作成に失敗しました");
                }
                echo json_encode(['result' => 'success']);
            }catch (Exception $e){
                echo json_encode([
                    'result' => 'failure',
                    'message' => $e->getMessage()
                ]);
            }
        });
    }

    /**
     * 貸付の更新アクション
     * @todo 過去日付については給与明細の変更が発生するかもしれない
     */
    public function updateLoanAction(){
        $this->jsonResponse(function (){

            try{
                $params = $this->request->getPost();
                $loan = Loans::updateLoan($params['loan_id'], $params['employee_id'], $params['date'], $params['type'], $params['amount'], $params['comment']);
                if( $loan->save() === false ){
                    throw new Exception("更新に失敗しました");
                }
                echo json_encode(['result' => 'success']);
            }catch (Exception $e){
                echo json_encode([
                    'result' => 'failure',
                    'message' => $e->getMessage()
                ]);
            }
        });
    }

    /**
     * 貸付の削除アクション
     * @todo 過去日付については給与明細の変更が発生するかもしれない
     */
    public function deleteLoanAction(){
        $this->jsonResponse(function (){
            try{
                $params = $this->request->getPost();
                Loans::deleteLoan($params['loan_id']);
                echo json_encode(['result' => 'success']);
            }catch (Exception $e) {
                echo json_encode([
                    'result' => 'failure',
                    'message' => $e->getMessage()
                ]);
            }
        });
    }

    /**
     * 明細の取得アクション
     */
    public function getLoanWithMemberAction(){
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

    /**
     * 明細の取得アクション
     */
    public function getLoanWithIdAction(){
        $this->jsonResponse(function (){
            $params = $this->request->getPost();
            $loanId = $params['loan_id'];
            $loan = Loans::getLoanByLoanId($loanId);
            echo json_encode([
                'result' => 'success',
                'loan' => $loan,
            ]);
        });
    }

    public function updateSalaryAction(){
        $this->jsonResponse(function (){
            try{
                // パラメタ収集
                $year = $this->dispatcher->getParam('year');
                $month = $this->dispatcher->getParam('month');
                $employee_id = $this->dispatcher->getParam('employee_id');
                $params = $this->request->getPost();

                $sarary = Salaries::getSalaryByEmployeeAndDate($employee_id, $year, $month);
                $sarary->{$params['name']} = $params['value'];

                if( $sarary->save() === false ){
                    throw new Exception('更新に失敗しました。');
                }
                echo json_encode(['result' => 'success']);
            }catch (Exception $e){
                echo json_encode([
                    'result' => 'failure',
                    'message' => $e->getMessage()
                ]);

            }
        });
    }

    public function undoSalaryAction(){
        $this->jsonResponse(function (){
            try{
                // パラメタ収集
                $year = $this->dispatcher->getParam('year');
                $month = $this->dispatcher->getParam('month');
                $employee_id = $this->dispatcher->getParam('employee_id');
                $params = $this->request->getPost();

                $sarary = Salaries::getSalaryByEmployeeAndDate($employee_id, $year, $month);
                $sarary->{$params['name']} = null;

                if( $sarary->save() === false ){
                    throw new Exception('更新に失敗しました。');
                }
                echo json_encode(['result' => 'success']);
            }catch (Exception $e){
                echo json_encode([
                    'result' => 'failure',
                    'message' => $e->getMessage()
                ]);

            }
        });
    }

    /**
     * 勤務表の保存アクション
     */
    public function saveReportAction(){
        $this->jsonResponse(function (){
            try{
                $params = $this->request->getPost();
                $report = new Reports();
                $report->employee_id = $params['nm_employee_id'];
                $report->at_day = $params['nm_date'];
                $report->site_id = $params['nm_site_id'];
                $report->worktype_id = $params['nm_wtype_id'];
                $report->time_from = $params['nm_timefrom'];
                $report->time_to = $params['nm_timeto'];
                $report->breaktime = $params['nm_breaktime'];

                if( $report->save() === false ){
                    throw new Exception('更新に失敗しました。');
                }
                echo json_encode(['result' => 'success']);
            }catch (Exception $e){
                echo json_encode([
                    'result' => 'failure',
                    'message' => $e->getMessage()
                ]);
            }
        });
    }

    /**
     * 勤務表の削除アクション
     */
    public function deleteReportAction(){
        $this->jsonResponse(function (){
            try{
                $params = $this->request->getPost();
                $employeeId = $params['nm_employee_id'];
                $date = $params['nm_date'];
                $report = Reports::getReportByEmployeeAndDay($employeeId, $date);

                if( empty($report) === true ){
                    throw new Exception("データが存在しません。");
                }

                if( $report->delete() === false ){
                    throw new Exception("削除に失敗しました。");
                }
                echo json_encode(['result' => 'success']);
            }catch (Exception $e){
                echo json_encode([
                    'result' => 'failure',
                    'message' => $e->getMessage()
                ]);
            }
        });
    }
}