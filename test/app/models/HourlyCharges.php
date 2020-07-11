<?php
use Phalcon\Mvc\Model;

class HourlyCharges extends Model
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

    /**
     * @var 職能ID
     */
    public $skill_id;

    /**
     * @var 時給
     */
    public $value;

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

    public function getSource()
    {
        return "hourlycharges";
    }

    /**
     * 現場-作業分類-職能別の時給単価を取得します
     * @param $siteId
     * @param $workId
     */
    public static function getHourlyChargeBySiteWork($siteId, $workId){
        $sql = '
                select
                    skills.skill_id,
                    skills.name as skill_name,
                    hc.value as hourly_charge
                from
                    skills
                join
                    site_rel_worktypes srw
                    on srw.site_id = :site_id and srw.worktype_id = :work_id
                left outer join
                    hourlycharges hc
                        on hc.site_id = srw.site_id
                       and hc.worktype_id = srw.worktype_id
                       and hc.skill_id = skills.skill_id
                order by
                    skills.skill_id asc';

        $entity = new Employees();
        $result = new \Phalcon\Mvc\Model\Resultset\Simple(null, $entity,
            $entity->getReadConnection()->query($sql, ['site_id' => $siteId, 'work_id' => $workId]));

        return $result;
    }

    /**
     * 現場-作業分類-職能の時給を更新します
     * @param $siteId
     * @param $workId
     * @param $skillId
     * @param $value
     * @throws Exception
     */
    public static function updateHourlyCharge($siteId, $workId, $skillId, $value){

        $entity = self::getEntity($siteId, $workId, $skillId);

        if( $entity === false ){
            $entity = new HourlyCharges();
            $entity->site_id = $siteId;
            $entity->worktype_id = $workId;
            $entity->skill_id = $skillId;
        }

        $entity->value = $value;

        if( $entity->save() === false ){
            throw new Exception("時給の更新に失敗しました。");
        }
    }

    /**
     * 現場-作業分類-職能の時給を削除します
     * @param $siteId
     * @param $workId
     * @param $skillId
     */
    public static function deleteHourlyCharge($siteId, $workId, $skillId){
        $entity = self::getEntity($siteId, $workId, $skillId);

        if( $entity === false ){
            throw new Exception("データが存在しません。");
        }

        if( $entity->delete() === false ){
            throw new Exception("時給の削除に失敗しました。");
        }
    }

    /**
     * エンティティを取得します
     * @param $siteId
     * @param $workId
     * @param $skillId
     */
    public static function getEntity($siteId, $workId, $skillId){

        return self::findFirst([
           'conditions' => 'site_id = :site_id: and worktype_id = :worktype_id: and skill_id = :skill_id:',
           'bind' => [
               'site_id' => $siteId,
               'worktype_id' => $workId,
               'skill_id' => $skillId
           ]
        ]);
    }

}