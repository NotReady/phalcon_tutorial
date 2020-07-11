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

    public function initialize(){
        $this->skipAttributes(
            [
                'created',
                'updated',
            ]
        );
    }


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

    /**
     * 現場の未登録作業を取得します
     * @param $site_id
     * @return Model\Resultset\Simple
     */
    public static function getNotAssignWorktypesBySite($site_id){
        $query = '
                select
                    w.id as worktype_id,
                    w.name as worktype_name
                from 
                    sites s
                join
                    worktypes w
                left join
                    site_rel_worktypes srw
                    on s.id = srw.site_id and w.id = srw.worktype_id 
                where
                    srw.site_id is null
                    and s.id = :site_id
        ';

        $proxy = new SiteRelWorktypes();
        $result = new \Phalcon\Mvc\Model\Resultset\Simple(null, $proxy,
            $proxy->getReadConnection()->query($query, ['site_id' => $site_id]));

        return $result;

    }

    /**
     * 現場-作業エンティティを取得します
     */
    public static function getEntity($site_id, $worktype_id){
        return self::findFirst([
            'conditions' => 'site_id = :site_id: and worktype_id = :worktype_id:',
            'bind' => ['site_id' => $site_id, 'worktype_id' => $worktype_id]
        ]);
    }

    /**
     * 現場-作業エンティティを作成します
     */
    public static function createEntity($site_id, $worktype_id){
        $entity = new SiteRelWorktypes();
        $entity->site_id = $site_id;
        $entity->worktype_id = $worktype_id;
        if( $entity->save() === false ){
            throw new Exception('更新に失敗しました');
        }
    }

    /**
     * 現場-作業エンティティを作成します
     */
    public static function deleteEntity($site_id, $worktype_id){

        $entity = self::getEntity($site_id, $worktype_id);

        if( empty($entity)  ){
            throw new Exception('データが存在しません');
        }

        if( $entity->delete() === false ){
            throw new Exception('削除に失敗しました');
        }
    }

}