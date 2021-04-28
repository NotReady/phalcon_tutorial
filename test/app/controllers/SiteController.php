<?php
use Phalcon\Mvc\Controller;

class SiteController extends ControllerBase
{
    /**
     * 現場一覧アクション
     */
    public function indexAction(){

        // 現場一覧
        $site = new Sites();
        $resultSet = $site->getSitesWithCustomer();
        $this->view->site_info = $resultSet;

        // 現場登録フォーム
        $sites = new Sites();
        $sitesCreateForm = new SitesCreateForm($sites);
        $this->view->form = $sitesCreateForm;
    }

    /**
     * 現場編集アクション
     */
    public function editAction()
    {
        $site_id = $this->dispatcher->getParam('site_id');

        // site_id
        $this->view->site_id = $site_id;

        // フォーム
        if( $this->session->has('site_edit_form') === true ) {
            $form = $this->session->get('site_edit_form');
            $this->session->remove('site_edit_form');
        }else{
            $site = Sites::findFirst($site_id);
            $form = new SitesCreateForm($site, $site->toArray());
        }
        $this->view->form = $form;

        // 登録作業
        $workTypes = SiteRelWorktypes::getWorktypesBySite($site_id);
        $this->view->work_types = $workTypes;

        // 未登録作業
        $addWorkTypes = SiteRelWorktypes::getNotAssignWorktypesBySite($site_id);
        $this->view->add_work_types = $addWorkTypes;
    }

    /**
     * 現場の更新
     */
    public function editCheckAction(){
        $params = $this->request->getPost();
        $form = new SitesCreateForm(null, $params);
        $site = new Sites();
        $form->bind($params, $site);

        try{
            // バリデーション
            if( $form->isValid() === false )
            {
                throw new Exception();
            }

            // 永続化
            if( $site->save() === false )
            {
                throw new Exception();
            }

        }catch (Exception $e){
            // インバリデーションフォームをセッションで連携
            $this->session->set('site_edit_form', $form);
        }

        return $this->response->redirect("/sites/edit/{$site->id}");
    }
}