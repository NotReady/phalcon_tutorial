<?php
use \Phalcon\Forms\Form;
use \Phalcon\Forms\Element\Text;
use \Phalcon\Forms\Element\Submit;
use \Phalcon\Forms\Element\Numeric;
use \Phalcon\Forms\Element\Hidden;
use \Phalcon\Forms\Element\Select;
use \Phalcon\Forms\Element\Date;
use \Phalcon\Validation\Validator\PresenceOf;

class CustomerForm extends Form
{
    public function initialize($emtry=null, $options=null){

        // id
        $this->add(new Hidden('id'));

        // 顧客名
        $sitename = new Text('name');
        $sitename->setLabel('顧客名');
        $sitename->setAttributes([
            'class' => 'form-control',
            'placeholder' => '顧客名を入力してください。',
        ]);
        $sitename->addValidators([
            new PresenceOf([
                'message' => '顧客名を入力してください。'
            ])
        ]);
        $this->add($sitename);

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