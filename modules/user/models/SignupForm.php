<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 13.04.15
 * Time: 13:30
 */

namespace app\modules\user\models;

use app\models\Base;
use app\models\Category;
use app\models\EmailSubscribe;
use app\models\Financy;
use app\models\UserSubscription;
use yii\base\Model;
use Yii;
use app\models\Links;
use yii\web\Cookie;

class SignupForm extends Model
{
    public $email;
    public $password;
    public $verifyCode;
    public $password_repeat;

    public $promo;


    public function attributeLabels()
    {
        return [
            'password' => 'Пароль',
            'email' => 'Ваш e-mail',
            'verifyCode'=>'Капча',
            'password_repeat'=>'Повтор пароля',
            'promo'=>'Промо-код',

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

            [['promo'], 'safe'],
            [['promo'], 'string',  'max'=>50],
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

            $betaUser = EmailSubscribe::findOne(['email'=>$this->email, 'ok'=>1]);

            $user = new User();
            //$user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->status = User::STATUS_WAIT;
            $user->generateAuthKey();
            $user->generateEmailConfirmToken();

            if($this->promo){
                $user->promo = mb_strtolower($this->promo,'UTF-8');
            }
            if ($user->save()) {

                //добавим группу пользователю по-умолчанию
                $category = new Category();
                $category->user_id = $user->id;
                $category->title = 'Без группы';
                $category->save();

                //отрпавка почты пользователю для подтверждения регистрации
                 Yii::$app->mailer->compose(['html'=>'confirmEmail'], ['user' => $user])
                    ->setFrom(['we@moab.pro' => 'MOAB.Pro'])
                    ->setTo($this->email)
                    ->setSubject('Регистрация в личном кабинете MOAB.pro')
                    ->send();

                //если пользователь оставил галочка о подписке его на рассылку, то отправляем запрос на подписку в Unisender
                if(Yii::$app->request->post('accept_subscribe'))
                {
                    User::sendUnisenderSebscribe($user->email, $user->email);
                }
            }

            return $user;
        }

        return null;
    }
}