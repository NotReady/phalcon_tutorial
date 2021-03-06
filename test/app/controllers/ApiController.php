<?php
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Model\Transaction\Manager as TransactionManager;
use Phalcon\Mvc\Model\Transaction\Failed as TransactionFailed;

class ApiController extends ControllerBase
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
     * 顧客の登録アクション
     */
    public function updateCustomerAction(){
        $this->jsonResponse(function (){
            try{

                $params = $this->request->getPost();
                $customer = new Customers();

                $form = new CustomerForm();
                $form->bind($params, $customer);

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

                if( $customer->save() === false ){ throw new Exception("登録に失敗しました"); }
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
     * 貸付明細の取得アクション
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
     * 貸付明細の取得アクション
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

    /**
     * 有給明細の取得アクション
     */
    public function getPaidHolidayOfEmployeeAction(){
        $this->jsonResponse(function (){
            $params = $this->request->getPost();
            $eid = $params['employee_id'];
            $page = $params['page'];
            $paidHolidays = PaidHolidays::getStatementOfEmplyoeePagingUnit($eid, $page);
            $json = json_encode($paidHolidays);
            echo json_encode([
                'result' => 'success',
                'holidays' => $json,
            ]);
        });
    }

    /**
     * 有給の登録アクション
     */
    public function createHolidayAction(){
        $this->jsonResponse(function (){
            try{
                $params = $this->request->getPost();
                $holiday = PaidHolidays::createHoliday($params['employee_id'], $params['date'], $params['type'], $params['amount'], $params['comment']);
                if( $holiday->save() === false ){
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
     * 有給の更新アクション
     */
    public function updateHolidayAction(){
        $this->jsonResponse(function (){

            try{
                $params = $this->request->getPost();
                $loan = PaidHolidays::updateHoliday($params['holiday_id'], $params['employee_id'], $params['date'], $params['type'], $params['amount'], $params['comment']);
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
     * 有給の削除アクション
     */
    public function deleteHolidayAction(){
        $this->jsonResponse(function (){
            try{
                $params = $this->request->getPost();
                PaidHolidays::deleteHoliday($params['holiday_id']);
                echo json_encode(['result' => 'success']);
            }catch (Exception $e) {
                echo json_encode([
                    'result' => 'failure',
                    'message' => $e->getMessage()
                ]);
            }
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
                $report = Reports::getReportAtDay($params['employee_id'], $params['at_day']);
                if( empty($report) ){
                    $report = $report = new Reports();
                }

                // リクエストにバインドする
                $report->assign($params);

                // フォームにバインドする
                $form = new ReportForm($report);
                $form->bind($params, $report);

                // バリデーション
                if( $form->isValid() === false ){
                    $invalidMessages = $form->getMessages();
                    $raiseMessages = [];
                    foreach ($invalidMessages as $message) {
                        $raiseMessages[] = $message->getMessage();
                    }
                    throw new KVSExtendedException($raiseMessages);
                }

                $transactionManager = new TransactionManager();
                $transaction = $transactionManager->get();

                // 有給
                if( $report->attendance === 'holidays' )
                {
                    if( empty($report->paid_holiday_id) ){
                        // 有給エンティティを作成する
                        $holiday = PaidHolidays::createHoliday($report->employee_id, $report->at_day, 2, 1, '有給消化');
                        $holiday->setTransaction($transaction);
                        if( $holiday->save() === false ){
                            throw new Exception('更新に失敗しました。');
                        }
                        $report->paid_holiday_id = $holiday->paid_holiday_id;
                    }

                    $report->site_id = null;
                    $report->worktype_id = null;
                    $report->breaktime = null;
                    $report->time_from = null;
                    $report->time_to = null;
                }
                else if( $report->attendance === 'holidays-half' )
                {
                    if( empty($report->paid_holiday_id) ){
                        // 有給エンティティを作成する
                        $holiday = PaidHolidays::createHoliday($report->employee_id, $report->at_day, 2, 0.5, '半日有給消化');
                        $holiday->setTransaction($transaction);
                        if( $holiday->save() === false ){
                            throw new Exception('更新に失敗しました。');
                        }
                        $report->paid_holiday_id = $holiday->paid_holiday_id;
                    }
                }
                else
                {
                    // 有給エンティティの削除
                    if( empty($report->paid_holiday_id) === false ){
                        $holiday = PaidHolidays::getStatementById($report->paid_holiday_id);
                        $holiday->setTransaction($transaction);
                        if( $holiday->delete() === false ){
                            throw new Exception('削除に失敗しました。');
                        }
                        $report->paid_holiday_id = null;
                    }

                    // 欠勤
                    if( $report->attendance === 'absenteeism'){
                        $report->site_id = null;
                        $report->worktype_id = null;
                        $report->breaktime = null;
                        $report->time_from = null;
                        $report->time_to = null;
                    }

                }

                // 保存
                $report->setTransaction($transaction);
                if( $report->save() === false ){
                    throw new Exception('更新に失敗しました。');
                }

                $transaction->commit();

                echo json_encode(['result' => 'success']);

            }catch (KVSExtendedException $e){
                echo json_encode([
                    'result' => 'failure',
                    'message' => $e->getKVSStore()
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
     * 勤務表の削除アクション
     */
    public function deleteReportAction(){
        $this->jsonResponse(function (){
            try{
                $params = $this->request->getPost();
                $employeeId = $params['employee_id'];
                $date = $params['at_day'];
                $report = Reports::getReportByEmployeeAndDay($employeeId, $date);

                if( empty($report) === true ){
                    throw new Exception("データが存在しません。");
                }

                $transactionManager = new TransactionManager();
                $transaction = $transactionManager->get();

                // 有給エンティティの削除
                if( empty($report->paid_holiday_id) === false ){
                    $holiday = PaidHolidays::getStatementById($report->paid_holiday_id);
                    $holiday->setTransaction($transaction);
                    if( $holiday->delete() === false ){
                        throw new Exception('削除に失敗しました。');
                    }
                    $report->paid_holiday_id = null;
                }

                $report->setTransaction($transaction);
                if( $report->delete() === false ){
                    throw new Exception("削除に失敗しました。");
                }

                $transaction->commit();

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
     * 勤務表の作業分類取得
     */
    public function getWorkTypeListAction(){
        $this->jsonResponse(function (){
            try{
                $params = $this->request->getPost();
                $employee_id = $params['employee_id'];
                $site_id = $params['site_id'];

                $worktypes = Worktypes::getWorkTypesByEmployeeAtSite($employee_id, $site_id);

                if( empty($worktypes) === true ){
                    throw new Exception("登録作業がありません");
                }

                echo json_encode([
                    'result' => 'success',
                    'worktypes' => $worktypes
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
     * 現場-作業種別-職能ごとの時給と請求単価を取得します
     */
    public function getHourlyChargeAction(){
        $this->jsonResponse(function (){
            try{
                $params = $this->request->getPost();
                $siteId = $params['site_id'];
                $workId = $params['work_id'];

                // 現場-作業種別-能力給ごとの時給一覧
                $hourlyCharges = HourlyCharges::getHourlyChargeBySiteWork($siteId, $workId);
                // 現場-作業種別の請求単価
                $siteRelWorktype = SiteRelWorktypes::getEntity($siteId, $workId);

                echo json_encode([
                    'result' => 'success',
                    'hourly_charge' => $hourlyCharges->toArray(),
                    'hourly_bill' => $siteRelWorktype->hourly_bill_amount
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
     * 時給を更新します
     */
    public function updateHourlyChargeAction(){
        $this->jsonResponse(function (){
            try{
                $params = $this->request->getPost();
                $siteId = $params['site_id'];
                $workId = $params['work_id'];
                $skillId = $params['skill_id'];
                $charge = $params['charge'];
                HourlyCharges::updateHourlyCharge($siteId, $workId, $skillId, $charge);

                echo json_encode([
                    'result' => 'success',
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
     * 時給を削除します
     */
    public function deleteHourlyChargeAction(){
        $this->jsonResponse(function (){
            try{
                $params = $this->request->getPost();
                $siteId = $params['site_id'];
                $workId = $params['work_id'];
                $skillId = $params['skill_id'];
                HourlyCharges::deleteHourlyCharge($siteId, $workId, $skillId);

                echo json_encode([
                    'result' => 'success',
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
     * 請求単価を更新します
     */
    public function updateHourlyBillAction(){
        $this->jsonResponse(function (){
            try{
                $params = $this->request->getPost();
                $siteId = $params['site_id'];
                $workId = $params['work_id'];
                $hourly_bill = $params['bill'];
                SiteRelWorktypes::saveEntity($siteId, $workId, $hourly_bill);
                echo json_encode([
                    'result' => 'success',
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
     * 請求単価を削除します
     */
    public function deleteHourlyBillAction(){
        $this->jsonResponse(function (){
            try{
                $params = $this->request->getPost();
                $siteId = $params['site_id'];
                $workId = $params['work_id'];
                SiteRelWorktypes::deleteEntity($siteId, $workId);

                echo json_encode([
                    'result' => 'success',
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
     * 現場に作業種別を追加します
     */
    public function associateWorkAction(){
        $this->jsonResponse(function (){
            try{
                $params = $this->request->getPost();
                $siteId = $params['site_id'];
                $workId = $params['work_id'];

                SiteRelWorktypes::saveEntity($siteId, $workId, null);

                echo json_encode([
                    'result' => 'success',
                ]);
            }catch (Exception $e){
                echo json_encode([
                    'result' => 'failure',
                    'message' => $e->getMessage()
                ]);
            }
        });
    }

}