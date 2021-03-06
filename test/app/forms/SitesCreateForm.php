<?php
use \Phalcon\Forms\Form;
use \Phalcon\Forms\Element\Text;
use \Phalcon\Forms\Element\Submit;
use \Phalcon\Forms\Element\Numeric;
use \Phalcon\Forms\Element\Hidden;
use \Phalcon\Forms\Element\Select;
use \Phalcon\Forms\Element\Date;
use \Phalcon\Validation\Validator\PresenceOf;

class SitesCreateForm extends Form
{
    public function initialize($entity=null, $options=[]){

        // id
        $this->add(new Hidden('id'));

        $customer_select = [''=>''];
        $customers = Customers::find();
        foreach ($customers as $customer){
            $customer_select[$customer->id] = $customer->name;
        }

        // 顧客
        $customer_id = new Select('customer_id', $customer_select);
        $customer_id->setLabel('顧客');
        $customer_id->setAttributes([
            'class' => 'form-control',
        ]);
        $customer_id->addValidators([
            new PresenceOf([
                'message' => '顧客を選択してください。'
            ])
        ]);
        $this->add($customer_id);

        // 契約種別
        $business_type = new Select('business_type',
            [''=>'契約種別を選択してください'] + Sites::BUSINESS_TYPE_MAP
        );
        $business_type->setLabel('契約種別');
        $business_type->setAttributes([
            'class' => 'form-control',
        ]);
        $business_type->addValidators([
            new PresenceOf([
                'message' => '契約種別を選択してください。'
            ])
        ]);
        $this->add($business_type);


        // 現場名
        $sitename = new Text('sitename');
        $sitename->setLabel('現場名');
        $sitename->setAttributes([
            'class' => 'form-control',
            'placeholder' => '現場名を入力してください。',
        ]);
        $sitename->addValidators([
            new PresenceOf([
                'message' => '現場名を入力してください。'
            ])
        ]);
        $this->add($sitename);

        // 業務開始時間
        $time_from = new ExtendedTimeForm('time_from');
        $time_from->setLabel('業務開始時間');
        $time_from->setAttributes([
            'class' => 'form-control',
            'placeholder' => '業務開始時間を入力してください。',
        ]);
        $time_from->addValidators([
            new PresenceOf([
                'message' => '業務開始時間を入力してください。'
            ])
        ]);
        $this->add($time_from);

        // 業務終了時間
        $time_to = new ExtendedTimeForm('time_to');
        $time_to->setLabel('業務終了時間');
        $time_to->setAttributes([
            'class' => 'form-control',
            'placeholder' => '業務終了時間を入力してください。',
        ]);
        $time_to->addValidators([
            new PresenceOf([
                'message' => '業務終了時間を入力してください。'
            ])
        ]);
        $this->add($time_to);

        // 休憩時間
        $breaktime = new ExtendedTimeForm('breaktime');
        $breaktime->setLabel('休憩時間');
        $breaktime->setAttributes([
            'class' => 'form-control',
            'placeholder' => '休憩時間を入力してください。',
        ]);
        $breaktime->addValidators([
            new PresenceOf([
                'message' => '休憩時間を入力してください。'
            ])
        ]);
        $this->add($breaktime);

        
        // 定時時間
        $regulartime = new ExtendedTimeForm('regulartime');
        $regulartime->setLabel('定時時間');
        $regulartime->setAttributes([
            'class' => 'form-control',
            'placeholder' => '現場の定時時間を入力してください',
        ]);
        $regulartime->addValidators([
            new PresenceOf([
                'message' => '現場の定時時間を入力してください。'
            ])
        ]);
        $this->add($regulartime);

        // 請負金額
        $monthly_bill_amount = new Numeric('monthly_bill_amount');
        $monthly_bill_amount->setLabel('請負金額');
        $monthly_bill_amount->setAttributes([
            'class' => 'form-control',
            'placeholder' => '請負契約時のみ入力',
            $options['business_type'] === 'takeup' ? '' : 'disabled' =>
                $options['business_type'] === 'takeup' ? '' : 'disabled'
        ]);

        // 請負契約のみ請負金額必須
        if( $options['business_type'] === 'takeup' ){
            $monthly_bill_amount->addValidators([
                new PresenceOf([
                    'message' => '請負金額を入力してください'
                ])
            ]);
        }
        $this->add($monthly_bill_amount);


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