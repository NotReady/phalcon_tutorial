<?php
/**
 * Created by PhpStorm.
 * User: notready
 * Date: 2019-04-30
 * Time: 19:20
 */
use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Select;
use \Phalcon\Forms\Element\Hidden;
use \Phalcon\Forms\Element\Date;
use \Phalcon\Validation\Validator\PresenceOf;

class ReportForm extends Form
{
    public function initialize($entity, $attend){

        // 社員ID
        $this->add(new Hidden('employee_id'));

        // 出勤日
        $at_day = new Date('at_day');
        $at_day->setLabel('出勤日');
        $at_day->setAttributes([
            'class' => 'form-control',
            'placeholder' => '出勤日を入力してください。',
        ]);
        $this->add($at_day);

        // 出勤
        $attendance = new Select('attendance',
            [''=>''] + Reports::ATTENDANCE_MAP
        );
        $attendance->setLabel('勤怠');
        $attendance->setAttributes([
            'class' => 'form-control',
        ]);
        $this->add($attendance);

        // 現場
        $site = new Select('site_id',
            [''=>''] + Sites::getSerializeFormData()
        );
        $site->setLabel('現場');
        $site->setAttributes([
            'class' => 'form-control',
            $entity->attendance === 'absenteeism' ? 'disabled' : '' => '',
        ]);
        $this->add($site);

        // 作業
        $workTypes = Worktypes::getWorkTypesByEmployeeAtSite( $entity->employee_id, $entity->site_id);
        $validWork = [];
        foreach ($workTypes as $work){
            $validWork[$work['worktype_id']] = $work['name'];
        }
        $worktype = new Select('worktype_id', $validWork);
        $worktype->setAttributes([
            'class' => 'form-control',
            $entity->attendance === 'absenteeism' ? 'disabled' : '' => '',
        ]);
        $this->add($worktype);

        // 業務開始時間
        $time_from = new ExtendedTimeForm('time_from');
        $time_from->setLabel('業務開始時間');
        $time_from->setAttributes([
            'class' => 'form-control',
            'placeholder' => '業務開始時間を入力してください。',
            $entity->attendance === 'absenteeism' ? 'disabled' : '' => '',
        ]);
        $this->add($time_from);

        // 業務終了時間
        $time_to = new ExtendedTimeForm('time_to');
        $time_to->setLabel('業務終了時間');
        $time_to->setAttributes([
            'class' => 'form-control',
            'placeholder' => '業務終了時間を入力してください。',
            $entity->attendance === 'absenteeism' ? 'disabled' : '' => '',
        ]);
        $this->add($time_to);

        // 休憩時間
        $breaktime = new ExtendedTimeForm('breaktime');
        $breaktime->setLabel('休憩時間');
        $breaktime->setAttributes([
            'class' => 'form-control',
            'placeholder' => '休憩時間を入力してください。',
            $entity->attendance === 'absenteeism' ? 'disabled' : '' => '',
        ]);
        $this->add($breaktime);

        /* validators */

        $at_day->addValidators([
            new PresenceOf([
                'message' => '出勤日を入力してください。'
            ])
        ]);

        $attendance->addValidators([
            new PresenceOf([
                'message' => '勤怠を選択してください。'
            ])
        ]);

        if( $attend !== 'absenteeism' ){

            $site->addValidators([
                new PresenceOf([
                    'message' => '現場を選択してください。'
                ])
            ]);

            $worktype->addValidators([
                new PresenceOf([
                    'message' => '作業を選択してください。'
                ])
            ]);

            $time_from->addValidators([
                new PresenceOf([
                    'message' => '業務開始時間を入力してください。'
                ])
            ]);

            $time_to->addValidators([
                new PresenceOf([
                    'message' => '業務終了時間を入力してください。'
                ])
            ]);

            $breaktime->addValidators([
                new PresenceOf([
                    'message' => '休憩時間を入力してください。'
                ])
            ]);
        }
    }
}