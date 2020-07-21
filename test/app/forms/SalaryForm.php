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

        // 基本給
        $base_charge = new Numeric('base_charge');
        $base_charge->setLabel('固定給');
        $base_charge->setAttributes([
            'class' => 'form-control text-right',
            'placeholder' => '基本給を入力してください。'
        ]);
        $base_charge->addValidators([
            new PresenceOf([
                'message' => '基本給を入力してください。'
            ])
        ]);
        $this->add($base_charge);

        // 賞与
        $bonus_charge = new Numeric('bonus_charge');
        $bonus_charge->setLabel('賞与');
        $bonus_charge->setAttributes([
            'class' => 'form-control text-right',
            'placeholder' => '賞与を入力してください。'
        ]);
        $bonus_charge->addValidators([
            new PresenceOf([
                'message' => '賞与を入力してください。'
            ])
        ]);
        $this->add($bonus_charge);

        // みなし残業額
        $base_charge = new Numeric('overtime_charge');
        $base_charge->setLabel('みなみ残業額');
        $base_charge->setAttributes([
            'class' => 'form-control text-right',
            'placeholder' => 'みなし残業額を入力してください。'
        ]);
        $base_charge->addValidators([
            new PresenceOf([
                'message' => 'みなし残業額を入力してください。'
            ])
        ]);
        $this->add($base_charge);

        // 役職手当
        $skill_charge = new Numeric('skill_charge');
        $skill_charge->setLabel('役職手当');
        $skill_charge->setAttributes([
            'class' => 'form-control text-right',
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
            'class' => 'form-control text-right',
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
            'class' => 'form-control text-right',
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
            'class' => 'form-control text-right',
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
            'class' => 'form-control text-right',
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
            'class' => 'form-control text-right',
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
            'class' => 'form-control text-right',
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
            'class' => 'form-control text-right',
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
            'class' => 'form-control text-right',
            'placeholder' => 'その他支給を入力してください。'
        ]);
        $etc_charge->addValidators([
            new PresenceOf([
                'message' => 'その他支給を入力してください。'
            ])
        ]);
        $this->add($etc_charge);

        // 社会保険料
        $insurance_bill = new Numeric('insurance_bill');
        $insurance_bill->setLabel('社会保険料');
        $insurance_bill->setAttributes([
            'class' => 'form-control text-right',
            'placeholder' => '社会保険料を入力してください。'
        ]);
        $insurance_bill->addValidators([
            new PresenceOf([
                'message' => '社会保険料を入力してください。'
            ])
        ]);
        $this->add($insurance_bill);

        // 厚生年金料
        $pension_bill = new Numeric('pension_bill');
        $pension_bill->setLabel('厚生年金料');
        $pension_bill->setAttributes([
            'class' => 'form-control text-right',
            'placeholder' => '厚生年金料を入力してください。'
        ]);
        $pension_bill->addValidators([
            new PresenceOf([
                'message' => '厚生年金料を入力してください。'
            ])
        ]);
        $this->add($pension_bill);

        // 雇用保険料
        $employment_insurance_bill = new Numeric('employment_insurance_bill');
        $employment_insurance_bill->setLabel('雇用保険料');
        $employment_insurance_bill->setAttributes([
            'class' => 'form-control text-right',
            'placeholder' => '雇用保険料を入力してください。'
        ]);
        $employment_insurance_bill->addValidators([
            new PresenceOf([
                'message' => '雇用保険料を入力してください。'
            ])
        ]);
        $this->add($employment_insurance_bill);

        // 所得税
        $income_tax = new Numeric('income_tax');
        $income_tax->setLabel('所得税');
        $income_tax->setAttributes([
            'class' => 'form-control text-right',
            'placeholder' => '所得税を入力してください。'
        ]);
        $income_tax->addValidators([
            new PresenceOf([
                'message' => '所得税を入力してください。'
            ])
        ]);
        $this->add($income_tax);

        // 家賃
        $rent_bill = new Numeric('rent_bill');
        $rent_bill->setLabel('家賃');
        $rent_bill->setAttributes([
            'class' => 'form-control text-right',
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
            'class' => 'form-control text-right',
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
            'class' => 'form-control text-right',
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
            'class' => 'form-control text-right',
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
            'class' => 'form-control text-right',
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
            'class' => 'form-control text-right',
            'placeholder' => 'その他控除を入力してください。'
        ]);
        $etc_bill->addValidators([
            new PresenceOf([
                'message' => 'その他控除を入力してください。'
            ])
        ]);
        $this->add($etc_bill);

        // 欠勤控除
        $attendance_deduction1 = new Numeric('attendance_deduction1');
        $attendance_deduction1->setLabel('欠勤控除');
        $attendance_deduction1->setAttributes([
            'class' => 'form-control text-right',
            'placeholder' => '欠勤控除額を入力してください。'
        ]);
        $attendance_deduction1->addValidators([
            new PresenceOf([
                'message' => '欠勤控除額を入力してください。'
            ])
        ]);
        $this->add($attendance_deduction1);

        // 勤怠控除
        $attendance_deduction2 = new Numeric('attendance_deduction2');
        $attendance_deduction2->setLabel('勤怠控除');
        $attendance_deduction2->setAttributes([
            'class' => 'form-control text-right',
            'placeholder' => '勤怠控除額を入力してください。'
        ]);
        $attendance_deduction2->addValidators([
            new PresenceOf([
                'message' => '勤怠控除額を入力してください。'
            ])
        ]);
        $this->add($attendance_deduction2);

        // 貸付返済
        $loan_bill = new Numeric('loan_bill');
        $loan_bill->setLabel('貸付返済額');
        $loan_bill->setAttributes([
            'class' => 'form-control text-right',
            'placeholder' => '貸付返済額を入力してください。'
        ]);
        $loan_bill->addValidators([
            new PresenceOf([
                'message' => '貸付返済額を入力してください。'
            ])
        ]);
        $this->add($loan_bill);

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