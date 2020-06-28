<?php
use \Phalcon\Forms\Form;
use \Phalcon\Forms\Element\Text;
use \Phalcon\Forms\Element\Submit;
use \Phalcon\Forms\Element\Numeric;
use \Phalcon\Forms\Element\Hidden;
use \Phalcon\Forms\Element\Select;
use \Phalcon\Forms\Element\Date;
use \Phalcon\Validation\Validator\PresenceOf;

class EmployeesForm extends Form
{
    public function initialize($emtry=null, $options=null){

        // id
        $this->add(new Hidden('id'));

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

        // 退社日
        $leave_date = new Date('leave_date');
        $leave_date->setLabel('退社日');
        $leave_date->setAttributes([
            'class' => 'form-control',
            'placeholder' => '退社日を入力してください。',
        ]);
        $this->add($leave_date);

        // 雇用状態
        $employee_status = new Select('employee_status',
            [''=>'雇用状態を選択してください'] + Employees::EMPLOYEE_STATUS_MAP
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
        $skill_id = new Select('skill_id',
            [''=>'職能を選択してください'] + Employees::EMPLOYEE_SKILL_MAP
        );
        $skill_id->setLabel('職能');
        $skill_id->setAttributes([
            'class' => 'form-control',
        ]);
        $skill_id->addValidators([
            new PresenceOf([
                'message' => '職能を選択してください。'
            ])
        ]);
        $this->add($skill_id);

        // 社会保険加入
        $insurance_type = new Select('insurance_type', [
            'enable' => '加入',
            'disable' => '非加入'
        ]);
        $insurance_type->setLabel('社会保険');
        $insurance_type->setAttributes([
            'class' => 'form-control',
        ]);
        $insurance_type->addValidators([
            new PresenceOf([
                'message' => '社会保険加入を選択してください。'
            ])
        ]);
        $this->add($insurance_type);

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

        // 課税交通費
        $transportation_expenses = new Numeric('transportation_expenses');
        $transportation_expenses->setLabel('課税交通費');
        $transportation_expenses->setAttributes([
            'class' => 'form-control',
            'placeholder' => '課税交通費を入力してください。'
        ]);
        $transportation_expenses->addValidators([
            new PresenceOf([
                'message' => '課税交通費を入力してください。'
            ])
        ]);
        $this->add($transportation_expenses);


        // 日割交通費
        $transportation_expenses_by_day = new Numeric('transportation_expenses_by_day');
        $transportation_expenses_by_day->setLabel('日割交通費');
        $transportation_expenses_by_day->setAttributes([
            'class' => 'form-control',
            'placeholder' => '日割交通費を入力してください。'
        ]);
        $transportation_expenses_by_day->addValidators([
            new PresenceOf([
                'message' => '日割交通費を入力してください。'
            ])
        ]);
        $this->add($transportation_expenses_by_day);

        // 非課税交通費
        $transportation_expenses_without_tax = new Numeric('transportation_expenses_without_tax');
        $transportation_expenses_without_tax->setLabel('非課税交通費');
        $transportation_expenses_without_tax->setAttributes([
            'class' => 'form-control',
            'placeholder' => '非課税交通費を入力してください。'
        ]);
        $transportation_expenses_without_tax->addValidators([
            new PresenceOf([
                'message' => '非課税交通費を入力してください。'
            ])
        ]);
        $this->add($transportation_expenses_without_tax);

        // 非課税通信費
        $communication_charge_without_tax = new Numeric('communication_charge_without_tax');
        $communication_charge_without_tax->setLabel('非課税通信費');
        $communication_charge_without_tax->setAttributes([
            'class' => 'form-control',
            'placeholder' => '非課税通信費を入力してください。'
        ]);
        $communication_charge_without_tax->addValidators([
            new PresenceOf([
                'message' => '非課税通信費を入力してください。'
            ])
        ]);
        $this->add($communication_charge_without_tax);

        // 住宅手当
        $house_charge = new Numeric('house_charge');
        $house_charge->setLabel('住宅手当');
        $house_charge->setAttributes([
            'class' => 'form-control',
            'placeholder' => '住宅手当を入力してください。'
        ]);
        $house_charge->addValidators([
            new PresenceOf([
                'message' => '住宅手当を入力してください。'
            ])
        ]);
        $this->add($house_charge);

        // 送迎手当
        $bus_charge = new Numeric('bus_charge');
        $bus_charge->setLabel('送迎手当');
        $bus_charge->setAttributes([
            'class' => 'form-control',
            'placeholder' => '送迎手当を入力してください。'
        ]);
        $bus_charge->addValidators([
            new PresenceOf([
                'message' => '送迎手当を入力してください。'
            ])
        ]);
        $this->add($bus_charge);

        // 事務手当
        $officework_charge = new Numeric('officework_charge');
        $officework_charge->setLabel('事務手当');
        $officework_charge->setAttributes([
            'class' => 'form-control',
            'placeholder' => '事務手当を入力してください。'
        ]);
        $officework_charge->addValidators([
            new PresenceOf([
                'message' => '事務手当を入力してください。'
            ])
        ]);
        $this->add($officework_charge);

        // その他支給
        $etc_charge = new Numeric('etc_charge');
        $etc_charge->setLabel('その他支給');
        $etc_charge->setAttributes([
            'class' => 'form-control',
            'placeholder' => 'その他支給を入力してください。'
        ]);
        $etc_charge->addValidators([
            new PresenceOf([
                'message' => 'その他支給を入力してください。'
            ])
        ]);
        $this->add($etc_charge);

        // 家賃
        $rent_bill = new Numeric('rent_bill');
        $rent_bill->setLabel('家賃');
        $rent_bill->setAttributes([
            'class' => 'form-control',
            'placeholder' => '家賃を入力してください。'
        ]);
        $rent_bill->addValidators([
            new PresenceOf([
                'message' => '家賃を入力してください。'
            ])
        ]);
        $this->add($rent_bill);

        // 電気代
        $electric_bill = new Numeric('electric_bill');
        $electric_bill->setLabel('電気代');
        $electric_bill->setAttributes([
            'class' => 'form-control',
            'placeholder' => '電気代を入力してください。'
        ]);
        $electric_bill->addValidators([
            new PresenceOf([
                'message' => '電気代を入力してください。'
            ])
        ]);
        $this->add($electric_bill);

        // ガス代
        $gas_bill = new Numeric('gas_bill');
        $gas_bill->setLabel('ガス代');
        $gas_bill->setAttributes([
            'class' => 'form-control',
            'placeholder' => 'ガス代を入力してください。'
        ]);
        $gas_bill->addValidators([
            new PresenceOf([
                'message' => 'ガス代を入力してください。'
            ])
        ]);
        $this->add($gas_bill);

        // 水道代
        $water_bill = new Numeric('water_bill');
        $water_bill->setLabel('水道代');
        $water_bill->setAttributes([
            'class' => 'form-control',
            'placeholder' => '水道代を入力してください。'
        ]);
        $water_bill->addValidators([
            new PresenceOf([
                'message' => '水道代を入力してください。'
            ])
        ]);
        $this->add($water_bill);

        // 弁当代
        $food_bill = new Numeric('food_bill');
        $food_bill->setLabel('弁当代');
        $food_bill->setAttributes([
            'class' => 'form-control',
            'placeholder' => '弁当代を入力してください。'
        ]);
        $food_bill->addValidators([
            new PresenceOf([
                'message' => '弁当代を入力してください。'
            ])
        ]);
        $this->add($food_bill);

        // その他控除
        $etc_bill = new Numeric('etc_bill');
        $etc_bill->setLabel('その他控除');
        $etc_bill->setAttributes([
            'class' => 'form-control',
            'placeholder' => 'その他控除を入力してください。'
        ]);
        $etc_bill->addValidators([
            new PresenceOf([
                'message' => 'その他控除を入力してください。'
            ])
        ]);
        $this->add($etc_bill);

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