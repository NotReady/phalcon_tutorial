<?php
use \Phalcon\Forms\Form;
use \Phalcon\Forms\Element\Text;
use \Phalcon\Forms\Element\Submit;
use \Phalcon\Forms\Element\Numeric;
use \Phalcon\Forms\Element\Hidden;
use \Phalcon\Forms\Element\Select;
use \Phalcon\Validation\Validator\PresenceOf;

class SalaryForm extends Form
{
    public function initialize($emtry=null, $options=null){

        // id
        $this->add(new Hidden('employee_id'));

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

        // 月給
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