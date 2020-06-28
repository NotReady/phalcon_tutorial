<?php
use \Phalcon\Forms\Form;
use \Phalcon\Forms\Element\Text;
use \Phalcon\Forms\Element\Submit;
use \Phalcon\Forms\Element\Numeric;
use \Phalcon\Forms\Element\Hidden;
use \Phalcon\Forms\Element\Select;
use \Phalcon\Forms\Element\Date;
use \Phalcon\Validation\Validator\PresenceOf;

class EmployeesCreateForm extends Form
{
    public function initialize($emtry=null, $options=null){

        // 社員番号
        $employee_no = new Numeric('employee_no');
        $employee_no->setLabel('社員番号');
        $employee_no->setAttributes([
            'class' => 'form-control',
            'placeholder' => '社員番号を入力してください。'
        ]);
        $employee_no->addValidators([
            new PresenceOf([
                'message' => '社員番号を入力してください。'
            ])
        ]);
        $this->add($employee_no);


        // 名字
        $first_name = new Text('first_name');
        $first_name->setLabel('名字');
        $first_name->setAttributes([
            'class' => 'form-control',
            'placeholder' => '名字を入力してください。',
        ]);
        $first_name->addValidators([
            new PresenceOf([
                'message' => '名字を入力してください。'
            ])
        ]);
        $this->add($first_name);


        // 名前
        $last_name = new Text('last_name');
        $last_name->setLabel('名前');
        $last_name->setAttributes([
            'class' => 'form-control',
            'placeholder' => '名前を入力してください。',
        ]);
        $last_name->addValidators([
            new PresenceOf([
                'message' => '名前を入力してください。'
            ])
        ]);
        $this->add($last_name);

        // 入社日
        $hire_date = new Date('hire_date');
        $hire_date->setLabel('入社日');
        $hire_date->setAttributes([
            'class' => 'form-control',
            'placeholder' => '入社日を入力してください。',
        ]);
        $hire_date->addValidators([
            new PresenceOf([
                'message' => '入社日を入力してください。'
            ])
        ]);
        $this->add($hire_date);

        // 雇用状態
        $employee_status = new Select('employee_status',
            [''=>'雇用状態を選択してください'] +  Employees::EMPLOYEE_STATUS_MAP
        );
        $employee_status->setLabel('雇用状態');
        $employee_status->setAttributes([
            'class' => 'form-control',
        ]);
        $employee_status->addValidators([
            new PresenceOf([
                'message' => '雇用状態を選択してください。'
            ])
        ]);
        $this->add($employee_status);

        // 雇用種別
        $employee_type = new Select('employee_type',
            [''=>'雇用種別を選択してください'] + Employees::EMPLOYEE_TYPE_MAP
        );

        $employee_type->setLabel('雇用種別');
        $employee_type->setAttributes([
            'class' => 'form-control',
        ]);
        $employee_type->addValidators([
            new PresenceOf([
                'message' => '雇用種別を選択してください。'
            ])
        ]);
        $this->add($employee_type);

        // 職能
        $employee_type = new Select('skill_id',
            [''=>'職能を選択してください'] + Employees::EMPLOYEE_SKILL_MAP

        );
        $employee_type->setLabel('職能');
        $employee_type->setAttributes([
            'class' => 'form-control',
        ]);
        $employee_type->addValidators([
            new PresenceOf([
                'message' => '職能を選択してください。'
            ])
        ]);
        $this->add($employee_type);

    }


    public function messages($nameControl)
    {
        if ($this->hasMessagesFor($nameControl)) {
            foreach ($this->getMessagesFor($nameControl) as $message) {
                $this->flash->error($message);
            }
        }
    }
}