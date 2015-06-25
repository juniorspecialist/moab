<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 13.04.15
 * Time: 13:30
 */

namespace app\modules\user\models;

use yii\base\Model;
use Yii;

class SignupForm extends Model
{
    public $email;
    public $password;
    public $verifyCode;
    public $password_repeat;


    public function attributeLabels()
    {
        return [
            'password' => 'Пароль',
            'email' => 'Адрес почты',
            'verifyCode'=>'Капча',
            'password_repeat'=>'Повтор пароля'
        ];
    }

    /**
     * @inheritdoc
     */
        public function rules()
    {
        return [

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::className(), 'message' => 'Этот адрес почты уже занят.'],

            [['password','password_repeat'], 'required'],
            ['password_repeat', 'compare', 'compareAttribute'=>'password', 'message'=>"Пароли не совпадают"],
            [['password','password_repeat'], 'string', 'min' => 6],

            //['verifyCode', 'captcha', 'captchaAction' => '/user/default/captcha'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
            $user = new User();
            //$user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->status = User::STATUS_WAIT;
            $user->generateAuthKey();
            $user->generateEmailConfirmToken();

            if ($user->save()) {
                //отрпавка почты пользователю для подтверждения регистрации
                Yii::$app->mailer->compose('confirmEmail', ['user' => $user])
                    ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name])
                    ->setTo($this->email)
                    ->setSubject('Подтверждение адреса почты для  ' . Yii::$app->name)
                    ->send();
            }

            return $user;
        }

        return null;
    }
}