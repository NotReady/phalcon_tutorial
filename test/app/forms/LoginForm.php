<?php
use \Phalcon\Forms\Form;
use \Phalcon\Forms\Element\Text;
use \Phalcon\Forms\Element\Password;
use \Phalcon\Forms\Element\Submit;
use \Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\Identical;
use \Phalcon\Validation\Validator\PresenceOf;

class LoginForm extends Form
{
    public function initialize($emtry=null, $options=null){

        // ログインID
        $username = new Text('username');
        $username->setLabel('ログインID');
        $username->setAttributes([
            'class' => 'form-control',
            'placeholder' => 'ログインID'
        ]);
        $username->addValidators([
            new PresenceOf([
                'message' => 'ログインIDを入力してください。'
            ])
        ]);
        $this->add($username);

        // ログインパスワード
        $password = new Password('password');
        $password->setLabel('パスワード');
        $password->setAttributes([
            'class' => 'form-control',
            'placeholder' => 'パスワード'
        ]);
        $password->addValidators([
            new PresenceOf([
                'message' => 'パスワードを入力してください。'
            ])
        ]);
        $this->add($password);

        // 送信
        $this->add(new Submit('submit', [
            'class' => 'form-control btn-primary',
            'value' => 'ログイン'
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