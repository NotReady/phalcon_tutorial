<?php
use \Phalcon\Forms\Form;
use \Phalcon\Forms\Element\Text;
use \Phalcon\Forms\Element\Submit;
use \Phalcon\Forms\Element\Numeric;
use \Phalcon\Forms\Element\Hidden;
use \Phalcon\Forms\Element\Select;
use \Phalcon\Validation\Validator\PresenceOf;

class EmployeesForm extends Form
{
    public function initialize($emtry=null, $options=null){

        // id
        $this->add(new Hidden('id'));

        // 雇用タイプ
        $employee_type = new Select('employee_type', [
            'pro' => '社員',
            'part' => 'アルバイト'
        ]);
        $employee_type->setLabel('雇用タイプ');
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
        $employee_type = new Select('skill_id', [
            '1' => '研修中',
            '2' => '一般職'
        ]);
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

        // 住所
        $last_name = new Text('address');
        $last_name->setLabel('住所');
        $last_name->setAttributes([
            'class' => 'form-control',
            'placeholder' => '住所を入力してください。',
        ]);
        $last_name->addValidators([
            new PresenceOf([
                'message' => '住所を入力してください。'
            ])
        ]);
        $this->add($last_name);


        // 交通費
        $transportation_expenses = new Numeric('transportation_expenses');
        $transportation_expenses->setLabel('交通費');
        $transportation_expenses->setAttributes([
            'class' => 'form-control',
            'placeholder' => '交通費を入力してください。'
        ]);
        $transportation_expenses->addValidators([
            new PresenceOf([
                'message' => '交通費を入力してください。'
            ])
        ]);
        $this->add($transportation_expenses);

        // 固定給
        $monthly_charge = new Numeric('monthly_charge');
        $monthly_charge->setLabel('固定給');
        $monthly_charge->setAttributes([
            'class' => 'form-control',
            'placeholder' => '固定給を入力してください。'
        ]);
        $monthly_charge->addValidators([
            new PresenceOf([
                'message' => '固定給を入力してください。'
            ])
        ]);
        $this->add($monthly_charge);

        // みなし残業代
        $monthly_charge = new Numeric('overtime_charge');
        $monthly_charge->setLabel('みなし残業代');
        $monthly_charge->setAttributes([
            'class' => 'form-control',
            'placeholder' => 'みなし残業代を入力してください。'
        ]);
        $monthly_charge->addValidators([
            new PresenceOf([
                'message' => 'みなし残業代を入力してください。'
            ])
        ]);
        $this->add($monthly_charge);


        // 役職手当
        $skill_charge = new Numeric('skill_charge');
        $skill_charge->setLabel('役職手当');
        $skill_charge->setAttributes([
            'class' => 'form-control',
            'placeholder' => '役職手当を入力してください。'
        ]);
        $skill_charge->addValidators([
            new PresenceOf([
                'message' => '役職手当を入力してください。'
            ])
        ]);
        $this->add($skill_charge);

        // 送信
        $this->add(new Submit('submit', [
            'class' => 'form-control btn-primary',
            'value' => '保存'
        ]));

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