<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 13.04.15
 * Time: 13:37
 */

namespace app\modules\user\models;

use app\models\Base;
use app\models\Financy;
use app\models\Links;
use app\models\UserSubscription;
use yii\base\InvalidParamException;
use yii\base\Model;
use Yii;


class ConfirmEmailForm extends Model
{
    /**
     * @var User
     */
    private $_user;

    /**
     * Creates a form model given a token.
     *
     * @param  string $token
     * @param  array $config
     * @throws \yii\base\InvalidParamException if token is empty or not valid
     */
        public function __construct($token, $config = [])
    {
        if (empty($token) || !is_string($token)) {
            throw new InvalidParamException('Отсутствует код подтверждения.');
        }
        //$this->_user = User::findByEmailConfirmToken($token);
        $this->_user = User::findOne(['email_confirm_token' => $token]);
        if (!$this->_user) {
            throw new InvalidParamException('Неверный токен.');
        }
        parent::__construct($config);
    }

        /**
         * Resets password.
         *
         * @return boolean if password was reset.
         */
        public function confirmEmail()
    {
        $user = $this->_user;
        $user->status = User::STATUS_ACTIVE;
        //$user->removeEmailConfirmToken();

        //проверим наличие почты
        //if (isset(Yii::$app->request->cookies['link'])) {

            $link = Links::findOne(['email'=>$user->email,'status'=>Links::STATUS_IS_NEW]);

            if($link){
                $base2 = Base::findOne(['id'=>1]);
                if($base2){
                    //запишим операцию пополнения в общий лог. фин. операций
                    $financy = new Financy();
                    $financy->user_id = $user->id;
                    $financy->amount = $base2->six_month_price;
                    $financy->balance_after = $base2->six_month_price;
                    $financy->type_operation = Financy::TYPE_OPERATION_PLUS;
                    $financy->status = Financy::STATUS_PAID;
                    $financy->desc = 'Пополнение от администрации сайта';
                    $financy->pay_system = Financy::PAY_SYSTEM_ADMIN;
                    $financy->save();

                    //обновим баланс юзера
                    $user->updateCounters(['balance'=>$base2->six_month_price]);

                    //эмулируем покупку подписки на базу-2
                    $userSubscribe = new UserSubscription();
                    $userSubscribe->user_id = $user->id;
                    $userSubscribe->base_id = $base2->id;
                    $userSubscribe->amount = $base2->six_month_price;
                    $userSubscribe->from = time();
                    $userSubscribe->to = strtotime('+6 month');
                    $userSubscribe->eternal_period = 0;
                    $userSubscribe->save(false);


                    //обновим привязку к почте юзера, чтобы выдать ему сообщеине об успешной подписке на использование системы

                    if($link){
                        //теперь меняем статус куки, чтобы большие никто не мог её пользоваться
                        $link->status = Links::STATUS_WRITE_COOKIE;
                        //$link->email = $user->email;
                        $link->save(false);
                    }


                }
            }
        //}

        return $user->save();
    }
}