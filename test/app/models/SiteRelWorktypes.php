<?php
use Phalcon\Mvc\Model;

class SiteRelWorktypes extends Model
{
    /**************** properties ****************/

    /**
     * @var 現場ID
     */
    public $site_id;

    /**
     * @var 作業分類ID
     */
    public $worktype_id;

    public $created;
    public $updated;

    /**************** methods ****************/

    /**
     * 現場の登録作業を取得します
     * @param $siteId
     */
    public static function getWorktypesBySite($site_id){
        $query = '
                select
                    srw.site_id,
                    srw.worktype_id,
                    w.name
                from
                  site_rel_worktypes srw
                join
                  worktypes w on w.id = srw.worktype_id
                where
                  srw.site_id = :site_id';

        $proxy = new SiteRelWorktypes();
        $result = new \Phalcon\Mvc\Model\Resultset\Simple(null, $proxy,
            $proxy->getReadConnection()->query($query, ['site_id' => $site_id]));

        return $result;

    }

    public function initialize(){
        $this->skipAttributes(
            [
                'created',
                'updated',
            ]
        );
    }
}