<?php
use \Phalcon\Forms\Form;
use \Phalcon\Forms\Element\Text;
use \Phalcon\Forms\Element\Submit;
use \Phalcon\Forms\Element\Numeric;
use \Phalcon\Forms\Element\Hidden;
use \Phalcon\Validation\Validator\PresenceOf;

class EmployeesForm extends Form
{
    public function initialize($emtry=null, $options=null){

        // id
        $this->add(new Hidden('id'));

        // 名字
        $first_name = new Text('first_name');
        $first_name->setLabel('名字');
        $first_name->setAttributes([
            'class' => 'form-control',
            'placeholder' => '名字',
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
            'placeholder' => '名前',
        ]);
        $last_name->addValidators([
            new PresenceOf([
                'message' => '名前を入力してください。'
            ])
        ]);
        $this->add($last_name);

        // 交通費
        $transportation_expenses = new Numeric('Transportation_expenses');
        $transportation_expenses->setLabel('交通費');
        $transportation_expenses->setAttributes([
            'class' => 'form-control',
            'placeholder' => '交通費'
        ]);
        $transportation_expenses->addValidators([
            new PresenceOf([
                'message' => '交通費を入力してください。'
            ])
        ]);
        $this->add($transportation_expenses);

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