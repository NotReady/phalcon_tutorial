<?php
use Phalcon\Mvc\Controller;

class SiteController extends Controller
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
        $site = Sites::getSiteById($site_id);
        $form = new SitesCreateForm($site);
        $this->view->form = $form;

        // 登録作業
        $worktypes = SiteRelWorktypes::getWorktypesBySite($site_id);
        $this->view->work_types = $worktypes;
    }

}